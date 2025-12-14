<template>
    <AdminLTELayout title="Pedidos por Fecha" index_title="Pedidos" index_link="reportes.index">
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
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Tienda</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="p in pedidos" :key="p.id">
                            <td>{{ p.id }}</td>
                            <td>{{ p.user?.nombres || p.user?.nombres }} {{  p.user?.apellidos }}</td>
                            <td>{{ p.tienda?.nombre }}</td>
                            <td>{{ p.fecha }}</td>
                            <td>{{ p.hora }}</td>
                            <td>{{ p.total }}</td>
                            <td>{{ p.estado }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLTELayout>
</template>

<script setup>
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    pedidos: Array,
    filters: Object
})

const form = useForm({...props.filters})

function filtrar() {
    const params = new URLSearchParams(form).toString()
    window.location.href = `/reportes/pedidos/reporte?${params}`
}
</script>
