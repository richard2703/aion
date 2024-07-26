<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputText from "primevue/inputtext";
import Chart from 'primevue/chart';

const props = defineProps({
    areas: Array,
    departamentos: Array || null,
    departamento: Object,
});

// const organizaciones = ref(1);
const organizaciones = ref(1);
const areas = ref(props.areas);
const departamentos = ref(props.departamentos);
const departamento = ref({
    departamento: { nombre: '' },
    kpis: [
        { actual: '', objetivo: '' } // Puedes agregar más objetos según sea necesario
    ]
});
// kpis[0].objetivo
//datos para tabla
const chartData = ref();
const chartOptions = ref();

const setChartData = () => {
    return {
        labels: ['Enero', 'Febrero', 'Marzo', 'Abril'],
        datasets: [
            {
                label: 'Contrataciones',
                data: [5, 7, 3, 4],
                backgroundColor: ['rgba(249, 115, 22, 0.2)', 'rgba(6, 182, 212, 0.2)', 'rgb(107, 114, 128, 0.2)', 'rgba(139, 92, 246 0.2)'],
                borderColor: ['rgb(249, 115, 22)', 'rgb(6, 182, 212)', 'rgb(107, 114, 128)', 'rgb(139, 92, 246)'],
                borderWidth: 1
            }
        ]
    };
};
const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
    const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

    return {
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            }
        }
    };
}
// fin datos tabla

const kpis = ref(props.kpis);
const totalRecords = ref(0);
const rows = ref(10);
const first = ref(0);
const globalFilter = ref("");
const filters = ref({});
const sortField = ref("id");
const sortOrder = ref(1);


const form = useForm({
    area_id: "",
    departamento_id: "",
    // nombre: "",
    // descripcion: "",
    // link_externo: "",
});

onMounted(() => {
    getAreas();
    getKpis();
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
});

async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}

async function getDepartamentos(event) {
    const taget_id = event.target.value;
    form.departamento_id = "";
    await axios
        .get(route("departamentos.byArea", taget_id))
        .then((response) => (departamentos.value = response.data.departamentos))
        .catch((error) => {
            console.log(error);
        });
}

async function getKpis(
    page = 1,
    rowsPerPage = rows.value,
    filter = "",
    sortField = "id",
    sortOrder = 1
) {
    try {
        const response = await axios.get("/api/kpis", {
            params: {
                page,
                rows: rowsPerPage,
                filter,
                sortField,
                sortOrder: sortOrder === 1 ? "asc" : "desc",
            },
        });
        kpis.value = response.data.data;
        totalRecords.value = response.data.total;
        first.value = (response.data.current_page - 1) * rows.value;
    } catch (error) {
        console.error(error);
    }
}

async function sysGestion(
    area_id,
    departamento_id
) {
    if (departamento_id == "") {
        alert("Selecciona un departamento");
        return false;
    }
    await axios
        .get(route("sysgestion.departamento", departamento_id))
        .then((response) => {
            (departamento.value = response.data)
            console.log('departamento', departamento.value);
        })
        .catch((error) => {
            console.log(error);
        });

}

const test = () => {
    const area = `${form.area_id}`;
    const departamento = `${form.departamento_id}`;
    if (area == "") {
        organizaciones.value = 1;

    } else {
        organizaciones.value = 0;
        sysGestion(area, departamento);

    }

};


const title = "sysGestion"; // Aquí asegúrate de que esto coincida con el valor que esperas en MainMenu
const subTitle = "subTitle2"; // Este segundo es por siu viene de un menu desplegable en MainMenu

</script>

