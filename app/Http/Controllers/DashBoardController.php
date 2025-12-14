<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Pedido;
use App\Models\Envio;
use App\Models\Pago;
use App\Models\DetallePedido;

class DashboardController extends Controller
{
    public function index()
    {
        // Contadores generales
        $countUsers = User::count();
        $countPedidos = Pedido::count();
        $countEnvios = Envio::count();
        $totalPagos = Pago::sum('monto');

        dd($countUsers);

        // Ventas por fecha
        $ventas = Pago::selectRaw('fecha, SUM(monto) as total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();
        $ventasLabels = $ventas->pluck('fecha')->map(fn($f) => $f->format('d/m/Y')); // opcional: formatear
        $ventasData = $ventas->pluck('total');

        // Pedidos por estado
        $pedidos = Pedido::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->get();
        $pedidosEstados = $pedidos->pluck('estado');
        $pedidosData = $pedidos->pluck('total');

        // Productos más vendidos
        $productos = DetallePedido::selectRaw('producto_id, SUM(cantidad) as total')
            ->with('producto')
            ->groupBy('producto_id')
            ->orderByDesc('total')
            ->get();
        $productosLabels = $productos->map(fn($p) => $p->producto->nombre);
        $productosData = $productos->pluck('total');

        return Inertia::render('Dashboard', [
            'stats' => [
                'usuarios' => $countUsers,
                'pedidos' => $countPedidos,
                'envios' => $countEnvios,
                'totalPagos' => $totalPagos,
            ],
            'ventasLabels' => $ventasLabels,
            'ventasData' => $ventasData,
            'pedidosEstados' => $pedidosEstados,
            'pedidosData' => $pedidosData,
            'productosLabels' => $productosLabels,
            'productosData' => $productosData,
        ]);
    }
}
