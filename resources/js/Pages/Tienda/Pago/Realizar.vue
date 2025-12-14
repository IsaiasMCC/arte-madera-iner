<template>
    <TiendaLayout title="Realizar Pago">

        <h1 class="mb-4 text-center" style="color:#8B5E3C;">
            Resumen de tu Pedido
        </h1>

        <!-- PRODUCTOS -->
        <div class="card mb-4">
            <div class="card-body">

                <h5 class="card-title">Productos</h5>

                <ul class="list-group list-group-flush">

                    <li
                        v-for="d in pedido.detalles"
                        :key="d.id"
                        class="list-group-item d-flex justify-content-between align-items-center"
                    >
                        {{ d.producto.nombre }} (x{{ d.cantidad }})
                        <span>
                            $ {{ (d.subtotal * d.cantidad) }}
                        </span>
                    </li>

                    <li class="list-group-item fw-bold d-flex justify-content-between">
                        Total:
                        <span>$ {{ pedido.total }}</span>
                    </li>

                </ul>
            </div>
        </div>


        <!-- ENVÍO -->
        <div class="card mb-4">
            <div class="card-body">

                <h5 class="card-title">Dirección de envío</h5>

                <p>
                    {{ pedido.envio.direccion }},
                    {{ pedido.envio.ciudad }}
                    {{ pedido.envio.codigo_postal ?? '' }}
                </p>

            </div>
        </div>



        <!-- FORM PAGO -->
        <form @submit.prevent="submit">

            <div class="mb-3">
                <label class="form-label">Seleccione un Plan de Pago</label>

                <select
                    class="form-select"
                    :class="{ 'is-invalid': form.errors.forma_pago }"
                    v-model="form.forma_pago"
                >
                    <option disabled value="">Seleccione un plan de pago</option>
                    <option value="CONTADO">Contado</option>
                    <option value="CREDITO">Crédito</option>
                </select>

                <div v-if="form.errors.forma_pago" class="invalid-feedback">
                    {{ form.errors.forma_pago }}
                </div>
            </div>


            <button class="btn btn-wood w-100">
                Realizar Pedido
            </button>

        </form>

    </TiendaLayout>
</template>



<script setup>
import { useForm } from '@inertiajs/vue3'
import TiendaLayout from '@/Layouts/TiendaLayout.vue'

const props = defineProps({
    pedido: Object
})

const form = useForm({
    forma_pago: ''
})

function submit() {
    form.post(route('checkout.procesar', props.pedido.id))
}
</script>
