<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout2.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { showToast } from "../utils/SweetAlert.service";
import Textarea from 'primevue/textarea';
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
})
const title = "assessment";
const subTitle = "secciones";
const areas = ref([]);

const form = useForm({
    area_id: "",
    titulo: "",
});

onMounted(() => {
    getAreas();
})

async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}




const submit = () => {
    try {
        form.post(route("seccion.store"));
        showToast("Registro guardado con éxito", "success");
    } catch (error) {
        showToast("Error al registrar", "error");
        console.log(error);
    }
};

</script>

<template>
    <Layout :titulo="title" :subTitulo="subTitle">

        <Head title="Secciones" />
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Secciones</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('seccion.index')" class="px-1">
                <h3>Secciones -</h3>
                </Link>
                <Link :href="route('seccion.create')" class="active">
                <h3>Nuevo</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden">
                <div class="border-gray-200 bg-white p-6 border-b">
                    <form @submit.prevent="submit">
                        <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2">
                            <div class="mt-4">
                                <InputLabel for="area_id" value="Pilar: " />
                                <select ref="area_select"
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
                                <InputLabel for="titulo" value="Sección: " />
                                <TextInput id="titulo" v-model="form.titulo" type="text" class="block mt-1 w-full"
                                    required autocomplete="titulo" maxlength="250" />
                            </div>


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
    </Layout>
</template>
