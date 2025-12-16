<template>
    <TiendaLayout title="Mis Pedidos">

        <h1 class="mb-4 text-center" style="color:#8B5E3C;">Mis Pedidos</h1>

        <div v-for="pedido in pedidos" :key="pedido.id" class="card mb-4 shadow-sm">

            <!-- HEADER -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    Pedido #{{ pedido.id }} - {{ formatDate(pedido.created_at) }}
                </span>

                <div>
                    <span class="badge bg-info me-2">
                        {{ pedido.pago?.tipo_pago === 'CONTADO' ? 'Contado' : 'Crédito' }}
                    </span>
                    <span class="badge" :class="pedido.saldo_pendiente === 0 ? 'bg-success' : 'bg-warning'">
                        {{ pedido.saldo_pendiente === 0 ? 'Pagado' : 'Pendiente' }}
                    </span>
                </div>
            </div>

            <!-- BODY -->
            <div class="card-body">

                <!-- PRODUCTOS -->
                <h5 class="card-title">Productos</h5>

                <ul class="list-group list-group-flush mb-3">
                    <li v-for="d in pedido.detalles" :key="d.id"
                        class="list-group-item d-flex justify-content-between align-items-center">
                        {{ d.producto.nombre }} (x{{ d.cantidad }})
                        <span>Bs {{ format(d.subtotal * d.cantidad) }}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                        Total:
                        <span>Bs {{ format(pedido.total) }}</span>
                    </li>
                </ul>

                <!-- PAGOS -->
                <h6>Pagos realizados</h6>
                <ul v-if="pedido.pago?.detalle_pagos?.length" class="list-group mb-3">
                    <li v-for="dp in pedido.pago.detalle_pagos" :key="dp.id"
                        class="list-group-item d-flex justify-content-between">
                        Fecha: {{ dp.fecha }} {{ dp.hora }}: Monto Bs {{ format(dp.monto) }} : Metodo: {{
                            dp.metodo_pago?.nombre }}
                        <span>Saldo restante: Bs {{ format(dp.saldo) }}</span>
                    </li>
                </ul>

                <p v-else class="text-muted">No se han realizado pagos aún.</p>


                <!-- SOLO MOSTRAR OPCIONES DE PAGO SI HAY SALDO PENDIENTE -->
                <div v-if="pedido.saldo_pendiente > 0">

                    <!-- ALERTA PARA CONTADO -->
                    <div v-if="esContado(pedido)" class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Este pedido es al <strong>CONTADO</strong>. Debes pagar el monto total de:
                        <strong>Bs {{ format(pedido.saldo_pendiente) }}</strong>
                    </div>

                    <!-- ALERTA PARA CRÉDITO -->
                    <div v-else class="alert alert-warning">
                        <i class="bi bi-credit-card me-2"></i>
                        Este pedido es al <strong>CRÉDITO</strong>. Puedes realizar pagos parciales.
                        Saldo pendiente: <strong>Bs {{ format(pedido.saldo_pendiente) }}</strong>
                    </div>

                    <!-- MÉTODO DE PAGO -->
                    <div class="mt-3">
                        <label class="form-label fw-bold">Método de pago</label>

                        <select class="form-select" v-model="metodo[pedido.id]">
                            <option value="manual">Pago manual</option>
                            <option value="pagofacil">Pago QR - PagoFácil</option>
                        </select>
                    </div>


                    <!-- PAGO MANUAL -->
                    <div v-if="metodo[pedido.id] === 'manual'" class="mt-3">

                        <!-- CONTADO: Monto fijo -->
                        <div v-if="esContado(pedido)">
                            <label class="form-label">
                                Monto a pagar (Total del pedido)
                            </label>
                            <input type="text" class="form-control mb-2" :value="'Bs ' + format(pedido.saldo_pendiente)"
                                disabled readonly />
                            <p class="text-muted small">
                                <i class="bi bi-lock-fill me-1"></i>
                                El monto está fijado porque el pedido es al contado
                            </p>
                        </div>

                        <!-- CRÉDITO: Monto variable -->
                        <div v-else>
                            <label class="form-label">
                                Monto a pagar (Saldo pendiente: Bs {{ format(pedido.saldo_pendiente) }})
                            </label>
                            <input type="number" class="form-control mb-2" v-model="montoManual[pedido.id]" :min="0.01"
                                :max="pedido.saldo_pendiente" step="0.01" placeholder="Ingrese el monto a pagar"
                                @input="validarMontoManual(pedido)" />
                            <small v-if="errorMontoManual[pedido.id]" class="text-danger d-block mb-2">
                                {{ errorMontoManual[pedido.id] }}
                            </small>
                        </div>

                        <button class="btn btn-wood w-100" @click="pagarManual(pedido)"
                            :disabled="!esContado(pedido) && !!errorMontoManual[pedido.id]">
                            Pagar Manualmente
                        </button>
                    </div>


                    <!-- PAGO QR -->
                    <div v-if="metodo[pedido.id] === 'pagofacil'" class="mt-3">

                        <!-- CONTADO: Monto fijo -->
                        <div v-if="esContado(pedido)">
                            <label class="form-label">
                                Monto a pagar con QR (Total del pedido)
                            </label>
                            <input type="text" class="form-control mb-2" :value="'Bs ' + format(pedido.saldo_pendiente)"
                                disabled readonly />
                            <p class="text-muted small">
                                <i class="bi bi-lock-fill me-1"></i>
                                El monto está fijado porque el pedido es al contado
                            </p>
                        </div>

                        <!-- CRÉDITO: Monto variable -->
                        <div v-else>
                            <label class="form-label">
                                Monto a pagar con QR (Saldo pendiente: Bs {{ format(pedido.saldo_pendiente) }})
                            </label>
                            <input type="number" class="form-control mb-2" v-model="montoQR[pedido.id]" :min="0.01"
                                :max="pedido.saldo_pendiente" step="0.01" placeholder="Ingrese el monto a pagar"
                                @input="validarMontoQR(pedido)" />
                            <small v-if="errorMontoQR[pedido.id]" class="text-danger d-block mb-2">
                                {{ errorMontoQR[pedido.id] }}
                            </small>
                        </div>

                        <button class="btn btn-primary w-100" @click="generarQR(pedido)"
                            :disabled="cargandoQR[pedido.id] || (!esContado(pedido) && !!errorMontoQR[pedido.id])">
                            {{ cargandoQR[pedido.id] ? 'Generando...' : 'Generar QR' }}
                        </button>

                        <!-- CONTENEDOR QR -->
                        <div class="text-center mt-3">
                            <img v-if="qr[pedido.id]" :src="qr[pedido.id].qr" style="width:230px;" class="img-fluid" />

                            <p v-if="qr[pedido.id]" class="mt-2">
                                Transacción: {{ qr[pedido.id].transaccion }}
                            </p>

                            <button v-if="qr[pedido.id]" class="btn btn-success mt-3" @click="verificarQR(pedido)">
                                Verificar Pago
                            </button>

                            <p v-if="estadoQR[pedido.id]" class="fw-bold mt-2">
                                {{ estadoQR[pedido.id] }}
                            </p>
                        </div>
                    </div>

                </div>

                <!-- ESTADO DEL ENVÍO -->
                <Link :href="route('pedidos.estado', pedido.id)" class="btn btn-outline-primary w-100 mt-3">
                    Ver estado del envío
                </Link>

            </div>
        </div>


        <!-- MODAL -->
        <div class="modal fade" id="modalPagoRealizado" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Pago realizado</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p class="fs-5 text-center">
                            <strong v-html="textoModal"></strong>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success w-100" data-bs-dismiss="modal" @click="recargar">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </TiendaLayout>
