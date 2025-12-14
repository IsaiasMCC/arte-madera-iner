<style>
    .bg-purple {
        background-color: #6f42c1 !important;
    }
</style>

<template>
    <AdminLTELayout title="Envíos">

        <h5>Envío de Pedidos de Clientes</h5>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pedido</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Total</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th v-if="canAny" class="text-end">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="e in envios" :key="e.id">
                    <td>{{ e.id }}</td>
                    <td>#{{ e.pedido?.id }}</td>
                    <td>{{ e.pedido?.fecha }}</td>
                    <td>{{ e.pedido?.hora }}</td>
                    <td>{{ e.pedido?.total }}</td>
                    <td>{{ e.pedido?.user?.nombres }} {{ e.pedido?.user?.apellidos }}</td>

                    <td>
                        <span :class="['badge', estadoInfo(e.estado).class]">
                            {{ estadoInfo(e.estado).label }}
                        </span>
                    </td>

                    <td v-if="canAny" class="text-end">
                        <Link
                            v-if="can('envios edit')"
                            :href="route('envios.edit', e.id)"
                            class="btn btn-warning btn-sm"
                        >
                            <i class="fa fa-pencil"></i>
                        </Link>
                    </td>

                </tr>
            </tbody>
        </table>

    </AdminLTELayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'
import { useCan } from '@/Composables/useCan'
import { computed } from 'vue'
const { can } = useCan()

const props = defineProps({
    envios: Array
})

/* Permisos necesarios */
const canAny = computed(() =>
    can('envios edit')
)

/* Estados */
const estadoInfo = (estado) => {
    const map = {
        PENDIENTE: { label: 'Pendiente', class: 'bg-warning text-dark' },
        PREPARANDO: { label: 'Preparando', class: 'bg-primary text-white' },
        EN_CAMINO: { label: 'En camino', class: 'bg-purple text-white' },
        ENTREGADO: { label: 'Entregado', class: 'bg-success text-white' }
    }
    return map[estado] || { label: estado, class: 'bg-secondary text-white' }
}
</script>
