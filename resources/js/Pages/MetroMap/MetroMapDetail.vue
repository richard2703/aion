<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import Layout2 from "@/Layouts/Layout2.vue";
import List from "@/Components/v2/List.vue";
import NavagationMenu from "@/Components/v2/NavigationMenu.vue";
import Card from "@/Components/v2/Card.vue";
import Tablapilares from "../utils/tablapilares.vue";

const props = defineProps({
    pilar: Object,
    pilar_id: String,
});

const columnsflujoDeValor = [
  { header: "Flujo de valor", key: "nombre" },
  { header: "KPI", key: "kpis", parse: (value) => value.map(v => v.titulo).join(', ') },
];

const columnProcedimientos = [
  { header: "Procedimientos", key: "nombre" },
  { header: "Ejecución", key: "link_herramienta" },
  { header: "Documentación", key: "link_externo" },
];

const selectedItem = ref("flujoDeValor"); // El ítem seleccionado

// Legacy code

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
    sortOrder = 1
) => {
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
                pilar: props.pilar,
            },
        });
        departamentos.value = response.data.data;
        // console.log(departamentos.value);
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

) => {
    selectedDepartamento.value = departamento;
    // departamento.value = departamento;
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
    sortOrder = 1
) => {
    selectedItem.value = "procedimiento"
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
    sortOrder = 1

) => {
    try {

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

watch(() => props.pilar, (newPilar) => {
    getDepartamentos();
});
</script>

<template>
  <Layout2>
    <Tablapilares :pilar="pilar_id"></Tablapilares>
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
          <NavagationMenu v-model="selectedItem" />
        </div>
      </div>

      <!-- Contenido dinámico -->
      <div class="xl:col-span-2 col-span-3 p-8">
        <!-- Contenido para Flujo de Valor -->
        <div v-if="selectedItem === 'flujoDeValor'" class="border border-gray-300 p-8 rounded-md">
          <h2 class="text-lg font-medium mb-4">Flujo de valor</h2>
          <List :columns="columnsflujoDeValor" :items="departamentos" :onClick="(item) => getProcesos(item.id, 1, rows,
                                                newValue, sortField, sortOrder)"/>
        </div>

        <!-- Contenido para Proceso -->
        <div v-if="selectedItem === 'proceso'" class="h-[620px] overflow-scroll">
          <h2 class="text-lg font-medium mb-4">Proceso</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <Card v-for="proceso in procesos"
            :title="proceso.nombre"
            :link="proceso.link_externo"
            @click="getProcedimientos(proceso.value = slotProps.data.id, 1, rows, newValue, sortField, sortOrder)"
            />
          </div>
        </div>

        <!-- Contenido para Procedimientos -->
        <div v-if="selectedItem === 'procedimiento'" class="border border-gray-300 p-8 rounded-md">
          <h2 class="text-lg font-medium mb-4">Procedimientos</h2>
          <List :columns="columnProcedimientos" :items="procedimientos" />
        </div>
      </div>
    </div>
  </Layout2>
</template>
