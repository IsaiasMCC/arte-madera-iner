<template>
<AdminLTELayout 
    title="Métodos de Pago"
    index_title="Métodos de Pago"
    index_link="metodos_pago.index"
>

    <div class="mb-3">
        <Link 
            v-if="can('metodos_pago create')"
            :href="route('metodos_pago.create')" 
            class="btn btn-primary"
        >
            Crear método
        </Link>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th v-if="canAny" class="text-end">Acciones</th>
            </tr>
        </thead>

        <tbody>
            <tr v-for="m in metodos" :key="m.id">
                <td>{{ m.id }}</td>
                <td>{{ m.nombre }}</td>

                <td v-if="canAny" class="text-end">
                    <Link 
                        v-if="can('metodos_pago edit')"
                        :href="route('metodos_pago.edit', m.id)"
                        class="btn btn-warning btn-sm me-1"
                    >
                      <i class="fa fa-pencil"></i>
                    </Link>

                    <button
                        v-if="can('metodos_pago delete')"
                        @click="destroy(m.id)"
                        class="btn btn-danger btn-sm"
                    >
                     <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>

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
    metodos: Array
})

// Comprobación de permisos global para la columna "Acciones"
const canAny = computed(() =>
    can('metodos_pago edit') ||
    can('metodos_pago delete') ||
    can('metodos_pago create')
)

function destroy(id) {
    Swal.fire({
        title: "¿Eliminar?",
        text: "No podrás revertir este cambio",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#1AB394",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar"
    }).then(res => {
        if (!res.isConfirmed) return

        axios.delete(route('metodos_pago.destroy', id))
            .then(response => {
                Swal.fire("Eliminado", response.data.success || "Registro borrado", "success")
                router.reload({ preserveScroll: true })
            })
            .catch(error => {
                Swal.fire("Error", error.response?.data?.error || "No se pudo eliminar", "error")
            })
    })
}
</script>
