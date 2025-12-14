<template>
    <TiendaLayout title="Checkout">

        <h1 class="mb-4 text-center" style="color:#8B5E3C;">
            Dirección de Envío
        </h1>

        <form @submit.prevent="submit">

            <!-- DIRECCIÓN -->
            <div class="mb-3">
                <label class="form-label">Dirección</label>
                <input
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.direccion }"
                    v-model="form.direccion"
                >
                <div v-if="form.errors.direccion" class="invalid-feedback">
                    {{ form.errors.direccion }}
                </div>
            </div>


            <!-- CIUDAD -->
            <div class="mb-3">
                <label class="form-label">Ciudad</label>
                <input
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.ciudad }"
                    v-model="form.ciudad"
                >
                <div v-if="form.errors.ciudad" class="invalid-feedback">
                    {{ form.errors.ciudad }}
                </div>
            </div>


            <!-- CODIGO POSTAL -->
            <div class="mb-3">
                <label class="form-label">Código Postal</label>
                <input
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.codigo_postal }"
                    v-model="form.codigo_postal"
                >
                <div v-if="form.errors.codigo_postal" class="invalid-feedback">
                    {{ form.errors.codigo_postal }}
                </div>
            </div>


            <button type="submit" class="btn btn-wood w-100">
                Realizar Pedido
            </button>

        </form>
    </TiendaLayout>
</template>



<script setup>
import { useForm } from '@inertiajs/vue3'
import TiendaLayout from '@/Layouts/TiendaLayout.vue'

const form = useForm({
    direccion: '',
    ciudad: '',
    codigo_postal: ''
})

function submit() {
    form.post(route('checkout.guardarEnvio'))
}
</script>
