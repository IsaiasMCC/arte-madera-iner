<?php

namespace Database\Seeders;

use App\Models\Pago;
use App\Models\Pedido;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $pedidos = Pedido::all();

        if ($pedidos->count() == 0) {
            $this->command->info('No hay pedidos para asignar pagos.');
            return;
        }

        $tiposPago = ['CREDITO', 'CONTADO'];

        foreach ($pedidos as $pedido) {
            Pago::create([
                'pedido_id' => $pedido->id,
                'fecha' => $faker->date('Y-m-d'),
                'hora' => $faker->time('H:i'),
                'monto' => $pedido->total, // podrías ajustar para pagos parciales si quieres
                'tipo_pago' => $tiposPago[array_rand($tiposPago)],
            ]);
        }
    }
}
