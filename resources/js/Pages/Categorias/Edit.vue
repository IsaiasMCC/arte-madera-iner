<template>
    <AdminLTELayout title="Editar Categoría" index_title="Categorías" index_link="categorias.index">

        <div class="card shadow">
            <div class="card-header">
                <h5>Editar Categoría</h5>
            </div>

            <div class="card-body">

                <form @submit.prevent="submit">

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="form.nombre"
                            :class="{ 'is-invalid': form.errors.nombre }">
                        <div class="invalid-feedback" v-if="form.errors.nombre">
                            {{ form.errors.nombre }}
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea class="form-control" v-model="form.descripcion"
                            :class="{ 'is-invalid': form.errors.descripcion }"></textarea>
                        <div class="invalid-feedback" v-if="form.errors.descripcion">
                            {{ form.errors.descripcion }}
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-4">
                        <Link :href="route('categorias.index')" class="btn btn-danger me-2">
                            Cancelar
                        </Link>

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
import Swal from 'sweetalert2'

const props = defineProps({
    categoria: Object
})

const form = useForm({
    nombre: props.categoria.nombre,
    descripcion: props.categoria.descripcion,
})

function submit() {
    form.put(route('categorias.update', props.categoria.id), {
        onSuccess: () => {
            Swal.fire('¡Éxito!', 'Categoría actualizada correctamente', 'success')
        },
        onError: () => {
            Swal.fire('Error', 'Por favor revisa los campos del formulario', 'error')
        }
    })
}
</script>
