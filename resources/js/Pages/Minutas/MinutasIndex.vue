<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
import Layout from "@/Layouts/Layout.vue";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputText from "primevue/inputtext";
import { format } from 'date-fns';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    area_id: Number,
    departamento_id: Number,
});

const title = "minutero";
const items = ref([]);
const areas = ref([]);
const departamentos = ref([]);
const procesos = ref([]);
const usuarios = ref([]);
const customFilter = ref(false);
const totalRecords = ref(0);
const rows = ref(10);
const first = ref(0);
const globalFilter = ref("");
const sortField = ref("id");
const sortOrder = ref(-1);
const filters = ref({});

const area_id = ref(props.area_id);
const departamento_id = ref(props.departamento_id);
const pilar = ref(area_id.value || "");
const flujoValor = ref(departamento_id.value || "");
const lider = ref("");
const tipo = ref("");
const proceso = ref("");
const desde = ref("");
const hasta = ref("");

const formFilter = useForm({
    area_id: "",
    departamento_id: "",
    lider_id: "",
    tipo: "",
    proceso_id: "",
    fecha_from: "",
    fecha_to: "",
});

async function getItems(page = 1, rowsPerPage = rows.value, filter = "", sortField = "id", sortOrder = -1) {
    try {
        const response = await axios.get("/api/minutas", {
            params: {
                page,
                rows: rowsPerPage,
                filter,
                sortField,
                sortOrder: sortOrder === 1 ? "asc" : "desc",
            },
        });
        items.value = response.data.data;
        totalRecords.value = response.data.total;
        first.value = (response.data.current_page - 1) * rows.value;

        // Fetch tareas and tareasTerminadas for each item
        for (let item of items.value) {
            item.tareas = await getTareas(item.id);
            item.tareasTerminadas = await getTareasTerminadas(item.id);
        }
    } catch (error) {
        console.error(error);
    }
}

