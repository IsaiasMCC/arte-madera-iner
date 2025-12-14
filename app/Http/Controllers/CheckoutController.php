<?php

namespace App\Http\Controllers;

use App\Models\DetallePago;
use App\Models\DetallePedido;
use App\Models\Envio;
use App\Models\MetodoPago;
use App\Models\Pago;
use App\Models\Pedido;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    /** ✅ Mostrar formulario de selección de pagos */
    public function index()
    {
        $metodos = MetodoPago::all();

        return Inertia::render('Tienda/Pago/Index', [
            'metodos' => $metodos
        ]);
    }



    /** ✅ Crear pedido desde carrito */
    public function pagar(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('tienda.index')
                ->with('error', 'El carrito está vacío.');
        }

        $total = collect($cart)
            ->sum(fn($i) => $i['precio'] * $i['cantidad']);

        $pedido = Pedido::create([
            'fecha' => now()->format('Y-m-d'),
            'hora'  => now()->format('H:i:s'),
            'total' => $total,
            'user_id' => Auth::id(),
            'tienda_id' => $cart[array_key_first($cart)]['producto_id'],
        ]);

        foreach ($cart as $id => $item) {
            $pedido->productos()->attach($id, ['cantidad' => $item['cantidad']]);
        }

        session()->forget('cart');

        return redirect()
            ->route('pedidos.seguimiento', $pedido->id)
            ->with('success', 'Pedido realizado correctamente.');
    }



    /** ✅ Guardar envío (Inertia) */
    public function guardarEnvio(Request $request)
    {
        $request->validate([
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'codigo_postal' => 'nullable|string|max:20',
        ]);

        $cart = session()->get('cart', []);

        $pedido = Pedido::create([
            'fecha' => now()->format('Y-m-d'),
            'hora' => now()->format('H:i:s'),
            'total' => 0,
            'user_id' => Auth::id(),
            'tienda_id' => 1
        ]);

        $total = 0;

        foreach ($cart as $id => $item) {
            DetallePedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $id,
                'cantidad' => $item['cantidad'],
                'subtotal' => $item['precio']
            ]);

            $total += $item['precio'] * $item['cantidad'];
        }

        $pedido->update(['total' => $total]);

        Envio::create([
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'codigo_postal' => $request->codigo_postal,
            'pedido_id' => $pedido->id,
            'estado' => 'PENDIENTE'
        ]);

        return redirect()->route('checkout.pagar', $pedido->id);
    }



    /** ✅ Mostrar pantalla pagar */
    public function mostrarPago(Pedido $pedido)
    {
        $pedido->load('detalles.producto', 'envio');

        return Inertia::render('Tienda/Pago/Realizar', [
            'pedido' => $pedido,
            'metodos' => MetodoPago::all()
        ]);
    }



    /** ✅ Registrar pago principal */
    public function procesarPago(Request $request, Pedido $pedido)
    {
        $request->validate([
            'forma_pago' => 'required|string'
        ]);

        Pago::create([
            'fecha' => now()->format('Y-m-d'),
            'hora'  => now()->format('H:i:s'),
            'monto' => $pedido->total,
            'pedido_id' => $pedido->id,
            'tipo_pago' => $request->forma_pago
        ]);
        session()->forget('cart');
        return redirect()
            ->route('pedidos.mios')
            ->with('success', 'Pago registrado correctamente.');
    }



    /** ✅ Registrar pago por cuotas / detalle */
    public function procesarPagoDetalle(Request $request, Pago $pago)
    {
        $pedido = $pago->pedido;

        $monto = round($request->monto * 100);
        $saldo = round($pedido->getSaldoPendienteAttribute() * 100);

        if ($monto > $saldo) {
            return response()->json([
                'success' => false,
                'error' => 'El monto no puede ser mayor al saldo pendiente'
            ], 422);
        }

        try {
            $detalle = DetallePago::create([
                'pago_id' => $pago->id,
                'fecha' => now()->format('Y-m-d'),
                'hora' => now()->format('H:i:s'),
                'monto' => $monto / 100,
                'saldo' => ($saldo - $monto) / 100,
                'metodo_pago_id' => 1,
            ]);

            $pago->update([
                'monto' => $pago->detallePagos()->sum('monto')
            ]);

            return response()->json([
                'success' => true,
                'monto' => $detalle->monto,
                'saldo' => $detalle->saldo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
