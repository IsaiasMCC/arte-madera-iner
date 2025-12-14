<template>
    <AdminLTELayout :title="`Estado del Pedido #${pedido.id}`">

        <h2 class="text-center mb-4">
            Estado del Pedido #{{ pedido.id }}
        </h2>

        <!-- Barra de progreso -->
        <div class="container">

            <div class="progress" style="height:25px;">
                <div class="progress-bar" :class="colorEstado" role="progressbar" :style="{ width: widthProgress }">
                </div>
            </div>

            <!-- Etiquetas -->
            <div class="d-flex justify-content-between mt-3">

                <span :class="actual >= 1 ? 'fw-bold text-warning' : 'text-muted'">
                    Pendiente
                </span>

                <span :class="actual >= 2 ? 'fw-bold text-info' : 'text-muted'">
                    Preparando
                </span>

                <span :class="actual >= 3 ? 'fw-bold text-primary' : 'text-muted'">
                    En camino
                </span>

                <span :class="actual >= 4 ? 'fw-bold text-success' : 'text-muted'">
                    Entregado
                </span>

            </div>

            <!-- Card info -->
            <div class="card mt-4">
                <div class="card-body">

                    <h5>Dirección de entrega</h5>
                    <p>
                        {{ pedido.envio?.direccion ?? '' }} <br>
                        {{ pedido.envio?.ciudad ?? '' }}
                        - {{ pedido.envio?.codigo_postal ?? '' }}
                    </p>

                    <h5 class="mt-4">Estado actual</h5>

                    <p :class="claseTextoEstado" class="fw-bold">
                        {{ textoEstado }}
                    </p>

                </div>
            </div>

        </div>

    </AdminLTELayout>
</template>



<script setup>
import AdminLTELayout from "@/Layouts/AdminLTELayout.vue"
import { computed } from "vue"

const props = defineProps({
    pedido: Object
})

const estado = computed(() =>
    (props.pedido.envio?.estado || "pendiente").toLowerCase()
)

const steps = {
    pendiente: 1,
    preparando: 2,
    en_camino: 3,
    entregado: 4
}

const actual = computed(() => steps[estado.value])

const widthProgress = computed(() => `${(actual.value - 1) * 33}%`)

const colorEstado = computed(() => {
    return {
        "pendiente": "bg-warning",
        "preparando": "bg-info",
        "en_camino": "bg-primary",
        "entregado": "bg-success"
    }[estado.value]
})

const claseTextoEstado = computed(() => {
    return {
        "pendiente": "text-warning",
        "preparando": "text-info",
        "en_camino": "text-primary",
        "entregado": "text-success"
    }[estado.value]
})

const textoEstado = computed(() => {
    return estado.value
        .replace("_", " ")
        .replace(/^\w/, c => c.toUpperCase())
})

</script>
