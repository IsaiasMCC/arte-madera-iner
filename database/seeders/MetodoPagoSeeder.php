<?php

namespace Database\Seeders;

use App\Models\MetodoPago;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MetodoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metodos = [
            ['nombre' => 'Efectivo', 'descripcion' => 'Pago en efectivo al momento de la entrega'],
            ['nombre' => 'Qr Pago Facil', 'descripcion' => 'Pago Qr Pago Facil'],
        ];

        foreach ($metodos as $m) {
            MetodoPago::create($m);
        }
    }
}
