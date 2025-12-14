<template>
<AdminLTELayout title="Pagos" index_title="Pagos" index_link="pagos.index">

    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Listado de Pagos</h3>

        </div>

        <div class="card-body table-responsive">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Monto</th>
                        <th>Tipo</th>
                        <th>Pedido</th>
                        <th v-if="canAny" class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="p in pagos" :key="p.id">
                        <td>{{ p.id }}</td>
                        <td>{{ p.fecha }}</td>
                        <td>{{ p.hora }}</td>
                        <td>{{ p.pedido?.total }}</td>
                        <td>{{ p.tipo_pago }}</td>
                        <td># {{ p.pedido?.id }}</td>

                        <td v-if="canAny" class="text-end">

                            <Link 
                                v-if="can('pagos edit')" 
                                :href="route('pagos.show', p.id)"
                                class="btn btn-info btn-sm me-1"
                            >
                                <i class="fa fa-eye"></i>
                            </Link>

                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

</AdminLTELayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'
import Swal from 'sweetalert2'
import axios from 'axios'
import { computed } from 'vue'
import { useCan } from '@/Composables/useCan'

const { can } = useCan()

const props = defineProps({
    pagos: Array
})

// Comprobación de permisos para la columna "Acciones"
const canAny = computed(() =>
    can('pagos edit') ||
    can('pagos delete') ||
    can('pagos create')
)

function destroy(id) {
    Swal.fire({
        title: '¿Eliminar pago?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then(result => {
        if (!result.isConfirmed) return

        axios.delete(`/pagos/${id}`)
            .then(res => {
                Swal.fire('¡Eliminado!', res.data.success || 'Pago eliminado', 'success')
                router.reload({ preserveScroll: true })
            })
            .catch(() => {
                Swal.fire('Error', 'No se pudo eliminar el pago', 'error')
            })
    })
}
</script>
