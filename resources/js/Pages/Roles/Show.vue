<template>
<AdminLTELayout 
    :title="`Permisos del Rol: ${role.name}`"
    index_title="Roles"
    index_link="roles.index"
>

    <div class="row">

        <div class="col-lg-12">

            <div class="card shadow">

                <div class="card-header">
                    <h5>Asignar Permisos</h5>
                </div>

                <div class="card-body">

                    <form @submit.prevent="submit">

                        <!-- check all -->
                        <div class="form-check mb-3">
                            <input
                                id="checkAll"
                                type="checkbox"
                                class="form-check-input"
                                v-model="all"
                            >
                            <label class="form-check-label" for="checkAll">
                                Seleccionar todos
                            </label>
                        </div>

                        <!-- permisos en columnas -->
                        <div class="row">

                            <div
                                class="col-md-3 mb-3"
                                v-for="chunk in chunks"
                                :key="chunk[0].id"
                            >
                                <div
                                    class="form-check mb-2"
                                    v-for="p in chunk"
                                    :key="p.id"
                                >
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        :value="p.id"
                                        v-model="form.permissions"
                                    >
                                    <label class="form-check-label">
                                        {{ p.name }}
                                    </label>
                                </div>
                            </div>

                        </div>

                        <!-- botones -->
                        <div class="mt-4">

                            <Link
                                :href="route('roles.index')"
                                class="btn btn-danger me-2"
                            >
                                Volver
                            </Link>

                            <button class="btn btn-primary">
                                Asignar permisos
                            </button>

                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</AdminLTELayout>
</template>



<script setup>
import { computed, ref, watch } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'


const props = defineProps({
    role: Object,
    permissions: Array,
    assigned: Array
})

/* form */
const form = useForm({
    permissions: [...props.assigned]   // <-- ESTO era lo que faltaba
})

/* seleccionar todos */
const all = ref(false)

watch(all, val => {
    if (val) {
        form.permissions = props.permissions.map(p => p.id)
    } else {
        form.permissions = []
    }
})

/* dividir permisos en columnas */
const chunks = computed(() => {
    const size = Math.ceil(props.permissions.length / 4)
    let out = []
    for (let i = 0; i < props.permissions.length; i += size)
        out.push(props.permissions.slice(i, i + size))
    return out
})

/* enviar */
function submit(){
    form.patch(route('roles.update.permissions', props.role.id))
}

</script>
