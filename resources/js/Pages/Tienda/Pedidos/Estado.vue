<template>
    <TiendaLayout title="Estado del Pedido">

        <h2 class="text-center mb-4">
            Estado del Pedido #{{ pedido.id }}
        </h2>

        <!-- BARRA DE PROGRESO -->
        <div class="progress" style="height:25px;">
            <div
                class="progress-bar bg-info"
                role="progressbar"
                :style="{ width: progressWidth }"
            ></div>
        </div>

        <!-- ETAPAS -->
        <div class="d-flex justify-content-between mt-3">

            <span class="badge rounded-pill px-3 py-2"
                :class="badgeClass(1)">
                Pendiente
            </span>

            <span class="badge rounded-pill px-3 py-2"
                :class="badgeClass(2)">
                Preparando
            </span>

            <span class="badge rounded-pill px-3 py-2"
                :class="badgeClass(3)">
                En camino
            </span>

            <span class="badge rounded-pill px-3 py-2"
                :class="badgeClass(4)">
                Entregado
            </span>

        </div>


        <!-- INFORMACIÓN PRINCIPAL -->
        <div class="card mt-4">
            <div class="card-body">

                <h5>Dirección de entrega</h5>
                <p>
                    {{ pedido.envio.direccion }}<br>
                    {{ pedido.envio.ciudad }} - {{ pedido.envio.codigo_postal }}
                </p>

                <h5 class="mt-4">Estado actual</h5>

                <p class="fw-bold text-uppercase">
                    <span class="badge px-3 py-2"
                          :class="estadoColor">
                        {{ estadoTexto }}
                    </span>
                </p>

            </div>
        </div>


        <!-- DETALLES DEL PEDIDO -->
        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Detalles del Pedido</h5>
            </div>

            <div class="card-body">

                <ul class="list-group list-group-flush mb-3">
                    <!-- <p> {{  pedido }}</p> -->
                    <li v-for="d in pedido.detalles"
                        :key="d.id"
                        class="list-group-item d-flex justify-content-between">

                        <div>
                            <strong>{{ d.producto.nombre }}</strong><br>
                            <small>Cantidad: {{ d.cantidad }}</small>
                        </div>

                        <span>
                            $ {{ format(d.subtotal * d.cantidad) }}
                        </span>

                    </li>

                    <li class="list-group-item d-flex justify-content-between fw-bold">
                        Total del Pedido:
                        <span>$ {{ format(pedido.total) }}</span>
                    </li>

                </ul>

                <h6>Envío</h6>
                <p>
                    {{ pedido.envio.direccion }},
                    {{ pedido.envio.ciudad }},
                    {{ pedido.envio.codigo_postal }}
                </p>

            </div>
        </div>

    </TiendaLayout>
</template>



<script setup>
import { computed } from "vue"
import TiendaLayout from "@/Layouts/TiendaLayout.vue"

const props = defineProps({
    pedido: Object
})


// MAPA DE ESTADOS
const steps = {
    PENDIENTE: 1,
    PREPARANDO: 2,
    EN_CAMINO: 3,
    ENTREGADO: 4,
}

const colores = {
    PENDIENTE: "bg-secondary",
    PREPARANDO: "bg-warning text-dark",
    EN_CAMINO: "bg-info text-dark",
    ENTREGADO: "bg-success",
}


// ESTADO ACTUAL
const actual = computed(() =>
    steps[props.pedido.envio.estado] ?? 1
)


// TEXTO
const estadoTexto = computed(() =>
    (props.pedido.envio.estado || "PENDIENTE").replace("_", " ")
)


// COLOR
const estadoColor = computed(() =>
    colores[props.pedido.envio.estado] || "bg-secondary"
)


// PORCENTAJE DEL PROGRESS
const progressWidth = computed(() =>
    ((actual.value - 1) * 33) + "%"
)


// CLASE POR STEP
const badgeClass = (n) => {
    return actual.value >= n
        ? estadoColor.value
        : "bg-light text-muted border"
}



// FORMATO DE NUMEROS
const format = n => Number(n).toFixed(2)

</script>
