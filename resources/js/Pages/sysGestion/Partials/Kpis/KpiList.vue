<script setup>
import { onMounted, ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from 'axios';
import Modal from "@/Components/Modal.vue";
import Chart from 'primevue/chart';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "@/Pages/utils/SweetAlert.service";
import { format } from 'date-fns';

const props = defineProps({
    kpi: Object,
    updateFlag: Boolean
});

defineOptions({
    inheritAttrs: false,
});

// define emits event for parent
const emit = defineEmits(['updateKpi']);

const kpi = ref(props.kpi);
const updateFlag = ref(props.updateFlag);
const registrosKpi = ref([]);
const chartData = ref();
const chartOptions = ref();
const chartValues = ref();
const chartYearToDate = ref();
const chartTarget = ref();
const chartLabels = ref();
const isCreateModalVidible = ref(false);
const isEditModalVidible = ref(false);
const editedRegistro = ref();
const titulo = ref();
const viejo = ref();

const formCreateModal = useForm({
    actual: "",
    kpi_id: "",
});

const formEditModal = useForm({
    actual: "",
});


onMounted(() => {

    // for carts
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
    formatDataSet();
});

watch(() => [props.kpi, props.updateFlag],
    ([newKpi, newFlag]) => {
        kpi.value = newKpi;
        formatDataSet();
        getNewKpi(kpi.value.id);
    });

const getClass = (kpi) => {
    if (kpi.regla === 1) {
        return kpi.actual >= kpi.objetivo ? 'bg-green-100' : 'bg-red-100';
    } else {
        return kpi.actual <= kpi.objetivo ? 'bg-green-100' : 'bg-red-100';
    }
};

// const getClassPromedio = (kpi) => {
//     if (kpi.regla === 1) {
//         return kpi.promedio >= kpi.objetivo ? 'bg-green-100' : 'bg-gray-100';
//     } else {
//         return kpi.promedio <= kpi.objetivo ? 'bg-green-100' : 'bg-gray-100';
//     }
// };

/** chart */
const formatDataSet = async () => {
    await axios
        .get(route("registros_kpi.registros", kpi.value.id))
        .then((response) => {
            chartValues.value = response.data.map(record => record.actual);
            chartLabels.value = response.data.map(record => formatearFecha(record.created_at));
            registrosKpi.value = response.data;

            chartYearToDate.value = chartValues.value.map(() => 0);
            chartTarget.value = chartValues.value.map(() => kpi.value.objetivo);
            chartYearToDate.value[chartYearToDate.value.length - 1] = kpi.value.promedio;
        })
        .catch((error) => {
            console.log(error);
        });
}

const setChartData = () => {
    return {
        labels: chartLabels,
        datasets: [
            {
                // label: kpi.value.titulo,
                type: 'bar',
                label: 'kpi',
                data: chartValues,
                backgroundColor: ['rgba(115, 72, 207, 0.2)'],
                borderColor: ['rgb(74, 13, 204)'],
                borderWidth: 1
            },
            {
                type: 'bar',
                label: 'YTD',
                data: chartYearToDate,
                backgroundColor: ['rgba(145, 144, 147 , 0.7)'],
                borderColor: ['rgb(145, 144, 147 )'],
                borderWidth: 1
            },
            {
                type: 'line',
                label: 'Objetivo',
                data: chartTarget,
                backgroundColor: ['rgba(245, 11, 53, 0.2)'],
                borderColor: ['rgb(245, 11, 53)'],
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
            },
            // TODO: agregar tooltip personalizado (falta confirmar con director el contenido)
            tooltip: {
                enabled: true,
                callbacks: {
                    label: function (context) {
                        // Custom message for the tooltip
                        const label = context.dataset.label || '';
                        const value = context.raw;
                        return `${label}: ${value} (este es un mensaje personalizado para el tooltip)`;
                    }
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

/** END CHART */

/** Modal */
const openCreateModal = (kpi_id, Mviejo, Mtitulo) => {
    titulo.value = Mtitulo;
    viejo.value = Mviejo;
    formCreateModal.kpi_id = kpi_id
    isCreateModalVidible.value = true
}

const closeModal = () => {
    isCreateModalVidible.value = false;
    isEditModalVidible.value = false;
}

async function submitCreateModal() {
    closeModal();
    await axios
        .post(route("registros_kpi.store"), formCreateModal)
        .then((response) => {
            formCreateModal.actual = "";
            getNewKpi(kpi.value.id);
            emit('updateKpi');
            showToast("El registro ha sido creado", "success");
        })
        .catch((error) => {
            showToast(error.response.data.error, "error");
        });
}

/** END Modal */

async function getNewKpi(id) {
    await axios.get(route("kpis.byId", id)).then((response) => {
        kpi.value = response.data;
        // formatDataSet();
    })
}

function formatNumber(value) {
    if (value == null) return ''; // Manejar el caso cuando el valor es nulo o indefinido
    return parseFloat(value).toFixed(2);
}

const formatearFecha = (dateString) => {
    return format(new Date(dateString), 'dd/MM/yyyy');
};
</script>

<template>
    <div v-bind="$attrs">
        <div>
            <div class="gap-4 grid sm:grid-cols-2 lg:grid-cols-2">
                <div class="my-4">
                    <div class="flex justify-between">
                        <span class="font-bold text-2xl">Plan</span>
                        <div>
                            {{ formatNumber(kpi.promedio) || '' }}
                            <PrimaryButton class="bg-black pi pi-plus"
                                @click="openCreateModal(kpi.id, kpi.actual, kpi.titulo)">
                            </PrimaryButton>
                        </div>
                    </div>

                    <h2 class="px-4 py-2" colspan="3">
                        {{ kpi.titulo }}
                    </h2>

                    <div class="flex gap-20">
                        <div class="py-2">
                            <p>Hoy</p>
                            <p class="py-2">{{ kpi.objetivo || '-' }}</p>
                        </div>
                        <div class="py-2">
                            <p>Promedio</p>
                            <p :class="getClass(kpi)" class="px-4 py-2" style="text-align-last: justify;">{{
                                kpi.actual || '-' }}
                            </p>
                        </div>
                    </div>


                </div>

                <div class="overflow-x-auto card">
                    <div class="text-center">
                        <span class="font-bold text-2xl">Check</span>
                    </div>
                    <Chart class="w-full h-full" type="bar" :data="chartData" :options="chartOptions" />
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL to add value -->
    <Modal :show="isCreateModalVidible" maxWidth="lg">
        <template v-slot="">
            <div>
                <div class="flex justify-center border-gray-200 bg-white my-4 px-4 py-2 border-b">
                    <p class="font-medium text-gray-900 text-lg">{{ titulo }}</p>
                </div>
                <div class="border-gray-200 bg-white px-4 py-2 border-b">
                    <div class="mx-auto container">
                        <form @submit.prevent="submitCreateModal">
                            <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2">
                                <div class="my-4">
                                    <InputLabel for="Valor Actual" value="Valor Actual: " />
                                    <TextInput id="viejo" v-model="viejo" type="text" class="block mt-1 w-full"
                                        disabled />
                                </div>
                                <div class="my-4">
                                    <InputLabel for="Nuevo Valor" value="Nuevo Valor " />
                                    <TextInput id="objetivo" v-model="formCreateModal.actual" type="number" step="any"
                                        class="block mt-1 w-full" required autocomplete="new-challenge" />
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <PrimaryButton @click.prevent="closeModal" class="bg-red-500 ms-4 pi pi-times" :class="{
                                    'opacity-25': formCreateModal.abort,
                                }" :disabled="formCreateModal.abort">
                                </PrimaryButton>

                                <PrimaryButton class="ms-4 pi pi-save" :class="{
                                    'opacity-25': formCreateModal.processing,
                                }" :disabled="formCreateModal.processing">
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </Modal>
</template>
