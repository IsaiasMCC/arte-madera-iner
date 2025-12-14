<template>
    <AdminLTELayout title="Categorías" index_title="Categorías" index_link="categorias.index">

        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">

                <Link v-if="can('categorias create')" :href="route('categorias.create')" class="btn btn-primary">
                    Nueva Categoría
                </Link>
            </div>

            <div class="card-body table-responsive">

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th v-if="canAny" class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="c in categorias" :key="c.id">
                            <td>{{ c.id }}</td>
                            <td>{{ c.nombre }}</td>
                            <td>{{ c.descripcion }}</td>

                            <td class="text-end">

                                <Link v-if="can('categorias edit')" :href="route('categorias.edit', c.id)"
                                    class="btn btn-warning btn-sm me-1">
                                    <i class="fa fa-pencil"></i>
                                </Link>

                                <button v-if="can('categorias delete')" class="btn btn-danger btn-sm"
                                    @click="destroy(c.id)">
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
import { computed } from 'vue'
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'
import Swal from 'sweetalert2'
import axios from 'axios'
import { useCan } from '@/Composables/useCan'
const { can } = useCan()

const props = defineProps({
    categorias: Array
})

const canAny = computed(() =>
    can('categorias edit') ||
    can('categorias delete') ||
    can('categorias create')
)



function destroy(id) {
    Swal.fire({
        title: '¿Eliminar categoría?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(route('categorias.destroy', id))
                .then(res => {
                    Swal.fire('¡Eliminado!', res.data.success || 'La categoría se eliminó correctamente', 'success')
                    // Recargar tabla/lista sin recargar toda la página
                    router.reload({ preserveScroll: true });
                })
                .catch(err => {
                    Swal.fire('Error', err.response?.data?.error || 'No se pudo eliminar la categoría', 'error')
                })
        }
    })
}

</script>
