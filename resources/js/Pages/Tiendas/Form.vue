<template>
    <div>

        <div class="mb-3">
            <label>Nombre <span class="text-danger">*</span> </label>
            <input type="text" class="form-control" v-model="form.nombre">
            <div class="text-danger small" v-if="form.errors.nombre">
                {{ form.errors.nombre }}
            </div>
        </div>

        <div class="mb-3">
            <label>NIT</label>
            <input type="number" class="form-control" v-model="form.nit">
        </div>

        <div class="mb-3">
            <label>Teléfono</label>
            <input type="number" class="form-control" v-model="form.telefono">
        </div>

        <div class="mb-3">
            <label>Ubicación</label>
            <input type="text" class="form-control" v-model="form.ubicacion">
        </div>

    </div>
</template>

<script setup>
const props = defineProps({
    form: Object
})
</script>
