<script setup>
import { ref, onMounted } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout2.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "../utils/SweetAlert.service";

const props = defineProps({
    departamentos: Array,
    reporte: Object,
});
const newAviso = ref("");
const newHighlight = ref("");
const newLowLight = ref("");

const departamentos = ref(props.departamentos);

async function getDepartamentos() {
    await axios
        .get("/api/getFlujo")
        .then((response) => {
            departamentos.value = response.data;
            console.log({ departamentos: departamentos.value });
        })
        .catch((error) => {
            console.log(error);
        });
}
// Pre-fill avisos, highlights, lowlights with the values from the report if available
const avisos = ref(
    props.reporte.avisos && props.reporte.avisos.length
        ? props.reporte.avisos.map(a => ({ value: a.aviso }))
        : [{ value: "" }]
);

const highlights = ref(
    props.reporte.highlights && props.reporte.highlights.length
        ? props.reporte.highlights.map(h => ({ value: h.light }))
        : [{ value: "" }]
);

const lowlights = ref(
    props.reporte.lowlights && props.reporte.lowlights.length
        ? props.reporte.lowlights.map(l => ({ value: l.light }))
        : [{ value: "" }]
);

const form = useForm({
    departamento_id: props.reporte.departamento_id || "",
    avisos: [],
    highlights: [],
    lowlights: [],
});

const submit = () => {
    // Assign the avisos, highlights, and lowlights to the form
    form.avisos = avisos.value.map(a => a.value);
    form.highlights = highlights.value.map(h => h.value);
    form.lowlights = lowlights.value.map(l => l.value);

    form.patch(route("misreporte.update", props.reporte.id), {
        onError: (errors) => {
            console.log(errors);
            showToast(errors.message, "error");
        },
        onFinish: () => form.reset(),
    });
};

// Functions for adding/removing dynamic fields
const addAviso = () => {
    if (!newAviso.value || newAviso.value.trim() != "") {
        avisos.value.push({ value: newAviso.value.trim() });
        newAviso.value = ""
    }
};

const addHighlight = () => {
    if (!newHighlight.value || newHighlight.value.trim() != "") {
        highlights.value.push({ value: newHighlight.value.trim() });
        newHighlight.value = "";
    }
};

const addLowlight = () => {
    if (!newLowLight.value || newLowLight.value.trim() != "") {
        lowlights.value.push({ value: newLowLight.value.trim() });
        newLowLight.value = "";
    }
};

const removeHighlight = (index) => highlights.value.splice(index, 1);
const removeAviso = (index) => avisos.value.splice(index, 1);
const removeLowlight = (index) => lowlights.value.splice(index, 1);

onMounted(() => {
    // console.log('reportes', reportes.value);
    getDepartamentos();

});
</script>

<template>
    <Layout>

        <Head title="Usuarios" />
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Editar Reporte</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('reporte.index')" class="px-1">
                <h3>Reportes -</h3>
                </Link>
                <Link class="active">
                <h3>Editar</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden">
                <div>
                    <div class="flex justify-end border-gray-200 bg-white my-4 px-4 py-2 border-b"></div>
                    <div class="border-gray-200 bg-white px-4 py-2 border-b">
                        <div class="mx-auto container">
                            <form @submit.prevent="submit">
                                <div class="gap-8 grid grid-cols-6 mt-8">
                                    <div class="col-span-6 md:col-span-3">
                                        <div class="border-gray-300 p-6 border border-solid rounded-md">
                                            <b>Highlights:</b>

                                            <div class="flex items-center gap-8 my-5">
                                                <TextInput v-model="newHighlight" placeholder="Añadir nombre"
                                                    type="text"
                                                    class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full text-black sm:text-sm" />
                                                <PrimaryButton type="button" @click="addHighlight"
                                                    class="bg-black hover:bg-gray-800">
                                                    <i class="pi pi-plus" style="font-size: 1rem"></i>
                                                </PrimaryButton>
                                            </div>

                                            <ul>
                                                <li class="h-48 min-h-48 overflow-scroll">
                                                    <div v-for="(highlight, index) in highlights" :key="index"
                                                        class="flex justify-between items-center my-6">
                                                        <TextInput v-model="highlight.value" placeholder="Añadir nombre"
                                                        type="text"
                                                        class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full text-black sm:text-sm" />
                                                        <i @click="removeHighlight(index)"
                                                            class="hover:bg-gray-200 p-3 hover:rounded-lg cursor-pointer pi pi-trash"
                                                            style="color: red; font-size: 1.3rem"></i>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-span-6 md:col-span-3">
                                        <div class="border-gray-300 p-6 border border-solid rounded-md">
                                            <b>Lowlights:</b>

                                            <div class="flex items-center gap-8 my-5">
                                                <TextInput v-model="newLowLight" placeholder="Añadir nombre" type="text"
                                                    class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full text-black sm:text-sm" />
                                                <PrimaryButton type="button" @click="addLowlight"
                                                    class="bg-black hover:bg-gray-800">
                                                    <i class="pi pi-plus" style="font-size: 1rem"></i>
                                                </PrimaryButton>
                                            </div>

                                            <ul>
                                                <li class="h-48 min-h-48 overflow-scroll">
                                                    <div v-for="(lowlight, index) in lowlights" :key="index"
                                                        class="flex justify-between items-center my-6">
                                                        <TextInput v-model="lowlight.value" placeholder="Añadir nombre"
                                                        type="text"
                                                        class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full text-black sm:text-sm" />
                                                        <i @click="removeLowlight(index)"
                                                            class="hover:bg-gray-200 p-3 hover:rounded-lg cursor-pointer pi pi-trash"
                                                            style="color: red; font-size: 1.3rem"></i>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-span-6 md:col-span-3">
                                        <div class="border-gray-300 p-6 border border-solid rounded-md">
                                            <b>Avisos:</b>

                                            <div class="flex items-center gap-8 my-5">
                                                <TextInput v-model="newAviso" placeholder="Añadir nombre" type="text"
                                                    class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full text-black sm:text-sm" />
                                                <PrimaryButton type="button" @click="addAviso"
                                                    class="bg-black hover:bg-gray-800">
                                                    <i class="pi pi-plus" style="font-size: 1rem"></i>
                                                </PrimaryButton>
                                            </div>

                                            <ul>
                                                <li class="h-48 min-h-48 overflow-scroll">
                                                    <div v-for="(aviso, index) in avisos" :key="index"
                                                        class="flex justify-between items-center my-6">
                                                        <TextInput v-model="aviso.value" placeholder="Añadir nombre"
                                                        type="text"
                                                        class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full text-black sm:text-sm" />
                                                        <i @click="removeAviso(index)"
                                                            class="hover:bg-gray-200 p-3 hover:rounded-lg cursor-pointer pi pi-trash"
                                                            style="color: red; font-size: 1.3rem"></i>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <PrimaryButton type="submit" @click="addLowlight"
                                    class="bg-black hover:bg-gray-800 mt-5">
                                    Guardar
                                </PrimaryButton>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
