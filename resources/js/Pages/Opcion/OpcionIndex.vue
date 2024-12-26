<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
import Layout from "@/Layouts/Layout2.vue";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputText from "primevue/inputtext";

const props = defineProps({
    opciones: Array,
});

const title = "assessment";
const subTitle = "opciones";
const opciones = ref([]);
const totalRecords = ref(0);
const rows = ref(10);
const first = ref(0);
const globalFilter = ref("");
const filters = ref({});
const sortField = ref("id");
const sortOrder = ref(1);

async function getOpciones(
    page = 1,
    rowsPerPage = rows.value,
    filter = "",
    sortField = "id",
    sortOrder = 1
) {
    try {
        const response = await axios.get("/api/opciones", {
            params: {
                page,
                rows: rowsPerPage,
                filter,
                sortField,
                sortOrder: sortOrder === 1 ? "asc" : "desc",
            },
        });
        opciones.value = response.data.data;
        totalRecords.value = response.data.total;
        first.value = (response.data.current_page - 1) * rows.value;
    } catch (error) {
        console.error(error);
    }
}

const deleteOpcion = async (id) => {
    try {
        const result = await confirmDialog(
            "Estas seguro?",
            "Ya no podras revertir esto!",
            "warning"
        );
        if (result.isConfirmed) {
            await axios.delete(route("opcion.destroy", id));
            opciones.value = opciones.value.filter((opcion) => opcion.id !== id);
            showToast("El registro ha sido eliminado", "success");

        }
    } catch (error) {
        console.log(error);

    }
};

onMounted(() => {
    getOpciones();
});

watch(globalFilter, (value) => {
    filters.value = {
        global: { value: newValue, matchMode: "contains" },
    };
    getOpciones(1, rows.value, value, sortField.value, sortOrder.value);
});

const onPage = (event) => {
    const page = event.page + 1;
    rows.value = event.rows;
    getOpciones(
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
    getOpciones(
        1,
        rows.value,
        globalFilter.value,
        sortField.value,
        sortOrder.value
    );
};
console.log(opciones.value);
</script>

<style scoped>
.mb-3 {
    margin-bottom: 1rem;
}
</style>

<template>
    <Layout :titulo="title" :subTitulo="subTitle">

        <Head title="Opciones" />
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Opciones</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link class="active">
                <h3>Opciones</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden">
                <div>
                    <div class="flex justify-end border-gray-200 bg-white px-4 py-2 border-b">
                        <PrimaryButton :href="route('opcion.create')" class="m-4 pi pi-plus"></PrimaryButton>
                    </div>
                    <div class="border-gray-200 bg-white px-4 py-2 border-b">
                        <div class="mx-auto overflow-x-auto container">
                            <InputText v-model="globalFilter" placeholder="Buscar..." class="mb-3" />
                            <DataTable :value="opciones" paginator :rows="rows" :totalRecords="totalRecords"
                                :lazy="true" :first="first" @page="onPage" @sort="onSort"
                                :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem" :filters="filters"
                                :globalFilterFields="[
                                    'id',
                                    'madurez',
                                    'in',
                                    'informal',
                                    'challenge.challenge',

                                ]" :sortField="sortField" :sortOrder="sortOrder"
                                class="p-datatable-gridlines p-datatable-sm p-datatable-striped">
                                <template #empty> No data found. </template>
                                <Column field="id" header="ID" headerStyle="width:4em;" bodyStyle="text-align:center;"
                                    sortable></Column>
                                <Column field="challenge.challenge" header="Opcion" headerStyle="width:4em;"
                                    bodyClass="text-center" sortable></Column>
                                <Column field="madurez" header="Madurez" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                                <Column field="formal" header="Formal" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                                <Column field="informal" header="Informal" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>

                                <Column header="" headerStyle="width:4em;">
                                    <template #body="slotProps" class="text-center">
                                        <PrimaryButton class="m-2 pi pi-file-edit" :href="route(
                                            'opcion.edit',
                                            slotProps.data.id
                                        )">

                                        </PrimaryButton>

                                        <PrimaryButton class="m-2 pi pi-trash" @click.prevent="
                                            deleteOpcion(slotProps.data.id)
                                            ">

                                        </PrimaryButton>
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
