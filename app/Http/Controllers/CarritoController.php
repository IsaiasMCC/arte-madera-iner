<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Inertia\Inertia;

class CarritoController extends Controller
{
    public function index()
    {
        return Inertia::render('Tienda/Carrito/Index', [
            'cart' => session()->get('cart', []),
        ]);
    }

    public function agregar(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        if ($producto->stock <= 0) {
            return back()->with('error', 'Producto sin stock.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['cantidad'] < $producto->stock) {
                $cart[$id]['cantidad']++;
            }
        } else {
            $cart[$id] = [
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => 1,
                'foto' => $producto->foto,
                'producto_id' => $producto->id,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Producto agregado al carrito.');
    }

    public function remover(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Producto eliminado.');
    }
}
