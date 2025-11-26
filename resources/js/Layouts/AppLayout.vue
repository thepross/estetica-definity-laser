<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, onMounted, watch, nextTick, Teleport } from 'vue';
import ControlSidebar from '../Components/ControlSidebar.vue';
import { router } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);
const num = computed(() => page.props.num || 0);


const flashSuccess = computed(() => page.props.flash?.success);
const errors = computed(() => page.props.errors);
const hasErrors = computed(() => Object.keys(page.props.errors).length > 0);

const can = (funcionalidad) => {
  return page.props.auth.privilegios?.includes(funcionalidad);
}


const isAdmin = computed(() => role.value === 'admin')
const isMedico = computed(() => role.value === 'medico')
const isSecretaria = computed(() => role.value === 'secretaria')


const userEstilo = computed(() => user.value ? user.value.estilo : null);



const cambiarFondo = (estilo) => {
  if (!estilo) return;

  document.body.className = 'fondo' + estilo + ' hold-transition sidebar-mini layout-fixed';
  let cssRules = "";

  if (estilo == 1) {
    cssRules = `
            .sidebar{background-color: #455279 !important;} 
            .main-sidebar{background: #455279 !important;}
            .nav-item p{color: #FFFF !important;}
            .form-inline input{background: #455279 !important;}
            .content-wrapper {background: #3C486C !important;}
            .card {background: #5C6C9C !important;color: #FFFF !important;}
            .table-hover {background: #5C6C9C !important;color: #FFFF !important;}
            .card-body {background: #5C6C9C !important;color: #000000 !important;}
            .modal-content {background: #3C486C !important; color: #FFFF !important;}
            .main-header {background: #41507D !important;color: #FFFF !important;}
            .main-header a{background: #41507D !important;color: #FFFF !important;}
        `;
  } else if (estilo == 2) {
    cssRules = `
            .sidebar{background-color: #FFFFFF !important;} 
            .main-sidebar{background: #FFFFFF !important;}
            .nav-item p{color: #404040 !important;}
            .form-inline input{background: #FFFFFF !important;}
            .main-header a{background: #FFFFFF !important;color: #666666 !important;}
            .main-header {background: #FFFFFF !important;color: #666666 !important;}
        `;
  } else if (estilo == 3) {
    cssRules = `
            .sidebar{background-color: #E7F37D !important;} 
            .main-sidebar{background: #E7F37D !important;}
            .nav-item p{color: #F3513B !important;}
            .form-inline input{background: #E7F37D !important;}
            .content-wrapper {background: #EC7C3C !important;}
            .card {background: #3CD4EC !important;color: #FFFF !important;}
            .table-hover {background: #3CD4EC !important;color: #FFFF !important;}
            .card-body {background: #3CD4EC !important;color: #000000 !important;}
            .modal-content {background: #F3513B !important; color: #FFFF !important;}
            .main-header {background: #F3513B !important;color: #E7F37D !important;}
            .main-header a{background: #F3513B !important;color: #E7F37D !important;}
        `;
  } else if (estilo == 4) {
    cssRules = `
            .content-wrapper {background-color: #77818C !important;}
            .card {background: #51575D !important;color: #FFFF !important;}
            .card-body {color: #000000 !important;}
            .table-hover {color: #FFFF !important;}
            .modal-content {background: #51575D !important; color: #FFFF !important;}
            .main-header {background: #343a40 !important;color: #FFFF !important;}
            .main-header a{background: #343a40 !important;color: #FFFF !important;}
        `;
  }
  else {
    cssRules = `
            .content-wrapper {background-color: #FFFFFF !important;}
            .card {background: #FFFFFF !important;color: #FFFF !important;}
            .card-body {background-color: #FFFFFF !important;color: #000000 !important;}
            .table-hover { color: #FFFF !important;}
            .table {color: #000000 !important;}
            .modal-content {background: #FFFFFF !important; color: #FFFF !important;}
            .main-header {background: #FFFFFF !important;color: #FFFF !important;}
            .main-header a{background: #FFFFFF !important;color: #666666 !important;}
            .sidebar{background-color: #51575D !important;}
            .form-inline input{background: #fff !important;}
            .content-wrapper {background: #fff !important;}
            .nav-item p{color: #DDDDDD !important;}
            .main-sidebar{background: #51575D !important;}
        `;
  }

  if (cssRules) {
    const hoja = document.createElement('style');
    hoja.innerHTML = cssRules;
    document.body.appendChild(hoja);
  }
};

watch(userEstilo, (nuevoEstilo) => {
  cambiarFondo(nuevoEstilo);
}, { immediate: true });

onMounted(() => {
  nextTick(() => {
    if (window.$) {
      const treeview = window.$('[data-widget="treeview"]');
      treeview.Treeview('init');
      window.$('[data-widget="pushmenu"]').PushMenu('init');
      // Inicializar ControlSidebar
      // window.$('[data-widget="control-sidebar"]').ControlSidebar('init');
    }
  });
});

const form = useForm({
  buscar: ''
});

const realizarBusqueda = () => {
  console.log('Realizando búsqueda con:', form.buscar);
  form.get(route('reportes.buscar'), {
    preserveState: true,
    replace: true
  });
};


const logout = () => {
  router.post(route('logout'));
};
</script>

