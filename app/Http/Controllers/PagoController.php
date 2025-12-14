<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PagoController extends Controller
{
    public function index()
    {
        return Inertia::render('Pagos/Index', [
            'pagos' => Pago::with('pedido')->get(),
            'can' => [
                'create' => true,
                'edit'   => true,
                'delete' => true
            ]
        ]);
    }

    public function show(Pago $pago)
    {
        // Cargar detalles del pago
        $pago->load('detallePagos.MetodoPago', 'pedido');

        return inertia('Pagos/Show', [
            'pago' => $pago,
            'detallePagos' => $pago->detallePagos,
            'pedido' => $pago->pedido,
        ]);
    }
}
