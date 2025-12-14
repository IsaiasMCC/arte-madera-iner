<template>
<div class="login-wrapper">

    <!-- Contenedor principal -->
    <div class="login-card">

        <!-- Lado imagen -->
        <div class="login-img">
            <h2 style="color: white;">TIENDA DE ARTE EN MADERA</h2>
            <img src="/images/logo-arte.jpg" alt="Logo">
        </div>

        <!-- Formulario -->
        <div class="login-form">

            <h1>Bienvenido</h1>

            <form @submit.prevent="submit">

                <!-- Email -->
                <div class="input-box">
                    <i class="fa fa-envelope"></i>
                    <input type="email"
                           v-model="form.email"
                           placeholder="Correo electrónico">

                    <small v-if="errors.email">{{ errors.email }}</small>
                </div>

                <!-- Password -->
                <div class="input-box">
                    <i class="fa fa-lock"></i>
                    <input type="password"
                           v-model="form.password"
                           placeholder="Contraseña">

                    <small v-if="errors.password">{{ errors.password }}</small>
                </div>

                <!-- Botón -->
                <button class="btn-login">Ingresar</button>

                <!-- Links -->
                <div class="extra">
                    <Link :href="route('register.form')">Crear cuenta</Link>
                </div>

                <!-- Remember -->
                <div class="remember">
                    <input type="checkbox" v-model="remember">
                    <label>Recordar mis datos</label>
                </div>

            </form>

        </div>
    </div>
</div>
</template>



<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    errors: Object
})

const logo = '/images/logo-arte.jpg'

const form = useForm({
    email: '',
    password: ''
})

const remember = ref(false)

if (localStorage.getItem('email')) {
    form.email = localStorage.getItem('email')
    remember.value = true
}

if (localStorage.getItem('password')) {
    form.password = localStorage.getItem('password')
}

function submit () {
    if (remember.value) {
        localStorage.setItem('email', form.email)
        localStorage.setItem('password', form.password)
    } else {
        localStorage.removeItem('email')
        localStorage.removeItem('password')
    }

    form.post(route('auth.store'))
}
</script>



<style>
/* Fondo */
.login-wrapper {
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#222;
}

/* Card */
.login-card {
    display:flex;
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,.35);
    width:900px;
}

/* Imagen */
.login-img {
    background:#111;
    color:white;
    width:40%;
    padding:20px;
    text-align:center;
}

.login-img img {
    width:100%;
    border-radius:12px;
    margin-top:10px;
}

/* Form */
.login-form {
    width:60%;
    padding:40px;
}

.login-form h1 {
    margin-bottom:20px;
}

/* Inputs */
.input-box {
    position:relative;
    margin-bottom:18px;
}

.input-box i {
    position:absolute;
    top:12px;
    left:10px;
    color:#666;
}

.input-box input {
    width:100%;
    padding:12px 12px 12px 40px;
    border-radius:8px;
    border:1px solid #ccc;
}

.input-box small {
    color:red;
}

/* boton */
.btn-login {
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    background:#8B5E3C;
    color:white;
    font-size:18px;
    margin-top:10px;
}

/* extra */
.extra {
    margin-top:10px;
    text-align:right;
}

.remember {
    margin-top:12px;
}

/* Responsive */
@media(max-width:760px){
    .login-card{flex-direction:column;width:95%;}
    .login-img{width:100%;}
    .login-form{width:100%;}
}
</style>
