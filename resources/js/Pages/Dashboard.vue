<script setup>
import { ref, onMounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import Chart from 'primevue/chart';
import tablapilares from "@/Pages/utils/tablapilares.vue";
import Radar from "./Evaluacion/Chart/Radar.vue";
import Calendario from "@/Components/Calendario.vue";

onMounted(() => {
    getLastAssessment();
    getItem();
});

const props = defineProps({
    item: Object || null,
    objetivos: Object || null
});

const item = ref({});
const objetivos = ref({});
const banner_path = ref();
const proposito = ref();
const slogan = ref();
const actuacion = ref();
const template = ref('');
const selectedPilar = ref(null);
const lastAssessment = ref({});
const loading = ref(true);
const isCollapsed = ref(true);
/**
 * prop color suport 10 colors
 * color: gray, red, orange, yellow, green, teal, blue, indigo, purple, pink
 */
const attrs = ref([
    {
        key: 'hoy',
        highlight: {
            color: '#ff5733',
            fillMode: 'light',
        },
        dates: new Date(),
    },
]);
const eventos = ref([]);

onMounted(() => {
    getEventos();
})

const togglePanel = () => {
    isCollapsed.value = !isCollapsed.value;
};

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

const getLastAssessment = async () => {
    try {
        const response = await axios.get(route('evaluaciones.getUltimaEvaluacion'));
        lastAssessment.value = response.data;
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

const getEventos = async () => {
    try {
        const { data } = await axios.get(route('eventos.findAll'));
        // Organize events by date
        // const organizedEvents = {};
        // data.forEach(event => {
        //     const dateKey = event.fecha_inicio
        //     if (!organizedEvents[dateKey]) {
        //         organizedEvents[dateKey] = []
        //     }
        //     organizedEvents[dateKey].push(event)
        // });
        console.log({ eventos_res: data });

        eventos.value = data;

        console.log({ eventos: eventos.value });
    } catch (error) {
        console.error('Error fetching events:', error);
    }
}

</script>

<template>
    <Layout title="Home">

        <div class="flex px-2">
            <div>

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
                                                <h2 class="text-center py-4 font-bold text-3xl">Principios de actuación
                                                </h2>
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
                                    <!-- <br> -->
                                    <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-4 bg-gray-300 h-screen">
                                        <div class="bg-gray-300">
                                            <h2 class="text-center py-4 font-bold text-3xl">Autoevaluación</h2>
                                            <div v-if="!loading && lastAssessment">
                                                <Radar :evaluacion="lastAssessment" />
                                            </div>
                                            <div v-else>
                                                Loading...
                                            </div>
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
            </div>

            <div class="flex h-screen justify-end">
                <!-- Collapsible Panel -->
                <div :class="[
                    'transition-all duration-300 bg-gray-200 overflow-hidden',
                    isCollapsed ? 'w-16' : 'w-96'
                ]" class="h-full">
                    <div class="p-4 flex flex-col items-center">
                        <!-- Toggle Button -->
                        <button @click="togglePanel" :class="isCollapsed ? 'w-16' : 'w-96'" class="top-50 text-3xl"
                            v-tooltip.left="'Abre la sección de Eventos '">
                            <span class="pi pi-calendar text-slate-800 font-bold shadow"></span>
                        </button>

                        <!-- Panel Content -->
                        <div v-show="!isCollapsed" class="py-6 w-full flex justify-center ">
                            <VCalendar :attributes='attrs' />
                        </div>
                        <ul v-show="!isCollapsed" class="mt-2 text-sm list-disc">
                            <li v-for="evento in eventos">
                                {{ evento.titulo }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </Layout>
</template>