</template>


<script setup>
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import TiendaLayout from '@/Layouts/TiendaLayout.vue'
import { onMounted } from 'vue'
import axios from "axios"
import { usePage } from '@inertiajs/inertia-vue3'

const page = usePage()
const user = page.props.auth.user
const userId = user.id
// PROPS
const props = defineProps({
    pedidos: Array
})

// ESTADOS
const metodo = ref({})
const montoManual = ref({})
const montoQR = ref({})
const qr = ref({})
const estadoQR = ref({})
const textoModal = ref("")
const cargandoQR = ref({})
const errorMontoManual = ref({})
const errorMontoQR = ref({})


// UTILS
const format = n => Number(n).toFixed(2)
const formatDate = d => new Date(d).toLocaleString("es-BO")

// Verificar si el pedido es al contado
const esContado = (pedido) => {
    return pedido.pago?.tipo_pago?.toUpperCase() === 'CONTADO'
}

function abrirModal(monto, saldo) {
    textoModal.value = `Pago registrado: Bs ${format(monto)}.<br>Saldo pendiente: Bs ${format(saldo)}`

    const modal = new bootstrap.Modal(
        document.getElementById("modalPagoRealizado")
    )
    modal.show()
}

function recargar() {
    montoManual.value = {}
    montoQR.value = {}
    errorMontoManual.value = {}
    errorMontoQR.value = {}
    router.reload()
}


// ---------------------------------------------
// VALIDACIONES
// ---------------------------------------------
function validarMontoManual(pedido) {
    const monto = Math.round(Number(montoManual.value[pedido.id]) * 100)
    const saldo = Math.round(Number(pedido.saldo_pendiente) * 100)

    if (isNaN(monto)) {
        errorMontoManual.value[pedido.id] = "Ingrese un monto válido"
    } else if (monto <= 0) {
        errorMontoManual.value[pedido.id] = "El monto debe ser mayor a 0"
    } else if (monto > saldo) {
        errorMontoManual.value[pedido.id] =
            `El monto no puede ser mayor a Bs ${format(pedido.saldo_pendiente)}`
    } else {
        errorMontoManual.value[pedido.id] = null
    }
}



