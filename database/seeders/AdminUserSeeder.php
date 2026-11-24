<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = trim((string) config('admin.admin.email', ''));
        $password = (string) config('admin.admin.password', '');

        if ($email === '' || $password === '') {
            $this->command?->warn('Admin tidak dibuat. Isi ADMIN_EMAIL dan ADMIN_PASSWORD di .env terlebih dahulu.');

            return;
        }

        $admin = User::firstOrNew(['email' => $email]);

        if ($admin->exists && $admin->hasRole(User::ROLE_SUPERADMIN)) {
            $this->command?->warn('Email tersebut sudah digunakan oleh superadmin.');

            return;
        }

        $admin->name = config('admin.admin.name', 'Admin Lactona');
        $admin->password = $password;
        $admin->is_active = true;
        $admin->save();
        $admin->syncRoles(User::ROLE_ADMIN);
    }
}
