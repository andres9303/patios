<?php

namespace App\Http\Controllers\Security;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Http\Requests\Security\UserPasswordRequest;
use App\Http\Requests\Security\UserRequest;
use App\Http\Requests\Security\UserRoleRequest;
use App\Models\Master\Company;
use App\Models\Security\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use PasswordValidationRules;
    
    public function index()
    {
        return view('security.user.index');
    }

    public function create()
    {
        return view('security.user.create');
    }

    public function store(UserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.index')->with('success', 'Se ha registrado el usuario correctamente.');
    }

    public function edit(User $user)
    {
        return view('security.user.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());

        return redirect()->route('user.index')->with('success', 'Se ha actualizado la información del usuario correctamente.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')->with('success', 'Se ha eliminado el usuario correctamente.');
    }

    public function editPassword(User $user)
    {
        return view('security.user.edit-password', compact('user'));
    }

    public function updatePassword(UserPasswordRequest $request, User $user)
    {
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.index')->with('success', 'Se ha actualizado la contraseña del usuario correctamente.');
    }

    public function indexRole(User $user)
    {
        return view('security.user.role.index', compact('user'));
    }

    public function createRole(User $user)
    {
        $roles = Role::all();
        $companies = Company::all();

        return view('security.user.role.create', compact('user', 'roles', 'companies'));
    }

    public function storeRole(UserRoleRequest $request, User $user)
    {
        if ($user->roles()->wherePivot('role_id', $request->role_id)->wherePivot('company_id', $request->company_id)->exists()) {
            session()->flash('error', 'El permiso ya existe para este centro de costos.');
            return redirect()->back();
        }

        DB::transaction(function () use ($request, $user) {
            $user->roles()->attach($request->role_id, ['company_id' => $request->company_id]);
        });

        return redirect()->route('user.role.index', ['user' => $user])->with('success', 'Se ha registrado el grupo correctamente.');
    }

    public function destroyRole(User $user, Role $role, Company $company)
    {
        $user->roles()->wherePivot('company_id', $company->id)->detach($role->id);

        return redirect()->route('user.role.index', ['user' => $user])->with('success', 'Se ha eliminado el permiso correctamente.');
    }
}
