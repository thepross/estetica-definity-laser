<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import NoPermiso from "@/Components/NoPermiso.vue";


const props = defineProps({
    planes: Array,
    num: Number,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

function hasPermission(type) {
    return user.value?.rol?.privilegios?.some(
        (p) => p.funcionalidad === "PlanPago" && p.state === "a" && p[type] === true
    );
}
const can = (funcionalidad) => {
    return page.props.auth.privilegios?.[funcionalidad]?.leer;
};

const canAdd = computed(() => hasPermission("agregar"));
const canEdit = computed(() => hasPermission("modificar"));
const canDelete = computed(() => hasPermission("borrar"));

const showAdd = ref(false);
const showEdit = ref(null);
const showDelete = ref(null);


const newPlanPago = ref({ cantidad_cuotas: "", fecha_inicio: "", monto_cuota: "", total_deuda: "", saldo_restante: "", estado: "" });
const editPlanPago = ref({ id: null, cantidad_cuotas: "", fecha_inicio: "", monto_cuota: "", total_deuda: "", saldo_restante: "", estado: "" });

function storePlanPago() {
    router.post(
        route("planes.store"),
        newPlanPago.value,
        {
            onSuccess: () => {
                showAdd.value = false;
                newPlanPago.value = { cantidad_cuotas: "", fecha_inicio: "", monto_cuota: "", total_deuda: "", saldo_restante: "", estado: "" };
            },
            onError: (e) => {
                console.log(e);
            }
        }
    );
}

function updatePlanPago() {
    router.put(
        route("planes.update", editPlanPago.value.id),
        editPlanPago.value,
        {
            onSuccess: () => {
                showEdit.value = null;
            }
        }
    );
}

function deletePlanPago(id) {
    router.delete(route("planes.destroy", id), {
        onSuccess: () => {
            showDelete.value = null;
        }
    });
}

// ---- DATATABLES ----
let dataTable = null;

onMounted(() => {
    if (window.$) {
        dataTable = window.$("#planes").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
            }
        });
    }
});

onUnmounted(() => {
    if (dataTable) {
        dataTable.destroy();
        dataTable = null;
    }
});
</script>

<template>

    <Head title="G. Planes de Pago" />
    <AppLayout>

        <section class="content" v-if="can('PlanPago')">
            <!-- HEADER -->
            <div class="card-header d-flex justify-content-between align-items-center">

                <h1 class="card-title mb-0">
                    <i class="fas fa-clock mr-2"></i><b>GESTIONAR PLANES DE PAGO</b>
                </h1>

                <div class="d-flex align-items-center ml-auto">
                    <button v-if="canAdd" class="btn btn-success" @click="showAdd = true">
                        <i class="fa fa-plus"></i>&nbsp; Agregar
                    </button>
                </div>
            </div>
            <!-- TABLA -->
            <div class="card table-responsive mt-3">
                <div class="card-body">
                    <table class="table table-hover" id="planes">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>FECHA INICIO</th>
                                <th>MONTO DE CUOTA</th>
                                <th>TOTAL DEUDA</th>
                                <th>ID VENTA</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="plan in planes" :key="plan.id">
                                {{ console.log(plan) }}
                                <td>{{ plan.id }}</td>
                                <td>{{ plan.fecha_inicio }}</td>
                                <td>{{ plan.monto_cuota }}</td>
                                <td>{{ plan.total_deuda }}</td>
                                <td>{{ plan.id_venta }}</td>
                                <td>{{ plan.estado }}</td>
                                <td>
                                    <a v-if="canEdit" href="#" @click.prevent="
                                        showEdit = plan.id;
                                    editPago = { ...plan };
                                    ">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    &nbsp;
                                    <a v-if="canDelete" href="#" @click.prevent="showDelete = plan.id">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MODAL ELIMINAR -->
            <div v-if="showDelete" class="modal-mask">
                <div class="modal-container text-center">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title">¿Eliminar Plan de pago?</h5>
                        <button type="button" class="btn-close" @click="showDelete = null"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar el plan de pago <strong>{{ showDelete.nombre
                                }}</strong>?
                        </p>
                    </div>
                    <div class="modal-footer text-end">
                        <button class="btn btn-secondary" @click="showDelete = null">
                            Cancelar
                        </button>
                        <button class="btn btn-danger" @click="deletePlanPago(showDelete)">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </section>
        <NoPermiso v-else mensaje="No tienes permiso para ver los planes de pago." />

    </AppLayout>
</template>

<style scoped>
.modal-mask {
    position: fixed;
    z-index: 9999;
    background: rgba(0, 0, 0, 0.35);
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    align-items: center;
    justify-content: center;
    display: flex;
    width: 100vw;
}

.modal-container {
    background: white;
    width: min(500px, 90%);
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
}
</style>
