<template>
  <AdminLTELayout :title="`Editar Producto: ${producto.nombre}`" index_title="Productos" index_link="productos.index">
    <div class="card shadow">
      <div class="card-header">
        <h5>Editar Producto</h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="submit">
          <Form :form="form" :categorias="categorias" :tiendas="tiendas" :producto="producto" />

          <div class="mt-3">
            <Link :href="route('productos.index')" class="btn btn-danger me-2">Volver</Link>
            <button class="btn btn-primary" :disabled="form.processing">Actualizar</button>
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
  producto: Object,
  categorias: Array,
  tiendas: Array
})

const form = useForm({
  nombre: props.producto.nombre,
  descripcion: props.producto.descripcion,
  precio: props.producto.precio,
  stock: props.producto.stock,
  estado: Boolean(props.producto.estado),
  categoria_id: props.producto.categoria_id,
  tienda_id: props.producto.tienda_id,
  foto: null,
  _method: 'PUT' // Agregar esto para simular PUT
})

function submit() {
  // Cambiar de form.put() a form.post()
  form.post(route('productos.update', props.producto.id), {
    forceFormData: true, // Forzar el uso de FormData
    onSuccess: () => {
      Swal.fire('¡Éxito!', 'Producto actualizado correctamente', 'success')
    },
    onError: (errors) => {
      console.log('Errores:', errors)
      Swal.fire('Error', 'Por favor revisa los campos del formulario', 'error')
    }
  })
}
</script>