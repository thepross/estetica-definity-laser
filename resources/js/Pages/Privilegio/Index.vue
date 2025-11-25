<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, usePage } from "@inertiajs/vue3";

const props = defineProps({
    privilegios: Array,
    num: Number,
    roles: Array,
    funcionalidades: Array,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const errors = computed(() => page.props.errors);

function hasPermission(type) {
    return user.value?.rol?.privilegios?.some(
        (p) => p.funcionalidad === "Privilegio" && p.state === "a" && p[type] === true
    );
}

const canAdd = computed(() => hasPermission("agregar"));
const canEdit = computed(() => hasPermission("modificar"));
const canDelete = computed(() => hasPermission("borrar"));

const showAdd = ref(false);
const showEdit = ref(null);
const showDelete = ref(null);

const newPrivilegio = ref({ funcion: "", id_rol: "", agregar: null, borrar: null, modificar: null, leer: null });
const editPrivilegio = ref({ funcion: "", id_rol: "", agregar: null, borrar: null, modificar: null, leer: null });

function storePrivilegio() {
    router.post(
        route("privilegio.store"),
        newPrivilegio.value,
        {
            onSuccess: () => {
                showAdd.value = false;
                newPrivilegio.value = { funcion: "", id_rol: "", agregar: null, borrar: null, modificar: null, leer: null };
            },
            onError: (e) => {
                console.log(e);
            }
        }
    );
}

function updatePrivilegio() {
    router.put(
        route("privilegio.update", editPrivilegio.value.id),
        editPrivilegio.value,
        {
            onSuccess: () => {
                showEdit.value = null;
            }
        }
    );
}

function deletePrivilegio(id) {
    router.delete(route("privilegio.destroy", id), {
        onSuccess: () => {
            showDelete.value = null;
        }
    });
}

// ---- DATATABLES ----
let dataTable = null;

onMounted(() => {
    if (window.$) {
        dataTable = window.$("#privilegios").DataTable({
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
    <AppLayout title="G. Privilegios">

        <!-- HEADER -->
        <div class="card-header d-flex justify-content-between align-items-center">

            <h1 class="card-title mb-0">
                <i class="fas fa-clock mr-2"></i><b>GESTIONAR PRIVILEGIOS</b>
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
                <table class="table table-hover" id="inventarios">
                    <thead class="table-light">
                        <tr>
                            <th>ROL</th>
                            <th>PRIVILEGIOS</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="rol in roles" :key="rol.id">
                            <td>{{ rol.nombre }}</td>
                            <td>
                                <table class="table table-sm table-striped table-hover mb-0">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Funcionalidad</th>
                                            <th>Agregar</th>
                                            <th>Borrar</th>
                                            <th>Modificar</th>
                                            <th>Leer</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="p in rol.privilegiosAgrupados" :key="p.id">
                                            <td>{{ p.funcionalidad }}</td>
                                            <td><i v-if="p.agregar" class="fa fa-check text-success"></i><i v-else
                                                    class="fa fa-times text-danger"></i>
                                            </td>
                                            <td><i v-if="p.borrar" class="fa fa-check text-success"></i><i v-else
                                                    class="fa fa-times text-danger"></i></td>
                                            <td><i v-if="p.modificar" class="fa fa-check text-success"></i><i v-else
                                                    class="fa fa-times text-danger"></i></td>
                                            <td><i v-if="p.leer" class="fa fa-check text-success"></i><i v-else
                                                    class="fa fa-times text-danger"></i></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>

                            <td>
                                <a v-if="canEdit" href="#" @click.prevent="
                                    showEdit = rol;
                                editPrivilegio = { ...privilegio };
                                ">
                                    <i class="fa fa-edit"></i>
                                </a>
                                &nbsp;
                                <a v-if="canDelete" href="#" @click.prevent="showDelete = rol">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL AGREGAR -->
        <div v-if="showAdd" class="modal-mask">
            <div class="modal-container">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title">Agregar Privilegio</h5>
                    <button type="button" class="btn-close" @click="showAdd = false"></button>
                </div>
                <div class="modal-body">

                    <div class="mt-3">
                        <label for="rol_id" class="form-label">Rol</label>
                        <select class="form-select" v-model="newPrivilegio.rol_id">
                            <option value="" disabled>Seleccione un rol</option>
                            <option v-for="rol in roles" :key="rol.id" :value="rol.id">{{ rol.nombre }}</option>
                        </select>
                        <p v-if="errors.rol_id" class="text-danger">{{ errors.id_rol }}</p>
                    </div>
                    <div class="mt-3">
                        <label for="funcion" class="form-label">Funcionalidad</label>
                        <select class="form-select" v-model="newPrivilegio.funcion">
                            <option value="" disabled>Seleccione una funcionalidad</option>
                            <option v-for="func in funcionalidades" :key="func" :value="func">{{ func }}</option>
                        </select>
                        <p v-if="errors.funcion" class="text-danger">{{ errors.funcion }}</p>
                    </div>
                    <div class="mt-3">
                        <label for="privilegio">Privilegio</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" v-model="newPrivilegio.agregar" />
                            <label class="form-check-label">Agregar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" v-model="newPrivilegio.borrar" />
                            <label class="form-check-label">Borrar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" v-model="newPrivilegio.modificar" />
                            <label class="form-check-label">Modificar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" v-model="newPrivilegio.leer" />
                            <label class="form-check-label">Leer</label>
                        </div>
                        <p v-if="errors.tipo" class="text-danger">{{ errors.tipo }}</p>
                    </div>

                    <div class="text-end mt-3 modal-footer">
                        <button class="btn btn-secondary" @click="showAdd = false">
                            Cancelar
                        </button>
                        <button class="btn btn-primary" @click="storePrivilegio">
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>



        <!-- MODAL EDITAR -->
        <div v-if="showEdit" class="modal-mask">
            <div class="modal-container">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title">Asignar Privilegios a: {{ showEdit.nombre }}</h5>
                    <button type="button" class="btn-close" @click="showEdit = false">
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Funcionalidad</th>
                                <th>Agregar</th>
                                <th>Borrar</th>
                                <th>Modificar</th>
                                <th>Leer</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="func in showEdit.privilegiosAgrupados" :key="func.funcionalidad">
                                {{ console.log(showEdit.privilegiosAgrupados[func]) }}

                                <td>{{ func.funcionalidad }}</td>
                                <td><input type="checkbox" v-model="func.agregar"></td>
                                <td><input type="checkbox" v-model="func.borrar"></td>
                                <td><input type="checkbox" v-model="func.modificar"></td>
                                <td><input type="checkbox" v-model="func.leer"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-3 modal-footer">
                    <button class="btn btn-secondary" @click="showEdit = false">Cancelar</button>
                    <button class="btn btn-primary" @click="updatePrivilegio">Guardar Cambios</button>
                </div>
            </div>
        </div>

        <!-- MODAL ELIMINAR -->
        <div v-if="showDelete" class="modal-mask">
            <div class="modal-container text-center">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title">¿Eliminar Privilegio?</h5>
                    <button type="button" class="btn-close" @click="showDelete = null"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el privilegio <strong>{{ showDelete.nombre }}</strong>?
                    </p>
                </div>
                <div class="modal-footer text-end">
                    <button class="btn btn-secondary" @click="showDelete = null">
                        Cancelar
                    </button>
                    <button class="btn btn-danger" @click="deletePrivilegio(showDelete)">
                        Eliminar
                    </button>
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
