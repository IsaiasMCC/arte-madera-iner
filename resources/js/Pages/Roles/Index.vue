<template>
    <AdminLTELayout title="Roles">

        <div class="card shadow">

            <div class="card-header d-flex justify-content-between">

                <button v-if="can.create" class="btn btn-primary" @click="$inertia.get(route('roles.create'))">
                    Agregar Rol
                </button>

            </div>

            <div class="card-body">

                <table class="table table-hover">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th v-if="can.edit || can.delete || can.perms">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="r in roles" :key="r.id">

                            <td>{{ r.id }}</td>
                            <td>{{ r.name }}</td>

                            <td>
                                <span :class="r.is_active ? 'badge bg-primary' : 'badge bg-warning'">
                                    {{ r.is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td v-if="can.edit || can.delete || can.perms">

                                <button v-if="can.perms" class="btn btn-success btn-sm"
                                    @click="$inertia.get(route('roles.show', r.id))">
                                    <i class="fa fa-unlock-alt"></i>
                                </button>

                                <button v-if="can.edit" class="btn btn-info btn-sm"
                                    @click="$inertia.get(route('roles.edit', r.id))">
                                    <i class="fa fa-pencil"></i>
                                </button>

                                <button v-if="can.delete" class="btn btn-danger btn-sm" @click="destroy(r.id)">
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
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'
import Swal from 'sweetalert2'
import axios from "axios";
import { router } from '@inertiajs/vue3'


const props = defineProps({
    roles: Array,
    can: Object
})

function destroy(id) {
    Swal.fire({
        title: '¿Eliminar rol?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(async (r) => {
        if (!r.isConfirmed) return;

        try {
            const resp = await axios.delete(route('roles.destroy', id));

            Swal.fire({
                title: 'Eliminado',
                text: resp.data?.message || 'Rol eliminado correctamente.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });

            // Recargar tabla/lista sin recargar toda la página
            router.reload({ preserveScroll: true });

        } catch (error) {
            Swal.fire({
                title: 'Error',
                text: error.response?.data?.message || 'No se pudo eliminar el rol.',
                icon: 'error'
            });
        }
    });
}


</script>
