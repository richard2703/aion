<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout2.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { showToast } from "../utils/SweetAlert.service";
import Textarea from 'primevue/textarea';


const props = defineProps({
    opcion: Object,
    areas: Array,
    departamentos: Array || null,
    challenges: Array || null,
});

const opcion = ref(props.opcion);
const areas = ref({});
const challenges = ref(props.challenges);

async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}

async function getChallenges(area_id) {
    await axios
        .get(route("challenges.byArea", area_id))
        .then((response) => (challenges.value = response.data.challenges))
        .catch((error) => {
            console.log(error);
        });
}

const form = useForm({
    area_id: opcion.value.challenge.area_id,
    departamento_id: opcion.value.departamento_id,
    challenge_id: opcion.value.challenge_id,
    madurez: opcion.value.madurez,
    formal: opcion.value.formal,
    informal: opcion.value.informal,
});

const onChangeArea = async (event) => {
    const taget_id = event.target.value;
    await axios
        .get(route("challenges.byArea", taget_id))
        .then((response) => (challenges.value = response.data.challenges))
        .catch((error) => {
            console.log(error);
        });
};

const submit = () => {
    try {
        form.patch(route("opcion.update", opcion.value.id), {
            onFinish: () => {
                showToast("El registro ha sido creado", "success");
                form.reset();
            },
        });

    } catch (error) {
        showToast("Ocurrio un error", "error");
        console.error(error);
    }
};

onMounted(() => {
    getAreas();
    getChallenges(opcion.value.challenge.area_id);
})

</script>

<template>
    <Layout>

        <Head title="Opciones" />

        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Opciones</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('opcion.index')" class="px-1">
                <h3>Opciones -</h3>
                </Link>
                <Link :href="route('opcion.create')" class="active">
                <h3>Nuevo</h3>
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

                                <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2">

                                    <div class="mt-4">
                                        <InputLabel for="area_id" value="Area: " />
                                        <select ref="area_select" @change="onChangeArea($event)"
                                            class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                            v-model="form.area_id" required>
                                            <option value="" disabled selected>
                                                Seleccione una opcion
                                            </option>
                                            <option v-for="area in areas" :key="area.id" :value="area.id">
                                                {{ area.nombre }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="challenge_id" value="Challenge: " />

                                        <select ref="challenge_select"
                                            class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                            v-model="form.challenge_id" required>
                                            <option value="" disabled selected>
                                                Seleccione una opcion
                                            </option>
                                            <option v-for="challenge in challenges" :key="challenge.id"
                                                :value="challenge.id">
                                                {{ challenge.challenge }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="madurez" value="Madurez: " />

                                        <select ref="madurez_select"
                                            class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                            v-model="form.madurez" required>
                                            <option value="" disabled selected>
                                                Seleccione una opcion
                                            </option>
                                            <option value="Nulo">Nulo</option>
                                            <option value="Basico">Basico</option>
                                            <option value="Maduro">Maduro</option>
                                            <option value="Avanzado">Avanzado</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2">
                                    <div class="mt-4">
                                        <InputLabel for="formal" value="Formal: " />
                                        <Textarea v-model="form.formal" rows="5" cols="30" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="informal" value="Informal: " />
                                        <Textarea v-model="form.informal" rows="5" cols="30" />
                                    </div>

                                    <div class="flex justify-end items-center mt-4">
                                        <PrimaryButton class="ms-4 pi pi-save" :class="{
                                            'opacity-25': form.processing,
                                        }" :disabled="form.processing">

                                        </PrimaryButton>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
