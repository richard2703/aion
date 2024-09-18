<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Layout from '@/Layouts/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import Chart from 'primevue/chart';
import { format } from 'date-fns';
import Bar from './Chart/Bar.vue';
import Radar from "./Chart/Radar.vue";
import Scatter from "./Chart/Scatter.vue";

const props = defineProps({
    evaluacion: Object,

});

const title = "assessment";
const subTitle = "evaluaciones";

onMounted(() => {
})
const formatearFecha = (dateString) => {
    return format(new Date(dateString), 'dd/MM/yyyy');
};
</script>

<template>
    <Layout :titulo="title" :subTitulo="subTitle">

        <Head title="Assessment" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>RESULTADOS DE AUTOEVALUACIÓN</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link href="#" class="px-1">
                <h3>Assessment -</h3>
                </Link>
                <Link href="#" class="px-1">
                <h3>Evaluaciones -</h3>
                </Link>
                <Link :href="route('evaluacion.index')" class="active">
                <h3>Benchmark</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{ evaluacion }}

                    <!-- R2 -->
                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-green-200 p-1 rounded">
                            Fecha: {{ formatearFecha(evaluacion.created_at) }}
                        </div>
                        <div class="bg-green-200 p-1 rounded flex">
                            <div class="px-1"> Score:</div>
                            <div class="px-1">algun porcentaje</div>
                            <div class="px-1"> [basico, intermedio, etc]</div>
                        </div>
                        <div class="bg-green-200 p-1 rounded">
                            <!-- SubGrid -->
                            <div class="grid grid-cols-3 gap-1">
                                <div>People</div>
                                <div>intermedio</div>
                                <div>20%</div>
                            </div>
                            <div class="grid grid-cols-3 gap-1">
                                <div>Process & Systems</div>
                                <div>Avansado</div>
                                <div>15%</div>
                            </div>
                        </div>

                    </div>

                    <!-- R3 -->
                    <div class="grid grid-cols-3 gap-2">
                        <div class="p-1">
                            <Radar />
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
                        <div class="p-1">
                            <Bar />
                        </div>
                        <div class="p-1">
                            <Scatter />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </Layout>
</template>
