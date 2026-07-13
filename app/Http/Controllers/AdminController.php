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
     * Weekly Attendance Logs View.
     */
    public function weeklyLogs(Request $request)
    {
        // 1. Build weekly list options (12 weeks range)
        $now = Carbon::now('Asia/Jakarta');
        $weeks = [];
        for ($i = 0; $i < 12; $i++) {
            $start = $now->copy()->startOfWeek()->subWeeks($i);
            $end = $now->copy()->endOfWeek()->subWeeks($i);
            $weeks[] = [
                'start' => $start->toDateString(),
                'end' => $end->toDateString(),
                'label' => "Minggu " . $start->isoWeek() . " (" . $start->translatedFormat('d M') . " - " . $end->translatedFormat('d M Y') . ")",
            ];
        }

        // 2. Determine selected week
        $selectedStart = $request->get('start', $weeks[0]['start']);
        $selectedEnd = $request->get('end', $weeks[0]['end']);

        // 3. Fetch records
        $attendances = Attendance::whereBetween('date', [$selectedStart, $selectedEnd])
            ->orderBy('date', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.weekly_logs', compact('weeks', 'selectedStart', 'selectedEnd', 'attendances'));
    }

    /**
     * Export to Excel.
     */
    public function exportExcel(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        if (!$start || !$end) {
            return redirect()->back()->with('error', 'Pilih rentang tanggal terlebih dahulu.');
        }

        $attendances = Attendance::whereBetween('date', [$start, $end])
            ->orderBy('date', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $weekLabel = Carbon::parse($start)->isoWeek();
        return Excel::download(new AttendanceExport($attendances), "laporan_absensi_minggu_{$weekLabel}.xlsx");
    }

    /**
     * Export to PDF.
     */
    public function exportPdf(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        if (!$start || !$end) {
            return redirect()->back()->with('error', 'Pilih rentang tanggal terlebih dahulu.');
        }

        $attendances = Attendance::whereBetween('date', [$start, $end])
            ->orderBy('date', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        $kknName = Setting::getValue('kkn_name', 'KKN Posko');
        $weekLabel = Carbon::parse($start)->isoWeek();

        $pdf = Pdf::loadView('exports.attendance_pdf', compact('attendances', 'start', 'end', 'kknName', 'weekLabel'));

        // Output to browser or download
        return $pdf->download("laporan_absensi_minggu_{$weekLabel}.pdf");
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
