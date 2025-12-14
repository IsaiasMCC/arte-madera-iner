<template>
    <AdminLTELayout title="Productos más Vendidos" index_title="Productos" index_link="reportes.index">
        <div class="card shadow">
            <div class="card-body">
                <form class="d-flex gap-2 mb-3">
                    <input type="date" v-model="filters.desde" class="form-control">
                    <input type="date" v-model="filters.hasta" class="form-control">
                    <button type="button" @click="filtrar" class="btn btn-warning">Filtrar</button>
                </form>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad Total Vendida</th>
                            <th>Total Ventas ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="p in productos" :key="p.producto_id">
                            <td>{{ p.producto.nombre }}</td>
                            <td>{{ p.total_cantidad }}</td>
                            <td>{{ p.total_ventas }}</td>
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
    productos: Array,
    filters: Object
})

const form = useForm({...props.filters})

function filtrar() {
    const params = new URLSearchParams(form).toString()
    window.location.href = `/reportes/productos/reporte?${params}`
}
</script>
