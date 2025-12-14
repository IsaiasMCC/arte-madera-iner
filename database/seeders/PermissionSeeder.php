<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Módulo Usuarios
            'roles index',
            'roles store',
            'roles permisos',
            'roles edit',
            'roles delete',
            'roles create',

            'usuarios index',
            'usuarios store',
            'usuarios edit',
            'usuarios delete',
            'usuarios create',

            // Módulo Tiendas
            'tiendas index',
            'tiendas store',
            'tiendas edit',
            'tiendas delete',
            'tiendas create',

            // Módulo Categorías
            'categorias index',
            'categorias store',
            'categorias edit',
            'categorias delete',
            'categorias create',

            // Módulo Productos
            'productos index',
            'productos store',
            'productos edit',
            'productos delete',
            'productos create',

            // Módulo Pedidos
            'pedidos index',
            'pedidos store',
            'pedidos edit',
            'pedidos delete',

            //MODULO DE ENVIOS
            'envios index',
            'envios edit',

            // Módulo Métodos de Pago
            'metodos_pago index',
            'metodos_pago store',
            'metodos_pago edit',
            'metodos_pago delete',
            'metodos_pago create',

            // Módulo Pagos
            'pagos index',
            'pagos edit',
            //REPORTES

            'reportes ventas',
            'reportes envios',
            'reportes productos',
            'reportes pedidos',

        ];

        // Crear los permisos si no existen
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