function validarMontoQR(pedido) {
    const monto = Math.round(Number(montoQR.value[pedido.id]) * 100)
    const saldo = Math.round(Number(pedido.saldo_pendiente) * 100)

    if (isNaN(monto)) {
        errorMontoQR.value[pedido.id] = "Ingrese un monto válido"
    } else if (monto <= 0) {
        errorMontoQR.value[pedido.id] = "El monto debe ser mayor a 0"
    } else if (monto > saldo) {
        errorMontoQR.value[pedido.id] =
            `El monto no puede ser mayor a Bs ${format(pedido.saldo_pendiente)}`
    } else {
        errorMontoQR.value[pedido.id] = null
    }
}



// ---------------------------------------------
// PAGO MANUAL
// ---------------------------------------------
function pagarManual(pedido) {
    let monto

    // Si es CONTADO, usar el saldo pendiente completo
    if (esContado(pedido)) {
        monto = Math.round(Number(pedido.saldo_pendiente) * 100)
    } else {
        // Si es CRÉDITO, usar el monto ingresado por el usuario
        monto = Math.round(Number(montoManual.value[pedido.id]) * 100)

        if (isNaN(monto) || monto <= 0) {
            return alert("Ingresa un monto válido")
        }

        if (monto > Math.round(Number(pedido.saldo_pendiente) * 100)) {
            return alert(`El monto no puede ser mayor al saldo pendiente (Bs ${format(pedido.saldo_pendiente)})`)
        }
    }

    axios.post(route('checkout.procesar_detalle', pedido.pago.id), {
        monto: monto / 100  // Mandar el monto en formato decimal al backend
    })
        .then(res => {
            abrirModal(res.data.monto, res.data.saldo)
        })
        .catch(err => {
            console.error(err)
            alert("Error al procesar el pago")
        })
}



// ---------------------------------------------
// GENERAR QR
// ---------------------------------------------
function generarQR(pedido) {
    console.log('Generando QR para pedido:', pedido)
    let monto

    // Si es CONTADO, usar el saldo pendiente completo
    if (esContado(pedido)) {
        monto = pedido.saldo_pendiente
    } else {
        // Si es CRÉDITO, usar el monto ingresado por el usuario
        monto = parseFloat(montoQR.value[pedido.id] || 0)

        if (!monto || monto <= 0) {
            return alert("Ingresa un monto válido")
        }

        if (monto > pedido.saldo_pendiente) {
            return alert(`El monto no puede ser mayor al saldo pendiente (Bs ${format(pedido.saldo_pendiente)})`)
        }
    }

    cargandoQR.value[pedido.id] = true

    axios.post(route('pagofacil.generar-qr'), {
        pago_id: pedido.pago.id,
        monto
    })
        .then(res => {
            qr.value = {
                ...qr.value,
                [pedido.id]: res.data
            }
        })
        .catch(err => {
            console.error(err)
            console.log('Error al generar el QR:', err.response ? err.response.data : err.message)
            alert("Error al generar el QR")
        })
        .finally(() => {
            cargandoQR.value[pedido.id] = false
        })
}


// ---------------------------------------------
// VERIFICAR QR
// ---------------------------------------------
function verificarQR(pedido) {
    console.log('Verificando pago para pedido:', pedido)
    estadoQR.value[pedido.id] = "Consultando..."

    axios.post(route('pagofacil.consultar-estado'), {
        tnTransaccion: qr.value[pedido.id].transaccion
    })
        .then(res => {
            console.log('Respuesta del servidor:', res.data)

            if (res.data.estado === "PENDIENTE") {
                estadoQR.value[pedido.id] = "Estado: PENDIENTE ⏳"
            } else if (res.data.estado === "COMPLETADO") {
                estadoQR.value[pedido.id] = "Estado: COMPLETADO ✓"
            } else if (res.data.estado === "RECHAZADO") {
                estadoQR.value[pedido.id] = "Estado: RECHAZADO ✗"
            } else if (res.data.estado === "ANULADO") {
                estadoQR.value[pedido.id] = "Estado: ANULADO"
            } else {
                estadoQR.value[pedido.id] = `Estado: ${res.data.estado}`
            }
        })
        .catch(err => {
            console.error('Error al verificar:', err)
            estadoQR.value[pedido.id] = "Error al consultar el estado"
            alert("No se pudo consultar el pago")
        })
}


// EVENTO EN TIEMPO REAL
onMounted(() => {
    if (!userId) return; // por seguridad, verificar que haya usuario

    window.Echo.private(`usuario.${userId}.pagos`)
        .listen('PagoRealizado', (e) => {
            abrirModal(e.monto, e.saldo_pendiente)
        });
});


</script>