<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import Layout2 from "@/Layouts/Layout2.vue";
import List from "@/Components/v2/List.vue";
import NavagationMenu from "@/Components/v2/NavigationMenu.vue";
import Card from "@/Components/v2/Card.vue";
import Tablapilares from "../utils/tablapilares.vue";
import InputText from "primevue/inputtext";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputLabel from "@/Components/InputLabel.vue";
import PilaresSelect from '@/Components/v2/PilaresSelect.vue';

const props = defineProps({
    pilar: Object,
    pilar_id: String,
});

const selectedItem = ref("flujoDeValor"); // El ítem seleccionado

const flujoName = ref("");
const procesoName = ref("");
const procedimientoName = ref("");

// Legacy code
const currentPilar = ref(props.pilar_id);
const departamentos = ref([]);
const departamento = ref({});
const procesos = ref([]);
const proceso = ref({});
const procedimientos = ref([]);
const procedimiento = ref({});
const estandares = ref([]);
const totalRecords = ref(0);
const rows = ref(10);
const first = ref(0);
const globalFilter = ref("");
const globalFilterProcesos = ref("");
const globalFilterProcedimientos = ref("");
const globalFilterEstandares = ref("");
const filters = ref({});
const filtersProcesos = ref({});

const sortField = ref("id");
const sortOrder = ref(1);

const selectedDepartamento = ref(null);
const selectedProceso = ref(null);
const selectedProcedimiento = ref(null);
const selectedEstandar = ref(null);

const getDepartamentos = async (
    page = 1,
    rowsPerPage = rows.value,
    filter = "",
    sortField = "id",
    sortOrder = 1,
    pilar = currentPilar.value,
) => {
    selectedItem.value = "flujoDeValor"
    flujoName.value = ""
    procesoName.value = ""
    procedimientoName.value = ""

    try {
        procesos.value = [];
        proceso.value = {};
        procedimientos.value = [];
        procedimiento.value = {};
        estandares.value = [];
        const response = await axios.get("/api/getDepartamentos", {
            params: {
                page,
                rows: rowsPerPage,
                filter,
                sortField,
                sortOrder: sortOrder === 1 ? "asc" : "desc",
                pilar: pilar,
            },
        });
        departamentos.value = response.data.data;
        totalRecords.value = response.data.total;
        first.value = (response.data.current_page - 1) * rows.value;
    } catch (error) {
        console.error(error);
    }
};

const getProcesos = async (
    departamento,
    page = 1,
    rowsPerPage = rows.value,
    filter = "",
    sortField = "id",
    sortOrder = 1,
    departamentoName,
) => {
    flujoName.value = departamentoName
    procesoName.value = ""
    procedimientoName.value = ""
    selectedDepartamento.value = departamento;
    selectedItem.value = "proceso"

    try {
        procesos.value = [];
        proceso.value = {};
        procedimientos.value = [];
        procedimiento.value = {};
        estandares.value = [];
        const response = await axios.get("/api/getProcesos", {
            params: {
                page,
                rows: rowsPerPage,
                filter,
                sortField,
                sortOrder: sortOrder === 1 ? "asc" : "desc",
                departamento,
            },
        });
        procesos.value = response.data.data;
        totalRecords.value = response.data.total;
        first.value = (response.data.current_page - 1) * rows.value;
    } catch (error) {
        console.error(error);
    }
    selectedDepartamento.value = departamento;

};

const getProcedimientos = async (
    proceso,
    page = 1,
    rowsPerPage = rows.value,
    filter = "",
    sortField = "id",
    sortOrder = 1,
    procesoNombre,
) => {
    selectedItem.value = "procedimiento"
    procesoName.value = procesoNombre
    procedimientoName.value = ""

    try {
        procedimientos.value = [];
        procedimiento.value = {};
        estandares.value = [];
        const response = await axios.get("/api/getProcedimientos", {
            params: {
                page,
                rows: rowsPerPage,
                filter,
                sortField,
                sortOrder: sortOrder === 1 ? "asc" : "desc",
                proceso,
            },
        });
        procedimientos.value = response.data.data;
        totalRecords.value = response.data.total;
        first.value = (response.data.current_page - 1) * rows.value;
    } catch (error) {
        console.error(error);
    }
    selectedProceso.value = proceso;

};

const getEstandares = async (
    procedimiento,
    page = 1,
    rowsPerPage = rows.value,
    filter = "",
    sortField = "id",
    sortOrder = 1,
    procedimientoNombre,
) => {
    procedimientoName.value = procedimientoNombre
    try {
        selectedItem.value = 'estandar'
        estandares.value = [];
        const response = await axios.get("/api/getEstandares", {
            params: {
                page,
                rows: rowsPerPage,
                filter,
                sortField,
                sortOrder: sortOrder === 1 ? "asc" : "desc",
                procedimiento,
            },
        });
        // console.log(response.data);
        estandares.value = response.data.data;
        totalRecords.value = response.data.total;
        first.value = (response.data.current_page - 1) * rows.value;
    } catch (error) {
        console.error(error);
    }

    selectedProcedimiento.value = procedimiento;
};