const getAreas = async () => {
    try {
        const response = await axios.get("/api/areas");
        areas.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

const getDepartamentos = async () => {
    try {
        const response = await axios.get("/api/departamentos");
        departamentos.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

async function getProcesos() {
    await axios
        .get("/api/procesos")
        .then((response) => (procesos.value = response.data.data))
        .catch((error) => {
            console.log(error);
        });
}

async function getUsuarios() {
    await axios
        .get("/api/usuarios/all/todo")
        .then((response) => (usuarios.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}

const deleteItems = async (id) => {
    try {
        const result = await confirmDialog("Estas seguro?", "Ya no podras revertir esto!", "warning");
        if (result.isConfirmed) {
            await axios.delete(route("minutas.destroy", id));
            items.value = items.value.filter((item) => item.id !== id);
            showToast("El registro ha sido eliminado", "success");
        }
    } catch (error) {
        console.log(error);
    }
};

onMounted(() => {
    filters.value = {
        area_id: { value: pilar.value, matchMode: "contains" },
        departamento_id: { value: flujoValor.value, matchMode: "contains" },
    };
    getItems('', '', filters.value, '', '');
    getAreas();
    getDepartamentos();
    getProcesos();
    getUsuarios();
});

watch(
    [globalFilter, pilar, flujoValor, lider, tipo, proceso, desde, hasta],
    ([newglobalFilter, newpilar, newflujoValor, newlider, newtipo, newproceso, newdesde, newhasta]) => {
        filters.value = {
            global: { value: newglobalFilter, matchMode: "contains" },
            area_id: { value: newpilar, matchMode: "contains" },
            departamento_id: { value: newflujoValor, matchMode: "contains" },
            lider_id: { value: newlider, matchMode: "contains" },
            tipo: { value: newtipo, matchMode: "contains" },
            proceso: { value: newproceso, matchMode: "contains" },
            desde: { value: newdesde, matchMode: "contains" },
            hasta: { value: newhasta, matchMode: "contains" },
        };
        getItems(1, rows.value, filters.value, sortField.value, sortOrder.value);
    });

const onPage = (event) => {
    const page = event.page + 1;
    rows.value = event.rows;
    getItems(page, rows.value, filters.value, sortField.value, sortOrder.value);
};

const onSort = (event) => {
    sortField.value = event.sortField;
    sortOrder.value = event.sortOrder;
    getItems(1, rows.value, filters.value, sortField.value, sortOrder.value);
};

const openFilter = () => {
    customFilter.value = !customFilter.value

};

const filterTable = async () => {
    await axios
        .get(`/api/minutas`, {
            params: {
                formFilter: formFilter.data(),
            },
        })
        .then((response) => (items.value = response.data.data))
        .catch((error) => {
            console.log(error);
        });
};

const clearFilter = () => {
    pilar.value = "";
    flujoValor.value = "";
    lider.value = "";
    tipo.value = "";
    proceso.value = "";
    desde.value = "";
    hasta.value = "";

    getItems();
};

const formatearFecha = (dateString) => {
    return format(new Date(dateString), 'dd/MM/yyyy');
};

async function getTareas(minuta_id) {
    try {
        const response = await axios.get(`/api/tareas/counter/${minuta_id}`);
        return response.data.count;
    } catch (error) {
        console.error(error);
        return 0;
    }
}

async function getTareasTerminadas(minuta_id) {
    try {
        const response = await axios.get(`/api/tareas/terminadas/counter/${minuta_id}`);
        return response.data.count;
    } catch (error) {
        console.error(error);
        return 0;
    }
}
</script>

<style scoped>
.breadcrumbs {
    display: flex;
    gap: 8px;
    align-items: center;
}

.breadcrumbsTitulo h3 {
    font-size: 24px;
    font-weight: bold;
}

.breadcrumbs h3 {
    font-size: 20px;
}

.breadcrumbs a {
    color: #000;
    text-decoration: none;
}

.breadcrumbs .active h3 {
    font-weight: bold;
}
</style>

<template>
    <Layout :titulo="title">

        <Head title="Minutas" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Minutas</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('minutas.index')" class="active">
                <h3>Minutas</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    <div class="px-4 py-2 flex justify-end bg-white border-b border-gray-200">
                        <PrimaryButton class="m-4 pi pi-plus" :href="route('minutas.create')"></PrimaryButton>
                    </div>
                    <div class="px-4 py-2 bg-white border-b border-gray-200">
                        <div class="container mx-auto overflow-x-auto">
                            <div class="flex gap-4">
                                <InputText v-model="globalFilter" placeholder="Buscar..." class="mb-3" />
                                <PrimaryButton class=" mb-4 float-right pi pi-filter" @click="openFilter">
                                </PrimaryButton>
                                <PrimaryButton v-if="customFilter" class=" mb-4 float-right pi pi-times"
                                    @click="clearFilter"></PrimaryButton>
                            </div>

                            <!-- formulario de filtrado de tareas -->
                            <div v-if="customFilter" class="">
                                <form @submit.prevent="filterTable()">
                                    <div class="m-4 border rounded-lg border-gray-200 flex gap-2 grid grid-cols-4">
                                        <div class="m-4">
                                            <InputLabel for="area_id" value="Pilar: " />
                                            <select ref="area_select"
                                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                                v-model="pilar">
                                                <option value="" selected>
                                                    Seleccione una opcion
                                                </option>
                                                <option v-for="area in areas" :key="area.id" :value="area.id">
                                                    {{ area.nombre }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="m-4">
                                            <InputLabel for="departamento_id" value="Flujo de valor: " />
                                            <select ref="departamento_select"
                                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                                v-model="flujoValor">
                                                <option value="" selected>
                                                    Seleccione una opcion
                                                </option>
                                                <option v-for="departamento in departamentos" :key="departamento.id"
                                                    :value="departamento.id">
                                                    {{ departamento.nombre }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="m-4">
                                            <InputLabel for="lider_id" value="Lider: " />
                                            <select ref="lider_select"
                                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                                v-model="lider">
                                                <option value="" selected>
                                                    Seleccione una opcion
                                                </option>
                                                <option v-for="usuario in usuarios" :key="usuario.id"
                                                    :value="usuario.id">{{
                                                        usuario.name }}</option>
                                            </select>
                                        </div>

                                        <div class="m-4">
                                            <InputLabel for="tipo" value="Tipo: " />
                                            <select ref="tipo_select"
                                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                                v-model="tipo">
                                                <option value="" selected>
                                                    Seleccione una opcion
                                                </option>
                                                <option value="D.D.S" selected>
                                                    D.D.S </option>
                                                <option value="W.D.S." selected>
                                                    W.D.S. </option>
                                                <option value="M.D.S." selected>
                                                    M.D.S. </option>
                                                <option value="R.O.B." selected>
                                                    R.O.B. </option>
                                            </select>
                                        </div>

                                        <div class="m-4">
                                            <InputLabel for="proceso_id" value="Proceso: " />
                                            <select ref="departamento_select"
                                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                                v-model="proceso">
                                                <option value="" selected>
                                                    Seleccione una opcion
                                                </option>
                                                <option v-for="proceso in procesos" :key="proceso.id"
                                                    :value="proceso.id">
                                                    {{ proceso.nombre }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="m-4">
                                            <InputLabel for="fecha" value="Fecha de entrega de: " />
                                            <TextInput id="fecha" v-model="desde" type="date" class="mt-1 block w-full"
                                                autocomplete="fecha" />
                                        </div>

                                        <div class="m-4">
                                            <InputLabel for="created_at" value="Fecha de entrega hasta: " />
                                            <TextInput id="fecha" v-model="hasta" type="date" class="mt-1 block w-full"
                                                autocomplete="fecha" />
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <DataTable :value="items" paginator :rows="rows" :totalRecords="totalRecords" :lazy="true"
                                :first="first" @page="onPage" @sort="onSort" :rowsPerPageOptions="[5, 10, 20, 50]"
                                tableStyle="min-width: 50rem" :filters="filters" :globalFilterFields="[
                                    'id',
                                    'departamento.nombre',
                                    'alias',
                                    'proceso.nombre',
                                    'lider.name',
                                    'notas',
                                    'created_at',
                                ]" :sortField="sortField" :sortOrder="sortOrder"
                                class="p-datatable-sm p-datatable-striped p-datatable-gridlines">
                                <template #empty> Sin registros </template>
                                <Column field="id" header="ID" headerStyle="width:4em;" bodyStyle="text-align:center;"
                                    sortable></Column>
                                <Column field="departamento.nombre" header="Fujo de valor" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                                <Column field="tipo_minuta.titulo" header="Tipo" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                                <Column field="alias" header="Alias" headerStyle="width:4em;" bodyClass="text-center"
                                    sortable></Column>
                                <Column field="proceso.nombre" header="Proceso" headerStyle="width:4em;"
                                    bodyClass="text-center" sortable></Column>
                                <Column field="lider.name" header="Lider" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                                <Column header="fecha" headerStyle="width:4em;" bodyStyle="text-align:center;"
                                    bodyClass="text-center" sortable>
                                    <template #body="slotProps">
                                        {{ formatearFecha(slotProps.data.created_at) }}
                                    </template>
                                </Column>
                                <Column header="Tareas" headerStyle="width:4em;" bodyStyle="text-align:center;"
                                    bodyClass="text-center" sortable>
                                    <template #body="slotProps">
                                        <span
                                            v-if="slotProps.data.tareas !== undefined && slotProps.data.tareasTerminadas !== undefined">
                                            {{ "(" + slotProps.data.tareasTerminadas + " / " + slotProps.data.tareas +
                                                ")" }}
                                        </span>
                                        <span v-else>
                                            Loading...
                                        </span>
                                    </template>
                                </Column>
                                <Column header="" headerStyle="width:4em;">
                                    <template #body="slotProps" class="text-center">
                                        <div class="flex justify-center">
                                            <PrimaryButton class="pi pi-file-edit m-2"
                                                :href="route('minutas.edit', slotProps.data.id)"></PrimaryButton>
                                            <PrimaryButton class="pi pi-file-check m-2"
                                                :href="route('minutas.show', slotProps.data.id)"></PrimaryButton>
                                            <PrimaryButton class="pi pi-trash m-2"
                                                @click.prevent="deleteItems(slotProps.data.id)"></PrimaryButton>
                                        </div>
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
