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
                <Link href="#" class="px-1">
                <h3>Evaluaciones -</h3>
                </Link>
                <Link :href="route('evaluacion.index')" class="active">
                <h3>Selector</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>

                    <div class="px-4 py-2 bg-white border-b border-gray-200">
                        <!--<div class="container mx-auto overflow-x-auto">
                    <InputText v-model="globalFilter" placeholder="Buscar..." class="mb-3" />
                    <DataTable :value="secciones" paginator :rows="rows" :totalRecords="totalRecords"
                        :lazy="true" :first="first" @page="onPage" @sort="onSort"
                        :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem" :filters="filters"
                        :globalFilterFields="[
                            'id',
                            'area.nombre',
                            'departamento.nombre',
                        ]" :sortField="sortField" :sortOrder="sortOrder"
                        class="p-datatable-sm p-datatable-striped p-datatable-gridlines">
                        <template #empty> No data found. </template>
<Column field="id" header="ID" headerStyle="width:4em;" bodyStyle="text-align:center;" sortable></Column>
<Column field="area.nombre" header="Pilar" headerStyle="width:4em;" bodyClass="text-center" sortable></Column>
<Column field="departamento.nombre" header="Flujo de valor" headerStyle="width:4em;" bodyStyle="text-align:center;"
    bodyClass="text-center" sortable></Column>
<Column header="" headerStyle="width:4em;">
    <template #body="slotProps" class="text-center">
                                <PrimaryButton class="m-2 pi pi-file-edit" :href="route(
                                    'seccion.edit',
                                    slotProps.data.id
                                )">

                                </PrimaryButton>

                                <PrimaryButton class="m-2 pi pi-trash" @click.prevent="
                                    deleteSeccion(slotProps.data.id)
                                    ">

                                </PrimaryButton>
                            </template>
</Column>
</DataTable>
</div>-->
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
