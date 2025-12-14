<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/Login');
    }


    public function showRegisterForm()
    {
        return Inertia::render('Auth/Register');
    }


    public function store(AuthRequest $request)
    {
        $credentials = $request->validated();

        try {

            $userAuth = User::where('estado', true)
                ->where('email', $credentials['email'])
                ->first();

            if (!$userAuth) {
                return back()
                    ->with('error', 'El usuario no está activo o no existe.')
                    ->withInput();
            }

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                $rol = Auth::user()->rol_id;
                return $rol == 3
                    ? redirect()->route('tiendas.tienda')
                    : redirect()->route('home');
            }



            return back()
                ->with('error', 'Credenciales incorrectas.')
                ->withInput();
        } catch (\Throwable $th) {
            return back()
                ->with('error', 'Credenciales incorrectas.')
                ->withInput();
        }
    }



    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.index');
    }



    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'ci' => $data['ci'],
            'nombres' => $data['nombres'],
            'apellidos' => $data['apellidos'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'rol_id' => 3,
            'estado' => true,
        ]);

        Auth::login($user);

        return redirect()
            ->route('tiendas.tienda')
            ->with('success', 'Registro exitoso. Bienvenido!');
    }
}
