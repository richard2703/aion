<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import Layout from "@/Layouts/Layout2.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputText from "primevue/inputtext";

// Definir propiedades
const props = defineProps({
    reporteSemanal: Array,
    areasAPI: Array,
});

const totalRecords = ref(0);
const rows = ref(10);
const first = ref(0);
const reporteSemanal = ref([]);
const globalFilter = ref("");
const filters = ref({});
const sortField = ref("id"); // Valor predeterminado
const sortOrder = ref(1);
const title = "reportes";

// Función para obtener áreas
async function getreporteSemanal(
    page = 1,
    rowsPerPage = rows.value,
    filter = "",
    sortField = "id",
    sortOrder = 1
) {
    try {
        const response = await axios.get("/api/getReporteSemanal", {
            params: {
                page,
                rows: rowsPerPage,
                filter,
                sortField,
                sortOrder: sortOrder === 1 ? "asc" : "desc",
            },
        });
        reporteSemanal.value = response.data;
        console.log('reporteSemanal', reporteSemanal.value);

        totalRecords.value = response.data.total;
        first.value = (response.data.current_page - 1) * rows.value;
    } catch (error) {
        console.error(error);
    }
}

// Manejar paginación
const onPage = (event) => {
    const page = event.page + 1;
    rows.value = event.rows; // Actualizar filas por página
    getreporteSemanal(
        page,
        rows.value,
        globalFilter.value,
        sortField.value,
        sortOrder.value
    );
};

// Manejar ordenación
const onSort = (event) => {
    sortField.value = event.sortField || "id";
    sortOrder.value = event.sortOrder;
    getreporteSemanal(
        1,
        rows.value,
        globalFilter.value,
        sortField.value,
        sortOrder.value
    );
};

// Obtener áreas al montar el componente
onMounted(() => {
    getreporteSemanal();
});

// Eliminar área
// const deleteArea = async (id) => {
//     try {
//         const result = await confirmDialog(
//             "Estas seguro?",
//             "Ya no podras revertir esto!",
//             "warning"
//         );
//         if (result.isConfirmed) {
//             const response = await axios.delete(route("area.destroy", id));
//             areas.value = areas.value.filter((area) => area.id !== id);
//             showToast("El registro ha sido eliminado", "success");
//         }
//     } catch (error) {
//         console.error(error);
//     }
// };

// Actualizar filtro global
watch(globalFilter, (newValue) => {
    filters.value = {
        global: { value: newValue, matchMode: "contains" },
    };
    getAreas(1, rows.value, newValue, sortField.value, sortOrder.value);
});

const isLoading = ref(false);
function handleClick(id) {
    isLoading.value = true;
    // El redireccionamiento ocurre después de cambiar el estado.
    window.location.href = route('reporte.pdf', id);
}
</script>

<style scoped>
.mb-3 {
    margin-bottom: 1rem;
}
</style>

<template>
    <Layout :titulo="title">

        <Head title="semanales" />
        <div class="pl-5 overflow-hidden">
            <div class="breadcrumbsTitulo">
                <h3 class="font-semibold text-xl">Tareas</h3>
            </div>
        </div>

        <div class="p-5 overflow-hidden">
            <div class="flex justify-between items-end mb-5">
                <input v-model="globalFilter" placeholder="Buscar..." id="task-title" type="text" class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black min-w-80 sm:text-sm" />
                <button type="button" :href="route('reporte.create')"
                    class="bg-black hover:bg-gray-800 shadow mt-8 px-3 py-3 rounded-md w-40 font-semibold text-md text-white">
                    Nueva tarea
                </button>
            </div>

            <div class="mx-auto container">


                <DataTable :value="reporteSemanal" paginator :rows="rows" :totalRecords="totalRecords" :lazy="true"
                    :first="first" @page="onPage" @sort="onSort" :rowsPerPageOptions="[5, 10, 20, 50]"
                    tableStyle="min-width: 50rem" :filters="filters" :globalFilterFields="[
                        'id',
                        'nombre',
                        'descripcion',
                    ]" :sortField="sortField" :sortOrder="sortOrder"
                    class="p-datatable-gridlines p-datatable-sm p-datatable-striped">
                    <template #empty> No data found. </template>
                    <Column field="id" header="ID" headerStyle="width:4em;" bodyStyle="text-align:center;" sortable>
                    </Column>
                    <Column field="numeroSemana" header="Numero de la Semana" headerStyle="width:4em;"
                        bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                    <Column header="Periodo" headerStyle="width:4em;">
                        <template #body="slotProps" class="text-center">
                            Del {{ slotProps.data.inicio }} <br> Al {{ slotProps.data.fin }}
                        </template>
                    </Column>
                    <!-- <Column field="reportes" header="Reportes Hechos " headerStyle="width:4em;"
                                    bodyClass="text-center" sortable></Column> -->

                    <Column header="" headerStyle="width:4em;">
                        <template #body="slotProps" class="text-center">
                            <PrimaryButton class="me-2 pi pi-eye" :href="route('reporte.show', slotProps.data.id)">
                            </PrimaryButton>
                            <!-- <PrimaryButton class="me-2 pi pi-eye" @click="handleClick(slotProps.data.id)"
                                            :disabled="isLoading">
                                            <span v-if="!isLoading">Ver Reporte</span>
                                            <span v-else>
                                                <i class="pi pi-spin pi-spinner"></i> Cargando...
                                            </span>
                                        </PrimaryButton> -->

                            <PrimaryButton class="me-2 pi pi-download" :href="route('reporte.pdf', slotProps.data.id)"
                                target="_blank">
                            </PrimaryButton>

                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>

    </Layout>
</template>
