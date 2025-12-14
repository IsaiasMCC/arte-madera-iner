<?php

namespace App\Http\Controllers;

use App\Http\Requests\productos\ProductoStoreRequest;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Tienda;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria', 'tienda')->get();

        return Inertia::render('Productos/Index', [
            'productos' => $productos,
        ]);
    }

    public function create()
    {
        return Inertia::render('Productos/Create', [
            'categorias' => Categoria::all(),
            'tiendas' => Tienda::all(),
        ]);
    }

    public function store(ProductoStoreRequest $request)
    {
        $data = $request->validated();

        try {
            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('productos', 'public');
            }

            Producto::create($data);

            return redirect()->route('productos.index')
                ->with('success', 'Producto creado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al crear el producto.');
        }
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);

        return Inertia::render('Productos/Edit', [
            'producto' => [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'precio' => $producto->precio,
                'stock' => $producto->stock,
                'categoria_id' => $producto->categoria_id,
                'tienda_id' => $producto->tienda_id,
                'foto' => $producto->foto ? asset("storage/{$producto->foto}") : null, // <-- aquí
                'estado' => $producto->estado,
            ],
            'categorias' => Categoria::all(),
            'tiendas' => Tienda::all(),
        ]);
    }


    public function update(ProductoStoreRequest $request, $id)
    {
        $data = $request->validated();
        $producto = Producto::findOrFail($id);

        try {
            if ($request->hasFile('foto')) {
                if ($producto->foto) {
                    Storage::disk('public')->delete($producto->foto);
                }
                $data['foto'] = $request->file('foto')->store('productos', 'public');
            } else {
                unset($data['foto']);
            }

            $producto->update($data);

            return redirect()->route('productos.index')
                ->with('success', 'Producto actualizado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al actualizar el producto.');
        }
    }

    public function destroy($id)
    {
        try {
            $producto = Producto::findOrFail($id);

            if ($producto->foto) {
                Storage::disk('public')->delete($producto->foto);
            }

            $producto->delete();

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }
}
