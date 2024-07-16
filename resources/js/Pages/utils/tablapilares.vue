<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import InputText from "primevue/inputtext";
import DataTable from "primevue/datatable";
import Column from "primevue/column";

const props = defineProps({
    pilar: String,
});

const departamentos = ref([]);
const totalRecords = ref(0);
const rows = ref(10);
const first = ref(0);
const globalFilter = ref("");
const filters = ref({});
const sortField = ref("id");
const sortOrder = ref(1);

const getDepartamentos = async (
    page = 1,
    rowsPerPage = rows.value,
    filter = "",
    sortField = "id",
    sortOrder = 1
) => {
    try {
        const response = await axios.get("/api/departamentos/find/todo", {
            params: {
                page,
                rows: rowsPerPage,
                filter,
                sortField,
                sortOrder: sortOrder === 1 ? "asc" : "desc",
                pilar: props.pilar,
            },
        });
        console.log(response.data);
        departamentos.value = response.data.data;
        totalRecords.value = response.data.total;
        first.value = (response.data.current_page - 1) * rows.value;
    } catch (error) {
        console.error(error);
    }
};

const deleteDepartamento = async (id) => {
    try {
        const result = await confirmDialog(
            "Estas seguro?",
            "Ya no podras revertir esto!",
            "warning"
        );
        if (result.isConfirmed) {
            await axios.delete(route("departamento.destroy", id));

            departamentos.value = departamentos.value.filter(
                (departamento) => departamento.id !== id
            );
            showToast("El registro ha sido eliminado", "success");
        }
    } catch (error) {
        console.error(error);
    }
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

watch(() => props.pilar, (newPilar) => {
    getDepartamentos();
});
</script>

<style scoped>
.mb-3 {
    margin-bottom: 1rem;
}
</style>

<template>
    <div class="py-2">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div>
                <div class="px-4 py-2 bg-white border-b border-gray-200">
                    <div class="container mx-auto overflow-x-auto">
                        <InputText v-model="globalFilter" placeholder="Buscar..." class="mb-3" />

                        <DataTable :value="departamentos" paginator :rows="rows" :totalRecords="totalRecords"
                            :lazy="true" :first="first" @page="onPage" @sort="onSort"
                            :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem" :filters="filters"
                            :globalFilterFields="[
                                'id',
                                'nombre',
                                'area.nombre',
                                'descripcion',
                            ]" :sortField="sortField" :sortOrder="sortOrder"
                            class="p-datatable-sm p-datatable-striped p-datatable-gridlines">
                            <template #empty> No data found. </template>
                            <Column field="id" header="ID" headerStyle="width:4em;" bodyStyle="text-align:center;"
                                sortable></Column>
                            <Column field="area.nombre" header="Area" headerStyle="width:4em;"
                                bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                            <Column field="nombre" header="Departamento" headerStyle="width:4em;"
                                bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                            <Column field="descripcion" header="Descripcion" headerStyle="width:4em;"
                                bodyClass="text-center" sortable></Column>
                            <Column header="Proceso" headerStyle="width:4em;" bodyStyle="text-align:center;">
                                <template #body="slotProps">
                                    <ul>
                                        <li v-for="proceso in slotProps.data.procesos" :key="proceso.id">
                                            {{ proceso.nombre }}
                                        </li>
                                    </ul>
                                </template>
                            </Column>
                            <Column header="Procedimiento" headerStyle="width:4em;" bodyStyle="text-align:center;">
                                <template #body="slotProps">
                                    <ul>
                                        <li v-for="proceso in slotProps.data.procesos" :key="proceso.id">
                                            <ul>
                                                <li v-for="procedimiento in proceso.procedimientos"
                                                    :key="procedimiento.id">
                                                    {{ procedimiento.nombre }}
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
