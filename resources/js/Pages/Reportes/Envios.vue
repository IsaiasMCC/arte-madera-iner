<template>
    <AdminLTELayout title="Envíos por Estado" index_title="Envíos" index_link="reportes.index">
        <div class="card shadow">
            <div class="card-body">
                <form class="d-flex gap-2 mb-3">
                    <select v-model="filters.estado" class="form-control">
                        <option value="">Todos</option>
                        <option value="PENDIENTE">Pendiente</option>
                        <option value="PREPARANDO">Preparando</option>
                        <option value="EN_CAMINO">En Camino</option>
                        <option value="ENTREGADO">Entregado</option>
                    </select>
                    <input type="date" v-model="filters.desde" class="form-control">
                    <input type="date" v-model="filters.hasta" class="form-control">
                    <button type="button" @click="filtrar" class="btn btn-success">Filtrar</button>
                </form>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Dirección</th>
                            <th>Ciudad</th>
                            <th>Estado</th>
                            <th>Fecha Pedido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="e in envios" :key="e.id">
                            <td>{{ e.id }}</td>
                            <td>{{ e.pedido.id }}</td>
                            <td>{{ e.pedido.user?.nombres || e.pedido.user?.name }} {{ e.pedido.user?.apellidos }}</td>
                            <td>{{ e.direccion }}</td>
                            <td>{{ e.ciudad }}</td>
                            <td>{{ e.estado }}</td>
                            <td>{{ e.pedido.fecha }}</td>
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
    envios: Array,
    filters: Object
})

const form = useForm({...props.filters})

function filtrar() {
    const params = new URLSearchParams(form).toString()
    window.location.href = `/reportes/envios/reporte?${params}`
}
</script>
