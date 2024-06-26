<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
import Layout from "@/Layouts/Layout.vue";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputText from "primevue/inputtext";

const props = defineProps({
    challenges: Array,
});

const title = "Challenges";
const challenges = ref([]);
const totalRecords = ref(0);
const rows = ref(10);
const first = ref(0);
const globalFilter = ref("");
const filters = ref({});
const sortField = ref("id");
const sortOrder = ref(1);

async function getChallenges(
    page = 1,
    rowsPerPage = rows.value,
    filter = "",
    sortField = "id",
    sortOrder = 1
) {
    try {
        const response = await axios.get("/api/challenges", {
            params: {
                page,
                rows: rowsPerPage,
                filter,
                sortField,
                sortOrder: sortOrder === 1 ? "asc" : "desc",
            },
        });
        challenges.value = response.data.data;
        totalRecords.value = response.data.total;
        first.value = (response.data.current_page - 1) * rows.value;
    } catch (error) {
        console.error(error);
    }
}

const deleteChallenge = async (id) => {
    try {
        const result = await confirmDialog(
            "Estas seguro?",
            "Ya no podras revertir esto!",
            "warning"
        );
        if (result.isConfirmed) {
            await axios.delete(route("challenge.destroy", id));
            challenges.value = challenges.value.filter((challenge) => challenge.id !== id);
            showToast("El registro ha sido eliminado", "success");

        }
    } catch (error) {
        console.log(error);

    }
};

onMounted(() => {
    getChallenges();
});

watch(globalFilter, (value) => {
    filters.value = {
        global: { value: newValue, matchMode: "contains" },
    };
    getChallenges(1, rows.value, value, sortField.value, sortOrder.value);
});

const onPage = (event) => {
    const page = event.page + 1;
    rows.value = event.rows;
    getChallenges(
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
    getChallenges(
        1,
        rows.value,
        globalFilter.value,
        sortField.value,
        sortOrder.value
    );
};
console.log(challenges.value);
</script>

<style scoped>
.mb-3 {
    margin-bottom: 1rem;
}
</style>

<template>
    <Layout :titulo="title">

        <Head title="Departamento" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Challenges</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link class="active">
                <h3>Challenges</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    <div class="px-4 py-2 flex justify-end bg-white border-b border-gray-200">
                        <PrimaryButton :href="route('challenge.create')">Nuevo</PrimaryButton>
                    </div>
                    <div class="px-4 py-2 bg-white border-b border-gray-200">
                        <div class="container mx-auto overflow-x-auto">
                            <InputText v-model="globalFilter" placeholder="Buscar..." class="mb-3" />
                            <DataTable :value="challenges" paginator :rows="rows" :totalRecords="totalRecords"
                                :lazy="true" :first="first" @page="onPage" @sort="onSort"
                                :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem" :filters="filters"
                                :globalFilterFields="[
                                    'id',
                                    'area.nombre',
                                    'departamento.nombre',
                                    'challenge',
                                ]" :sortField="sortField" :sortOrder="sortOrder"
                                class="p-datatable-sm p-datatable-striped p-datatable-gridlines">
                                <Column field="id" header="ID" headerStyle="width:4em;" bodyStyle="text-align:center;"
                                    sortable></Column>
                                <Column field="area.nombre" header="Area" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                                <Column field="departamento.nombre" header="Departamento" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                                <Column field="challenge" header="Challenge" headerStyle="width:4em;"
                                    bodyClass="text-center" sortable></Column>

                                <Column header="" headerStyle="width:4em;">
                                    <template #body="slotProps" class="text-center">
                                        <PrimaryButton class="m-2" :href="route(
                                            'challenge.edit',
                                            slotProps.data.id
                                        )">
                                            Editar
                                        </PrimaryButton>

                                        <PrimaryButton class="m-2" @click.prevent="
                                            deleteChallenge(slotProps.data.id)
                                            ">
                                            Borrar
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
