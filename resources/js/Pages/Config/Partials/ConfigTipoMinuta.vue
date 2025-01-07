<script setup>
import { ref, onMounted, watch } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { usePage } from '@inertiajs/vue3';
import axios from "axios";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TipoMinutaList from "@/Pages/Config/Partials/TipoMinutaList.vue";
import TipoMinutaCreate from "@/Pages/Config/Partials/TipoMinutaCreate.vue";
import TipoMinutaEdit from "@/Pages/Config/Partials/TipoMinutaEdit.vue";
const accion = ref('list');
const tipoMinuta = ref();


const acciones = (opcion) => {
    if (opcion === 'create') {
        accion.value = 'create'
    }
    if (opcion === 'list') {
        accion.value = 'list'
    }
}

const editTipo = (id) => {

    axios.get(route("tipo-minuta.edit", id))
        .then((response) => {
            tipoMinuta.value = response.data;
            accion.value = 'edit';
        })
        .catch((error) => {
            console.log(error);
        });
};
</script>

<template>
    <div class="py-2">
        <div class="bg-white overflow-hidden">
            <div class="border-gray-200 bg-white px-4 py-2 border-b">
                <div class="mx-auto container">
                    <h2 class="text-2xl text-center">Tipo de minuta</h2>
                    <PrimaryButton v-if="accion === 'list'" class="float-right mb-4 pi pi-plus"
                        @click="acciones('create')">
                    </PrimaryButton>
                    <PrimaryButton v-if="accion !== 'list'" class="float-right pi-arrow-left mb-4 pi"
                        @click="acciones('list')">
                    </PrimaryButton>
                    <!-- <PrimaryButton v-if="accion === 'edit'" class="float-right mb-4 pi pi-plus" @click="">
                    </PrimaryButton>-->

                    <div class="mt-5 py-2">

                        <div v-if="accion === 'list'">
                            <TipoMinutaList @edit="(id) => editTipo(id)" />
                        </div>
                        <div v-if="accion === 'create'">
                            <TipoMinutaCreate @list="accion = 'list'" />
                        </div>
                        <div v-if="accion === 'edit'">
                            <TipoMinutaEdit :tipoMinuta="tipoMinuta" @list="accion = 'list'" />
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
