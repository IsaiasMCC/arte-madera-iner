<template>
    <AdminLTELayout title="Crear Tienda" index_title="Tiendas" index_link="tiendas.index">

        <div class="card shadow">
            <div class="card-header">
                <h5>Nueva Tienda</h5>
            </div>

            <div class="card-body">
                <form @submit.prevent="submit">

                    <Form :form="form" />


                    <div class="mt-3">
                        <Link :href="route('tiendas.index')" class="btn btn-danger me-2">Cancelar</Link>
                        <button class="btn btn-primary">Guardar</button>
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

const form = useForm({
    nombre: '',
    nit: '',
    telefono: '',
    ubicacion: '',
})

import Swal from 'sweetalert2'

function submit() {
    form.post(route('tiendas.store'), {
        onSuccess: () => {
            Swal.fire('¡Éxito!', 'La tienda se creó correctamente', 'success')
            form.reset() // opcional: limpia el formulario
        },
        onError: () => {
            Swal.fire('Error', 'Por favor revisa los campos del formulario', 'error')
        }
    })
}

</script>
