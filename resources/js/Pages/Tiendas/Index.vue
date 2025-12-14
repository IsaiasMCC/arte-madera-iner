<template>
    <AdminLTELayout title="Tiendas" index_title="Tiendas" index_link="tiendas.index">

        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <Link v-if="can('tiendas create')" :href="route('tiendas.create')" class="btn btn-primary">
                    Nueva Tienda
                </Link>
            </div>

            <div class="card-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>NIT</th>
                            <th>Teléfono</th>
                            <th>Ubicación</th>
                            <th v-if="canAny" style="width: 120px;">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="t in tiendas" :key="t.id">
                            <td>{{ t.nombre }}</td>
                            <td>{{ t.nit ?? '-' }}</td>
                            <td>{{ t.telefono ?? '-' }}</td>
                            <td>{{ t.ubicacion ?? '-' }}</td>

                            <td v-if="canAny" class="text-center d-flex">
                                <Link v-if="can('tiendas edit')" :href="route('tiendas.edit', t.id)" class="btn btn-warning btn-sm me-1">
                                   <i class="fa fa-pencil"></i>
                                </Link>

                                <button v-if="can('tiendas delete')" class="btn btn-danger btn-sm" @click="destroy(t.id)">
                                  <i class="fa fa-trash"></i>
                                </button>
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

const props = defineProps({
    tiendas: Array
})

const { can } = useCan()

const canAny = computed(() =>
    can('tiendas edit') || can('tiendas delete') || can('tiendas create')
)

function destroy(id) {
    Swal.fire({
        title: '¿Eliminar tienda?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(async (result) => {
        if (!result.isConfirmed) return;

        try {
            const response = await axios.delete(route('tiendas.destroy', id));

            Swal.fire({
                title: 'Eliminado',
                text: response.data?.message || 'Tienda eliminada correctamente.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });

            router.reload({ preserveScroll: true });

        } catch (error) {
            Swal.fire({
                title: 'Error',
                text: error.response?.data?.message || 'No se pudo eliminar la tienda.',
                icon: 'error'
            });
        }
    });
}
</script>
