<template>
  <div class="app-wrapper">
    <!-- Navbar -->
    <nav class="app-header navbar navbar-expand bg-body">
      <div class="container-fluid">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
              <i class="bi bi-list"></i>
            </a>
          </li>
          <!-- <li class="nav-item d-none d-md-block">
            <a href="#" class="nav-link">Home</a>
          </li>
          <li class="nav-item d-none d-md-block">
            <a href="#" class="nav-link">Contacto</a>
          </li> -->
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ms-auto">
          <!-- Buscar -->
          <!-- <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
              <i class="bi bi-search"></i>
            </a>
          </li> -->

          <!-- Mensajes (ahora es selector de accesibilidad) -->
          <li class="nav-item dropdown">
            <a class="nav-link" data-bs-toggle="dropdown" href="#">
              <i class="bi bi-universal-access-circle"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-2">
              <span class="dropdown-item dropdown-header">Accesibilidad</span>
              <div class="dropdown-divider"></div>

              <!-- Tamaño de fuente -->
              <span class="dropdown-item dropdown-header">Tamaño de Fuente</span>

              <button class="dropdown-item d-flex align-items-center" @click="setTamanoFuente('normal')">
                <i class="bi bi-type me-2"></i> Normal
                <i v-if="tamanoFuente === 'normal'" class="bi bi-check-lg ms-auto text-success"></i>
              </button>

              <button class="dropdown-item d-flex align-items-center" @click="setTamanoFuente('fuente-grande')">
                <i class="bi bi-type-h1 me-2"></i> Grande
                <i v-if="tamanoFuente === 'fuente-grande'" class="bi bi-check-lg ms-auto text-success"></i>
              </button>

              <button class="dropdown-item d-flex align-items-center" @click="setTamanoFuente('fuente-extra-grande')">
                <i class="bi bi-type-h2 me-2"></i> Extra Grande
                <i v-if="tamanoFuente === 'fuente-extra-grande'" class="bi bi-check-lg ms-auto text-success"></i>
              </button>

              <div class="dropdown-divider"></div>

              <!-- Contraste -->
              <button class="dropdown-item d-flex align-items-center" @click="toggleContrasteAlto">
                <i class="bi bi-circle-half me-2"></i>
                <span>{{ contrasteAlto ? 'Desactivar' : 'Activar' }} Contraste Alto</span>
                <i v-if="contrasteAlto" class="bi bi-check-lg ms-auto text-success"></i>
              </button>
            </div>
          </li>

          <!-- Selector de Temas -->
          <li class="nav-item dropdown">
            <a class="nav-link" data-bs-toggle="dropdown" href="#">
              <i class="bi bi-palette-fill"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end p-2">
              <span class="dropdown-item dropdown-header">Selecciona Tema</span>
              <div class="dropdown-divider"></div>

              <button class="dropdown-item d-flex align-items-center" @click="setTheme('tema-ninos')">
                <i class="bi bi-emoji-smile me-2 text-danger"></i> Niños
              </button>

              <button class="dropdown-item d-flex align-items-center" @click="setTheme('tema-jovenes')">
                <i class="bi bi-emoji-laughing me-2 text-primary"></i> Jóvenes
              </button>

              <button class="dropdown-item d-flex align-items-center" @click="setTheme('tema-adultos')">
                <i class="bi bi-emoji-neutral me-2 text-secondary"></i> Adultos
              </button>

              <div class="dropdown-divider"></div>

              <button class="dropdown-item d-flex align-items-center" @click="toggleModoNoche">
                <i class="bi" :class="modoNoche ? 'bi-sun-fill text-warning' : 'bi-moon-fill text-dark'"></i>
                <span class="ms-2">{{ modoNoche ? 'Modo Día' : 'Modo Noche' }}</span>
              </button>
            </div>
          </li>


          <!-- Pantalla completa -->
          <li class="nav-item">
            <a class="nav-link" href="#" data-lte-toggle="fullscreen">
              <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
              <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
            </a>
          </li>

          <!-- Perfil de usuario -->
          <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <img src="https://ui-avatars.com/api/?name=Admin+User" class="user-image rounded-circle shadow"
                alt="User Image">
              <span class="d-none d-md-inline">{{ authUser.nombres }} {{ authUser.apellidos }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <li class="user-header text-bg-primary">
                <img src="https://ui-avatars.com/api/?name=Admin+User" class="rounded-circle shadow" alt="User Image">
                <p>
                  {{ authUser.nombres }} {{ authUser.apellidos }}
                  <!-- <small>Miembro desde Nov. 2023</small> -->
                </p>
              </li>
              <!-- <li class="user-body">
                <div class="row">
                  <div class="col-4 text-center">
                    <a href="#">Seguidores</a>
                  </div>
                  <div class="col-4 text-center">
                    <a href="#">Ventas</a>
                  </div>
                  <div class="col-4 text-center">
                    <a href="#">Amigos</a>
                  </div>
                </div>
              </li> -->
              <li class="user-footer">
                <!-- <a href="#" class="btn btn-default btn-flat">Perfil</a> -->
                <Link :href="route('auth.logout')" method="get" as="button" class="btn btn-default btn-flat float-end">
                  Cerrar Sesión
                </Link>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Sidebar -->
    <aside class="app-sidebar shadow">
      <div class="sidebar-brand">
        <a href="#" class="brand-link">
          <img src="https://ui-avatars.com/api/?name=ML&background=007bff&color=fff" alt="Logo"
            class="brand-image opacity-75 shadow">
          <span class="brand-text fw-light"> Arte en Madera </span>
        </a>
      </div>

      <!-- SCROLL ENABLED -->
      <div class="sidebar-wrapper" style="overflow-y: auto; max-height: calc(100vh - 60px);">

        <nav class="mt-2">
          <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

            <!-- Dashboard -->
            <li class="nav-header">PRINCIPAL</li>
            <li class="nav-item">
              <Link :href="route('home')" class="nav-link active">
                <i class="nav-icon bi bi-speedometer2"></i>
                <p>Dashboard</p>
              </Link>
            </li>

            <!-- GESTIÓN -->
            <li class="nav-header">GESTIÓN</li>

            <li class="nav-item" v-if="$page.props.auth.permissions.includes('usuarios index')">
              <Link :href="route('usuarios.index')" class="nav-link">
                <i class="nav-icon bi bi-people-fill"></i>
                <p>Usuarios</p>
              </Link>
            </li>

            <li class="nav-item" v-if="$page.props.auth.permissions.includes('roles index')">
              <Link :href="route('roles.index')" class="nav-link">
                <i class="nav-icon bi bi-shield-lock-fill"></i>
                <p>Roles</p>
              </Link>
            </li>

            <li class="nav-item" v-if="$page.props.auth.permissions.includes('categorias index')">
              <Link :href="route('categorias.index')" class="nav-link">
                <i class="nav-icon bi bi-tags-fill"></i>
                <p>Categorías</p>
              </Link>
            </li>

            <li class="nav-item" v-if="$page.props.auth.permissions.includes('metodos_pago index')">
              <Link :href="route('metodos_pago.index')" class="nav-link">
                <i class="nav-icon bi bi-credit-card-2-back-fill"></i>
                <p>Métodos de Pago</p>
              </Link>
            </li>

            <li class="nav-item" v-if="$page.props.auth.permissions.includes('tiendas index')">
              <Link :href="route('tiendas.index')" class="nav-link">
                <i class="nav-icon bi bi-shop"></i>
                <p>Tiendas</p>
              </Link>
            </li>

            <li class="nav-item" v-if="$page.props.auth.permissions.includes('productos index')">
              <Link :href="route('productos.index')" class="nav-link">
                <i class="nav-icon bi bi-box-seam-fill"></i>
                <p>Productos</p>
              </Link>
            </li>

            <li class="nav-item" v-if="$page.props.auth.permissions.includes('envios index')">
              <Link :href="route('envios.index')" class="nav-link">
                <i class="nav-icon bi bi-truck"></i>
                <p>Envíos</p>
              </Link>
            </li>

            <li class="nav-item">
              <Link :href="route('tiendas.tienda')" class="nav-link">
                <i class="nav-icon bi bi-cart-check-fill"></i>
                <p>Tienda de Productos</p>
              </Link>
            </li>

            <li class="nav-item" v-if="$page.props.auth.permissions.includes('pagos index')">
              <Link :href="route('pagos.index')" class="nav-link">
                <i class="nav-icon bi bi-cash-stack"></i>
                <p>Pagos</p>
              </Link>
            </li>

            <!-- <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-bag-check-fill"></i>
            <p>
              Pedidos
              <span class="badge text-bg-danger">6</span>
            </p>
          </a>
        </li> -->

            <!-- Configuración -->
            <!-- <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-gear-fill"></i>
            <p>
              Configuración
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>General</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Perfil</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Seguridad</p>
              </a>
            </li>
          </ul>
        </li> -->

            <!-- REPORTES -->
            <li class="nav-header">REPORTES</li>

            <li class="nav-item">
              <Link :href="route('reportes.ventas')" class="nav-link">
                <i class="nav-icon bi bi-graph-up"></i>
                <p> Ventas </p>
              </Link>
            </li>
            <li class="nav-item">
              <Link :href="route('reportes.envios')" class="nav-link">
                <i class="nav-icon bi bi-graph-up"></i>
                <p> Envios </p>
              </Link>
            </li>
            <li class="nav-item">
              <Link :href="route('reportes.productos')" class="nav-link">
                <i class="nav-icon bi bi-graph-up"></i>
                <p> Productos </p>
              </Link>
            </li>
            <li class="nav-item">
              <Link :href="route('reportes.pedidos')" class="nav-link">
                <i class="nav-icon bi bi-graph-up"></i>
                <p> Pedidos </p>
              </Link>
            </li>


            <!-- <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-table"></i>
            <p>Reportes</p>
          </a>
        </li> -->

          </ul>
        </nav>
      </div> <!-- END SCROLL WRAPPER -->

    </aside>


    <!-- Content Wrapper -->
    <main class="app-main">
      <!-- Content Header -->
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">{{ title }}</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item">
                  <Link :href="route(index_link)"> {{ index_title }} </Link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ title }}</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <!-- Main content -->
      <div class="app-content">
        <div class="container-fluid">
          <slot />
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="app-footer">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-md-6 text-end">
            <span class="badge bg-primary me-2">
              <!-- <i class="bi bi-eye-fill"></i> -->
              Página: {{ visitasActuales.toLocaleString() }}
            </span>
            <span class="badge bg-success">
              <i class="bi bi-graph-up"></i>
              Total: {{ visitasTotales.toLocaleString() }}
            </span>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import Swal from "sweetalert2";
