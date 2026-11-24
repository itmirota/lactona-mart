<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'role')) {
            return;
        }

        $permissionNames = [
            'dashboard.view',
            'products.manage',
            'categories.manage',
            'discounts.manage',
            'users.manage',
        ];

        foreach ($permissionNames as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        Role::findOrCreate('admin', 'web')->syncPermissions([
            'dashboard.view',
            'products.manage',
            'categories.manage',
            'discounts.manage',
        ]);

        Role::findOrCreate('superadmin', 'web')->syncPermissions($permissionNames);

        DB::table('users')
            ->select(['id', 'role'])
            ->orderBy('id')
            ->chunkById(100, function (Collection $users): void {
                foreach ($users as $userData) {
                    if (! in_array($userData->role, ['admin', 'superadmin'], true)) {
                        continue;
                    }

                    $user = User::find($userData->id);
                    $user?->syncRoles($userData->role);
                }
            });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('users') || Schema::hasColumn('users', 'role')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->string('role', 30)->default('user')->after('email');
        });

        User::query()
            ->select('id')
            ->orderBy('id')
            ->chunkById(100, function (Collection $users): void {
                foreach ($users as $user) {
                    $role = $user->hasRole('superadmin')
                        ? 'superadmin'
                        : ($user->hasRole('admin') ? 'admin' : 'user');

                    $user->forceFill(['role' => $role])->save();
                }
            });
    }
};
