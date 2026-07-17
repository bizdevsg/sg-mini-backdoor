<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\UserManagement\StoreUserRequest;
use App\Http\Requests\UserManagement\UpdateUserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $role = UserRole::tryFrom($request->string('role')->toString());

        $users = User::query()
            ->when(
                $request->string('search')->isNotEmpty(),
                fn ($query) => $query->where(function ($builder) use ($request) {
                    $search = $request->string('search')->toString();

                    $builder
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                })
            )
            ->when($role, fn ($query) => $query->where('role', $role->value))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('user-management.index', [
            'adminCount' => User::query()->where('role', UserRole::Admin->value)->count(),
            'superadminCount' => User::query()->where('role', UserRole::Superadmin->value)->count(),
            'availableRoles' => UserRole::cases(),
            'selectedRole' => $role,
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        return view('user-management.create', $this->formViewData());
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::query()->create($request->validated());

        return redirect()
            ->route('user-management.index')
            ->with('status', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('user-management.edit', [
            ...$this->formViewData(),
            'managedUser' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        if (($validated['password'] ?? null) === null) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('user-management.index')
            ->with('status', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ((int) auth()->id() === (int) $user->id) {
            return redirect()
                ->route('user-management.index')
                ->with('status', 'User yang sedang login tidak bisa dihapus.');
        }

        if (
            $user->isSuperadmin()
            && User::query()
                ->where('role', UserRole::Superadmin->value)
                ->count() <= 1
        ) {
            return redirect()
                ->route('user-management.index')
                ->with('status', 'Superadmin terakhir tidak bisa dihapus.');
        }

        $user->delete();

        return redirect()
            ->route('user-management.index')
            ->with('status', 'User berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formViewData(): array
    {
        return [
            'availableRoles' => UserRole::cases(),
        ];
    }
}
