<script setup>
import { ref, onMounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import Chart from 'primevue/chart';


onMounted(() => {
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
    getItem();
});

const props = defineProps({
    item: Object || null,
});

const chartData = ref();
const chartOptions = ref();
const item = ref({});
const banner_path = ref();
const proposito = ref();

const setChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');

    return {
        labels: ['GyC', 'MyV', 'Ops', 'IT', 'Admon'],
        datasets: [
            {
                label: 'Resultados por Pilar',
                borderColor: documentStyle.getPropertyValue('--p-gray-400'),
                pointBackgroundColor: documentStyle.getPropertyValue('--p-gray-400'),
                pointBorderColor: documentStyle.getPropertyValue('--p-gray-400'),
                pointHoverBackgroundColor: textColor,
                pointHoverBorderColor: documentStyle.getPropertyValue('--p-gray-400'),
                data: [80, 80, 90, 81, 67]
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

const getItem = () => {
    axios
        .get("/api/config-dashboard")
        .then((response) => {
            item.value = response.data;
            proposito.value = item.value.proposito;
            banner_path.value = item.value.banner_path;
            // Set other form fields here as needed
        })
        .catch((error) => {
            console.error('Error fetching item:', error);
        });
};


</script>

<template>
    <Layout title="Home">

        <Head title="Usuarios" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Home</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    <div class="px-4 my-4 py-2 flex justify-end bg-white border-b border-gray-200"></div>
                    <div class="px-4 py-2 bg-white border-b border-gray-200">
                        <div class="container mx-auto">
                            <div class="grid sm:grid-cols-1 md:grid-cols-2 bg-gray-300">
                                <div class="bg-gray-300 h-96">
                                    <img class="h-96" :src="banner_path" alt="Banner actual" srcset="">
                                </div>
                                <div class="bg-gray-300 ">
                                    <h2 class="text-center font-bold text-2xl">Propósito</h2>
                                    <p class="italic m-4 text-lg">{{ proposito }}</p>
                                </div>
                            </div>
                            <br>
                            <div class="grid  grid-cols-1 bg-gray-300">
                                <div class="bg-gray-300 ">
                                    <h2 class="text-center font-bold text-2xl">Metromap</h2>
                                    <img class="w-full" src="../../img/metromaps/metromap.png" alt="">

                                </div>
                            </div>
                            <br>
                            <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-4 bg-gray-300">
                                <div class="bg-gray-300 ">
                                    <h2 class="text-center font-bold text-2xl">Autoevaluación</h2>
                                    <Chart type="radar" :data="chartData" :options="chartOptions" class="" />
                                </div>
                                <div class="bg-gray-300 ">
                                    <h2 class="text-center font-bold text-2xl">Objetivos</h2>
                                    <ul>
                                        <li>Objetivo 1</li>
                                        <li>Objetivo 2</li>
                                        <li>Objetivo 3</li>
                                        <li>Objetivo 4</li>
                                        <li>Objetivo 5</li>
                                    </ul>
                                </div>
                                <br>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
