<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\users\PostUserRequest;
use App\Http\Requests\users\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    
    public function index()
    {
        $users = User::with('role')->get();

        return Inertia::render('Usuarios/Index', [
            'users' => $users,
            'can' => [
                'create' => optional(auth()->user())->can('usuarios create'),
                'edit'   => optional(auth()->user())->can('usuarios edit'),
                'delete' => optional(auth()->user())->can('usuarios delete'),
            ]

        ]);
    }


    
    public function create()
    {
        return Inertia::render('Usuarios/Create', [
            'roles' => Role::all()
        ]);
    }



    public function store(PostUserRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $user = User::create([
                'ci'        => $data['ci'],
                'nombres'   => $data['name'],
                'apellidos' => $data['lastname'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['password']),
                'rol_id'    => $data['role'],
            ]);

            $user->assignRole($data['role']);

            DB::commit();

            return redirect()
                ->route('usuarios.index')
                ->with('success','Usuario creado');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => $e->getMessage()
            ])->withInput();
        }
    }



    public function edit($id)
    {
        $user = User::findOrFail($id);

        return Inertia::render('Usuarios/Edit', [
            'user'  => $user,
            'roles' => Role::all()
        ]);
    }



    public function update(UpdateUserRequest $request, $id)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            $user->nombres   = $data['name'];
            $user->apellidos = $data['lastname'];
            $user->email     = $data['email'];
            $user->rol_id    = $data['role'];
            $user->estado    = $data['estado'];

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->syncRoles($data['role']);

            $user->save();

            DB::commit();

            return redirect()
                ->route('usuarios.index')
                ->with('success','Usuario actualizado');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }



    public function destroy($id)
    {
        try {
            User::findOrFail($id)->delete();

            return response()->json([
                'success'=>true,
                'message'=>'Eliminado'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()
            ],500);
        }
    }
}
