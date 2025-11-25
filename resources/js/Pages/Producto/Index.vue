<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, usePage } from "@inertiajs/vue3";

const props = defineProps({
    productos: Array,
    num: Number,
    categorias: Array,
    promociones: Array,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const errors = computed(() => page.props.errors);

function hasPermission(type) {
    return user.value?.rol?.privilegios?.some(
        (p) => p.funcionalidad === "Producto" && p.state === "a" && p[type] === true
    );
}

const canAdd = computed(() => hasPermission("agregar"));
const canEdit = computed(() => hasPermission("modificar"));
const canDelete = computed(() => hasPermission("borrar"));

const showAdd = ref(false);
const showEdit = ref(null);
const showDelete = ref(null);

const newProducto = ref({
    nombre: "",
    descripcion: "",
    fecha_ingreso: "",
    precio: 0,
    stock: 0,
    id_categoria: "",
    id_promocion: ""
});
const editProducto = ref({
    id: null,
    nombre: "",
    descripcion: "",
    fecha_ingreso: "",
    precio: 0,
    stock: 0,
    id_categoria: "",
    id_promocion: ""
});

function storeProducto() {
    router.post(
        route("producto.store"),
        newProducto.value,
        {
            onSuccess: () => {
                showAdd.value = false;
                newProducto.value = { nombre: "", descripcion: "", fecha_ingreso: "", precio: 0, stock: 0, id_categoria: "", id_promocion: "" };
            },
            onError: (e) => {
                console.log(e);
            }
        }
    );
}

function updateProducto() {
    router.put(
        route("producto.update", editProducto.value.id),
        editProducto.value,
        {
            onSuccess: () => {
                showEdit.value = null;
            }
        }
    );
}

function deleteProducto(id) {
    router.delete(route("producto.destroy", id), {
        onSuccess: () => {
            showDelete.value = null;
        }
    });
}

// ---- DATATABLES ----
let dataTable = null;

onMounted(() => {
    if (window.$) {
        dataTable = window.$("#productos").DataTable({
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
    <AppLayout title="G. Productos">

        <!-- HEADER -->
        <div class="card-header d-flex justify-content-between align-items-center">

            <h1 class="card-title mb-0">
                <i class="fas fa-clock mr-2"></i><b>GESTIONAR PRODUCTOS</b>
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
                <table class="table table-hover" id="productos">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>DESCRIPCION</th>
                            <th>PRECIO</th>
                            <th>CATEGORIA</th>
                            <th>FECHA INGRESO</th>
                            <th>PROMOCION</th>
                            <th>STOCK</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="producto in productos" :key="producto.id">
                            <td>{{ producto.id }}</td>
                            <td>{{ producto.nombre }}</td>
                            <td>{{ producto.descripcion }}</td>
                            <td>
                                <template v-if="producto.promocion">
                                    <template v-if="producto.precio !== producto.precioConDescuento">
                                        <span style="text-decoration: line-through; color: red;">
                                            ${{ Number(producto.precio).toFixed(2) }}
                                        </span>
                                        <span style="color: green;">
                                            ${{ Number(producto.precioConDescuento).toFixed(2) }}
                                        </span>
                                    </template>

                                    <template v-else>
                                        ${{ Number(producto.precio).toFixed(2) }}
                                    </template>
                                </template>

                                <template v-else>
                                    ${{ Number(producto.precio).toFixed(2) }}
                                </template>
                            </td>
                            <td>{{ producto.categoria?.nombre || "N/A" }}</td>
                            <td>{{ producto.fecha_ingreso }}</td>
                            <td>{{ producto.promocion?.descripcion || "N/A" }}</td>
                            <td>{{ producto.stock }}</td>
                            <td>
                                <a v-if="canEdit" href="#" @click.prevent="
                                    showEdit = producto.id;
                                editProducto = { ...producto };
                                ">
                                    <i class="fa fa-edit"></i>
                                </a>
                                &nbsp;
                                <a v-if="canDelete" href="#" @click.prevent="showDelete = producto">
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
                    <h5 class="modal-title">Agregar Producto</h5>
                    <button type="button" class="btn-close" @click="showAdd = false"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input class="form-control" v-model="newProducto.nombre" placeholder="Nombre" />
                        <p v-if="errors.nombre" class="text-danger">{{ errors.nombre }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripcion</label>
                        <input class="form-control" v-model="newProducto.descripcion" placeholder="Descripcion" />
                        <p v-if="errors.descripcion" class="text-danger">{{ errors.descripcion }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                        <input type="date" class="form-control" v-model="newProducto.fecha_ingreso"
                            placeholder="Fecha de Ingreso" />
                        <p v-if="errors.fecha_ingreso" class="text-danger">{{ errors.fecha_ingreso }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" class="form-control" v-model="newProducto.precio" placeholder="Precio" />
                        <p v-if="errors.precio" class="text-danger">{{ errors.precio }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" v-model="newProducto.stock" placeholder="Stock" />
                        <p v-if="errors.stock" class="text-danger">{{ errors.stock }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" v-model="newProducto.id_categoria">
                            <option value="" disabled>Seleccione una categoría</option>
                            <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">
                                {{ categoria.nombre }}
                            </option>
                        </select>
                        <p v-if="errors.id_categoria" class="text-danger">{{ errors.id_categoria }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="promocion" class="form-label">Promoción</label>
                        <select class="form-select" v-model="newProducto.id_promocion">
                            <option value="" disabled>Seleccione una promoción</option>
                            <option v-for="promocion in promociones" :key="promocion.id" :value="promocion.id">
                                {{ promocion.nombre }}
                            </option>
                        </select>
                        <p v-if="errors.id_promocion" class="text-danger">{{ errors.id_promocion }}</p>
                    </div>
                </div>

                <div class="text-end mt-3 modal-footer">
                    <button class="btn btn-secondary" @click="showAdd = false">
                        Cancelar
                    </button>
                    <button class="btn btn-primary" @click="storeProducto">
                        Guardar
                    </button>
                </div>
            </div>
        </div>


        <!-- MODAL EDITAR -->
        <div v-if="showEdit" class="modal-mask">
            <div class="modal-container">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title">Editar Rol</h5>
                    <button type="button" class="btn-close" @click="showEdit = null"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input class="form-control" v-model="editProducto.nombre" placeholder="Nombre" />
                        <p v-if="errors.nombre" class="text-danger">{{ errors.nombre }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripcion</label>
                        <input class="form-control" v-model="editProducto.descripcion" placeholder="Descripcion" />
                        <p v-if="errors.descripcion" class="text-danger">{{ errors.descripcion }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                        <input type="date" class="form-control" v-model="editProducto.fecha_ingreso"
                            placeholder="Fecha de Ingreso" />
                        <p v-if="errors.fecha_ingreso" class="text-danger">{{ errors.fecha_ingreso }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" class="form-control" v-model="editProducto.precio" placeholder="Precio" />
                        <p v-if="errors.precio" class="text-danger">{{ errors.precio }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" v-model="editProducto.stock" placeholder="Stock" />
                        <p v-if="errors.stock" class="text-danger">{{ errors.stock }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" v-model="editProducto.id_categoria">
                            <option value="" disabled>Seleccione una categoría</option>
                            <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">
                                {{ categoria.nombre }}
                            </option>
                        </select>
                        <p v-if="errors.id_categoria" class="text-danger">{{ errors.id_categoria }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="promocion" class="form-label">Promoción</label>
                        <select class="form-select" v-model="editProducto.id_promocion">
                            <option value="" disabled>Seleccione una promoción</option>
                            <option v-for="promocion in promociones" :key="promocion.id" :value="promocion.id">
                                {{ promocion.nombre }}
                            </option>
                        </select>
                        <p v-if="errors.id_promocion" class="text-danger">{{ errors.id_promocion }}</p>
                    </div>
                </div>

                <div class="text-end mt-3 modal-footer">
                    <button class="btn btn-secondary" @click="showEdit = null">
                        Cancelar
                    </button>
                    <button class="btn btn-primary" @click="updateProducto">
                        Guardar cambios
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL ELIMINAR -->
        <div v-if="showDelete" class="modal-mask">
            <div class="modal-container text-center">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title">¿Eliminar Producto?</h5>
                    <button type="button" class="btn-close" @click="showDelete = null"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el producto <strong>{{ showDelete.nombre }}</strong>?</p>
                </div>
                <div class="modal-footer text-end">
                    <button class="btn btn-secondary" @click="showDelete = null">
                        Cancelar
                    </button>
                    <button class="btn btn-danger" @click="deleteProducto(showDelete)">
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
