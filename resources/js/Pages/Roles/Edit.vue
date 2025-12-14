<template>
<AdminLTELayout
    title="Editar Rol"
    index_title="Roles"
    index_link="roles.index"
>

<form @submit.prevent="submit">

    <div class="form-group mb-3">
        <label>Nombre</label>
        <input
            v-model="form.name"
            class="form-control"
            :class="{ 'is-invalid': form.errors.name }"
        >
        <div v-if="form.errors.name" class="invalid-feedback d-block">
            {{ form.errors.name }}
        </div>
    </div>

    <div class="form-group mb-3">
        <label>Estado</label>
        <select v-model="form.is_active" class="form-control">
            <option :value="1">Activo</option>
            <option :value="0">Inactivo</option>
        </select>
    </div>

    <div>
        <Link :href="route('roles.index')" class="btn btn-danger me-2">Cancelar</Link>
        <button class="btn btn-primary">Actualizar</button>
    </div>

</form>

</AdminLTELayout>
</template>



<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'

const props = defineProps({
    role: Object
})

const form = useForm({
    name: props.role.name,
    is_active: props.role.is_active
})

function submit(){
    form.patch(route('roles.update', props.role.id))
}

</script>
