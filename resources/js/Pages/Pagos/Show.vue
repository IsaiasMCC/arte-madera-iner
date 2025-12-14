<template>
    <AdminLTELayout title="Detalle del Pago" index_title="Pagos" index_link="pagos.index">

        <div class="card shadow">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="m-0">Detalle del Pago</h4>
            </div>

            <div class="card-body">

                <!-- Información del pago -->
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <th>ID Pedido</th>
                            <td>{{ pago.id }}</td>
                        </tr>

                        <tr>
                            <th>Fecha</th>
                            <td>{{ pago.fecha }}</td>
                        </tr>

                        <tr>
                            <th>Hora</th>
                            <td>{{ pago.hora }}</td>
                        </tr>

                        <tr>
                            <th>Monto Inicial </th>
                            <td>Bs. {{ Number(pago?.pedido?.total).toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <th>Monto Pagado
                            </th>
                            <td>Bs. {{ Number(pago.monto).toFixed(2) }}</td>
                        </tr>

                        <tr>
                            <th>Tipo Pago</th>
                            <td>{{ pago.tipo_pago }}</td>
                        </tr>

                        <tr>
                            <th>Pedido Asociado</th>
                            <td>#{{ pedido.id }}</td>
                        </tr>

                        <tr v-if="pago.transaccion_qr">
                            <th>Transacción QR</th>
                            <td>{{ pago.transaccion_qr }}</td>
                        </tr>

                    </tbody>
                </table>

                <!-- Detalles del pago -->
                <h5 class="mb-3">Historial de Detalles del Pago</h5>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Monto</th>
                            <th>Metodo Pago</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="d in detallePagos" :key="d.id">
                            <td>{{ d.id }}</td>
                            <td>{{ d.fecha }}</td>
                            <td>{{ d.hora }}</td>
                            <td>Bs. {{ Number(d.monto).toFixed(2) }}</td>
                            <td> {{d.metodo_pago?.nombre  }}</td>
                            <td>Bs. {{ Number(d.saldo).toFixed(2) }}</td>
                        </tr>

                        <tr v-if="detallePagos.length === 0">
                            <td colspan="5" class="text-center text-muted">
                                No existen registros de detalle.
                            </td>
                        </tr>

                    </tbody>
                </table>
                
                    <div class="mt-3">
                        <Link :href="route('pagos.index')" class="btn btn-danger me-2"> Volver </Link>
                    </div>
            </div>
        </div>

    </AdminLTELayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'

const props = defineProps({
    pago: Object,
    detallePagos: Array,
    pedido: Object,
})
</script>
