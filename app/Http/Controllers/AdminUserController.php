<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $users = User::query()
            ->whereHas('roles', function (Builder $query): void {
                $query->where('name', User::ROLE_ADMIN)
                    ->where('guard_name', 'web');
            })
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create($validated);
        $user->assignRole(User::ROLE_ADMIN);

        ActivityLogger::log(
            'created',
            'Akun Admin',
            "Membuat akun admin {$user->name}",
            $user,
            [
                'name' => $user->name,
                'email' => $user->email,
                'role' => User::ROLE_ADMIN,
            ]
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun admin berhasil dibuat.');
    }

    public function edit(User $user): View
    {
        $this->ensureManagedAdmin($user);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureManagedAdmin($user);

        $oldData = [
            'name' => $user->name,
            'email' => $user->email,
            'is_active' => $user->is_active,
        ];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['required', 'boolean'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->is_active = (bool) $validated['is_active'];

        if (filled($validated['password'] ?? null)) {
            $user->password = $validated['password'];
        }

        $user->save();

        ActivityLogger::log(
            'updated',
            'Akun Admin',
            "Memperbarui akun admin {$user->name}",
            $user,
            [
                'old' => $oldData,
                'new' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_active' => $user->is_active,
                ],
            ]
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun admin berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->ensureManagedAdmin($user);

        ActivityLogger::log(
            'deleted',
            'Akun Admin',
            "Menghapus akun admin {$user->name}",
            $user,
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        );

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun admin berhasil dihapus.');
    }

    private function ensureManagedAdmin(User $user): void
    {
        abort_unless($user->hasRole(User::ROLE_ADMIN), 404);
    }
}
