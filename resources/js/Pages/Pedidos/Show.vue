<template>
    <AdminLTELayout title="Detalle Pedido #{{ pedido.id }}">

        <h1 class="mb-4 text-center" style="color:#8B5E3C;">
            Detalle Pedido #{{ pedido.id }}
        </h1>

        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    Pedido #{{ pedido.id }}
                    - {{ pedido.fecha }} {{ pedido.hora }}
                </span>

                <span
                    class="badge"
                    :class="pedido.saldoPendiente == 0 ? 'bg-success' : 'bg-warning'"
                >
                    {{ pedido.saldoPendiente == 0 ? 'Pagado' : 'Pendiente' }}
                </span>
            </div>

            <div class="card-body">

                <!-- Productos -->
                <h5>Productos</h5>

                <ul class="list-group list-group-flush mb-3">
                    <li
                        class="list-group-item d-flex justify-content-between align-items-center"
                        v-for="detalle in pedido.detalles"
                        :key="detalle.id"
                    >
                        {{ detalle.producto.nombre }}
                        (x{{ detalle.cantidad }})
                        <span>
                            $ {{ format(detalle.subtotal * detalle.cantidad) }}
                        </span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                        Total:
                        <span>$ {{ format(pedido.total) }}</span>
                    </li>
                </ul>

                <!-- Envío -->
                <h6>Envío</h6>
                <p>
                    {{ pedido.envio?.direccion ?? 'Sin dirección' }},
                    {{ pedido.envio?.ciudad ?? '' }}
                    {{ pedido.envio?.codigo_postal ?? '' }}
                </p>

                <!-- Pagos -->
                <h6>Pagos</h6>

                <template v-if="pedido.pago && pedido.pago.detallePagos.length > 0">
                    <ul class="list-group mb-3">
                        <li
                            class="list-group-item d-flex justify-content-between"
                            v-for="dp in pedido.pago.detallePagos"
                            :key="dp.id"
                        >
                            {{ dp.fecha }} {{ dp.hora }}:
                            $ {{ format(dp.monto) }}

                            <span>
                                Saldo restante:
                                $ {{ format(dp.saldo) }}
                            </span>
                        </li>
                    </ul>
                </template>

                <p v-else class="text-muted">No se han realizado pagos aún.</p>

            </div>
        </div>

    </AdminLTELayout>
</template>



<script setup>
import AdminLTELayout from "@/Layouts/AdminLTELayout.vue"
import { defineProps } from "vue"

const props = defineProps({
    pedido: Object
})

function format(n){
    return Number(n).toFixed(2)
}

</script>
