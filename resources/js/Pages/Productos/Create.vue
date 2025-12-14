<template>
  <AdminLTELayout title="Crear Producto" index_title="Productos" index_link="productos.index">
    <div class="card shadow">
      <div class="card-header"><h5>Nuevo Producto</h5></div>
      <div class="card-body">
        <form @submit.prevent="submit">
          <Form :form="form" :categorias="categorias" :tiendas="tiendas" />

          <div class="mt-3">
            <Link :href="route('productos.index')" class="btn btn-danger me-2">Volver</Link>
            <button class="btn btn-primary" :disabled="form.processing">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </AdminLTELayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'
import Form from './Form.vue'
import Swal from 'sweetalert2'

const props = defineProps({
  categorias: Array,
  tiendas: Array
})

const form = useForm({
  nombre: '',
  descripcion: '',
  precio: '',
  stock: '',
  estado: true,
  categoria_id: '',
  tienda_id: '',
  foto: null
})

function submit() {
  form.post(route('productos.store'), {
    onSuccess: () => {
      Swal.fire('¡Éxito!', 'Producto creado correctamente', 'success')
      form.reset()
    },
    onError: (err) => {
      console.log(err)
      Swal.fire('Error', 'Por favor revisa los campos del formulario', 'error')
    }
  })
}
</script>
