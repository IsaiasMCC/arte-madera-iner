<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Tienda;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PedidoController extends Controller
{
    /** ✅ Seguimiento (usuario) */
    public function seguimiento($id)
    {
        $pedido = Pedido::with('productos')->findOrFail($id);

        return Inertia::render('Pedidos/Seguimiento', [
            'pedido' => $pedido
        ]);
    }



    /** ✅ Lista de pedidos del usuario */
    public function misPedidos()
    {
        $pedidos = Pedido::where('user_id', Auth::id())
            ->with([
                'detalles.producto',
                'envio',
                'pago.detallePagos' => function ($query) {
                    $query->where('estado', 'PAGADO');
                },
                'pago.detallePagos.metodoPago'
            ])
            ->latest()
            ->get()
            ->each->append('saldo_pendiente');

        return Inertia::render('Tienda/Pedidos/Index', [
            'pedidos' => $pedidos
        ]);
    }




    /** ✅ Estado de envío */
    public function estado($id)
    {
        $pedido = Pedido::with('envio', 'detalles.producto')->findOrFail($id);

        return Inertia::render('Tienda/Pedidos/Estado', [
            'pedido' => $pedido
        ]);
    }



    /** ✅ Lista general (admin) */
    public function index()
    {
        $pedidos = Pedido::with('user', 'tienda', 'detalles.producto')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Pedidos/Index', [
            'pedidos' => $pedidos
        ]);
    }



    /** ✅ Editar */
    public function edit($id)
    {
        $pedido = Pedido::with('detalles.producto')->findOrFail($id);

        return Inertia::render('Pedidos/Edit', [
            'pedido'   => $pedido,
            'usuarios' => User::orderBy('nombres')->get(),
            'tiendas'  => Tienda::orderBy('nombre')->get()
        ]);
    }

    public function show($id)
    {
        $pedido = Pedido::with('detalles.producto', 'envio', 'pago.detallePagos')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return Inertia::render('Pedidos/Show', [
            'pedido' => $pedido
        ]);
    }




    /** ✅ Actualizar */
    public function update(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        $validated = $request->validate([
            'fecha'     => 'required|date',
            'hora'      => 'required',
            'total'     => 'required|numeric|min:0',
            'estado'    => 'required|in:PENDIENTE,FINALIZADO,CANCELADO',
            'user_id'   => 'required|exists:users,id',
            'tienda_id' => 'required|exists:tiendas,id',
        ]);

        $pedido->update($validated);

        return redirect()
            ->route('pedidos.edit', $pedido->id)
            ->with('success', 'Pedido actualizado correctamente.');
    }



    /** ✅ Eliminar */
    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pedido eliminado correctamente.'
        ]);
    }
}
