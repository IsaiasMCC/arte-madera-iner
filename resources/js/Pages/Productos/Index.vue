<template>
  <AdminLTELayout title="Productos" index_title="Productos" index_link="productos.index">
    <div class="card shadow">
      <div class="card-header d-flex justify-content-between align-items-center">
        <Link v-if="can('productos create')" :href="route('productos.create')" class="btn btn-primary">
          Nuevo Producto
        </Link>
      </div>

      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Categoría</th>
              <th>Tienda</th>
              <th>Precio</th>
              <th>Estado</th>
              <th v-if="canAny" style="width:140px">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="p in productos" :key="p.id">
              <td>{{ p.nombre }}</td>
              <td>{{ p.categoria?.nombre ?? '-' }}</td>
              <td>{{ p.tienda?.nombre ?? '-' }}</td>
              <td>{{ p.precio }}</td>
              <td>
                <span :class="p.estado ? 'badge bg-success' : 'badge bg-warning'">
                  {{ p.estado ? 'Activo' : 'Inactivo' }}
                </span>
              </td>
              <td v-if="canAny" class="text-center">
                <Link v-if="can('productos edit')" :href="route('productos.edit', p.id)"
                  class="btn btn-warning btn-sm me-1">
                  <i class="fa fa-pencil"></i>
                </Link>
                <button v-if="can('productos delete')" class="btn btn-danger btn-sm" @click="destroy(p.id)">
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
import { computed } from 'vue'
import { useCan } from '@/Composables/useCan'
import axios from 'axios'

const props = defineProps({
  productos: Array
})

const { can } = useCan()

const canAny = computed(() =>
  can('productos edit') || can('productos delete') || can('productos create')
)

function destroy(id) {
  Swal.fire({
    title: '¿Eliminar producto?',
    text: 'Esta acción no se puede deshacer.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then(async (result) => {
    if (!result.isConfirmed) return;

    try {
      const response = await axios.delete(route('productos.destroy', id));
      Swal.fire({
        title: '¡Eliminado!',
        text: response.data?.message || 'Producto eliminado correctamente.',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
      });
      router.reload({ preserveScroll: true });
    } catch (error) {
      Swal.fire({
        title: 'Error',
        text: error.response?.data?.message || 'No se pudo eliminar el producto.',
        icon: 'error'
      });
    }
  });
}
</script>