const onPage = (event) => {
    const page = event.page + 1;
    rows.value = event.rows; // Actualizar filas por página
    getDepartamentos(
        page,
        rows.value,
        globalFilter.value,
        sortField.value,
        sortOrder.value
    );
};

const onSort = (event) => {
    sortField.value = event.sortField || "id";
    sortOrder.value = event.sortOrder;
    getDepartamentos(
        1,
        rows.value,
        globalFilter.value,
        sortField.value,
        sortOrder.value
    );
};

const setSelectedItem = (value) => {
    selectedItem.value = value;
};

const onSelectedPilar = (pilarID) => {
    // getDepartamentos(1, rows.value, "", "id", 1, pilarID)
    currentPilar.value = pilarID;
    getDepartamentos();
}

onMounted(() => {
    getDepartamentos();
});

watch(globalFilter, (newValue) => {
    filters.value = {
        global: { value: newValue, matchMode: "contains" },
    };
    getDepartamentos(1, rows.value, newValue, sortField.value, sortOrder.value);

});

watch(globalFilterProcesos, (newValue) => {
    filters.value = {
        global: { value: newValue, matchMode: "contains" },
    };
    getProcesos(departamento.value, 1, rows.value, newValue, sortField.value, sortOrder.value);
});

watch(globalFilterProcedimientos, (newValue) => {
    filters.value = {
        global: { value: newValue, matchMode: "contains" },
    };
    getProcedimientos(proceso.value, 1, rows.value, newValue, sortField.value, sortOrder.value);
});

watch(globalFilterEstandares, (newValue) => {
    filters.value = {
        global: { value: newValue, matchMode: "contains" },
    };
    getEstandares(procedimiento.value, 1, rows.value, newValue, sortField.value, sortOrder.value);
});

watch(() => currentPilar, (newPilar) => {
    getDepartamentos();
});
</script>

