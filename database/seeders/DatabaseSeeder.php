<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default Admin User
        User::updateOrCreate(
            ['email' => 'admin@kkn.com'],
            [
                'name' => 'Administrator KKN',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Seed default Student User
        User::updateOrCreate(
            ['email' => 'user@kkn.com'],
            [
                'name' => 'Budi Santoso',
                'password' => bcrypt('password'),
                'role' => 'user',
                'division' => 'Kesehatan',
            ]
        );

        // Seed default settings
        $defaultSettings = [
            'kkn_name' => 'KKN Posko Desa Sukamaju',
            'latitude' => '-6.175392', // Monas, Jakarta as default center
            'longitude' => '106.827153',
            'radius' => '200',
            'check_in_start' => '06:30',
            'check_in_end' => '08:00',
            'check_out_start' => '09:30',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}

