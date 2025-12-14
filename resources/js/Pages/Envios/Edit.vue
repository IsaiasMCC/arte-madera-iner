<template>
    <AdminLTELayout title="Editar envío">

        <form @submit.prevent="submit">

            <div class="mb-3">
                <label>Estado</label>
                <select v-model="form.estado" class="form-control">
                    <option v-for="(label, key) in estados" :key="key" :value="key">
                        {{ label }}
                    </option>
                </select>
                <div v-if="form.errors.estado" class="text-danger">
                    {{ form.errors.estado }}
                </div>
            </div>

            <div class="mb-3">
                <label>Dirección</label>
                <input v-model="form.direccion" class="form-control">
                <div v-if="form.errors.direccion" class="text-danger">
                    {{ form.errors.direccion }}
                </div>
            </div>

            <div class="mb-3">
                <label>Ciudad</label>
                <input v-model="form.ciudad" class="form-control">
                <div v-if="form.errors.ciudad" class="text-danger">
                    {{ form.errors.ciudad }}
                </div>
            </div>

            <div class="mb-3">
                <label>Código Postal</label>
                <input v-model="form.codigo_postal" class="form-control">
                <div v-if="form.errors.codigo_postal" class="text-danger">
                    {{ form.errors.codigo_postal }}
                </div>
            </div>

            <Link :href="route('envios.index')" class="btn btn-danger me-2">Cancelar</Link>
            <button class="btn btn-primary">Actualizar</button>

        </form>

    </AdminLTELayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'
import Swal from 'sweetalert2'

const props = defineProps({
    envio: Object,
    estados: Object
})

const form = useForm({
    estado: props.envio.estado,
    direccion: props.envio.direccion,
    ciudad: props.envio.ciudad,
    codigo_postal: props.envio.codigo_postal
})

function submit() {
    form.patch(route('envios.update', props.envio.id), {
        onSuccess: () => {
            Swal.fire({
                title: '¡Actualizado!',
                text: 'El estado del envío fue actualizado correctamente.',
                icon: 'success',
                confirmButtonText: 'OK'
            })
        },
        onError: () => {
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un problema al actualizar el envío.',
                icon: 'error',
                confirmButtonText: 'Cerrar'
            })
        }
    })
}

</script>
