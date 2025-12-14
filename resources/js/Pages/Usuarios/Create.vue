<template>
    <AdminLTELayout title="Crear Usuario" index_title="Usuarios" index_link="usuarios.index">

        <div class="row">

            <div class="col-lg-12">

                <form @submit.prevent="submit">

                    <!-- CI -->
                    <div class="form-group mb-3">
                        <label class="input-label">Ci</label>
                        <input v-model="form.ci" class="form-control" :class="errors.ci && 'is-invalid'">
                        <div v-if="errors.ci" class="invalid-feedback d-block">
                            {{ errors.ci }}
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div class="form-group mb-3">
                        <label class="input-label">Nombre</label>
                        <input v-model="form.name" class="form-control" :class="errors.name && 'is-invalid'">
                        <div v-if="errors.name" class="invalid-feedback d-block">
                            {{ errors.name }}
                        </div>
                    </div>

                    <!-- Apellido -->
                    <div class="form-group mb-3">
                        <label class="input-label">Apellido</label>
                        <input v-model="form.lastname" class="form-control" :class="errors.lastname && 'is-invalid'">
                        <div v-if="errors.lastname" class="invalid-feedback d-block">
                            {{ errors.lastname }}
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group mb-3">
                        <label class="input-label">Correo</label>
                        <input type="email" v-model="form.email" class="form-control"
                            :class="errors.email && 'is-invalid'">
                        <div v-if="errors.email" class="invalid-feedback d-block">
                            {{ errors.email }}
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group mb-3">
                        <label class="input-label">Contraseña</label>
                        <input type="password" v-model="form.password" class="form-control"
                            :class="errors.password && 'is-invalid'">
                        <div v-if="errors.password" class="invalid-feedback d-block">
                            {{ errors.password }}
                        </div>
                    </div>

                    <!-- Rol -->
                    <div class="form-group mb-3">
                        <label>Rol</label>
                        <select v-model="form.role" class="form-control" :class="errors.role && 'is-invalid'">
                            <option selected disabled value="">Seleccione un rol</option>
                            <option v-for="r in roles" :key="r.id" :value="r.id">
                                {{ r.name }}
                            </option>
                        </select>

                        <div v-if="errors.role" class="invalid-feedback d-block">
                            {{ errors.role }}
                        </div>
                    </div>

                    <!-- botones -->
                    <div class="mt-3">

                        <Link :href="route('usuarios.index')" class="btn btn-danger me-2">
                            Cancelar
                        </Link>

                        <button class="btn btn-primary">
                            Guardar
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </AdminLTELayout>
</template>



<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'

const props = defineProps({
    roles: Array,
    errors: Object
})

const form = useForm({
    ci: '',
    name: '',
    lastname: '',
    email: '',
    password: '',
    role: ''
})

function submit() {
    form.post(route('usuarios.store'))
}
</script>
