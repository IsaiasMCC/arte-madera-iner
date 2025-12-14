<template>
    <AdminLTELayout title="Ventas por Fecha" index_title="Ventas" index_link="reportes.index">
        <div class="card shadow">
            <div class="card-body">
                <form class="d-flex gap-2 mb-3">
                    <input type="date" v-model="filters.desde" class="form-control">
                    <input type="date" v-model="filters.hasta" class="form-control">
                    <button type="button" @click="filtrar" class="btn btn-primary">Filtrar</button>
                </form>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Pago</th>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Tienda</th>
                            <th>Fecha Pago</th>
                            <th>Hora</th>
                            <th>Monto ($)</th>
                            <th>Saldo ($)</th>
                            <th>Tipo de Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="dp in detallePagos" :key="dp.id">
                            <td>{{ dp.pago.id }}</td>
                            <td>{{ dp.pago.pedido.id }}</td>
                            <td>{{ dp.pago.pedido.user?.nombres || dp.pago.pedido.user?.name }}</td>
                            <td>{{ dp.pago.pedido.tienda.nombre }}</td>
                            <td>{{ dp.fecha }}</td>
                            <td>{{ dp.hora }}</td>
                            <td>{{ dp.monto }}</td>
                            <td>{{ dp.saldo }}</td>
                            <td>{{ dp.pago.tipo_pago }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLTELayout>
</template>

<script setup>
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    detallePagos: Array,
    filters: Object
})

const form = useForm({...props.filters})

function filtrar() {
    const params = new URLSearchParams(form).toString()
    window.location.href = `/reportes/ventas/reporte?${params}`
}
</script>
