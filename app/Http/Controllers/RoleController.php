<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\roles\RoleStore;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
            'can' => [
                'create'   => auth()->user()->can('roles create'),
                'edit'     => auth()->user()->can('roles edit'),
                'delete'   => auth()->user()->can('roles delete'),
                'perms'    => auth()->user()->can('roles permisos'),
            ]
        ]);
    }

    public function create()
    {
        return Inertia::render('Roles/Create');
    }


    public function store(RoleStore $request)
    {
        $data = $request->validated();

        try {
            Role::create([
                'name'        => $data['name'],
                'descripcion' => $data['description'] ?? '',
                'is_active'   => true
            ]);

            return redirect()
                ->route('roles.index')
                ->with('success', 'Rol creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al crear el rol.');
        }
    }


    public function edit(Role $role)
    {
        return Inertia::render('Roles/Edit', [
            'role' => $role
        ]);
    }


    public function update(RoleStore $request, Role $role)
    {
        $data = $request->validated();

        try {
            $role->update([
                'name'        => $data['name'],
                'descripcion' => $data['description'] ?? '',
                'is_active'   => $data['is_active'] ?? true,
            ]);

            return redirect()
                ->route('roles.index')
                ->with('success', 'Rol actualizado');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al editar el rol.');
        }
    }


    public function destroy(Role $role)
    {
        try {
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rol eliminado correctamente.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Ocurrió un error al eliminar el rol.'
            ], 500);
        }
    }


    public function show(Role $role)
    {
        return Inertia::render('Roles/Show', [
            'role'        => $role,
            'permissions' => Permission::all(),
            'assigned'    => $role->permissions->pluck('id'),
        ]);
    }


    public function updatePermissions(Request $request, Role $role)
    {
        try {
            $permissions = $request->input('permissions', []);
            $role->syncPermissions($permissions);
            
            return redirect()->back()
            ->with('success', 'Permisos actualizado correctamente.');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('error', 'Ocurrió un error al actualizar permisos.');
        }
    }
}
