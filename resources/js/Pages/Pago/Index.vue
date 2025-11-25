<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, usePage } from "@inertiajs/vue3";

const props = defineProps({
    pagos: Array,
    num: Number,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const errors = computed(() => page.props.errors);

function hasPermission(type) {
    return user.value?.rol?.privilegios?.some(
        (p) => p.funcionalidad === "Pago" && p.state === "a" && p[type] === true
    );
}

const canEdit = computed(() => hasPermission("modificar"));
const canDelete = computed(() => hasPermission("borrar"));

const showEdit = ref(null);
const showDelete = ref(null);

const editPago = ref({ id: null, estado_pago: "", tipo_pago: "" });

function updatePago() {
    router.put(
        route("pago.update", editPago.value.id),
        editPago.value,
        {
            onSuccess: () => {
                showEdit.value = null;
            }
        }
    );
}

function deletePago(id) {
    router.delete(route("pago.destroy", id), {
        onSuccess: () => {
            showDelete.value = null;
        }
    });
}

// ---- DATATABLES ----
let dataTable = null;

onMounted(() => {
    if (window.$) {
        dataTable = window.$("#pagos").DataTable({
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
    <AppLayout title="G. Pagos">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1 class="card-title mb-0">
                <i class="fas fa-money-bill-wave mr-2"></i><b>GESTIONAR PAGOS</b>
            </h1>
        </div>

        <div class="card table-responsive mt-3">
            <div class="card-body">
                <table class="table table-hover" id="pagos">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>CLIENTE</th>
                            <th>MONTO</th>
                            <th>TIPO PAGO</th>
                            <th>ESTADO</th>
                            <th>FECHA</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="pago in pagos" :key="pago.id">
                            <td>{{ pago.id }}</td>
                            <td>{{ pago.venta?.cliente?.nombre || 'N/A' }}</td>
                            <td>{{ pago.monto }}</td>
                            <td>{{ pago.tipo_pago }}</td>
                            <td>
                                <span :class="{
                                    'badge bg-success': pago.estado_pago === 'pagado',
                                    'badge bg-warning': pago.estado_pago === 'pendiente',
                                    'badge bg-danger': pago.estado_pago === 'anulado'
                                }">
                                    {{ pago.estado_pago }}
                                </span>
                            </td>
                            <td>{{ new Date(pago.fecha_pago).toLocaleDateString() }}</td>
                            <td>
                                <a v-if="canEdit" href="#" @click.prevent="
                                    showEdit = pago.id;
                                    editPago = { ...pago };
                                ">
                                    <i class="fa fa-edit"></i>
                                </a>
                                &nbsp;
                                <a v-if="canDelete" href="#" @click.prevent="showDelete = pago">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL EDITAR -->
        <div v-if="showEdit" class="modal-mask">
            <div class="modal-container">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title">Editar Pago</h5>
                    <button type="button" class="btn-close" @click="showEdit = null"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tipo_pago" class="form-label">Tipo de Pago</label>
                        <select class="form-control" v-model="editPago.tipo_pago">
                            <option value="Efectivo">Efectivo</option>
                            <option value="QR">QR</option>
                            <option value="Transferencia">Transferencia</option>
                            <option value="Tarjeta">Tarjeta</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="estado_pago" class="form-label">Estado</label>
                        <select class="form-control" v-model="editPago.estado_pago">
                            <option value="pagado">Pagado</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="anulado">Anulado</option>
                        </select>
                    </div>
                </div>
                <div class="text-end mt-3 modal-footer">
                    <button class="btn btn-secondary" @click="showEdit = null">Cancelar</button>
                    <button class="btn btn-primary" @click="updatePago">Guardar cambios</button>
                </div>
            </div>
        </div>

        <!-- MODAL ELIMINAR -->
        <div v-if="showDelete" class="modal-mask">
            <div class="modal-container text-center">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title">¿Eliminar Pago?</h5>
                    <button type="button" class="btn-close" @click="showDelete = null"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el pago #<strong>{{ showDelete.id }}</strong>?</p>
                </div>
                <div class="modal-footer text-end">
                    <button class="btn btn-secondary" @click="showDelete = null">Cancelar</button>
                    <button class="btn btn-danger" @click="deletePago(showDelete.id)">Eliminar</button>
                </div>
            </div>
        </div>
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
