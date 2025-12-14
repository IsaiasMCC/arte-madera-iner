<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import AdminLTELayout from '../../Layouts/AdminLTELayout.vue'

const props = defineProps({
    pedidos: Array,
    errors: Object,
    flash: Object
})

const form = useForm({
    pedido_id: '',
    direccion: '',
    ciudad: '',
    codigo_postal: '',
    estado: 'pendiente'
})

const estados = {
    pendiente: 'Pendiente',
    preparando: 'Preparando',
    en_camino: 'En camino',
    entregado: 'Entregado'
}

// Toastr equivalente
if (Object.keys(props.errors).length) {
    toastr.warning("Validaciones Incorrectas", "Warning");
}

if (props.flash.error) {
    toastr.error(props.flash.error, "Error");
}

const submit = () => {
    form.post(route('envios.store'))
}
</script>

<template>
    <AdminLTELayout title="Crear Pedidos">

        <template #header>
            <h2> Agregar Envío </h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <Link :href="route('envios.index')"> Inicio </Link>
                </li>
                <li class="breadcrumb-item active">
                    <Link :href="route('envios.create')"> Agregar </Link>
                </li>
            </ol>
        </template>

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox-content style-tema">

                    <form @submit.prevent="submit">

                        <!-- Pedido -->
                        <div class="form-group">
                            <label for="pedido_id">Pedido</label>

                            <select
                                class="form-control"
                                :class="{'is-invalid': errors.pedido_id}"
                                v-model="form.pedido_id"
                                required
                            >
                                <option value="">Seleccionar Pedido</option>

                                <option
                                    v-for="p in pedidos"
                                    :value="p.id"
                                >
                                    Pedido #{{ p.id }} - {{ p.user.name }}
                                </option>
                            </select>

                            <div class="invalid-feedback d-block" v-if="errors.pedido_id">
                                {{ errors.pedido_id }}
                            </div>
                        </div>


                        <!-- Dirección -->
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input
                                type="text"
                                class="form-control"
                                :class="{'is-invalid': errors.direccion}"
                                v-model="form.direccion"
                                required
                            >
                            <div class="invalid-feedback d-block" v-if="errors.direccion">
                                {{ errors.direccion }}
                            </div>
                        </div>


                        <!-- Ciudad -->
                        <div class="form-group">
                            <label for="ciudad">Ciudad</label>
                            <input
                                type="text"
                                class="form-control"
                                :class="{'is-invalid': errors.ciudad}"
                                v-model="form.ciudad"
                                required
                            >
                            <div class="invalid-feedback d-block" v-if="errors.ciudad">
                                {{ errors.ciudad }}
                            </div>
                        </div>


                        <!-- Código Postal -->
                        <div class="form-group">
                            <label for="codigo_postal">Código Postal</label>
                            <input
                                type="text"
                                class="form-control"
                                :class="{'is-invalid': errors.codigo_postal}"
                                v-model="form.codigo_postal"
                            >
                            <div class="invalid-feedback d-block" v-if="errors.codigo_postal">
                                {{ errors.codigo_postal }}
                            </div>
                        </div>


                        <!-- Estado -->
                        <div class="form-group">
                            <label for="estado">Estado del Envío</label>

                            <select
                                class="form-control"
                                :class="{'is-invalid': errors.estado}"
                                v-model="form.estado"
                                required
                            >
                                <option
                                    v-for="(value,key) in estados"
                                    :value="key"
                                >
                                    {{ value }}
                                </option>
                            </select>

                            <div class="invalid-feedback d-block" v-if="errors.estado">
                                {{ errors.estado }}
                            </div>
                        </div>


                        <Link :href="route('envios.index')" class="btn btn-danger">
                            Cancelar
                        </Link>

                        <button type="submit" class="btn btn-primary">
                            Guardar
                        </button>

                    </form>

                </div>
            </div>
        </div>

    </AdminLTELayout>
</template>