import { usePage } from '@inertiajs/vue3';
import { watch, ref, onMounted } from 'vue';

const page = usePage()
const authUser = page.props.auth?.user ?? null
// Props del footer
const visitasActuales = ref(page.props.visitasActuales ?? 0)
const visitasTotales = ref(page.props.visitasTotales ?? 0)

// Actualizar visitas cuando cambien las props
watch(
  () => [page.props.visitasActuales, page.props.visitasTotales],
  ([nuevasActuales, nuevasTotales]) => {
    visitasActuales.value = nuevasActuales ?? 0
    visitasTotales.value = nuevasTotales ?? 0
  }
)


watch(
  () => page.props.flash,
  flash => {
    if (!flash) return
    if (flash.success) Swal.fire('¡Éxito!', flash.success, 'success')
    if (flash.error) Swal.fire('Error', flash.error, 'error')
    if (flash.info) Swal.fire('Info', flash.info, 'info')
  },
  { immediate: true }
)

defineProps({
  title: { type: String, default: 'Dashboard' },
  index_title: { type: String, default: '' },
  index_link: { type: String, default: 'usuarios.index' },
  Link
});

// Estados
const modoNoche = ref(false)
const tamanoFuente = ref('normal')
const contrasteAlto = ref(false)

// Cambiar tema
function setTheme(theme) {
  document.body.classList.remove('bg-body-tertiary', 'bg-body', 'bg-body-secondary')
  document.body.classList.remove('tema-ninos', 'tema-jovenes', 'tema-adultos')
  document.body.classList.add(theme)
  localStorage.setItem('theme', theme)
  console.log('Tema aplicado:', theme)
}

