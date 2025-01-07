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
    metaFlujo: Object,
});

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

// Pre-fill fields with values from the report if available
const treintas = ref(
    props.metaFlujo.treintas && props.metaFlujo.treintas.length
        ? props.metaFlujo.treintas.map(a => ({ value: a.meta, status: Boolean(a.status) }))
        : [{ value: "", status: false }]
);

const sesentas = ref(
    props.metaFlujo.sesentas && props.metaFlujo.sesentas.length
        ? props.metaFlujo.sesentas.map(h => ({ value: h.meta, status: Boolean(h.status) }))
        : [{ value: "", status: false }]
);

const noventas = ref(
    props.metaFlujo.noventas && props.metaFlujo.noventas.length
        ? props.metaFlujo.noventas.map(l => ({ value: l.meta, status: Boolean(l.status) }))
        : [{ value: "", status: false }]
);

const form = useForm({
    departamento_id: props.metaFlujo.departamento_id || "",
    treintas: [],
    treintasStatus: [],
    sesentas: [],
    sesentasStatus: [],
    noventas: [],
    noventasStatus: [],
});

const submit = () => {
    form.treintas = treintas.value.map(a => a.value);
    form.treintasStatus = treintas.value.map(a => a.status);
    form.sesentas = sesentas.value.map(h => h.value);
    form.sesentasStatus = sesentas.value.map(h => h.status);
    form.noventas = noventas.value.map(l => l.value);
    form.noventasStatus = noventas.value.map(l => l.status);

    form.patch(route("metas.update", props.metaFlujo.id), {
        onError: (errors) => {
            console.log(errors);
            showToast(errors.message, "error");
        },
        onFinish: () => form.reset(),
    });
};

// Functions for adding/removing dynamic fields
const addtreintas = () => treintas.value.push({ value: "", status: false });
const removetreintas = (index) => treintas.value.splice(index, 1);

const addsesentas = () => sesentas.value.push({ value: "", status: false });
const removesesentas = (index) => sesentas.value.splice(index, 1);

const addnoventas = () => noventas.value.push({ value: "", status: false });
const removenoventas = (index) => noventas.value.splice(index, 1);

onMounted(() => {
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
                <div class="flex justify-end border-gray-200 bg-white my-4 px-4 py-2 border-b"></div>
                <div class="border-gray-200 bg-white px-4 py-2 border-b">
                    <div class="mx-auto container">
                        <form @submit.prevent="submit">
                            <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2">
                                <div>
                                    <InputLabel for="area_id" value="Flujo de valor: " />
                                    <select ref="select"
                                        class="border-gray-300 focus:border-indigo-500 shadow-sm mt-1 px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                        v-model="form.departamento_id" required>
                                        <option value="" disabled selected>Seleccione una opción</option>
                                        <option v-for="departamento in departamentos" :key="departamento.id"
                                            :value="departamento.departamento.id">
                                            {{ departamento.departamento.nombre }}
                                        </option>
                                    </select>
                                </div>

                                <!-- Campos dinámicos para Highlight -->
                                <div class="flex items-center col-span-full mt-4">
                                    <InputLabel for="Meta a 30 Dias" value="Metas a 30 Dias:" />
                                </div>
                                <div v-for="(treinta, index) in treintas" :key="index"
                                    class="flex justify-between items-center col-span-full">
                                    <input type="checkbox" v-model="treinta.status" /> Terminada
                                    <TextInput v-model="treinta.value" type="text" class="block mt-1 w-full"
                                        autocomplete="treinta" maxlength="250" />
                                    <button type="button" @click="removetreintas(index)"
                                        class="ml-2 text-red-500">Eliminar</button>
                                </div>
                                <button type="button" @click="addtreintas" class="mt-2 text-blue-500">Agregar
                                    item</button>

                                <!-- Campos dinámicos para Lowlight -->
                                <div class="flex items-center col-span-full mt-4">
                                    <InputLabel for="60" value="Metas a 60 Dias:" />
                                </div>
                                <div v-for="(sesenta, index) in sesentas" :key="index"
                                    class="flex justify-between items-center col-span-full">
                                    <input type="checkbox" v-model="sesenta.status" /> Terminada
                                    <TextInput v-model="sesenta.value" type="text" class="block mt-1 w-full"
                                        autocomplete="sesenta" maxlength="250" />
                                    <button type="button" @click="removesesentas(index)"
                                        class="ml-2 text-red-500">Eliminar</button>
                                </div>
                                <button type="button" @click="addsesentas" class="mt-2 text-blue-500">Añadir
                                    Item</button>

                                <!-- Campos dinámicos para 90 -->
                                <div class="flex items-center col-span-full mt-4">
                                    <InputLabel for="Meta a 90 Dias:" value="Metas a 90 Dias:" />
                                </div>
                                <div v-for="(noventa, index) in noventas" :key="index"
                                    class="flex justify-between items-center col-span-full">
                                    <input type="checkbox" v-model="noventa.status" /> Terminada
                                    <TextInput v-model="noventa.value" type="text" class="block mt-1 w-full"
                                        autocomplete="noventas" />
                                    <button type="button" @click="removenoventas(index)"
                                        class="ml-2 text-red-500">Eliminar</button>
                                </div>
                                <button type="button" @click="addnoventas" class="mt-2 text-blue-500">Añadir
                                    Item</button>

                                <div class="flex justify-end items-center col-span-full mt-4">
                                    <PrimaryButton class="ms-4 pi pi-save" :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing">
                                    </PrimaryButton>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
