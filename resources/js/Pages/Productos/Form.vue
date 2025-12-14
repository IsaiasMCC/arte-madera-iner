<template>
  <div>
    <!-- Nombre -->
    <div class="mb-3">
      <label class="form-label">Nombre</label>
      <input type="text" class="form-control" v-model="form.nombre" :class="{ 'is-invalid': form.errors.nombre }" />
      <div v-if="form.errors.nombre" class="invalid-feedback d-block">{{ form.errors.nombre }}</div>
    </div>

    <!-- Descripción -->
    <div class="mb-3">
      <label class="form-label">Descripción</label>
      <textarea class="form-control" v-model="form.descripcion"
        :class="{ 'is-invalid': form.errors.descripcion }"></textarea>
      <div v-if="form.errors.descripcion" class="invalid-feedback d-block">{{ form.errors.descripcion }}</div>
    </div>

    <div class="row">
      <!-- Precio -->
      <div class="col-md-6 mb-3">
        <label class="form-label">Precio</label>
        <input type="number" class="form-control" v-model="form.precio" :class="{ 'is-invalid': form.errors.precio }" />
        <div v-if="form.errors.precio" class="invalid-feedback d-block">{{ form.errors.precio }}</div>
      </div>

      <!-- Stock -->
      <div class="col-md-6 mb-3">
        <label class="form-label">Stock</label>
        <input type="number" class="form-control" v-model="form.stock" :class="{ 'is-invalid': form.errors.stock }" />
        <div v-if="form.errors.stock" class="invalid-feedback d-block">{{ form.errors.stock }}</div>
      </div>

      <!-- Categoría -->
      <div class="col-md-6 mb-3">
        <label class="form-label">Categoría</label>
        <select class="form-control" v-model="form.categoria_id" :class="{ 'is-invalid': form.errors.categoria_id }">
          <option value="">-- Seleccionar --</option>
          <option v-for="c in categorias" :key="c.id" :value="c.id">{{ c.nombre }}</option>
        </select>
        <div v-if="form.errors.categoria_id" class="invalid-feedback d-block">{{ form.errors.categoria_id }}</div>
      </div>

      <!-- Tienda -->
      <div class="col-md-6 mb-3">
        <label class="form-label">Tienda</label>
        <select class="form-control" v-model="form.tienda_id" :class="{ 'is-invalid': form.errors.tienda_id }">
          <option value="">-- Seleccionar --</option>
          <option v-for="t in tiendas" :key="t.id" :value="t.id">{{ t.nombre }}</option>
        </select>
        <div v-if="form.errors.tienda_id" class="invalid-feedback d-block">{{ form.errors.tienda_id }}</div>
      </div>
    </div>

    <!-- Foto -->
    <div class="mb-3">
      <label class="form-label">Foto</label>
      <input type="file" class="form-control" @change="onFile" :class="{ 'is-invalid': form.errors.foto }" />
      <div v-if="preview" class="mt-2">
        <img :src="preview" style="width:120px;height:120px;object-fit:cover" class="img-thumbnail" />
      </div>
      <div v-if="form.errors.foto" class="invalid-feedback d-block">{{ form.errors.foto }}</div>
    </div>

    <!-- Estado -->
    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" v-model="form.estado" id="estadoCheck" />
      <label class="form-check-label" for="estadoCheck">Activo</label>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  form: Object,
  categorias: Array,
  tiendas: Array,
  producto: Object // opcional, solo para editar
})

const preview = ref(props.producto?.foto ?? null)

function onFile(e) {
  const file = e.target.files[0]
  if (!file) {
    preview.value = props.producto?.foto ?? null
    props.form.foto = null
    return
  }
  props.form.foto = file
  const reader = new FileReader()
  reader.onload = (ev) => (preview.value = ev.target.result)
  reader.readAsDataURL(file)
}


</script>
