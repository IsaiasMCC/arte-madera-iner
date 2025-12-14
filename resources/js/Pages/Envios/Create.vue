<template>
<AdminLTELayout title="Crear envío">

    <form @submit.prevent="submit">

        <div class="mb-3">
            <label>Pedido ID</label>
            <input v-model="form.pedido_id" class="form-control">
            <div v-if="form.errors.pedido_id" class="text-danger">
                {{ form.errors.pedido_id }}
            </div>
        </div>

        <div class="mb-3">
            <label>Estado</label>
            <select v-model="form.estado" class="form-control">
                <option value="">Seleccione</option>
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

        <button class="btn btn-primary">Guardar</button>
        <Link :href="route('envios.index')" class="btn btn-danger">Volver</Link>

    </form>

</AdminLTELayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'

const props = defineProps({
    estados: Object
})

const form = useForm({
    pedido_id: '',
    estado: '',
    direccion: '',
    ciudad: '',
    codigo_postal: ''
})

function submit() {
    form.post(route('envios.store'))
}
</script>
