<script setup>
import { ref } from 'vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head } from '@inertiajs/vue3';

const currentView = ref('landing');
const isLoading = ref(false);

const loginForm = ref({
    email: 'demo@lumiere.com',
    password: '123'
});

const services = [
    { title: 'Depilación Láser', desc: 'Eliminación definitiva del vello.', icon: 'fa fa-eraser' },
    { title: 'Rejuvenecimiento', desc: 'Tratamientos faciales láser.', icon: 'fa fa-magic' },
    { title: 'Eliminación Tatuajes', desc: 'Tecnología Q-Switch avanzada.', icon: 'fa fa-tint' }
];

const handleLogin = () => {
    isLoading.value = true;

    setTimeout(() => {
        currentView.value = 'dashboard';
        isLoading.value = false;
    }, 1200);
};
</script>

<template>

    <Head title="LUMIERE" />
    <AuthLayout>

        <div id="app">
            <div v-if="currentView === 'landing'" class="fade-in">

                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light fixed-top">
                    <div class="container">
                        <a class="navbar-brand" href="#"><i class="fa fa-star text-cyan"></i> LUMIERE</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto align-items-center">
                                <li class="nav-item"><a class="nav-link" href="#inicio">Inicio</a></li>
                                <li class="nav-item"><a class="nav-link" href="#servicios">Tratamientos</a></li>
                                <li class="nav-item"><a class="nav-link" href="#nosotros">La Clínica</a></li>
                                <li class="nav-item ms-lg-3">
                                    <a :href="route('login')" class="btn btn-primary text-white px-4">Área Clientes</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <!-- Hero Section -->
                <header id="inicio" class="hero-section text-center">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <h1 class="display-3 fw-bold mb-4">Revela tu mejor versión</h1>
                                <p class="lead mb-5 text-white-50">
                                    Tecnología láser de vanguardia para el cuidado de tu piel. Resultados visibles desde
                                    la
                                    primera sesión.
                                </p>
                                <a href="#login" class="btn btn-outline-light btn-lg me-2 px-5">Reservar Cita</a>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Servicios -->
                <section id="servicios" class="section-padding">
                    <div class="container">
                        <div class="text-center m-5">
                            <h6 class="text-cyan text-uppercase fw-bold mb-2">Nuestros Tratamientos</h6>
                            <h2 class="fw-bold display-6">Excelencia en Estética Láser</h2>
                            <div class="mx-auto mt-3"
                                style="width: 60px; height: 3px; background-color: var(--primary-color);">
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-4" v-for="(service, index) in services" :key="index">
                                <div class="card service-card h-100 p-4 text-center">
                                    <div class="card-body">
                                        <div class="service-icon">
                                            <i :class="service.icon"></i>
                                        </div>
                                        <h4 class="card-title mb-3">{{ service.title }}</h4>
                                        <p class="card-text text-muted">{{ service.desc }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Login Section -->
                <section id="login" class="login-section my-5 py-5">
                    <div class="container">
                        <div class="row justify-content-center align-items-center">

                            <div class="col-lg-5 mb-4 mb-lg-0">
                                <h2 class="fw-bold mb-3">Acceso al Portal de Pacientes</h2>
                                <p class="text-muted mb-4">
                                    Gestiona tus citas, revisa tu historial de tratamientos y accede a promociones
                                    exclusivas desde nuestro dashboard.
                                </p>

                                <ul class="list-unstyled text-muted">
                                    <li class="mb-2"><i class="fa fa-check-circle text-cyan me-2"></i> Agenda
                                        disponible 24/7</li>
                                    <li class="mb-2"><i class="fa fa-check-circle text-cyan me-2"></i> Historial
                                        clínico digital</li>
                                    <li class="mb-2"><i class="fa fa-check-circle text-cyan me-2"></i>
                                        Recordatorios
                                        automáticos</li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </section>

                <!-- Footer -->
                <footer class="bg-dark text-white py-5">
                    <div class="container">
                        <div class="row">

                            <div class="col-md-4 mb-4">
                                <h4 class="fw-bold mb-3"><i class="fa fa-star text-cyan"></i> LUMIERE</h4>
                                <p class="text-white-50 small">Dedicados a realizar tu belleza natural con tecnología
                                    avanzada.</p>
                            </div>

                            <div class="col-md-4 mb-4">
                                <h5 class="mb-3">Contacto</h5>
                                <p class="text-white-50 small mb-1"><i class="fa fa-map-marker me-2"></i> Av. Reforma
                                    123
                                </p>
                                <p class="text-white-50 small"><i class="fa fa-envelope me-2"></i> citas@lumiere.com</p>
                            </div>

                            <div class="col-md-4 mb-4">
                                <h5 class="mb-3">Horarios</h5>
                                <p class="text-white-50 small mb-1">Lun - Vie: 9:00 AM - 8:00 PM</p>
                            </div>

                        </div>

                        <hr class="border-secondary mt-4">

                        <div class="text-center text-white-50 small">
                            &copy; 2023 Lumière Estética. Todos los derechos reservados.
                        </div>

                    </div>
                </footer>

            </div>

            <!-- ==========================================
         VISTA 2: DASHBOARD
    ========================================== -->
            <div v-else class="dashboard-container fade-in">
                <!-- (TODO: dashboard completo aquí, lo mantengo igual que en tu HTML) -->
            </div>

        </div>
    </AuthLayout>
</template>


<style>
/* --- Variables y Estilos Generales --- */
:root {
    --primary-color: #00bcd4;
    /* Cyan Vibrante */
    --primary-dark: #008ba3;
    /* Cyan Oscuro */
    --secondary-color: #1a252f;
    /* Azul Oscuro */
    --light-bg: #f8f9fa;
    --text-muted: #6c757d;
}

body {
    font-family: 'Open Sans', sans-serif;
    color: var(--secondary-color);
    overflow-x: hidden;
}

h1,
h2,
h3,
h4,
h5,
h6,
.navbar-brand {
    font-family: 'Montserrat', sans-serif;
    letter-spacing: -0.5px;
}

.text-cyan {
    color: var(--primary-color);
}

.bg-cyan {
    background-color: var(--primary-color);
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    border-radius: 30px;
    padding: 10px 30px;
    transition: all 0.3s;
    font-weight: 600;
}

.btn-primary:hover {
    background-color: var(--primary-dark);
    border-color: var(--primary-dark);
    transform: translateY(-2px);
}

.btn-outline-light {
    border-radius: 30px;
}

/* --- Navbar --- */
.navbar {
    background-color: rgba(255, 255, 255, 0.95);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.navbar-brand {
    font-size: 1.5rem;
    font-weight: bold;
    letter-spacing: 1px;
}

/* --- Hero Section --- */
.hero-section {
    background: linear-gradient(rgba(26, 37, 47, 0.6), rgba(26, 37, 47, 0.6)), url('https://images.unsplash.com/photo-1629909613654-28e377c37b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
    background-size: cover;
    background-position: center;
    height: 85vh;
    display: flex;
    align-items: center;
    color: white;
}

/* --- Services --- */
.service-card {
    border: none;
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.service-icon {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

/* --- Login Section --- */
.login-section {
    background-color: var(--light-bg);
    padding: 80px 0;
}

.login-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.login-header {
    background-color: var(--secondary-color);
    color: white;
    padding: 30px;
    text-align: center;
    transition: background-color 0.3s;
}

/* --- Dashboard --- */
.dashboard-container {
    min-height: 100vh;
    background-color: #f4f6f9;
}

.sidebar {
    background-color: var(--secondary-color);
    min-height: 100vh;
    color: white;
}

.sidebar .nav-link {
    color: rgba(255, 255, 255, 0.7);
    padding: 15px 20px;
    border-radius: 5px;
    margin-bottom: 5px;
    cursor: pointer;
}

.sidebar .nav-link:hover,
.sidebar .nav-link.active {
    color: white;
    background-color: rgba(255, 255, 255, 0.1);
}

.stat-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    transition: 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

/* Utilities */
.cursor-pointer {
    cursor: pointer;
}

.section-padding {
    padding: 80px 0;
}

/* Transitions Vue */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>