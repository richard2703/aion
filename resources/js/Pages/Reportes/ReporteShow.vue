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
    reporteSemanal: Object,
    reportes: Array,
});

const totalRecords = ref(0);
const rows = ref(10);
const first = ref(0);
const reporteSemanal = ref(props.reporteSemanal);
const reportes = ref("");
const globalFilter = ref("");
const filters = ref({});
const sortField = ref("id"); // Valor predeterminado
const sortOrder = ref(1);
const title = "reportes";

// Función para obtener áreas
async function getreportes(
    page = 1,
    rowsPerPage = rows.value,
    filter = "",
    sortField = "id",
    sortOrder = 1,
    id = reporteSemanal.value.id,
) {
    try {
        const response = await axios.get(route("getReportes.findAllReportes", id), {
            params: {
                page,
                rows: rowsPerPage,
                filter,
                sortField,
                sortOrder: sortOrder === 1 ? "asc" : "desc",
            },
        });
        reportes.value = response.data;
        console.log('reportes', response);

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
    getreportes(
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
    getreportes(
        1,
        rows.value,
        globalFilter.value,
        sortField.value,
        sortOrder.value
    );
};

const collapsedReport = ref([]);

function collapsedReportReport(id) {
    if (collapsedReport.value.includes(id)) {
        collapsedReport.value = collapsedReport.value.filter(item => item !== id);
    } else {
        collapsedReport.value.push(id);
    }
}

function iscollapsedReport(id) {
    return collapsedReport.value.includes(id);
}

// Obtener áreas al montar el componente
onMounted(() => {
    getreportes();
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

function formatNumber(value) {
    if (value == null) return ''; // Manejar el caso cuando el valor es nulo o indefinido
    return parseFloat(value).toFixed(2);
}

const getClass = (kpiItem) => {
    if (kpiItem.regla === 1) {
        return kpiItem.actual >= kpiItem.objetivo ? 'bg-green-100' : 'bg-red-100';
    } else {
        return kpiItem.actual <= kpiItem.objetivo ? 'bg-green-100' : 'bg-red-100';
    }
};

const getTerminada = (estatus) => {
    if (estatus === 1) {
        return estatus === 1 ? 'text-green-500 font-bold' : '';
    }
};

const getTrend = (promedio, objetivo, regla) => {

    const diferencia = (promedio - objetivo) / objetivo;

    if (diferencia > 0.05) {
        return regla === 1 ? 'pi pi-arrow-up text-green-500 font-black' : 'pi pi-arrow-up text-red-500 font-black';
    } else if (diferencia > 0.03) {
        return regla === 1 ? 'pi pi-arrow-up text-green-300' : 'pi pi-arrow-up text-red-300';
    } else if (diferencia > 0.01) {
        return regla === 1 ? 'pi pi-arrow-up text-yellow-500' : 'pi pi-arrow-up text-orange-500';
    } else if (diferencia < -0.01) {
        return regla === 1 ? 'pi pi-arrow-down text-orange-500' : 'pi pi-arrow-down text-yellow-500 ';
    } else if (diferencia < -0.03) {
        return regla === 1 ? 'pi pi-arrow-down font-weight: bolder' : 'pi pi-arrow-down text-green-300';
    } else if (diferencia < -0.05) {
        return regla === 1 ? 'pi pi-arrow-down text-red-500 font-black' : 'pi pi-arrow-down text-green-500 font-black';
    } else {
        return 'pi pi-minus text-gray-500';
    }
};
</script>

<style scoped>
.mb-3 {
    margin-bottom: 1rem;
}

.reporte {
    border: 1px solid #ccc;
    margin-bottom: 10px;
    border-radius: 5px;
    padding: 10px;
    background-color: white;

}

.departamento-header {
    cursor: pointer;
    background-color: #f0f0f0;
    padding: 10px;
    border-radius: 5px;
}

.departamento-content {
    padding: 10px;
}

.trend-cell {
    font-size: 1rem;
    /* Tamaño de fuente similar al resto */
    padding: 0.5rem;
    text-align: center;
}
</style>

<template>
    <Layout :titulo="'Reporte Semanal ' + reporteSemanal.numeroSemana">

        <Head title="Reporte Semanal" />
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Reporte Semanal: Semana {{ reporteSemanal.numeroSemana }}</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('reporte.index')" class="px-1">
                <h3>Reportes -</h3>
                </Link>
                <Link class="active">
                <h3>Reporte Semanal</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div v-for="reporte in reportes" :key="reporte.id" class="reporte">
                <div @click="collapsedReportReport(reporte.id)" class="departamento-header">
                    <h3 class="font-bold text-purple-700 text-xl">{{ reporte.departamento.nombre }}</h3>
                    <div v-if="reporte.departamento.deleted_at">
                        <p class="text-red-500">Este Flujo ha sido eliminado.</p>
                    </div>
                </div>
                <div v-if="!iscollapsedReport(reporte.id)" class="departamento-content">
                    <div class="gap-4 grid grid-cols-3">
                        <!-- Highlights Section -->
                        <div>
                            <h2 class="font-bold text-purple-700 text-xl">Plan a 30 Dias</h2>
                            <ul class="mt-2 pl-5">
                                <!-- <li v-for="treinta in reporte.treintas" :key="treinta.id"
                                    :class="getTerminada(treinta.status)">
                                    {{ treinta.meta }}
                                </li> -->
                                <li v-for="treinta in reporte.treintas" :key="treinta.id"
                                    :class="getTerminada(treinta.status)">
                                    <span v-if="treinta.status === 1" class="ml-2 pi pi-check"></span>
                                    {{ treinta.meta }}
                                </li>
                            </ul>
                            <div v-if="!reporte.treintas.length">
                                <p>No hay Metas a 30 dias disponibles.</p>
                            </div>
                        </div>

                        <!-- Low Lights Section -->
                        <div>
                            <h2 class="font-bold text-purple-700 text-xl">Plan a 60 Dias</h2>
                            <ul class="mt-2 pl-5">
                                <li v-for="sesenta in reporte.sesentas" :key="sesenta.id"
                                    :class="getTerminada(sesenta.status)">
                                    <span v-if="sesenta.status === 1" class="ml-2 pi pi-check"></span>
                                    {{ sesenta.meta }}
                                </li>
                            </ul>
                            <div v-if="!reporte.sesentas.length">
                                <p>No hay Metas a 60 dias disponibles.</p>
                            </div>
                        </div>

                        <div>
                            <h2 class="font-bold text-purple-700 text-xl">Plan a 90 Dias</h2>
                            <ul class="mt-2 pl-5">
                                <li v-for="noventa in reporte.noventas" :key="noventa.id"
                                    :class="getTerminada(noventa.status)">
                                    <span v-if="noventa.status === 1" class="ml-2 pi pi-check"></span>
                                    {{ noventa.meta }}
                                </li>
                            </ul>
                            <div v-if="!reporte.noventas.length">
                                <p>No hay Metas a 90 dias disponibles.</p>
                            </div>
                        </div>
                    </div>
                    <div class="gap-4 grid grid-cols-2">
                        <!-- Highlights Section -->
                        <div>
                            <h2 class="font-bold text-purple-700 text-xl">Destacados</h2>
                            <ul class="mt-2 pl-5">
                                <li v-for="highlight in reporte.highlights" :key="highlight.id">
                                    {{ highlight.light }}
                                </li>
                            </ul>
                            <div v-if="!reporte.highlights.length">
                                <p>No hay Highlight disponibles.</p>
                            </div>
                        </div>

                        <!-- Low Lights Section -->
                        <div>
                            <h2 class="font-bold text-purple-700 text-xl">Negativos</h2>
                            <ul class="mt-2 pl-5">
                                <li v-for="lowlight in reporte.lowlights" :key="lowlight.id">
                                    {{ lowlight.light }}
                                </li>
                            </ul>
                            <div v-if="!reporte.lowlights.length">
                                <p>No hay Lowlights disponibles.</p>
                            </div>
                        </div>
                    </div>
                    <!-- <div v-if="!reporte.highlight.length && !reporte.lowlight.length">
                        <p>No hay highlights ni lowlights disponibles.</p>
                    </div> -->

                    <!-- KPIs Section -->
                    <div class="gap-4 grid grid-cols-2 mt-8">

                        <div>
                            <h2 class="font-bold text-purple-700 text-xl">Acciones PDCA</h2>
                            <ul class="mt-2 pl-5">
                                <li v-for="actividad in reporte.actividades" :key="actividad.id">
                                    {{ actividad.titulo }}
                                </li>
                            </ul>
                            <div v-if="!reporte.actividades.length">
                                <p>No hay acciones disponibles.</p>
                            </div>
                        </div>
                        <div>
                            <h2 class="font-bold text-purple-700 text-xl">Avisos y Acciones de mejora</h2>
                            <ul class="mt-2 pl-5">
                                <li v-for="aviso in reporte.avisos" :key="aviso.id">
                                    {{ aviso.aviso }}
                                </li>
                            </ul>
                            <div v-if="!reporte.avisos.length">
                                <p>No hay Avisos disponibles.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Seccion de CAPAIA y ScoreCard -->
                    <div class="gap-4 grid grid-cols-1 mt-8">
                        <div>
                            <h2 class="font-bold text-purple-700 text-xl">Comprobacion de KPI's</h2>
                            <table v-for="kpis in reporte.kpis" :key="kpis.id" class="border-collapse mb-4 min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 border" colspan="4" style="border-color: black;">
                                            {{ kpis?.titulo }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-2 border" style="border-color: black;">Plan</td>
                                        <td class="px-4 py-2 border" style="border-color: black;">Hoy</td>
                                        <td class="px-4 py-2 border" style="border-color: black;">Promedio</td>
                                        <td class="px-4 py-2 border" style="border-color: black;">Tendencia</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 border" style="border-color: black;">
                                            {{ kpis?.objetivo }}
                                        </td>
                                        <td :class="getClass(kpis)" class="px-4 py-2 border"
                                            style="text-align-last: justify; border-color: black;">
                                            {{ formatNumber(kpis?.actual) }}
                                        </td>
                                        <td :class="getClass(kpis)" class="px-4 py-2 border"
                                            style="text-align-last: justify; border-color: black;">
                                            {{ formatNumber(kpis?.promedio) }}
                                        </td>

                                        <td class="px-4 py-2 border"
                                            style="text-align-last: justify; border-color: black;">
                                            <p :class="getTrend(kpis.promedio, kpis.objetivo, kpis.regla)"></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-if="!reporte.kpis.length">
                                <p>No hay Kpis disponibles.</p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </Layout>
</template>