<template>
  <div class="wrapper">

    <div class="preloader flex-column justify-content-center align-items-center" v-if="false">
      <img class="animation__shake" :src="`/dist/img/AdminLTELogo.png`" alt="A" height="60" width="60">
    </div>

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <Link :href="route('dashboard')" class="nav-link">Principal</Link>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Contacto</a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fas fa-search"></i>
          </a>
          <div class="navbar-search-block">
            <form class="form-inline" @submit.prevent="realizarBusqueda">
              <input type="hidden" name="_token" :value="page.props.csrf_token">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" name="buscar" placeholder="Search"
                  v-model="form.buscar" aria-label="Search" :disabled="form.processing">
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                  <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
            {{ user ? user.email : 'Invitado' }}
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="#" class="dropdown-item">Perfil</a>
            <div class="dropdown-divider"></div>
            <button @click="logout" class="dropdown-item">
              Cerrar sesión
            </button>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
            Estilos
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <Link :href="route('cargarEstilo', { estilo: '1' })" class="dropdown-item">Azul</Link>
            <Link :href="route('cargarEstilo', { estilo: '2' })" class="dropdown-item">Claro</Link>
            <Link :href="route('cargarEstilo', { estilo: '3' })" class="dropdown-item">Infantil</Link>
            <Link :href="route('cargarEstilo', { estilo: '4' })" class="dropdown-item">Dark</Link>
            <Link :href="route('cargarEstilo', { estilo: '0' })" class="dropdown-item">Por defecto</Link>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
          </a>
        </li>
      </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <Link :href="route('dashboard')" class="brand-link" style="text-decoration: none;">
      <img :src="'/dist/img/AdminLTELogo.png'" alt="A" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class=""><b>Clinica Estetica</b></span>
      </Link>

      <div class="sidebar">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-item" v-if="can('Administracion')">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-copy"></i>
                <p>
                  Administracion
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item" v-if="can('Usuario')">
                  <Link :href="route('usuario.index')" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Usuario</p>
                  </Link>
                </li>
                <li class="nav-item" v-if="can('Rol')">
                  <Link :href="route('rol.index')" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rol</p>
                  </Link>
                </li>
                <li class="nav-item" v-if="can('Privilegio')">
                  <Link :href="route('privilegio.index')" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Privilegio</p>
                  </Link>
                </li>
                <li class="nav-item" v-if="can('Administracion')">
                  <Link :href="route('empresa.index')" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Configuracion</p>
                  </Link>
                </li>
              </ul>
            </li>

            <li class="nav-item" v-if="can('Cliente') || can('Cita') || can('Venta') || can('Pago') || can('Servicio')">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>
                  Gestion Clinica
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item" v-if="can('Cliente')">
                  <Link :href="route('cliente.index')" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Clientes</p>
                  </Link>
                </li>
                <li class="nav-item">
                  <Link :href="route('cita.index')" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Citas</p>
                  </Link>
                </li>
                <li class="nav-item" v-if="can('Servicio')">
                  <Link :href="route('servicio.index')" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Servicios</p>
                  </Link>
                </li>
                <li class="nav-item" v-if="can('Pago')">
                  <Link :href="route('pago.index')" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pagos</p>
                  </Link>
                </li>
                <li class="nav-item" v-if="can('Venta')">
                  <Link :href="route('venta.index')" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ventas</p>
                  </Link>
                </li>
              </ul>
            </li>

            <li class="nav-item" v-if="can('Inventario') || can('Producto') || can('Categoria') || can('Promocion')">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>
                  Gestion de Recursos
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item" v-if="can('Inventario')">
                  <Link :href="route('inventario.index')" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inventario</p>
                  </Link>
                </li>
                <li class="nav-item" v-if="can('Producto')">
                  <Link :href="route('producto.index')" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Productos</p>
                  </Link>
                </li>
                <li class="nav-item" v-if="can('Categoria')">
                  <Link :href="route('categoria.index')" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Categorias</p>
                  </Link>
                </li>
                <li class="nav-item" v-if="can('Promocion')">
                  <Link :href="route('promocion.index')" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Promociones</p>
                  </Link>
                </li>
              </ul>
            </li>

            <li class="nav-item" v-if="can('Reportes')">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-tree"></i>
                <p>
                  Reportes y estadisticas
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <Link :href="route('estadisticas.index')" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Estadisticas de acceso</p>
                  </Link>
                </li>
                <li class="nav-item">
                  <Link :href="route('dashboard')" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reporte de ventas</p>
                  </Link>
                </li>
              </ul>
            </li>

          </ul>
        </nav>
      </div>
    </aside>

    <div class="content-wrapper">
      <br>
      <section class="content">
        <div class="container-fluid">

          <div v-if="flashSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="icon fas fa-check"></i> {{ flashSuccess }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div v-if="hasErrors" class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5><i class="icon fas fa-ban"></i> ¡Atención!</h5>
            <ul class="mb-0 pl-3">
              <li v-for="(error, key) in errors" :key="key">
                {{ error }}
              </li>
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div v-if="form.processing">
            <div class="loading-content">
              <i class="fas fa-circle-notch fa-spin fa-3x text-primary"></i>
              <h4 class="text-primary mt-3 font-weight-light">Buscando...</h4>
            </div>
          </div>

          <div class="app">

            <slot />

          </div>
        </div>
      </section>
    </div>

    <footer class="main-footer">
      <strong>Copyright &copy; 2025 <a href="https://adminlte.io">Tecnoweb</a>.</strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
        Visitas: <strong> {{ num }} </strong>
      </div>
    </footer>

    <ControlSidebar />
  </div>
</template>


<style scoped>
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: auto;
  height: auto;
  background-color: rgba(0, 0, 0, 0.7);
  z-index: 9999;
  display: flex;
  justify-content: center;
  align-items: center;
  backdrop-filter: blur(4px);
}

.loading-content {
  text-align: center;
}
</style>
