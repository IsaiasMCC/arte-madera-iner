<template>
<div class="register-wrapper">

    <div class="register-card">

        <div class="card-header text-center bg-wood text-white">
            <h3>Registro de Usuario</h3>
        </div>

        <div class="card-body">

            <form @submit.prevent="submit">

                <!-- CI -->
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                    <input v-model="form.ci" class="form-control" placeholder="CI">
                </div>
                <small class="text-danger" v-if="errors.ci">{{ errors.ci }}</small>

                <!-- Nombres -->
                <div class="input-group mb-3 mt-2">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                    <input v-model="form.nombres" class="form-control" placeholder="Nombres">
                </div>
                <small class="text-danger" v-if="errors.nombres">{{ errors.nombres }}</small>

                <!-- Apellidos -->
                <div class="input-group mb-3 mt-2">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                    <input v-model="form.apellidos" class="form-control" placeholder="Apellidos">
                </div>
                <small class="text-danger" v-if="errors.apellidos">{{ errors.apellidos }}</small>

                <!-- Email -->
                <div class="input-group mb-3 mt-2">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    <input v-model="form.email" type="email" class="form-control" placeholder="Correo electrónico">
                </div>
                <small class="text-danger" v-if="errors.email">{{ errors.email }}</small>

                <!-- Password -->
                <div class="input-group mb-3 mt-2">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input v-model="form.password" type="password" class="form-control" placeholder="Contraseña">
                </div>
                <small class="text-danger" v-if="errors.password">{{ errors.password }}</small>

                <!-- Confirmar -->
                <div class="input-group mb-3 mt-2">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input v-model="form.password_confirmation"
                           type="password"
                           class="form-control"
                           placeholder="Confirmar contraseña">
                </div>

                <!-- Botón -->
                <button class="btn btn-wood w-100 mt-3">Registrarse</button>
            </form>

            <div class="text-center mt-3">
                <Link :href="route('auth.index')" class="text-primary">
                    Iniciar sesión
                </Link>
            </div>

        </div>
    </div>
</div>
</template>



<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({
    errors: Object
})

const form = useForm({
    ci: '',
    nombres: '',
    apellidos: '',
    email: '',
    password: '',
    password_confirmation: '',
})

function submit(){
    form.post(route('register'))
}
</script>



<style>
.register-wrapper {
    display:flex;
    justify-content:center;
    padding-top:40px;
}

.register-card {
    width:500px;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,.25);
}

/* Color madera */
.btn-wood, .bg-wood {
    background:#8B5E3C !important;
}

.btn-wood:hover {
    opacity:.9;
}

.input-group-text {
    background:#eee;
}

@media(max-width:600px){
    .register-card{ width:95% }
}
</style>
