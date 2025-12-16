<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePago extends Model
{
    protected $table = 'detalle_pagos';
    protected $fillable = ['fecha', 'hora', 'monto', 'saldo', 'pago_id', 'transaccion_qr', 'metodo_pago_id', 'estado'];

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }
}
