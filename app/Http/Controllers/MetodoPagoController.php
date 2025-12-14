<?php

namespace App\Http\Controllers;

use App\Http\Requests\metodopagos\MetodoPagoStoreRequest;
use App\Models\MetodoPago;
use Inertia\Inertia;

class MetodoPagoController extends Controller
{
    public function index()
    {
        return Inertia::render('MetodosPago/Index', [
            'metodos' => MetodoPago::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('MetodosPago/Create');
    }

    public function store(MetodoPagoStoreRequest $request)
    {
        $data = $request->validated();

        try {
            MetodoPago::create($data);

            return redirect()
                ->route('metodos_pago.index')
                ->with('success', 'Método de pago creado exitosamente');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al crear el método de pago.');
        }
    }

    public function edit(MetodoPago $metodos_pago)
    {
        return Inertia::render('MetodosPago/Edit', [
            'metodo' => $metodos_pago
        ]);
    }

    public function update(MetodoPagoStoreRequest $request, MetodoPago $metodos_pago)
    {
        $data = $request->validated();

        try {
            $metodos_pago->update($data);

            return redirect()
                ->route('metodos_pago.index')
                ->with('success', 'Método de pago actualizado correctamente');

        } catch (\Throwable $th) {
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar el método.');
        }
    }

    public function destroy(MetodoPago $metodos_pago)
    {
        try {
            $metodos_pago->delete();

            return response()->json([
                'success'=>true,
                'message'=>'Método eliminado correctamente'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success'=>false,
                'message'=>'Error al eliminar'
            ],500);
        }
    }
}
