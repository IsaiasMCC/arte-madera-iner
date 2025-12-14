<template>
<div class="auth-wrapper">

    <!-- Lado imagen -->
    <div class="auth-left">
        <h2 class="title">TIENDA DE ARTE EN MADERA</h2>
        <img :src="logo" class="logo">
    </div>

    <!-- Lado formulario -->
    <div class="auth-right">
        <slot />
    </div>

</div>
</template>

<script setup>
const logo = '/images/logo-arte.jpg'
</script>

<style>
.auth-wrapper{
    display:flex;
    min-height:100vh;
    align-items:center;
}

.auth-left{
    flex:1;
    text-align:center;
}

.title{
    color:white;
    font-weight:700;
}

.logo{
    width:350px;height:300px;
    object-fit:cover;
    border-radius:10px;
}

.auth-right{
    flex:1;
}

@media(max-width:768px){
    .auth-wrapper{flex-direction:column;}
    .logo{width:150px;height:150px;}
}


</style>
