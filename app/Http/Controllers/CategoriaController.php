<?php

namespace App\Http\Controllers;

use App\Http\Requests\categorias\CategoriaStoreRequest;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoriaController extends Controller
{
    public function index()
    {
        return Inertia::render('Categorias/Index', [
            'categorias' => Categoria::all()
        ]);
    }

    public function create()
    {
        return Inertia::render('Categorias/Create');
    }

    public function store(CategoriaStoreRequest $request)
    {
        $data = $request->validated();

        try {
            Categoria::create([
                'nombre'      => $data['nombre'],
                'descripcion' => $data['descripcion'],
                'is_active'   => $data['is_active'] ?? true,
            ]);

            return redirect()
                ->route('categorias.index')
                ->with('success', 'Categoría creada exitosamente');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al crear la categoría.');
        }
    }

    public function edit(Categoria $categoria)
    {
        return Inertia::render('Categorias/Edit', [
            'categoria' => $categoria
        ]);
    }

    public function update(CategoriaStoreRequest $request, Categoria $categoria)
    {
        $data = $request->validated();

        try {
            $categoria->update([
                'nombre'      => $data['nombre'],
                'descripcion' => $data['descripcion'],
                'is_active'   => $data['is_active'] ?? true,
            ]);

            return redirect()
                ->route('categorias.index')
                ->with('success', 'Categoría actualizada correctamente');
        } catch (\Throwable $th) {
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al editar la categoría.');
        }
    }

    public function destroy(Categoria $categoria)
    {
        try {
            $categoria->delete();

            return response()->json([
                'success' => true,
                'message' => 'Categoría eliminada correctamente.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar la categoría.'
            ], 500);
        }
    }
}
