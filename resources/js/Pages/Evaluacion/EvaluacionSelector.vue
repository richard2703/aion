<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Layout from '@/Layouts/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const props = defineProps({
    evaluacion: Object,

});

const title = "assessment";
const subTitle = "evaluaciones";
const incompleto = ref();
const completo = ref();

onMounted(() => {
    incompleto.value = 'p-6 w-full bg-yellow-200 border-b border-gray-200 hover:bg-gray-50 cursor-pointer block flex justify-between';
    completo.value = 'p-6 w-full bg-green-300 border-b border-gray-200-gray-50 block flex justify-between cursor-not-allowed';
})
const goToEvaluacion = (assessment_asignado) => {
    if (assessment_asignado.estatus === "INCOMPLETO") {
        window.location.href = route('evaluacion.form', assessment_asignado.id);
    }
}

</script>

<template>
    <Layout :titulo="title" :subTitulo="subTitle">

        <Head title="Assessment" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Evaluaciones</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link href="#" class="px-1">
                <h3>Assessment -</h3>
                </Link>
                <Link :href="route('evaluacion.index')" class="px-1">
                <h3>Evaluaciones -</h3>
                </Link>
                <Link href="#" class="active">
                <h3>Selector</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>

                    <div class="px-4 py-2 bg-white border-b border-gray-200">

                        <button v-for="assessmentAsignado in evaluacion.assessment_asignado"
                            @click="goToEvaluacion(assessmentAsignado)"
                            :class="[assessmentAsignado.estatus === 'INCOMPLETO' ? incompleto : completo]">
                            <span>{{ assessmentAsignado.seccion.departamento.nombre }}</span>
                            <span>{{ assessmentAsignado.estatus }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
