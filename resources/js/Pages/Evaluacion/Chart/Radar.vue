<script setup>
import { ref, onMounted } from "vue";
import Chart from 'primevue/chart';
import axios from "axios";

const props = defineProps({
    evaluacion: Object
})

const evaluacion = ref(props.evaluacion);
const results = ref({});
const chartData = ref();
const chartOptions = ref();
const chartValues = ref();
const chartLabels = ref();

onMounted(() => {
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
    formatDataSet();
});

const formatDataSet = async () => {
    await axios
        .get(route('evaluaciones.radarChart', evaluacion.value.id))
        .then((response) => {
            chartValues.value = response.data.map(record => record.score);
            chartLabels.value = response.data.map(record => record.area.nombre);
            results.value = response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

const setChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');

    return {
        labels: chartLabels,
        datasets: [
            {
                label: 'Resultados por Pilar',
                borderColor: documentStyle.getPropertyValue('--p-blue-400'),
                pointBackgroundColor: documentStyle.getPropertyValue('--p-blue-400'),
                pointBorderColor: documentStyle.getPropertyValue('--p-green-400'),
                pointHoverBackgroundColor: textColor,
                pointHoverBorderColor: documentStyle.getPropertyValue('--p-green-400'),
                data: chartValues
            },
            {
                label: 'Resultados por Pilar',
                fill: false,
                // borderColor: documentStyle.getPropertyValue('--p-gray-50'),
                // pointBackgroundColor: documentStyle.getPropertyValue('--p-gray-50'),
                // pointBorderColor: documentStyle.getPropertyValue('--p-gray-50'),
                // pointHoverBackgroundColor: textColor,
                // pointHoverBorderColor: documentStyle.getProspertyValue('--p-gray-50'),
                data: [100, 100, 100, 100, 100]
            },
        ]

    };
};
const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');

    return {
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            r: {
                grid: {
                    color: textColorSecondary
                },
                ticks: {
                    beginAtZero: true,
                    min: 1,
                    max: 100,
                    stepSize: 20,
                    suggestedMin: 50,
                    suggestedMax: 100
                }
            }
        }
    };
};

function getResultLabel(score) {
    if (score < 21) {
        return 'Inicial';
    } else if (score < 41) {
        return 'Básico';
    } else if (score < 61) {
        return 'Intermedio';
    } else if (score < 81) {
        return 'Avanzado';
    } else {
        return 'Líder';
    }
}

function getBackgroundColor(score) {
    if (score < 21) {
        return 'bg-black';
    } else if (score < 41) {
        return 'bg-gray-500';
    } else if (score < 61) {
        return 'bg-blue-500';
    } else if (score < 81) {
        return 'bg-green-500';
    } else {
        return 'bg-green-500';
    }
}

</script>

<template>
    <div class="card">
        <div>
            <Chart type="radar" :data="chartData" :options="chartOptions" class="flex h-[380px] radar" />
        </div>

        <div class="mb-3">
            <h2 class="mb-3 font-semibold text-gray-800 text-xl">Comparativo entre Evaluaciones</h2>
            <div class="space-y-3">
                <div v-for="result in results" class="flex items-center gap-3">
                    <div class="my-2 rounded-full w-7 h-7" :class="getBackgroundColor(result.score)"></div>
                    <span class="text-gray-700">{{ result.area.nombre }} ({{ result.score }}%) - {{ getResultLabel(result.score) }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