// Alternar modo noche
function toggleModoNoche() {
  modoNoche.value = !modoNoche.value
  document.body.classList.toggle('modo-noche', modoNoche.value)
  localStorage.setItem('modoNoche', modoNoche.value)
  console.log('Modo noche:', modoNoche.value)
}

// Cambiar tamaño de fuente
function setTamanoFuente(tamano) {
  // Remover clases anteriores
  document.body.classList.remove('fuente-grande', 'fuente-extra-grande')

  // Agregar nueva clase si no es normal
  if (tamano !== 'normal') {
    document.body.classList.add(tamano)
  }

  tamanoFuente.value = tamano
  localStorage.setItem('tamanoFuente', tamano)
  console.log('Tamaño de fuente:', tamano)
}

// Alternar contraste alto
function toggleContrasteAlto() {
  contrasteAlto.value = !contrasteAlto.value
  document.body.classList.toggle('contraste-alto', contrasteAlto.value)
  localStorage.setItem('contrasteAlto', contrasteAlto.value)
  console.log('Contraste alto:', contrasteAlto.value)
}

// Cargar configuraciones guardadas
onMounted(() => {
  // Remover clases de Bootstrap
  document.body.classList.remove('bg-body-tertiary', 'bg-body', 'bg-body-secondary')

  // Tema
  const savedTheme = localStorage.getItem('theme') || 'tema-adultos'
  setTheme(savedTheme)

  // Modo noche
  const savedModo = localStorage.getItem('modoNoche') === 'true'
  if (savedModo) {
    modoNoche.value = true
    document.body.classList.add('modo-noche')
  }

  // Tamaño de fuente
  const savedTamano = localStorage.getItem('tamanoFuente') || 'normal'
  setTamanoFuente(savedTamano)

  // Contraste alto
  const savedContraste = localStorage.getItem('contrasteAlto') === 'true'
  if (savedContraste) {
    contrasteAlto.value = true
    document.body.classList.add('contraste-alto')
  }

  console.log('Clases del body:', document.body.className)
})
</script>