<template>
    <Layout2>
        {{ procesoName }}
        {{ procedimientoName }}
        <PilaresSelect :currentPilarID="currentPilar" :onSelectedPilar="onSelectedPilar"></PilaresSelect>
        <div class="grid grid-cols-3">
            <!-- Menú de navegación -->
            <div class="xl:col-span-1 col-span-3 p-10">
                <div class="text-base">
                    <h4 class="font-semibold mb-1">Lista de navegación</h4>
                    <p class="text-sm text-gray-500">
                        Accede rápidamente a cada nivel de información dentro de los pilares
                    </p>
                </div>
                <div class="mt-5">
                    <NavagationMenu :value="selectedItem" :onValueChange="setSelectedItem"
                        :activeFlujo="flujoName" :activeProceso="procesoName" :activeProcedimiento="procedimientoName" />
                </div>
            </div>

            <!-- Contenido dinámico -->
            <div class="xl:col-span-2 col-span-3 p-8">
                <!-- Contenido para Flujo de Valor -->
                <div v-if="selectedItem === 'flujoDeValor'" class="border border-gray-300 p-8 rounded-md">
                    <h2 class="text-lg font-medium mb-4">Flujo de valor</h2>
                    <div class="container mx-auto overflow-x-auto mb-3">
                        <div class="sm:col-span-1 lg:col-span-1">
                            <InputText v-model="globalFilter" placeholder="Buscar..." class="mb-3" />
                            <DataTable :value="departamentos" paginator :rows="rows" :totalRecords="totalRecords"
                                :lazy="true" :first="first" @page="onPage" @sort="onSort"
                                :rowsPerPageOptions="[5, 10, 20, 50]" :filters="filters" :globalFilterFields="[
                                    'nombre',
                                ]" :sortField="sortField" :sortOrder="sortOrder"
                                class="p-datatable-sm p-datatable-striped p-datatable-gridlines">
                                <template #empty> Sin Registros. </template>
                                <Column field="nombre" header="Flujo de valor" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable>
                                    <template #body="slotProps">
                                        <button
                                            v-bind:class="[selectedDepartamento == slotProps.data.id ? 'selected' : '']"
                                            @click="getProcesos(departamento.value = slotProps.data.id, 1, rows,
                                                newValue, sortField, sortOrder, slotProps.data.nombre)">
                                            {{ slotProps.data.nombre }}
                                        </button>
                                    </template>
                                </Column>
                                <Column field="kpis" header="KPIs" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable>
                                    <template #body="slotProps">
                                        <div v-if="slotProps.data.kpis && slotProps.data.kpis.length">
                                            <ul>
                                                <li v-for="(kpi, index) in slotProps.data.kpis" :key="index">
                                                    {{ kpi.titulo }}
                                                </li>
                                            </ul>
                                        </div>
                                        <div v-else>
                                            Sin KPIs
                                        </div>
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                    </div>
                </div>

                <!-- Contenido para Proceso -->
                <div v-if="selectedItem === 'proceso'" class="h-[620px] overflow-scroll">
                    <h2 class="text-lg font-medium mb-4">Procesos</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <Card v-for="proceso in procesos" :title="proceso.nombre" :link="proceso.link_externo"
                            @click="getProcedimientos(proceso.value = proceso.id, 1, rows, newValue, sortField, sortOrder, proceso.nombre)" />
                    </div>
                </div>

                <!-- Contenido para Procedimientos -->
                <div v-if="selectedItem === 'procedimiento'" class="border border-gray-300 p-8 rounded-md">
                    <h2 class="text-lg font-medium mb-4">Procedimientos</h2>
                    <div>
                        <InputText v-model="globalFilterProcedimientos" placeholder="Buscar..." class="mb-3" />
                        <DataTable :value="procedimientos" paginator :rows="rows" :totalRecords="totalRecords"
                            :lazy="true" :first="first" @page="onPage" @sort="onSort"
                            :rowsPerPageOptions="[5, 10, 20, 50]" :filters="filters" :globalFilterFields="[
                                'nombre',
                            ]" :sortField="sortField" :sortOrder="sortOrder"
                            class="p-datatable-sm p-datatable-striped p-datatable-gridlines">
                            <template #empty> Sin Registros. </template>
                            <Column field="nombre" header="Procedimientos" headerStyle="width:4em;"
                                bodyStyle="text-align:center;" bodyClass="text-center" sortable>
                                <template #body="slotProps">
                                    <button
                                        v-bind:class="[selectedProcedimiento == slotProps.data.id ? 'selected' : '']"
                                        @click="getEstandares(procedimiento.value = slotProps.data.id, 1, rows, newValue, sortField, sortOrder, slotProps.data.nombre)">
                                        {{ slotProps.data.nombre }}
                                    </button>
                                </template>
                            </Column>
                            <Column field="link_externo" header="Ejecución" headerStyle="width:4em;"
                                bodyStyle="text-align:center;" bodyClass="text-center" sortable>
                                <template #body="slotProps">
                                    <a :href="slotProps.data.link_herramienta" target="_blank"
                                        rel="noopener noreferrer">
                                        {{ slotProps.data.link_herramienta }}
                                    </a>
                                </template>
                            </Column>
                            <Column field="link_externo" header="Documentación" headerStyle="width:4em;"
                                bodyStyle="text-align:center;" bodyClass="text-center" sortable>
                                <template #body="slotProps">
                                    <a :href="slotProps.data.link_externo" target="_blank" rel="noopener noreferrer">
                                        {{ slotProps.data.link_externo }}
                                    </a>
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </div>

                <div v-if="selectedItem === 'estandar'" class="border border-gray-300 p-8 rounded-md">
                    <h2 class="text-lg font-medium mb-4">Estandares</h2>
                    <div>
                        <InputText v-model="globalFilterEstandares" placeholder="Buscar..." class="mb-3" />
                        <DataTable :value="estandares" paginator :rows="rows" :totalRecords="totalRecords" :lazy="true"
                            :first="first" @page="onPage" @sort="onSort" :rowsPerPageOptions="[5, 10, 20, 50]"
                            :filters="filters" :globalFilterFields="[
                                'nombre',
                            ]" :sortField="sortField" :sortOrder="sortOrder"
                            class="p-datatable-sm p-datatable-striped p-datatable-gridlines">
                            <template #empty> Sin Registros. </template>
                            <Column field="nombre" header="Estandares" headerStyle="width:4em;"
                                bodyStyle="text-align:center;" bodyClass="text-center" sortable>
                                <!-- <template #body="slotProps">
                                            <button @click="getEstandares(slotProps.data.id)">
                                                {{ slotProps.data.nombre }}
                                            </button>s
                                        </template> -->
                            </Column>
                            <Column field="link_externo" header="Documentación" headerStyle="width:4em;"
                                bodyStyle="text-align:center;" bodyClass="text-center" sortable>
                                <template #body="slotProps">
                                    <a :href="slotProps.data.link_externo" target="_blank" rel="noopener noreferrer">
                                        {{ slotProps.data.link_externo }}
                                    </a>
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </Layout2>
</template>
