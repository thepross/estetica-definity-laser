<script setup>
import { onMounted, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const props = defineProps({
    cantidadVentas: Number,
    cantidadVendida: String,
    cantidadClientes: Number,
    cantidadVisitas: Number,
    mes: Array,
    cantidad: Array,
    productosTop: Array,
    dias: Array,
    cantidadDias: Array
});

const page = usePage();
const user = page.props.auth.user;

const graficoTortaRef = ref(null);
const graficoTorta2Ref = ref(null);

onMounted(() => {
    if (user.id_rol === 1) {
        renderGraficoMes();
        renderGraficoDias();
    }
});

const renderGraficoMes = () => {
    if (graficoTortaRef.value && props.mes && props.cantidad && props.mes.length > 0) {
        new window.Chart(graficoTortaRef.value, {
            type: 'pie',
            data: {
                labels: props.mes,
                datasets: [{
                    data: props.cantidad,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                    ],
                    borderWidth: 1,
                }]
            },
        });
    }
};

const renderGraficoDias = () => {
    if (graficoTorta2Ref.value && props.dias && props.cantidadDias && props.dias.length > 0) {
        new window.Chart(graficoTorta2Ref.value, {
            type: 'line',
            data: {
                labels: props.dias,
                datasets: [{
                    label: 'Ventas por Día',
                    data: props.cantidadDias,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }
};
</script>


<template>

    <Head title="Reportes Dashboard" />

    <AppLayout>

        <section class="content" v-if="user.id_rol === 1">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ props.cantidadVentas ?? 0 }}</h3>
                                <p>Nro Ventas</p>
                            </div>
                            <div class="icon"><i class="ion ion-bag"></i></div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ props.cantidadVendida ?? 0 }}<sup style="font-size: 20px">Bs</sup>
                                </h3>
                                <p>Ingresos por ventas</p>
                            </div>
                            <div class="icon"><i class="ion ion-stats-bars"></i></div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ props.cantidadClientes ?? 0 }}</h3>
                                <p>Clientes registrados</p>
                            </div>
                            <div class="icon"><i class="ion ion-person-add"></i></div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ props.cantidadVisitas ?? 0 }}</h3>
                                <p>Visitas a la página</p>
                            </div>
                            <div class="icon"><i class="ion ion-pie-graph"></i></div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <section class="col-lg-6 connectedSortable mb-4">
                        <div class="card bg-gradient h-100">
                            <div class="card-body d-flex flex-column align-items-center">
                                <h3 class="mb-3">VENTAS POR MES</h3>

                                <div v-if="props.mes && props.mes.length > 0">
                                    <canvas ref="graficoTortaRef" id="graficoTorta"></canvas>
                                </div>
                                <p v-else class="text-muted">No hay datos de ventas por mes.</p>
                            </div>
                        </div>
                    </section>
                    <section class="col-lg-6 connectedSortable">
                        <div class="card bg-gradient">
                            <div class="card-body d-flex flex-column align-items-center">
                                <h3 class="mb-3">VENTAS POR DÍA</h3>

                                <div v-if="props.dias && props.dias.length > 0">
                                    <canvas ref="graficoTorta2Ref" id="graficoTorta2"></canvas>
                                </div>
                                <p v-else class="text-muted">No hay datos de ventas por día.</p>
                            </div>
                        </div>
                    </section>
                    <section class="col-lg-12 connectedSortable mb-4">
                        <div class="card bg-gradient h-100">
                            <div class="card-body d-flex flex-column">
                                <h3 class="text-center mb-3">PRODUCTOS TOP</h3>

                                <div v-if="props.productosTop && props.productosTop.length > 0"
                                    class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Producto</th>
                                                <th scope="col">Unidades</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-group-divider">
                                            <tr v-for="producto in props.productosTop" :key="producto.id">
                                                <td>{{ producto.producto }}</td>
                                                <td>{{ producto.total_vendido }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <p v-else class="text-center text-muted pb-3 mt-auto mb-auto">
                                    No hay productos registrados.
                                </p>
                            </div>
                        </div>
                    </section>

                </div>

            </div>
        </section>

        <div v-else class="p-6 bg-white rounded shadow">
            <p class="text-center text-red-500">No tienes permisos para ver este reporte.</p>
        </div>

    </AppLayout>
</template>