<script setup>
import { ref, onMounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import Chart from 'primevue/chart';
import tablapilares from "@/Pages/utils/tablapilares.vue";

onMounted(() => {
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
    getItem();
});

const props = defineProps({
    item: Object || null,
    objetivos: Object || null
});

const chartData = ref();
const chartOptions = ref();
const item = ref({});
const objetivos = ref({});
const banner_path = ref();
const proposito = ref();
const slogan = ref();
const actuacion = ref();
const template = ref('');
const selectedPilar = ref(null);

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
            objetivos.value = item.value[1];
            proposito.value = item.value[0].proposito;
            slogan.value = item.value[0].slogan;
            actuacion.value = item.value[0].actuacion;
            banner_path.value = item.value[0].banner_path;
            // Set other form fields here as needed
        })
        .catch((error) => {
            console.error('Error fetching item:', error);
        });
};

const getPilar = async (pilar) => {
    if (template.value === 'open' && selectedPilar.value === pilar) {
        template.value = 'close';
        return;
    }
    template.value = 'open';
    selectedPilar.value = pilar;

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
                                <div class="bg-gray-300">
                                    <img class="h-96" :src="banner_path" alt="Banner actual" srcset="">
                                    <div>
                                        <p class="italic m-4 text-lg">{{ slogan }}</p>
                                    </div>
                                </div>
                                <div class="bg-gray-300 ">
                                    <div>
                                        <h2 class="text-center py-4 font-bold text-3xl">Propósito</h2>
                                        <p class="italic m-4 text-lg">{{ proposito }}</p>
                                    </div>
                                    <!-- <div>
                                        <h2 class="text-center py-4 font-bold text-3xl">Slogan</h2>
                                        <p class="italic m-4 text-lg">{{ slogan }}</p>
                                    </div> -->
                                    <div>
                                        <h2 class="text-center py-4 font-bold text-3xl">Principios de actuación</h2>
                                        <p class="italic m-4 text-lg">{{ actuacion }}</p>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="grid  grid-cols-1  bg-gray-300">
                                <div class="bg-gray-300 ">
                                    <h2 class="text-center py-4 font-bold text-3xl">Metromap</h2>
                                </div>
                            </div>
                            <div class="grid  grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 bg-gray-300">
                                <div class="bg-gray-300 ">
                                    <!-- <h2 class="text-center py-4 font-bold text-3xl">Metromap</h2> -->
                                    <button @click="getPilar(1)">
                                        <img class="w-full" src="../../img/metromaps/gyc.jpg" alt="">
                                    </button>
                                </div>
                                <div class="bg-gray-300 ">
                                    <button @click="getPilar(2)">
                                        <img class="w-full" src="../../img/metromaps/ti.jpg" alt="">
                                    </button>
                                </div>
                                <div class="bg-gray-300 ">
                                    <button @click="getPilar(3)">
                                        <img class="w-full" src="../../img/metromaps/ventas.jpg" alt="">
                                    </button>
                                </div>
                                <div class="bg-gray-300 ">
                                    <button @click="getPilar(4)">
                                        <img class="w-full" src="../../img/metromaps/ops.jpg" alt="">
                                    </button>
                                </div>
                                <div class="bg-gray-300 ">
                                    <button @click="getPilar(5)">
                                        <img class="w-full" src="../../img/metromaps/admin.jpg" alt="">
                                    </button>
                                </div>
                            </div>

                            <tablapilares :pilar="selectedPilar" v-if="template === 'open'" />

                            <!-- <div v-if="template === 'open'"
                                class="grid sm:grid-cols-1 md:grid-cols-2 gap-4 bg-gray-300 my-8">
                                <div class="bg-gray-300">
                                    <h2 class="text-center py-4 font-bold text-3xl">Autoevaluación</h2>
                                    <Chart type="radar" :data="chartData" :options="chartOptions" class="h-96 m-4" />
                                    <table class="w-full table-auto border-collapse border border-slate-400 m-4">
                                        <tr>
                                            <td class="text-center border border-slate-500">GyC</td>
                                            <td class="text-center border border-slate-500 bg-yellow-400">80 %</td>
                                            <td class="text-center border border-slate-500">Intermediate</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center border border-slate-500">MyV</td>
                                            <td class="text-center border border-slate-500 bg-yellow-400">80 %</td>
                                            <td class="text-center border border-slate-500">Intermediate</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center border border-slate-500">Ops</td>
                                            <td class="text-center border border-slate-500 bg-green-400">90 %</td>
                                            <td class="text-center border border-slate-500">Mature</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center border border-slate-500">IT</td>
                                            <td class="text-center border border-slate-500 bg-yellow-400">81 %</td>
                                            <td class="text-center border border-slate-500">Intermediate</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center border border-slate-500">Admon</td>
                                            <td class="text-center border border-slate-500 bg-red-400">67 %</td>
                                            <td class="text-center border border-slate-500">Basic</td>
                                        </tr>

                                    </table>
                                </div>
                                <div class="bg-gray-300 ">
                                    <h2 class="text-center py-4 font-bold text-3xl">Objetivos</h2>
                                    <ul>
                                        <li v-for="objetivo in objetivos"
                                            class="m-4 py-2 text-lg list-disc list-inside">{{
                                                objetivo.objetivo }}
                                        </li>
                                    </ul>
                                </div>
                                <br>

                            </div> -->
                            <!-- <br> -->
                            <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-4 bg-gray-300">
                                <div class="bg-gray-300">
                                    <h2 class="text-center py-4 font-bold text-3xl">Autoevaluación</h2>
                                    <Chart type="radar" :data="chartData" :options="chartOptions" class="h-96 m-4" />
                                    <table class="w-full table-auto border-collapse border border-slate-400 m-4">
                                        <tr>
                                            <td class="text-center border border-slate-500">GyC</td>
                                            <td class="text-center border border-slate-500 bg-yellow-400">80 %</td>
                                            <td class="text-center border border-slate-500">Intermediate</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center border border-slate-500">MyV</td>
                                            <td class="text-center border border-slate-500 bg-yellow-400">80 %</td>
                                            <td class="text-center border border-slate-500">Intermediate</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center border border-slate-500">Ops</td>
                                            <td class="text-center border border-slate-500 bg-green-400">90 %</td>
                                            <td class="text-center border border-slate-500">Mature</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center border border-slate-500">IT</td>
                                            <td class="text-center border border-slate-500 bg-yellow-400">81 %</td>
                                            <td class="text-center border border-slate-500">Intermediate</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center border border-slate-500">Admon</td>
                                            <td class="text-center border border-slate-500 bg-red-400">67 %</td>
                                            <td class="text-center border border-slate-500">Basic</td>
                                        </tr>

                                    </table>
                                </div>
                                <div class="bg-gray-300 ">
                                    <h2 class="text-center py-4 font-bold text-3xl">Objetivos</h2>
                                    <ul>
                                        <li v-for="objetivo in objetivos"
                                            class="m-4 py-2 text-lg list-disc list-inside">{{
                                                objetivo.objetivo }}
                                        </li>
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
