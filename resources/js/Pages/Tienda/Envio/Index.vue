<template>
    <TiendaLayout title="Seguimiento de Pedido">

        <h1 class="mb-4 text-center" style="color:#8B5E3C;">Seguimiento de tu Pedido</h1>

        <div v-if="pedido" class="card p-3 bg-white">

            <h5>Pedido #{{ pedido.id }}</h5>

            <p><strong>Nombre:</strong> {{ pedido.nombre }}</p>
            <p><strong>Estado:</strong> {{ pedido.estado }}</p>
            <p><strong>Total:</strong> Bs {{ formatNumber(pedido.total) }}</p>
            <p><strong>Enviado a:</strong> {{ pedido.direccion }}</p>

        </div>

        <p v-else class="text-center">No se encontró el pedido.</p>

    </TiendaLayout>
</template>

<script setup>
import TiendaLayout from '@/Layouts/TiendaLayout.vue'

const props = defineProps({
    pedido: {
        type: Object,
        default: null
    }
})

function formatNumber(num) {
    return Number(num).toFixed(2)
}
</script>
