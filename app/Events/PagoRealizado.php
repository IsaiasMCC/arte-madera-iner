<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\DetallePago;

class PagoRealizado implements ShouldBroadcast
{
    use SerializesModels;

    public $detallePago;

    public function __construct(DetallePago $detallePago)
    {
        $this->detallePago = $detallePago;
    }

    public function broadcastOn()
    {
        // Canal del usuario dueño del pedido
        return new PrivateChannel('usuario.' . $this->detallePago->pago->pedido->user_id . '.pagos');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->detallePago->id,
            'monto' => $this->detallePago->monto,
            'saldo_pendiente' => $this->detallePago->saldo,
            'pedido_id' => $this->detallePago->pago->pedido->id,
        ];
    }
}
