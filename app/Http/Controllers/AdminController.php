<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use App\Models\Attendance;
use App\Exports\AttendanceExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (Auth::check() && Auth::user()->role !== 'admin') {
                    return redirect()->route('user.index');
                }
                return $next($request);
            }, except: ['showLogin', 'login']),
        ];
    }

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle authentication.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('user.index');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang Anda masukkan tidak cocok.',
        ])->onlyInput('email');
    }

    /**
     * Log the admin out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    /**
     * Admin Dashboard view.
     */
    public function dashboard()
    {
        $today = Carbon::today('Asia/Jakarta')->toDateString();

        $stats = [
            'total_check_in' => Attendance::where('date', $today)->whereNotNull('check_in')->count(),
            'total_check_out' => Attendance::where('date', $today)->whereNotNull('check_out')->count(),
            'total_attendance' => Attendance::count(),
            'latest_activity' => Attendance::where('date', $today)
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Live QR Code Display.
     */
    public function showQr()
    {
        return view('admin.qr');
    }

    public function getQrToken()
    {
        // Use the current request's URL (which will automatically be the Railway domain)
        $baseUrl = rtrim(url('/'), '/');
        $qrUrl = $baseUrl . '/?ref=posko_qr';

        // Render QR Code SVG using SimpleQRCode
        $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)
            ->color(17, 24, 39) // Dark grey/black for high contrast
            ->backgroundColor(255, 255, 255)
            ->margin(1)
            ->generate($qrUrl);

        return response()->json([
            'qr_url' => $qrUrl,
            'svg' => (string) $svg,
        ]);
    }

    /**
     * Edit settings view.
     */
    public function settings()
    {
        $settings = [
            'kkn_name' => Setting::getValue('kkn_name', 'KKN Posko Desa Sukamaju'),
            'latitude' => Setting::getValue('latitude', '-6.175392'),
            'longitude' => Setting::getValue('longitude', '106.827153'),
            'radius' => Setting::getValue('radius', '200'),
            'check_in_start' => Setting::getValue('check_in_start', '06:30'),
            'check_in_end' => Setting::getValue('check_in_end', '08:00'),
            'check_out_start' => Setting::getValue('check_out_start', '09:30'),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'kkn_name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:1',
            'check_in_start' => 'required|date_format:H:i',
            'check_in_end' => 'required|date_format:H:i|after:check_in_start',
            'check_out_start' => 'required|date_format:H:i',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        Setting::setValue('kkn_name', $request->kkn_name);
        Setting::setValue('latitude', $request->latitude);
        Setting::setValue('longitude', $request->longitude);
        Setting::setValue('radius', $request->radius);
        Setting::setValue('check_in_start', $request->check_in_start);
        Setting::setValue('check_in_end', $request->check_in_end);
        Setting::setValue('check_out_start', $request->check_out_start);

        // Update admin password if provided
        if ($request->filled('password')) {
            $user = Auth::user();
            $user->password = bcrypt($request->password);
            $user->save();
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }

    /**
     * Daily Attendance Logs View.
     */
    public function weeklyLogs(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta');
        $selectedDate = $request->get('date', $now->toDateString());

        // Fetch records
        $attendances = Attendance::where('date', $selectedDate)
            ->orderBy('name', 'asc')
            ->get();

        $students = User::where('role', 'user')->orderBy('name', 'asc')->get();

        return view('admin.weekly_logs', compact('selectedDate', 'attendances', 'students'));
    }

    /**
     * Store manual attendance.
     */
    public function storeManualAttendance(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:Present,Checkout Only',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
        ]);

        $student = User::findOrFail($request->student_id);

        $existingAttendance = Attendance::where('name', $student->name)
            ->where('division', $student->division)
            ->where('date', $request->date)
            ->first();

        if ($request->status === 'Present') {
            if ($existingAttendance && $existingAttendance->check_in) {
                return redirect()->back()->withErrors(['error' => 'Mahasiswa ini sudah melakukan absen Masuk pada tanggal tersebut.']);
            }
            
            Attendance::updateOrCreate(
                [
                    'name' => $student->name,
                    'division' => $student->division,
                    'date' => $request->date,
                ],
                [
                    'check_in' => $request->check_in ? $request->check_in . ':00' : null,
                    'status' => 'Present',
                ]
            );
        } else {
            if ($existingAttendance && $existingAttendance->check_out) {
                return redirect()->back()->withErrors(['error' => 'Mahasiswa ini sudah melakukan absen Pulang pada tanggal tersebut.']);
            }

            Attendance::updateOrCreate(
                [
                    'name' => $student->name,
                    'division' => $student->division,
                    'date' => $request->date,
                ],
                [
                    'check_out' => $request->check_out ? $request->check_out . ':00' : null,
                    'status' => $existingAttendance ? $existingAttendance->status : 'Checkout Only',
                ]
            );
        }

        return redirect()->back()->with('success', 'Absensi manual berhasil disimpan!');
    }

    /**
     * Export to Excel.
     */
    public function exportExcel(Request $request)
    {
        $date = $request->get('date');

        if (!$date) {
            return redirect()->back()->with('error', 'Pilih tanggal terlebih dahulu.');
        }

        $attendances = Attendance::where('date', $date)
            ->orderBy('name', 'asc')
            ->get();

        return Excel::download(new AttendanceExport($attendances), "laporan_absensi_{$date}.xlsx");
    }

    /**
     * Export to PDF.
     */
    public function exportPdf(Request $request)
    {
        $date = $request->get('date');

        if (!$date) {
            return redirect()->back()->with('error', 'Pilih tanggal terlebih dahulu.');
        }

        $attendances = Attendance::where('date', $date)
            ->orderBy('name', 'asc')
            ->get();

        $kknName = Setting::getValue('kkn_name', 'KKN Posko');

        $pdf = Pdf::loadView('exports.attendance_pdf', compact('attendances', 'date', 'kknName'));

        // Output to browser or download
        return $pdf->download("laporan_absensi_{$date}.pdf");
    }

    /**
     * Display a list of registered students.
     */
    public function studentsIndex()
    {
        $students = User::where('role', 'user')->orderBy('name', 'asc')->get();
        return view('admin.students', compact('students'));
    }

    /**
     * Store a new registered student.
     */
    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => trim($request->name),
            'email' => trim($request->email),
            'password' => bcrypt($request->password),
            'role' => 'user',
            'division' => trim($request->division),
        ]);

        return redirect()->back()->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    /**
     * Update a registered student.
     */
    public function updateStudent(Request $request, User $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'password' => 'nullable|string|min:8',
        ]);

        $data = [
            'name' => trim($request->name),
            'email' => trim($request->email),
            'division' => trim($request->division),
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $student->update($data);

        return redirect()->back()->with('success', 'Data mahasiswa berhasil diperbarui!');
    }

    /**
     * Delete a registered student.
     */
    public function deleteStudent(User $student)
    {
        $student->delete();
        return redirect()->back()->with('success', 'Mahasiswa berhasil dihapus!');
    }
}
