<script setup>
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout2.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "../utils/SweetAlert.service";

const props = defineProps({
    departamentos: Array,
});

const departamentos = ref(props.departamentos);
const reportCards = ref([
    {
        title: "Highlight",
        items: [
            { id: 1, name: "Nombre de nuevo Highlight" },
            { id: 2, name: "Nombre de nuevo Highlight" },
            { id: 3, name: "Nombre de nuevo Highlight" },
            { id: 4, name: "Nombre de nuevo Highlight" },
        ],
    },
    {
        title: "Avisos",
        items: [
            { id: 1, name: "Aviso importante 1" },
            { id: 2, name: "Aviso importante 2" },
        ],
    },
    {
        title: "Lowlights",
        items: [
            { id: 1, name: "Problema 1" },
            { id: 2, name: "Problema 2" },
        ],
    },
]);

const addItem = (cardIndex) => {
    reportCards.value[cardIndex].items.push({ id: Date.now(), name: "Nuevo item" });
};

const removeItem = (cardIndex, itemIndex) => {
    reportCards.value[cardIndex].items.splice(itemIndex, 1);
};
</script>

<template>
    <Layout>

        <Head title="Usuarios" />
        <div class="pl-5 overflow-hidden">
            <div class="breadcrumbsTitulo">
                <h3 class="font-semibold text-xl">Reportes</h3>
            </div>

            <div class="flex items-center gap-2 breadcrumbs">
                <Link :href="route('reporte.index')" class="px-1">
                <h3>Lista de reportes</h3>
                </Link>
                <i class="pi-angle-right pi" style="font-size: 1rem"></i>
                <Link class="active">
                <b>Nuevo</b>
                </Link>
            </div>
        </div>

        <div class="p-5">
            <div class="overflow-hidden">
                <div class="mt-5">
                    <InputLabel for="departamento_id" value="Flujo de valor: " />
                    <select class="border-gray-300 focus:border-black rounded-md focus:ring-black w-80 cursor-pointer" required >
                        <option value="" disabled selected>
                            Seleccione un flujo de valor
                        </option>
                        <option>
                            nombre de flujo de valor
                        </option>
                    </select>
                </div>
                <div>
                    <form @submit.prevent="submit">
                        <div class="gap-8 grid grid-cols-6 grid-rows-5 mt-8">
                            <div v-for="(card, cardIndex) in reportCards" :key="cardIndex"
                                class="col-span-6 md:col-span-3">
                                <div class="border-gray-300 p-6 border border-solid rounded-md">
                                    <b>{{ card.title }}:</b>

                                    <div class="flex items-center gap-8 my-5">
                                        <input placeholder="Añadir nombre" type="text"
                                            class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full text-black sm:text-sm" />
                                        <PrimaryButton @click="addItem(cardIndex)" class="bg-black hover:bg-gray-800">
                                            <i class="pi pi-plus" style="font-size: 1rem"></i>
                                        </PrimaryButton>
                                    </div>

                                    <ul>
                                        <li class="h-48 min-h-48 overflow-scroll">
                                            <div v-for="(item, index) in card.items" :key="item.id"
                                                class="flex justify-between items-center my-6">
                                                <p class="text-lg">{{ item.name }}</p>
                                                <i @click="removeItem(cardIndex, index)"
                                                    class="hover:bg-gray-200 p-3 hover:rounded-lg cursor-pointer pi pi-trash"
                                                    style="color: red; font-size: 1.3rem"></i>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Layout>
</template>
