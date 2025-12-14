<template>
    <AdminLTELayout :title="`Editar Tienda: ${tienda.nombre}`" index_title="Tiendas" index_link="tiendas.index">

        <div class="card shadow">
            <div class="card-header">
                <h5>Editar Tienda</h5>
            </div>

            <div class="card-body">
                <form @submit.prevent="submit">

                    <Form :form="form" />

                    <div class="mt-3">
                        <Link :href="route('tiendas.index')" class="btn btn-danger me-2">Volver</Link>
                        <button class="btn btn-primary">Actualizar</button>
                    </div>

                </form>
            </div>
        </div>

    </AdminLTELayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'
import Form from './Form.vue'
import Swal from 'sweetalert2'

const props = defineProps({
    tienda: Object
})

const form = useForm({
    nombre: props.tienda.nombre,
    nit: props.tienda.nit,
    telefono: props.tienda.telefono,
    ubicacion: props.tienda.ubicacion,
})


function submit() {
    form.put(route('tiendas.update', props.tienda.id), {
        onSuccess: () => {
            Swal.fire('¡Éxito!', 'La tienda se actualizó correctamente', 'success')
        },
        onError: () => {
            Swal.fire('Error', 'Por favor revisa los campos del formulario', 'error')
        }
    })
}

</script>
