<script setup>
import { onMounted, ref, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Layout from '@/Layouts/Layout2.vue';
import { confirmDialog, showToast } from "../utils/SweetAlert.service";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputText from "primevue/inputtext";
import { format } from 'date-fns';

const props = defineProps({
});

const title = "assessment";
const subTitle = "evaluaciones";
const evaluaciones = ref([]);

const totalRecords = ref(0);
const rows = ref(10);
const first = ref(0);
const globalFilter = ref("");
const filters = ref({});
const sortField = ref("id");
const sortOrder = ref(1);

onMounted(() => {
    getEvaluaciones();
})

watch(globalFilter, (newValue) => {
    filters.value = {
        global: { value: newValue, matchMode: "contains" },
    };
    getEvaluaciones(1, rows.value, newValue, sortField.value, sortOrder.value);
});

async function getEvaluaciones(
    page = 1,
    rowsPerPage = rows.value,
    filter = "",
    sortField = "id",
    sortOrder = -1
) {
    try {
        const response = await axios.get("/api/evaluaciones", {
            params: {
                page,
                rows: rowsPerPage,
                filter,
                sortField,
                sortOrder: sortOrder === 1 ? "asc" : "desc",
            },
        });
        evaluaciones.value = response.data.data;
        totalRecords.value = response.data.total;
        first.value = (response.data.current_page - 1) * rows.value;
        console.log({ evaluaciones: evaluaciones.value });
    } catch (error) {
        console.error(error);
    }
}
const formatearFecha = (dateString) => {
    return format(new Date(dateString), 'dd/MM/yyyy');
};

const onPage = (event) => {
    const page = event.page + 1;
    rows.value = event.rows; // Actualizar filas por página
    getEvaluaciones(
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
    getEvaluaciones(
        1,
        rows.value,
        globalFilter.value,
        sortField.value,
        sortOrder.value
    );
};

</script>

<template>
    <Layout :titulo="title" :subTitulo="subTitle">

        <Head title="Assessment" />
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-3 breadcrumbsTitulo">
                <h3 class="font-semibold text-xl">Evaluaciones</h3>
            </div>
            <div class="flex items-center mt-2 breadcrumbs">
                <Link href="#" class="px-3">
                <h3>Assessment</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden">
                <div>
                    <div v-if="$page.props.flash.error"
                        class="bg-red-200 w-full font-semibold text-center alert alert-dismissible">
                        {{ $page.props.flash.error }}
                        <button type="button" class="float-right gap-2 close" @click="$page.props.flash.error = null">
                            <span aria-hidden="true"><i class="pi pi-times"></i></span>
                        </button>
                    </div>



                    <div class="flex justify-end bg-white">
                        <PrimaryButton :href="route('evaluacion.create')" class="bg-black hover:bg-gray-800">
                            <i class="pi pi-plus" style="margin-right: 10px; font-size: 1rem"></i> Nueva evaluacion
                        </PrimaryButton>
                    </div>
                    <div class="border-gray-200 bg-white mt-7">
                        <div class="mx-auto overflow-x-auto">
                            <InputText v-model="globalFilter" placeholder="Buscar..." class="mb-3" />
                            <DataTable :value="evaluaciones" paginator :rows="rows" :totalRecords="totalRecords"
                                :lazy="true" :first="first" @page="onPage" @sort="onSort"
                                :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem" :filters="filters"
                                :globalFilterFields="[
                                    'id'
                                ]" :sortField="sortField" :sortOrder="sortOrder"
                                class="p-datatable-gridlines p-datatable-sm p-datatable-striped">
                                <template #empty> No data found. </template>
                                <Column field="id" header="ID" headerStyle="width:4em;" bodyStyle="text-align:center;"
                                    sortable></Column>
                                <Column header="Fecha" headerStyle="width:4em;" bodyStyle="text-align:center;"
                                    bodyClass="text-center" sortable>
                                    <template #body="slotProps">
                                        {{ formatearFecha(slotProps.data.created_at) }}
                                    </template>
                                </Column>
                                <Column header="Progreso" headerStyle="width:4em;" bodyStyle="text-align:center;"
                                    bodyClass="text-center">
                                    <template #body="slotProps">
                                        {{ slotProps.data.seccion_completa }} / {{ slotProps.data.seccion_total }}
                                    </template>
                                </Column>

                                <Column header="" headerStyle="width:4em;" bodyStyle=" justify-content-center;">
                                    <template #body="slotProps" class="justify-center">
                                        <PrimaryButton
                                            v-if="slotProps.data.seccion_completa !== slotProps.data.seccion_total"
                                            class="bg-black m-2 pi pi-file-edit" :href="route(
                                                'evaluacion.select',
                                                slotProps.data.id
                                            )">
                                        </PrimaryButton>

                                        <PrimaryButton class="bg-black m-2 pi pi-chart-scatter" :href="route(
                                            'evaluacion.details',
                                            slotProps.data.id
                                        )">
                                        </PrimaryButton>

                                        <!-- <PrimaryButton class="m-2 pi pi-trash" @click.prevent="
                                            deleteSeccion(slotProps.data.id)
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
