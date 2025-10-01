<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with(['roles', 'almacen'])->orderBy('name')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::orderBy('name')->pluck('name', 'id');
        $almacenes = Almacen::orderBy('nombre')->pluck('nombre', 'id');

        return view('admin.users.create', compact('roles', 'almacenes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'role_id' => ['required', Rule::exists('roles', 'id')],
            'almacen_id' => ['nullable', Rule::exists('almacenes', 'id')],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'almacen_id' => $validated['almacen_id'] ?? null,
        ]);

        $role = Role::findOrFail($validated['role_id']);
        $user->syncRoles([$role->name]);

        return redirect()->route('admin.users.index')->with('status', 'Usuario creado correctamente.');
    }

    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->pluck('name', 'id');
        $almacenes = Almacen::orderBy('nombre')->pluck('nombre', 'id');
        $userRole = $user->roles->first();

        return view('admin.users.edit', compact('user', 'roles', 'almacenes', 'userRole'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'role_id' => ['required', Rule::exists('roles', 'id')],
            'almacen_id' => ['nullable', Rule::exists('almacenes', 'id')],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->almacen_id = $validated['almacen_id'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $role = Role::findOrFail($validated['role_id']);
        $user->syncRoles([$role->name]);

        return redirect()->route('admin.users.index')->with('status', 'Usuario actualizado correctamente.');
    }
}
