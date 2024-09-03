<script setup>
import { onMounted, ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from 'axios';
import Modal from "@/Components/Modal.vue";
import Chart from 'primevue/chart';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";

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
    });

const getClass = (kpi) => {
    if (kpi.regla === 1) {
        return kpi.actual >= kpi.objetivo ? 'bg-green-100' : 'bg-red-100';
    } else {
        return kpi.actual <= kpi.objetivo ? 'bg-green-100' : 'bg-red-100';
    }
};

/** chart */
const formatDataSet = async () => {
    await axios
        .get(route("registros_kpi.registros", kpi.value.id))
        .then((response) => {
            chartValues.value = response.data.map(record => record.actual);
            chartLabels.value = response.data.map(record => record.mes);
            registrosKpi.value = response.data;
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
                label: kpi.value.titulo,
                data: chartValues,
                backgroundColor: ['rgba(115, 72, 207, 0.2)'],
                borderColor: ['rgb(74, 13, 204)'],
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
            console.log(error);
        });
}

/** END Modal */

async function getNewKpi(id) {
    await axios.get(route("kpis.byId", id)).then((response) => {
        kpi.value = response.data;
        formatDataSet();
    })
}

function formatNumber(value) {
    if (value == null) return ''; // Manejar el caso cuando el valor es nulo o indefinido
    return parseFloat(value).toFixed(2);
}
</script>

<template>
    <div v-bind="$attrs" class="m-4 p-4">
        <div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-2 gap-4">
                <div class="my-4">
                    <div class="text-center">
                        <span class="text-2xl font-bold">Plan</span>
                    </div>
                    <div class="rotation-table">
                        <!-- Tabla de Kpis to move-->
                        <table class="min-w-full border-collapse mb-4">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border" colspan="3">
                                        {{ kpi.titulo }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="py-2 px-4 border">Plan</td>
                                    <td class="py-2 px-4 border">Hoy</td>
                                    <td class="py-2 px-4 border">Promedio</td>
                                </tr>
                                <tr>

                                    <td class="py-2 px-4 border">{{ kpi.objetivo || '-' }}</td>
                                    <td class="py-2 px-4 border">{{ kpi.actual || '-' }}</td>
                                    <td :class="getClass(kpi)" class="py-2 px-4 border "
                                        style="text-align-last: justify;">
                                        {{ formatNumber(kpi.promedio) || '-' }}
                                        <PrimaryButton class="pi pi-plus"
                                            @click="openCreateModal(kpi.id, kpi.actual, kpi.titulo)">
                                        </PrimaryButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card overflow-x-auto">
                    <div class="text-center">
                        <span class="text-2xl font-bold">Check</span>
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
                <div class="px-4 my-4 py-2 flex justify-center bg-white border-b border-gray-200">
                    <p class=" text-lg font-medium text-gray-900">{{ titulo }}</p>
                </div>
                <div class="px-4 py-2 bg-white border-b border-gray-200">
                    <div class="container mx-auto">
                        <form @submit.prevent="submitCreateModal">
                            <div class=" grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                                <div class="my-4">
                                    <InputLabel for="Valor Actual" value="Valor Actual: " />
                                    <TextInput id="viejo" v-model="viejo" type="text" class="mt-1 block w-full"
                                        disabled />
                                </div>
                                <div class="my-4">
                                    <InputLabel for="Nuevo Valor" value="Nuevo Valor " />
                                    <TextInput id="objetivo" v-model="formCreateModal.actual" type="number" step="any"
                                        class="mt-1 block w-full" required autocomplete="new-challenge" />
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
