<template>
<AdminLTELayout 
    title="Crear Categoría"
    index_title="Categorías"
    index_link="categorias.index"
>

<div class="card shadow">
    <div class="card-header">
        <h5>Nueva Categoría</h5>
    </div>

    <div class="card-body">

        <form @submit.prevent="submit">

            <!-- Nombre -->
            <div class="mb-3">
                <label>Nombre <span class="text-danger">*</span></label>
                <input 
                    type="text"
                    class="form-control"
                    v-model="form.nombre"
                    :class="{ 'is-invalid': form.errors.nombre }"
                >
                <div class="invalid-feedback" v-if="form.errors.nombre">
                    {{ form.errors.nombre }}
                </div>
            </div>

            <!-- Descripción -->
            <div class="mb-3">
                <label>Descripción</label>
                <textarea
                    class="form-control"
                    v-model="form.descripcion"
                    :class="{ 'is-invalid': form.errors.descripcion }"
                ></textarea>
                <div class="invalid-feedback" v-if="form.errors.descripcion">
                    {{ form.errors.descripcion }}
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-4">
                <Link :href="route('categorias.index')" class="btn btn-danger me-2">
                    Cancelar
                </Link>

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
import Swal from 'sweetalert2'

const form = useForm({
    nombre: '',
    descripcion: '',
    is_active: true
})

function submit() {
    form.post(route('categorias.store'), {
        onSuccess: () => {
            Swal.fire('¡Éxito!', 'Categoría creada correctamente', 'success')
            form.reset() // opcional: limpiar formulario después de crear
        },
    })
}
</script>
