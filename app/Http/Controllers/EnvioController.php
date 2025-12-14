<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EnvioController extends Controller
{
    public function index()
    {
        $envios = Envio::with('pedido.user')->get();

        return Inertia::render('Envios/Index', [
            'envios' => $envios
        ]);
    }

    public function create()
    {
        $estados = [
            'PENDIENTE' => 'Pendiente',
            'PREPARANDO' => 'Preparando',
            'EN_CAMINO' => 'En camino',
            'ENTREGADO' => 'Entregado',
        ];

        return Inertia::render('Envios/Create', [
            'estados' => $estados
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'estado' => 'required|in:PENDIENTE,PREPARANDO,EN_CAMINO,ENTREGADO',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'codigo_postal' => 'nullable|string|max:20',
        ]);

        Envio::create($validated);

        return redirect()
            ->route('envios.index')
            ->with('success', 'Envío creado correctamente.');
    }

    public function edit(Envio $envio)
    {
        $estados = [
            'PENDIENTE' => 'Pendiente',
            'PREPARANDO' => 'Preparando',
            'EN_CAMINO' => 'En camino',
            'ENTREGADO' => 'Entregado',
        ];

        return Inertia::render('Envios/Edit', [
            'envio' => $envio,
            'estados' => $estados
        ]);
    }

    public function update(Request $request, Envio $envio)
    {
        $validated = $request->validate([
            'estado' => 'required|in:PENDIENTE,PREPARANDO,EN_CAMINO,ENTREGADO',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'codigo_postal' => 'nullable|string|max:20',
        ]);

        $envio->update($validated);

        return redirect()
            ->route('envios.index', $envio->id)
            ->with('success', 'Envío actualizado correctamente.');
    }
}
