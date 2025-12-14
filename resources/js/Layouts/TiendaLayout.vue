<template>
  <div>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark px-5">
        <Link class="navbar-brand" :href="route('tiendas.tienda')">
            Arte Madera
        </Link>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-center">

                <!-- Carrito -->
                <li class="nav-item position-relative me-3">
                    <Link class="nav-link" :href="route('carrito.index')">
                        <i class="fa fa-shopping-cart"></i> Carrito
                        <span class="cart-badge">{{ cartCount }}</span>
                    </Link>
                </li>

                <!-- Mis pedidos -->
                <li class="nav-item me-3">
                    <Link class="nav-link" :href="route('pedidos.mios')">
                        <i class="fa fa-box"></i> Mis pedidos
                    </Link>
                </li>

                <!-- Usuario autenticado -->
                <li v-if="authUser" class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">

                        <i class="fa fa-user-circle me-1"></i>
                        {{ authUser.nombres }} {{ authUser.apellidos }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li class="text-center">
                            <!-- <Link class="dropdown-item" href="#"> -->
                                <i class="fa fa-user"></i> Perfil
                            <!-- </Link> -->
                        </li>

                        <li><hr class="dropdown-divider" /></li>

                        <li>
                            <Link :href="route('auth.logout')" method="get" as="button"
                                class="dropdown-item text-danger">
                                <i class="fa fa-sign-out-alt"></i> Cerrar sesión
                            </Link>
                        </li>
                    </ul>
                </li>

                <!-- Invitado -->
                <li v-else class="nav-item">
                    <Link class="nav-link" :href="route('auth.index')">
                        <i class="fa fa-sign-in-alt"></i> Iniciar sesión
                    </Link>
                </li>

            </ul>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <div class="container my-5">
        <slot />
    </div>

    <!-- FOOTER -->
    <footer class="text-center footer-madera">
        © {{ new Date().getFullYear() }} Tienda de Arte en Madera. Todos los derechos reservados.
    </footer>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'

// Props de Inertia
const page = usePage()

// Usuario autenticado
const authUser = page.props.auth?.user ?? null

// Carrito reactivo
const cart = ref(Object.values(page.props.cart || {}))

// Contador de productos reactivo
const cartCount = computed(() => cart.value.length)

// Actualizar carrito si Inertia cambia los props
watch(() => page.props.cart, (newCart) => {
    cart.value = Object.values(newCart || {})
}, { deep: true })
</script>

<style>
body {
    background-color: #f7f1e3;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Navbar */
.navbar {
    background-color: #8B5E3C;
}

.navbar a,
.navbar-brand {
    color: #fff;
}

/* Cards de productos */
.product-card {
    border: 1px solid #d1bfa7;
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s;
    background-color: #fffaf0;
}

.product-card:hover {
    transform: scale(1.05);
}

.product-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

/* Botón agregar */
.btn-wood {
    background-color: #A9746E;
    color: white;
}

.btn-wood:hover {
    background-color: #8B5E3C;
}

/* Carrito */
.cart-badge {
    position: absolute;
    top: 5px;
    font-size: 0.8rem;
    background: red;
    color: white;
    padding: 2px 6px;
    border-radius: 50%;
}

/* Footer */
.footer-madera {
    background-color: #8B5E3C;
    color: white;
    padding: 20px 0;
    margin-top: 40px;
}
</style>
