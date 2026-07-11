<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display the user attendance page.
     */
    public function index()
    {
        // The user attendance page is public. 
        // We no longer redirect admins because admins might want to test the public page.

        $settings = [
            'kkn_name' => Setting::getValue('kkn_name', 'KKN Posko'),
            'latitude' => (double) Setting::getValue('latitude', '-6.175392'),
            'longitude' => (double) Setting::getValue('longitude', '106.827153'),
            'radius' => (int) Setting::getValue('radius', '200'),
            'check_in_start' => Setting::getValue('check_in_start', '06:30'),
            'check_in_end' => Setting::getValue('check_in_end', '08:00'),
            'check_out_start' => Setting::getValue('check_out_start', '09:30'),
        ];

        $students = User::where('role', 'user')->orderBy('name', 'asc')->get();
        $token = request()->get('ref', '');

        return view('user.index', compact('settings', 'students', 'token'));
    }

    /**
     * Handle the attendance submission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'action' => 'required|in:masuk,pulang',
            'qr_data' => 'required|string',
        ]);

        $name = trim($request->name);
        $division = trim($request->division);
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $action = $request->action;
        $qrData = $request->qr_data;

        // 1. Check if scan originates from Posko QR (static ref)
        if ($qrData !== 'posko_qr') {
            return response()->json([
                'success' => false,
                'message' => 'Barcode/QR Code tidak valid. Silakan scan barcode resmi di layar admin.',
            ], 422);
        }

        // 2. Geolocation/Radius Check
        $targetLat = (double) Setting::getValue('latitude', '-6.175392');
        $targetLng = (double) Setting::getValue('longitude', '106.827153');
        $allowedRadius = (int) Setting::getValue('radius', '200');

        $distance = $this->calculateDistance($latitude, $longitude, $targetLat, $targetLng);

        if ($distance > $allowedRadius) {
            return response()->json([
                'success' => false,
                'message' => sprintf(
                    'Anda berada di luar radius posko KKN. Jarak Anda: %.1f meter. Maksimal radius: %d meter.',
                    $distance,
                    $allowedRadius
                ),
            ], 422);
        }

        // 3. Time Window Validation
        $now = Carbon::now('Asia/Jakarta');
        $todayStr = $now->toDateString();
        $currentTimeStr = $now->toTimeString();

        $checkInStart = Setting::getValue('check_in_start', '06:30');
        $checkInEnd = Setting::getValue('check_in_end', '08:00');
        $checkOutStart = Setting::getValue('check_out_start', '09:30');

        if ($action === 'masuk') {
            // Check-in Time Validation
            if ($currentTimeStr < $checkInStart || $currentTimeStr > $checkInEnd) {
                return response()->json([
                    'success' => false,
                    'message' => "Absen masuk hanya diperbolehkan pukul {$checkInStart} - {$checkInEnd} WIB. Sekarang pukul {$now->format('H:i:s')} WIB.",
                ], 422);
            }

            // Check if already checked in today
            $existing = Attendance::where('name', $name)
                ->where('division', $division)
                ->where('date', $todayStr)
                ->first();

            if ($existing && $existing->check_in) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melakukan absen masuk hari ini.',
                ], 422);
            }

            // Save check-in
            Attendance::updateOrCreate(
                [
                    'name' => $name,
                    'division' => $division,
                    'date' => $todayStr,
                ],
                [
                    'check_in' => $currentTimeStr,
                    'check_in_latitude' => $latitude,
                    'check_in_longitude' => $longitude,
                    'status' => 'Present',
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Absen masuk berhasil direkam! Selamat bertugas.',
            ]);

        } else {
            // Check-out Time Validation
            if ($currentTimeStr < $checkOutStart) {
                return response()->json([
                    'success' => false,
                    'message' => "Absen pulang baru diperbolehkan mulai pukul {$checkOutStart} WIB (Realtime). Sekarang pukul {$now->format('H:i:s')} WIB.",
                ], 422);
            }

            // Find today's attendance record
            $existing = Attendance::where('name', $name)
                ->where('division', $division)
                ->where('date', $todayStr)
                ->first();

            if ($existing && $existing->check_out) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melakukan absen pulang hari ini.',
                ], 422);
            }

            // Save check-out
            Attendance::updateOrCreate(
                [
                    'name' => $name,
                    'division' => $division,
                    'date' => $todayStr,
                ],
                [
                    'check_out' => $currentTimeStr,
                    'check_out_latitude' => $latitude,
                    'check_out_longitude' => $longitude,
                    'status' => $existing ? $existing->status : 'Checkout Only',
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Absen pulang berhasil direkam! Terima kasih atas dedikasi Anda hari ini.',
            ]);
        }
    }

    // validateQrCode method removed as QR is now static

    /**
     * Calculate distance between two points in meters using the Haversine formula.
     */
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // Earth radius in meters

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
