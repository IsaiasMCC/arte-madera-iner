<template>
  <AdminLTELayout title="Dashboard">

    <div class="row">
      <div class="col-lg-3 col-md-6">
        <div class="card text-white bg-primary mb-3">
          <div class="card-body d-flex justify-content-between">
            <div>
              <h5 class="card-title">Usuarios</h5>
              
              <h2>{{ stats?.usuarios ?? 0 }}</h2>
            </div>
            <div class="icon">
              <i class="fa fa-users fa-3x"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="card text-white bg-success mb-3">
          <div class="card-body d-flex justify-content-between">
            <div>
              <h5 class="card-title">Pedidos</h5>
              <h2>{{ stats?.pedidos ?? 0 }}</h2>
            </div>
            <div class="icon">
              <i class="fa fa-shopping-cart fa-3x"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="card text-white bg-warning mb-3">
          <div class="card-body d-flex justify-content-between">
            <div>
              <h5 class="card-title">Envíos</h5>
              <h2>{{ stats?.envios ?? 0 }}</h2>
            </div>
            <div class="icon">
              <i class="fa fa-truck fa-3x"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="card text-white bg-danger mb-3">
          <div class="card-body d-flex justify-content-between">
            <div>
              <h5 class="card-title">Pagos</h5>
              <hr></hr>
              <h4> {{ stats?.totalPagos.toLocaleString() ?? 0 }} Bs.</h4>
            </div>
            <div class="icon">
              <i class="fa fa-dollar-sign fa-3x"></i>
            </div>
          </div>
        </div>
      </div>
    </div>



    <!-- Gráficos -->
    <div class="row">
      <div class="col-md-6">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">Ventas por Fecha</h3>
          </div>
          <div class="card-body">
            <canvas ref="ventasChartRef"></canvas>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card card-warning card-outline">
          <div class="card-header">
            <h3 class="card-title">Pedidos por Estado</h3>
          </div>
          <div class="card-body">
            <canvas ref="pedidosChartRef"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-12">
        <div class="card card-success card-outline">
          <div class="card-header">
            <h3 class="card-title">Productos más vendidos</h3>
          </div>
          <div class="card-body">
            <canvas ref="productosChartRef"></canvas>
          </div>
        </div>
      </div>
    </div>

  </AdminLTELayout>
</template>

<script setup>
import AdminLTELayout from '@/Layouts/AdminLTELayout.vue'
import { onMounted, ref } from 'vue'
import { defineProps } from 'vue'
import Chart from 'chart.js/auto'

const props = defineProps({
  stats: Object,
  ventasLabels: Array,
  ventasData: Array,
  pedidosEstados: Array,
  pedidosData: Array,
  productosLabels: Array,
  productosData: Array
})

const ventasChartRef = ref(null)
const pedidosChartRef = ref(null)
const productosChartRef = ref(null)

onMounted(() => {
  // Ventas
  new Chart(ventasChartRef.value, {
    type: 'line',
    data: {
      labels: props.ventasLabels,
      datasets: [{
        label: 'Ventas',
        data: props.ventasData,
        borderColor: 'rgba(54, 162, 235, 1)',
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        tension: 0.3
      }]
    },
    options: { responsive: true }
  })

  // Pedidos
  new Chart(pedidosChartRef.value, {
    type: 'doughnut',
    data: {
      labels: props.pedidosEstados,
      datasets: [{
        data: props.pedidosData,
        backgroundColor: ['#f6c23e', '#36b9cc', '#e74a3b', '#1cc88a']
      }]
    },
    options: { responsive: true }
  })

  // Productos
  new Chart(productosChartRef.value, {
    type: 'bar',
    data: {
      labels: props.productosLabels,
      datasets: [{
        label: 'Cantidad Vendida',
        data: props.productosData,
        backgroundColor: '#4e73df'
      }]
    },
    options: { responsive: true }
  })
})
</script>
