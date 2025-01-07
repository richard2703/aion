<script setup>
import { ref, onMounted } from "vue";
import Chart from 'primevue/chart';

const props = defineProps({
    evaluacion: Object
})

const evaluacion = ref(props.evaluacion);
const results = ref({});
const chartData = ref();
const chartOptions = ref();
// const chartValues = ref();
const chartLabels = ref();
const aionX = ref();
const aionY = ref();

onMounted(() => {
    formatDataSet();
});

const formatDataSet = async () => {
    await axios
        .get(route('evaluaciones.scatterChart', evaluacion.value.id))
        .then((response) => {


            results.value = response.data;
            aionX.value = Number(formatNumber(results.value.promedioGente))
            aionY.value = Number(formatNumber(results.value.promedioProcess))
            // Seinicializa el grafico con los valores iniciales de la BD
            chartData.value = setChartData();
            chartOptions.value = setChartOptions();
        })
        .catch((error) => {
            console.log(error);
        });
}
const setChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: chartLabels,
        datasets: [
            {
                label: 'Aion',
                data: [
                    { x: aionX.value, y: aionY.value },


                ],
                backgroundColor: 'rgba(0, 0, 0, 1)',
                borderColor: 'rgba(0, 0, 0, 0.2)',
                borderWidth: 1
            },
            {
                label: 'Others',
                data: [
                    { x: 30, y: 78 },
                    { x: 40, y: 48 },
                    { x: 60, y: 38 },
                    { x: 70, y: 58 },
                    { x: 50, y: 48 }
                ],
                backgroundColor: 'rgba(83, 83, 229, 0.3)',
                borderColor: 'rgba(83, 83, 229, 1)',
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
        maintainAspectRatio: false,
        aspectRatio: 0.6,
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                min: 0,
                max: 100,
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            },
            y: {
                min: 0,
                max: 100,
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

function formatNumber(value) {
    if (value == null) return ''; // Manejar el caso cuando el valor es nulo o indefinido
    return parseFloat(value).toFixed(2);
}
</script>

<template>
    <div class="card">
        <Chart type="scatter" :data="chartData" :options="chartOptions" class="flex justify-center w-full h-96" />
    </div>
</template>
