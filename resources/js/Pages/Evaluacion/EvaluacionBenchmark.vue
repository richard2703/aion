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

function formatNumber(value) {
    if (value == null) return ''; // Manejar el caso cuando el valor es nulo o indefinido
    return parseFloat(value).toFixed(2);
}
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

                    <!-- R2 -->
                    <div class="grid grid-cols-3 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3  gap-2">
                        <div class="bg-slate-500 p-1 rounded content-center">
                            Fecha: {{ formatearFecha(evaluacion.created_at) }}
                        </div>
                        <div class="bg-white p-1 rounded flex">
                            <div class="px-1 bg-slate-500 font-bold rounded content-center"> Score:</div>
                            <div class="px-1 content-center">{{ formatNumber(evaluacion.global) }} %</div>
                            <div v-if="evaluacion.global > 0 && evaluacion.global < 21" class="px-1 content-center">
                                INICIAL
                            </div>
                            <div v-if="evaluacion.global > 21 && evaluacion.global < 41" class="px-1 content-center">
                                BÁSICO
                            </div>
                            <div v-if="evaluacion.global > 41 && evaluacion.global < 61" class="px-1 content-center">
                                INTERMEDIO
                            </div>
                            <div v-if="evaluacion.global > 61 && evaluacion.global < 81" class="px-1 content-center">
                                AVANZADO
                            </div>
                            <div v-if="evaluacion.global > 81" class="px-1 content-center">LÍDER</div>
                        </div>
                        <div class="bg-white p-1 rounded">
                            <!-- SubGrid -->
                            <div class="grid grid-cols-3 gap-1">
                                <div class="bg-slate-500 content-center">People</div>
                                <div v-if="evaluacion.gente > 0 && evaluacion.gente < 21" class="content-center">
                                    INICIAL
                                </div>
                                <div v-if="evaluacion.gente > 21 && evaluacion.gente < 41" class="content-center">
                                    BÁSICO
                                </div>
                                <div v-if="evaluacion.gente > 41 && evaluacion.gente < 61" class="content-center">
                                    INTERMEDIO
                                </div>
                                <div v-if="evaluacion.gente > 61 && evaluacion.gente < 81" class="content-center">
                                    AVANZADO
                                </div>
                                <div v-if="evaluacion.gente > 81" class="content-center">LÍDER</div>
                                <div class="content-center">{{ formatNumber(evaluacion.gente) }}%</div>
                            </div>
                            <div class="grid grid-cols-3 gap-1">
                                <div class="bg-slate-500 content-center">Process & Systems</div>
                                <div v-if="evaluacion.proceso > 0 && evaluacion.proceso < 21" class="content-center">
                                    INICIAL
                                </div>
                                <div v-if="evaluacion.proceso > 21 && evaluacion.proceso < 41" class="content-center">
                                    BÁSICO
                                </div>
                                <div v-if="evaluacion.proceso > 41 && evaluacion.proceso < 61" class="content-center">
                                    INTERMEDIO
                                </div>
                                <div v-if="evaluacion.proceso > 61 && evaluacion.proceso < 81" class="content-center">
                                    AVANZADO
                                </div>
                                <div v-if="evaluacion.proceso > 81" class="content-center">LÍDER</div>
                                <div class="content-center">{{ formatNumber(evaluacion.proceso) }}%</div>
                            </div>
                        </div>

                    </div>

                    <!-- R3 -->
                    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3  gap-2">
                        <div class="">
                            <Radar :evaluacion="evaluacion" />
                        </div>
                        <div class="">
                            <Bar :evaluacion="evaluacion" />
                        </div>
                        <div class="">
                            <Scatter :evaluacion="evaluacion" />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </Layout>
</template>
