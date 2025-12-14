<template>
<TiendaLayout>

    <h1 class="mb-4 text-center" style="color:#8B5E3C;">Nuestros Productos</h1>

    <!-- FILTROS -->
    <div class="d-flex justify-content-center mb-4 flex-wrap">

        <!-- Todas -->
        <Link
            :href="route('tiendas.tienda')"
            class="btn btn-outline-wood mx-1"
            :class="{ active: !categoria_id }">
            Todas
        </Link>

        <!-- Categorías -->
        <Link
            v-for="cat in categorias"
            :key="cat.id"
            :href="route('tiendas.tienda', { categoria: cat.id })"
            class="btn btn-outline-wood mx-1"
            :class="{ active: categoria_id === cat.id }">
            {{ cat.nombre }}
        </Link>

    </div>

    <!-- BUSCADOR -->
    <div class="row mb-4">
        <div class="col-md-6 offset-md-3">
            <form @submit.prevent="buscar">
                <div class="input-group">
                    <input
                        v-model="search"
                        type="text"
                        class="form-control"
                        placeholder="Buscar producto...">

                    <button class="btn btn-wood">Buscar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- PRODUCTOS -->
    <div class="row g-4">

        <div v-for="producto in productos" :key="producto.id" class="col-md-4">
            <div class="card h-100 shadow-sm hover-shadow">

                <img
                    :src="`/storage/${producto.foto}`"
                    class="card-img-top"
                    :alt="producto.nombre"
                    style="height: 250px; object-fit: cover;"
                >

                <div class="card-body d-flex flex-column">

                    <h5 class="card-title">{{ producto.nombre }}</h5>

                    <p class="card-text text-muted flex-grow-1">
                        {{ limitText(producto.descripcion, 80) }}
                    </p>

                    <h6 class="fw-bold mb-3">$ {{ Number(producto.precio).toFixed(2) }}</h6>

                    <form @submit.prevent="agregarAlCarrito(producto.id)">
                        <button class="btn btn-wood w-100">Agregar al carrito</button>
                    </form>

                </div>

            </div>
        </div>

        <div v-if="productos.length === 0" class="col-12">
            <p class="text-center text-muted">No hay productos disponibles.</p>
        </div>

    </div>

</TiendaLayout>
</template>

<script setup>
import { Link, router, usePage } from "@inertiajs/vue3"
import { ref } from "vue"
import TiendaLayout from "@/Layouts/TiendaLayout.vue"

const props = defineProps({
    productos: Array,
    categorias: Array,
    categoria_id: Number,
    search: String,
})

const search = ref(props.search ?? "")

function buscar() {
    router.get(route("tiendas.tienda"), { search: search.value, categoria: props.categoria_id })
}

function agregarAlCarrito(id) {
    router.post(route("carrito.agregar", id))
}

/* Función para limitar texto como Str::limit */
function limitText(text, length) {
    if (!text) return ""
    return text.length > length ? text.substring(0, length) + "..." : text
}
</script>

<style>
.btn-outline-wood {
    border: 2px solid #8B5E3C;
    color: #8B5E3C;
}

.btn-outline-wood.active,
.btn-outline-wood:hover {
    background-color: #8B5E3C;
    color: #fff;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    transition: 0.3s;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}
</style>
