<template>
    <AdminLTELayout title="Editar Método de Pago">

        <div class="row">
            <div class="col-lg-6">

                <form @submit.prevent="submit">

                    <!-- Nombre -->
                    <div class="form-group mb-3">
                        <label class="input-label">Nombre</label>
                        <input v-model="form.nombre" class="form-control" :class="errors.nombre && 'is-invalid'">
                        <div v-if="errors.nombre" class="invalid-feedback d-block">
                            {{ errors.nombre }}
                        </div>
                    </div>

                    <!-- botones -->
                    <div class="mt-3">

                        <Link :href="route('metodos_pago.index')" class="btn btn-danger me-2">
                            Cancelar
                        </Link>
                        <button class="btn btn-primary ">
                            Actualizar
                        </button>

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
    metodo: Object,
    errors: Object
})

/*
    IMPORTANTE:
    Si no ponés estas propiedades con guards,
    Vue se rompe cuando el componente
    renderiza antes de recibir props.
*/

const form = useForm({
    nombre: props.metodo?.nombre ?? ''
})



function submit() {
    form.patch(route('metodos_pago.update', props.metodo.id), {
        onSuccess: () => {
            Swal.fire('¡Éxito!', 'Método de pago actualizado correctamente', 'success')
        },
        onError: () => {
            Swal.fire('Error', 'Por favor revisa los campos del formulario', 'error')
        }
    })
}

</script>
