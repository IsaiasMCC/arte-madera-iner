<?php
namespace App\Http\Controllers;

use App\Models\DetallePago;
use App\Models\DetallePedido;
use App\Models\Envio;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReporteController extends Controller
{
    public function index()
    {
        return Inertia::render('Reportes/Index');
    }

    public function pedidosPorFecha(Request $request)
    {
        $query = Pedido::query()->with('user')->with('tienda');
        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('fecha', [$request->desde, $request->hasta]);
        }
        $pedidos = $query->orderBy('fecha', 'desc')->get();

        return Inertia::render('Reportes/Pedidos', [
            'pedidos' => $pedidos,
            'filters' => $request->only(['desde','hasta'])
        ]);
    }

    public function enviosPorEstado(Request $request)
    {
        $query = Envio::with('pedido.user');
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereHas('pedido', function ($q) use ($request) {
                $q->whereBetween('fecha', [$request->desde, $request->hasta]);
            });
        }
        $envios = $query->orderBy('id', 'desc')->get();

        return Inertia::render('Reportes/Envios', [
            'envios' => $envios,
            'filters' => $request->only(['estado','desde','hasta'])
        ]);
    }

    public function productosMasVendidos(Request $request)
    {
        $query = DetallePedido::select(
            'producto_id',
            DB::raw('SUM(cantidad) as total_cantidad'),
            DB::raw('SUM(subtotal) as total_ventas')
        )->groupBy('producto_id');

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereHas('pedido', function ($q) use ($request) {
                $q->whereBetween('fecha', [$request->desde, $request->hasta]);
            });
        }

        $productos = $query->with('producto')->orderByDesc('total_cantidad')->get();

        return Inertia::render('Reportes/Productos', [
            'productos' => $productos,
            'filters' => $request->only(['desde','hasta'])
        ]);
    }

    public function ventas(Request $request)
    {
        $query = DetallePago::with('pago.pedido.user', 'pago.pedido.tienda');

        if ($request->filled('desde')) {
            $query->where('fecha', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->where('fecha', '<=', $request->hasta);
        }

        $detallePagos = $query->get();

        return Inertia::render('Reportes/Ventas', [
            'detallePagos' => $detallePagos,
            'filters' => $request->only(['desde','hasta'])
        ]);
    }
}
