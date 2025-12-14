<template>
<TiendaLayout title="Carrito">

    <h1 class="text-center mb-4 text-wood">Tu Carrito</h1>

    <div v-if="hasItems">
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(item, id) in cart" :key="id">
                    <td>{{ item.nombre }}</td>
                    <td>Bs {{ format(item.precio) }}</td>
                    <td>{{ item.cantidad }}</td>
                    <td>Bs {{ format(item.precio * item.cantidad) }}</td>
                    <td>
                        <form @submit.prevent="removeItem(id)">
                            <button class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>

                <tr>
                    <td colspan="3" class="text-end fw-bold">Total:</td>
                    <td colspan="2" class="fw-bold">Bs {{ format(total) }}</td>
                </tr>
            </tbody>
        </table>

        <Link 
            :href="route('checkout.index')" 
            class="btn btn-wood w-100 mt-3">
            Proceder al Pedido
        </Link>

    </div>

    <p v-else class="text-center">Tu carrito está vacío.</p>

</TiendaLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import TiendaLayout from '@/Layouts/TiendaLayout.vue'

const props = defineProps({
    cart: Object
})

// Verificar si hay productos
const hasItems = computed(() => Object.keys(props.cart).length > 0)

// Calcular total
const total = computed(() => {
    return Object.values(props.cart).reduce((acc, item) => {
        return acc + item.precio * item.cantidad
    }, 0)
})

// Formato de números
function format(n) {
    return Number(n).toFixed(2)
}

// Eliminar producto del carrito
function removeItem(id) {
    router.post(route('carrito.remover', id))
}
</script>

<style scoped>
.text-wood {
    color: #8B5E3C;
}
</style>
