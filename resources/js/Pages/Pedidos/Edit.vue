<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { onMounted } from "vue"
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'

const props = defineProps({
    pedido: Object,
    usuarios: Array,
    tiendas: Array,
    errors: Object,
    flash: Object
})

const form = useForm({
    fecha: props.pedido.fecha,
    hora: props.pedido.hora,
    total: props.pedido.total,
    estado: props.pedido.estado,
    user_id: props.pedido.user_id,
    tienda_id: props.pedido.tienda_id
})

onMounted(() => {
    toastr.options = {
        positionClass: "toast-top-right",
        timeOut: 2000,
        progressBar: true
    };

    if (Object.keys(props.errors).length)
        toastr.warning("Validaciones Incorrectas", "Warning");

    if (props.flash.error)
        toastr.error(props.flash.error, "Error");
})

const submit = () => {
    form.put(route('pedidos.update', props.pedido.id))
}
</script>


<template>
<AdminLTELayout title="Editar Pedido">

    <!-- HEADER -->
    <template #header>
        <h2> Editar Pedido </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <Link :href="route('pedidos.index')"> Inicio </Link>
            </li>
            <li class="breadcrumb-item active">
                Editar Pedido #{{ pedido.id }}
            </li>
        </ol>
    </template>



    <!-- CONTENT -->
    <div class="row">
        <div class="col-lg-12">

            <div class="ibox-content style-tema">

                <form @submit.prevent="submit">

                    <!-- Fecha -->
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date"
                            class="form-control"
                            :class="{'is-invalid': errors.fecha}"
                            v-model="form.fecha">

                        <div class="invalid-feedback d-block" v-if="errors.fecha">
                            {{ errors.fecha }}
                        </div>
                    </div>


                    <!-- Hora -->
                    <div class="form-group">
                        <label>Hora</label>
                        <input type="time"
                            class="form-control"
                            :class="{'is-invalid': errors.hora}"
                            v-model="form.hora">

                        <div class="invalid-feedback d-block" v-if="errors.hora">
                            {{ errors.hora }}
                        </div>
                    </div>


                    <!-- Total -->
                    <div class="form-group">
                        <label>Total</label>
                        <input type="number" step="0.01"
                            class="form-control"
                            :class="{'is-invalid': errors.total}"
                            v-model="form.total">

                        <div class="invalid-feedback d-block" v-if="errors.total">
                            {{ errors.total }}
                        </div>
                    </div>


                    <!-- Estado -->
                    <div class="form-group">
                        <label>Estado</label>
                        <select class="form-control"
                            :class="{'is-invalid': errors.estado}"
                            v-model="form.estado">

                            <option value="PENDIENTE">Pendiente</option>
                            <option value="FINALIZADO">Finalizado</option>
                            <option value="CANCELADO">Cancelado</option>
                        </select>

                        <div class="invalid-feedback d-block" v-if="errors.estado">
                            {{ errors.estado }}
                        </div>
                    </div>


                    <!-- Usuario -->
                    <div class="form-group">
                        <label>Cliente</label>
                        <select class="form-control"
                            :class="{'is-invalid': errors.user_id}"
                            v-model="form.user_id">

                            <option
                                v-for="u in usuarios"
                                :value="u.id">
                                {{ u.nombres }} {{ u.apellidos }} - ({{ u.email }})
                            </option>
                        </select>

                        <div class="invalid-feedback d-block" v-if="errors.user_id">
                            {{ errors.user_id }}
                        </div>
                    </div>



                    <!-- Tienda -->
                    <div class="form-group">
                        <label>Tienda</label>
                        <select class="form-control"
                            :class="{'is-invalid': errors.tienda_id}"
                            v-model="form.tienda_id">

                            <option
                                v-for="t in tiendas"
                                :value="t.id">
                                {{ t.nombre }}
                            </option>
                        </select>

                        <div class="invalid-feedback d-block" v-if="errors.tienda_id">
                            {{ errors.tienda_id }}
                        </div>
                    </div>



                    <!-- Productos -->
                    <h5 class="mt-4">Productos del pedido</h5>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="d in pedido.detalles">
                                <td>{{ d.producto.nombre }}</td>
                                <td>{{ d.cantidad }}</td>
                                <td>${{ (d.subtotal * d.cantidad).toFixed(2) }}</td>
                            </tr>
                        </tbody>
                    </table>



                    <!-- Buttons -->
                    <Link :href="route('pedidos.index')" class="btn btn-danger">
                        Cancelar
                    </Link>

                    <button class="btn btn-primary">
                        Actualizar Pedido
                    </button>

                </form>

            </div>
        </div>
    </div>

</AdminLTELayout>
</template>
