<?php

namespace App\Http\Controllers;

use App\Http\Requests\tiendas\TiendaStoreRequest;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TiendaController extends Controller
{
    public function index()
    {
        return Inertia::render('Tiendas/Index', [
            'tiendas' => Tienda::all()
        ]);
    }

    public function create()
    {

        return Inertia::render('Tiendas/Create');
    }

    public function store(TiendaStoreRequest $request)
    {
        try {
            Tienda::create($request->validated());

            return redirect()
                ->route('tiendas.index')
                ->with('success', 'Tienda creada exitosamente');
        } catch (\Throwable $th) {
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al crear la tienda.');
        }
    }

    public function edit(Tienda $tienda)
    {
        return Inertia::render('Tiendas/Edit', [
            'tienda' => $tienda
        ]);
    }

    public function update(TiendaStoreRequest $request, Tienda $tienda)
    {
        try {
            $tienda->update($request->validated());

            return redirect()
                ->route('tiendas.index')
                ->with('success', 'Tienda actualizada correctamente');
        } catch (\Throwable $th) {
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar la tienda.');
        }
    }

    public function destroy(Tienda $tienda)
    {
        try {
            $tienda->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tienda eliminada correctamente.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar la tienda.'
            ], 500);
        }
    }


    public function tienda(Request $request)
    {
        // Filtros
        $categoria_id = $request->get('categoria');
        $search       = $request->get('search');

        // Categorías para los filtros superiores
        $categorias = Categoria::select('id', 'nombre')->get();

        // Query base
        $query = Producto::where('estado', true);

        // Filtro por categoría
        if ($categoria_id) {
            $query->where('categoria_id', $categoria_id);
        }

        // Búsqueda
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        // Productos finales
        $productos = $query->get();

        return Inertia::render('Tienda/Index', [
            'categorias'    => $categorias,
            'productos'     => $productos,
            'categoria_id'  => $categoria_id,
            'search'        => $search,
        ]);
    }
}
