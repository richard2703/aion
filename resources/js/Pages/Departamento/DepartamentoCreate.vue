<script setup>
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout2.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "../utils/SweetAlert.service";

const props = defineProps({
    areas: Array,
});

const areas = ref(props.areas);

async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => {
            areas.value = response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

const form = useForm({
    nombre: "",
    area_id: "",
    descripcion: "",
});

const submit = () => {
    form.post(route("departamento.store"), {
        onFinish: () => form.reset(),
    });
};

getAreas();
</script>

<template>
    <Layout>

        <Head title="Flujos de valor" />

        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Flujos de valor</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('departamento.index')" class="px-1">
                <h3>Flujos de valor -</h3>
                </Link>
                <Link :href="route('departamento.create')" class="active">
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
                                    <div>
                                        <InputLabel for="name" value="Nombre: " />
                                        <TextInput id="name" v-model="form.nombre" type="text" class="block mt-1 w-full"
                                            required autofocus autocomplete="name" />
                                    </div>

                                    <div>
                                        <InputLabel for="area_id" value="Pilar: " />
                                        <select ref="select"
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

                                    <div>
                                        <InputLabel for="descripcion" value="Descripcion:" />
                                        <TextInput id="descripcion" v-model="form.descripcion" type="text"
                                            class="block mt-1 w-full" autocomplete="descripcion" />
                                    </div>

                                    <div class="flex justify-end items-center col-span-full mt-4">
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
