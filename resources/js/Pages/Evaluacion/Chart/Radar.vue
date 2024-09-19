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
                borderColor: documentStyle.getPropertyValue('--p-gray-400'),
                pointBackgroundColor: documentStyle.getPropertyValue('--p-gray-400'),
                pointBorderColor: documentStyle.getPropertyValue('--p-gray-400'),
                pointHoverBackgroundColor: textColor,
                pointHoverBorderColor: documentStyle.getPropertyValue('--p-gray-400'),
                data: chartValues
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
                }
            }
        }
    };
}
</script>

<template>
    <div class="card">
        <Chart type="radar" :data="chartData" :options="chartOptions" class="w-full h-full" />
        <table class="table w-full border-collapse border border-slate-400  text-sm">
            <tr v-for="result in results">
                <td class="text-center border border-slate-500">{{ result.area.nombre }}</td>
                <td class="text-center border border-slate-500">{{ result.score }}%</td>
                <td v-if="result.score > 0 && result.score < 21" class="text-center border border-slate-500">INICIAL
                </td>
                <td v-if="result.score > 21 && result.score < 41" class="text-center border border-slate-500">BÁSICO
                </td>
                <td v-if="result.score > 41 && result.score < 61" class="text-center border border-slate-500">INTERMEDIO
                </td>
                <td v-if="result.score > 61 && result.score < 81" class="text-center border border-slate-500">AVANZADO
                </td>
                <td v-if="result.score > 81" class="text-center border border-slate-500">LÍDER</td>
            </tr>
        </table>
    </div>
</template>
