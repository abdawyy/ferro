<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ferro.com'],
            [
                'name'              => 'FERRO Admin',
                'password'          => Hash::make('Ferro@2025!'),
                'is_admin'          => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created: admin@ferro.com / Ferro@2025!');
    }
}
