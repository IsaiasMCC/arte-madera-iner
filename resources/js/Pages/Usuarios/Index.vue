<template>
    <AdminLTELayout title="Usuarios">

        <div class="card shadow">

            <div class="card-header d-flex justify-content-between align-items-center">

                <button v-if="can.create" class="btn btn-primary" @click="$inertia.get(route('usuarios.create'))">
                    Agregar Usuario
                </button>

                <input v-model="search" type="text" class="form-control w-25" placeholder="Buscar...">

            </div>

            <div class="card-body">

                <table class="table table-hover">

                    <thead>
                        <tr>
                            <th @click="sort('id')" class="click">ID</th>
                            <th @click="sort('nombres')" class="click">Nombres</th>
                            <th @click="sort('apellidos')" class="click">Apellidos</th>
                            <th @click="sort('email')" class="click">Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th v-if="can.edit || can.delete">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="user in paginated" :key="user.id">

                            <td>{{ user.id }}</td>
                            <td>{{ user.nombres }}</td>
                            <td>{{ user.apellidos }}</td>
                            <td>{{ user.email }}</td>
                            <td>{{ user.role?.name ?? 'Ninguno' }}</td>

                            <td>
                                <span :class="user.estado ? 'badge bg-primary' : 'badge bg-warning'">
                                    {{ user.estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td v-if="can.edit || can.delete">

                                <button v-if="can.edit" class="btn btn-info btn-sm"
                                    @click="$inertia.get(route('usuarios.edit', user.id))">
                                    <i class="fa fa-pencil"></i>
                                </button>

                                <button v-if="can.delete" class="btn btn-danger btn-sm" @click="destroy(user.id)">
                                    <i class="fa fa-trash"></i>
                                </button>

                            </td>

                        </tr>
                    </tbody>

                </table>

                <!-- PAGINACION -->
                <div class="d-flex justify-content-between">

                    <div>
                        Mostrando {{ from }} - {{ to }} de {{ filtered.length }}
                    </div>

                    <div>
                        <button class="btn btn-sm btn-secondary me-1" @click="page--" :disabled="page === 1">
                            Prev
                        </button>

                        <button class="btn btn-sm btn-secondary" @click="page++" :disabled="page === pages">
                            Next
                        </button>
                    </div>

                </div>

            </div>
        </div>

    </AdminLTELayout>
</template>



<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from "axios";
import Swal from 'sweetalert2'
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'

const props = defineProps({
    users: Array,
    can: Object
})

/* BUSCADOR */
const search = ref('')

/* ORDENAMIENTO */
const sortKey = ref('id')
const sortAsc = ref(true)

function sort(key) {
    if (sortKey.value === key) {
        sortAsc.value = !sortAsc.value
    } else {
        sortKey.value = key
        sortAsc.value = true
    }
}

/* FILTRAR */
const filtered = computed(() => {
    return props.users.filter(u =>
        u.nombres.toLowerCase().includes(search.value.toLowerCase()) ||
        u.apellidos.toLowerCase().includes(search.value.toLowerCase()) ||
        u.email.toLowerCase().includes(search.value.toLowerCase())
    ).sort((a, b) => {
        let r = (a[sortKey.value] > b[sortKey.value]) ? 1 : -1
        return sortAsc.value ? r : -r
    })
})

/* PAGINA */
const page = ref(1)
const perPage = 10

const pages = computed(() => Math.ceil(filtered.value.length / perPage))

const paginated = computed(() => {
    const start = (page.value - 1) * perPage
    return filtered.value.slice(start, start + perPage)
})

const from = computed(() => (page.value - 1) * perPage + 1)
const to = computed(() => Math.min(page.value * perPage, filtered.value.length))



function destroy(id) {
    Swal.fire({
        title: '¿Eliminar usuario?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(async (result) => {
        if (!result.isConfirmed) return;

        try {
            const response = await axios.delete(route('usuarios.destroy', id));

            Swal.fire({
                title: 'Eliminado',
                text: response.data?.message || 'Usuario eliminado correctamente.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });

            // Recargar la página de Inertia manualmente
            router.reload({ preserveScroll: true });

        } catch (error) {
            Swal.fire({
                title: 'Error',
                text: error.response?.data?.message || 'No se pudo eliminar el usuario.',
                icon: 'error'
            });
        }
    });
}

</script>



<style>
.click {
    cursor: pointer;
    user-select: none;
}

.click:hover {
    text-decoration: underline;
}
</style>