<template>
    <Layout :titulo="title" :subTitulo="subTitle">

        <Head title="Dashboard" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Sistema de Gestion</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link class="active">
                <h3>Sistema de gestion</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    <div class="px-4 py-2 flex justify-end bg-white border-b border-gray-200">
                        <div class="container mx-auto">
                            <!-- <form @submit.prevent="submit"> -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div class="mt-4">
                                    <InputLabel for="area_id" value="Pilar: " />
                                    <select ref="area_select" @change="getDepartamentos($event)"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                        v-model="form.area_id" required>
                                        <option value="" selected>
                                            Organizacion </option>
                                        <option v-for="area in areas" :key="area.id" :value="area.id">
                                            {{ area.nombre }}
                                        </option>
                                    </select>
                                </div>
                                <div class="mt-4">
                                    <InputLabel for="departamento_id" value="Flujo de valor: " />

                                    <select ref="departamento_select"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                        v-model="form.departamento_id" required>
                                        <option value="" disabled selected>
                                            Seleccione una opcion
                                        </option>
                                        <option v-for="departamento in departamentos" :key="departamento.id"
                                            :value="departamento.id">
                                            {{ departamento.nombre }}
                                        </option>
                                    </select>
                                </div>
                                <div class="mt-4">
                                    <PrimaryButton class=" mb-4 pi pi-filter" @click="test"></PrimaryButton>
                                </div>

                            </div>
                            <!-- </form> -->
                        </div>
                    </div>
                    <div v-if="organizaciones == 1" class="px-4 py-2 bg-white border-b border-gray-200">
                        <div class="container mx-auto overflow-x-auto">
                            <!-- <InputText v-model="globalFilter" placeholder="Buscar..." class="mb-3" /> -->
                            <DataTable :value="kpis" paginator :rows="rows" :totalRecords="totalRecords" :lazy="true"
                                :first="first" @page="onPage" @sort="onSort" :rowsPerPageOptions="[5, 10, 20, 50]"
                                tableStyle="min-width: 50rem" :filters="filters" :globalFilterFields="[
                                    'Nombre',
                                    'Real',
                                    'Plan',
                                    'Tendencia',
                                ]" :sortField="sortField" :sortOrder="sortOrder"
                                class="p-datatable-sm p-datatable-striped p-datatable-gridlines">
                                <template #empty> No data found. </template>
                                <Column field="titulo" header="Kpi" headerStyle="width:4em;" bodyClass="text-center"
                                    sortable>
                                </Column>
                                <Column field="actual" header="Real" headerStyle="width:4em;" bodyClass="text-center"
                                    sortable>
                                </Column>
                                <Column field="objetivo" header="Objetivo" headerStyle="width:4em;"
                                    bodyClass="text-center" sortable>
                                </Column>
                                <Column field="tendencia" header="Tendencia" headerStyle="width:4em;"
                                    bodyClass="text-center" sortable>
                                </Column>
                                <Column field="estatus" header="Estatus" headerStyle="width:4em;"
                                    bodyClass="text-center" sortable>
                                </Column>

                                <!-- <Column header="" headerStyle="width:4em;">
                                    <template #body="slotProps" class="text-center">
                                        <PrimaryButton class="m-2" :href="route(
                                            'kpi.edit',
                                            slotProps.data.id
                                        )">
                                            Editar
                                        </PrimaryButton>

                                        <PrimaryButton class="m-2" @click.prevent="
                                            deleteKpi(slotProps.data.id)
                                            ">
                                            Borrar
                                        </PrimaryButton>
                                    </template>
                                </Column> -->
                            </DataTable>
                        </div>
                    </div>

                    <div v-if="organizaciones == 0" class="px-4 py-2 bg-white border-b border-gray-200">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                            <div class="mt-4">
                                <div class="rotation-table">
                                    <table class="min-w-full border-collapse">
                                        <thead>
                                            <tr>
                                                <th class="py-2 px-4 border" colspan="2">
                                                    {{ departamento.departamento.nombre }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="py-2 px-4 border">Plan</td>
                                                <td class="py-2 px-4 border">Hoy</td>
                                            </tr>
                                            <tr>

                                                <td class="py-2 px-4 border bg-yellow-100">{{
                                                    departamento.kpis[0].objetivo }}</td>
                                                <td class="py-2 px-4 border"> {{ departamento.kpis[0].actual }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="card">
                                    <Chart type="bar" :data="chartData" :options="chartOptions" />
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                            <div class="mt-4">
                                <!-- Procesos -->
                                <div class="max-h-48 overflow-y-auto">
                                    <table class="min-w-full border-collapse">
                                        <thead>
                                            <tr>
                                                <th class="py-2 px-4 border" colspan="2">
                                                    Procesos
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(procesoItem, index) in departamento.departamento.procesos"
                                                :key="index">
                                                <td class="py-2 px-4 border">
                                                    <Link :href="route('proceso.edit', procesoItem.id)"
                                                        class="text-blue-500 hover:underline">
                                                    {{ procesoItem.nombre }}
                                                    </Link>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="mt-4">
                                <!-- Acciones -->
                                <table class="min-w-full border-collapse">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border" colspan="2">
                                                Acciones
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="py-2 px-4 border">Correctivas</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border">Preventivas</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border">Mejoras</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border">
                                                <Link :href="route('minutas.index')"
                                                    class="text-blue-500 hover:underline">
                                                Minutas
                                                </Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </Layout>
</template>
