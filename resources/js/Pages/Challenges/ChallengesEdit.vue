<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout2.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "../utils/SweetAlert.service";

const props = defineProps({
    challenge: Object,
    areas: Array,
});

const challenge = ref(props.challenge);
const areas = ref(props.areas);
const challengeArea = challenge.value.area_id;
const secciones = ref([]);

async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}


const form = useForm({
    area_id: challenge.value.area_id,
    departamento_id: challenge.value.departamento_id,
    seccion_id: challenge.value.seccion_id,
    challenge: challenge.value.challenge,
});

const onAreaChange = async (event) => {
    const taget_id = event.target.value;
    await axios
        .get(route("secciones.byArea", taget_id))
        .then((response) => {
            secciones.value = response.data;
            console.log({ secciones: secciones.value });

        })
        .catch((error) => {
            console.log(error);
        });
};

const getSecciones = async (areaId) => {
    await axios
        .get(route("secciones.byArea", areaId))
        .then((response) => {
            secciones.value = response.data;
            console.log({ secciones: secciones.value });

        })
        .catch((error) => {
            console.log(error);
        });
};

const submit = () => {
    try {
        form.patch(route("challenge.update", challenge.value.id), {
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
    getSecciones(challengeArea);
})

</script>

<template>
    <Layout>

        <Head title="Challenges" />

        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Challenges</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('challenge.index')" class="px-1">
                <h3>Challenges -</h3>
                </Link>
                <Link :href="route('challenge.edit', challenge.id)" class="active">
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


                                <div class="mt-4">
                                    <InputLabel for="area_id" value="Pilar: " />
                                    <select ref="area_select" @change="onAreaChange($event)"
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
                                    <InputLabel for="seccion_select" value="Seccion: " />

                                    <select ref="seccion_select"
                                        class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                        v-model="form.seccion_id" required>
                                        <option value="" disabled selected>
                                            Seleccione una opcion
                                        </option>
                                        <option v-for="seccion in secciones" :key="seccion.id" :value="seccion.id">
                                            {{ seccion.titulo }}
                                        </option>
                                    </select>
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="challenge" value="challenge" />
                                    <TextInput id="challenge" v-model="form.challenge" type="text"
                                        class="block mt-1 w-full" required autocomplete="new-challenge" />
                                </div>

                                <div class="flex justify-end items-center mt-4">
                                    <PrimaryButton class="ms-4 pi pi-save" :class="{
                                        'opacity-25': form.processing,
                                    }" :disabled="form.processing">

                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
