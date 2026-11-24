<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuperadminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = trim((string) config('admin.superadmin.email', ''));
        $password = (string) config('admin.superadmin.password', '');
        $superadmin = $email === '' ? new User : User::firstOrNew(['email' => $email]);

        if ($email === '') {
            $existingAdmins = User::query()
                ->whereHas('roles', function (Builder $query): void {
                    $query->where('name', User::ROLE_ADMIN)
                        ->where('guard_name', 'web');
                })
                ->limit(2)
                ->get();

            if ($existingAdmins->count() === 1) {
                $superadmin = $existingAdmins->first();
                $email = $superadmin->email;
            } else {
                $this->command?->warn('Superadmin tidak dibuat. Isi SUPERADMIN_EMAIL atau ADMIN_EMAIL di .env.');

                return;
            }
        }

        if (! $superadmin->exists && $password === '') {
            $this->command?->warn('Superadmin belum dibuat. Isi SUPERADMIN_PASSWORD di .env.');

            return;
        }

        DB::transaction(function () use ($superadmin, $email, $password): void {
            $previousSuperadmins = User::query()
                ->whereHas('roles', function (Builder $query): void {
                    $query->where('name', User::ROLE_SUPERADMIN)
                        ->where('guard_name', 'web');
                });

            if ($superadmin->exists) {
                $previousSuperadmins->where('id', '<>', $superadmin->getKey());
            }

            $previousSuperadmins->get()->each(function (User $user): void {
                $user->syncRoles(User::ROLE_ADMIN);
            });

            if (! $superadmin->exists) {
                $superadmin->fill([
                    'name' => config('admin.superadmin.name', 'Superadmin Lactona'),
                    'email' => $email,
                    'password' => $password,
                ]);
            }

            $superadmin->is_active = true;
            $superadmin->save();
            $superadmin->syncRoles(User::ROLE_SUPERADMIN);
        });
    }
}
