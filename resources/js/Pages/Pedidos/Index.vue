<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({
    pedidos: Array
})
</script>

<template>
    <AppLayout>
        <template #header>
            <h2> Mis Pedidos </h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Inicio</li>
            </ol>
        </template>

        <!-- CONTENIDO EXACTO -->
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox-content style-tema">

                    <div class="table-responsive">

                        <table class="table table-striped table-bordered table-hover">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Tienda</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="pedido in pedidos" :key="pedido.id">

                                    <td>{{ pedido.id }}</td>
                                    <td>{{ pedido.fecha }}</td>
                                    <td>{{ pedido.hora }}</td>

                                    <td>
                                        ${{ Number(pedido.total).toFixed(2) }}
                                    </td>

                                    <td>
                                        <span class="badge" :class="{
                                            'bg-warning': pedido.estado === 'CANCELADO',
                                            'bg-info': pedido.estado === 'PENDIENTE',
                                            'bg-success': pedido.estado === 'FINALIZADO'
                                        }">
                                            {{ pedido.estado.toLowerCase() }}
                                        </span>
                                    </td>

                                    <td>{{ pedido.tienda.nombre }}</td>

                                    <td>
                                        <Link :href="route('pedidos.edit', pedido.id)" class="btn btn-info btn-sm">
                                            <i class="fa fa-eye"></i>
                                            Actualizar Pedido
                                        </Link>

                                        <Link :href="route('envios.edit', pedido.envio.id)"
                                            class="btn btn-primary btn-sm">
                                            <i class="fa fa-truck"></i>
                                            Ver estado Envío
                                        </Link>
                                    </td>

                                </tr>
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>
        </div>

    </AppLayout>
</template>
