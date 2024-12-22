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
    // reporteSemanal: Array,
    reportes: Array,
});

const reportes = ref(props.reportes);
const totalRecords = ref(0);
const rows = ref(10);
const first = ref(0);
const reporteSemanal = ref([]);
const globalFilter = ref("");
const filters = ref({});
const sortField = ref("id"); // Valor predeterminado
const sortOrder = ref(1);
const title = "reportes";

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

// Obtener áreas al montar el componente
onMounted(() => {
    console.log('reportes', reportes.value);

});

// Actualizar filtro global
watch(globalFilter, (newValue) => {
    filters.value = {
        global: { value: newValue, matchMode: "contains" },
    };
    getAreas(1, rows.value, newValue, sortField.value, sortOrder.value);
});
</script>

<style scoped>
.mb-3 {
    margin-bottom: 1rem;
}
</style>

<template>
    <Layout :titulo="title">

        <Head title="semanales" />
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Mis Reportes</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('reporte.index')" class="px-1">
                <h3>Reportes -</h3>
                </Link>
                <Link class="active">
                <h3>Mis Reportes</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
                <div>
                    <div class="flex justify-end border-gray-200 bg-white px-4 py-2 border-b">
                        <PrimaryButton :href="route('reporte.create')" class="m-4 pi pi-plus"></PrimaryButton>
                    </div>
                    <div class="border-gray-200 bg-white px-4 py-2 border-b">
                        <div class="mx-auto container">
                            <!-- <InputText v-model="globalFilter" placeholder="Buscar..." class="mb-3" /> -->

                            <DataTable :value="reportes" paginator :rows="rows" :totalRecords="totalRecords"
                                :lazy="true" :first="first" @page="onPage" @sort="onSort"
                                :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem" :filters="filters"
                                :globalFilterFields="[
                                    'id',
                                    'nombre',
                                    'descripcion',
                                ]" :sortField="sortField" :sortOrder="sortOrder"
                                class="p-datatable-gridlines p-datatable-sm p-datatable-striped">
                                <template #empty> No data found. </template>
                                <Column field="id" header="ID" headerStyle="width:4em;" bodyStyle="text-align:center;"
                                    sortable></Column>
                                <Column field="semana.numeroSemana" header="Numero de la Semana"
                                    headerStyle="width:4em;" bodyStyle="text-align:center;" bodyClass="text-center"
                                    sortable></Column>
                                <Column field="departamento.nombre" header="Flujo de valor " headerStyle="width:4em;"
                                    bodyClass="text-center" sortable>
                                </Column>

                                <Column header="" headerStyle="width:4em;">
                                    <template #body="slotProps" class="text-center">
                                        <PrimaryButton class="me-2 pi pi-file-edit" :href="route(
                                            'misreporte.edit',
                                            slotProps.data.id
                                        )">
                                        </PrimaryButton>

                                        <!-- <PrimaryButton class="me-2 pi pi-trash" @click.prevent="
                                            deleteArea(slotProps.data.id)
                                            ">

                                        </PrimaryButton> -->
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
