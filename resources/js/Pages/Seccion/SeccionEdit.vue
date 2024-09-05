<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { showToast } from "../utils/SweetAlert.service";
import Textarea from 'primevue/textarea';
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    seccion: Object,
    secciones: Array,
})
const title = "assessment";
const subTitle = "secciones";
const seccion = ref(props.seccion);
const secciones = ref(props.secciones);
const departamentos = ref([]);

const form = useForm({
    titulo: seccion.value.titulo,
    descripcion: seccion.value.descripcion,
});

onMounted(() => {
    getDepartamentos();
})
console.log({ seccion: seccion.value });

async function getDepartamentos() {
    const newSecciones = secciones.value.map(a => a.titulo);
    await axios
        .get(route('departamentos.findAll'))
        .then((response) => {
            departamentos.value = response.data.filter((departamento) => {
                return !newSecciones.some(newSeccion => newSeccion === departamento.nombre && newSeccion !== seccion.value.titulo);
            });
        })
        .catch((error) => {
            console.error(error);
        });
}

const submit = () => {
    try {
        form.patch(route("seccion.update", seccion.value.id));
        showToast("Registro guardado con éxito", "success");
    } catch (error) {
        showToast("Error al registrar", "error");
        console.log(error);
    }
};

</script>

<template>
    <Layout :titulo="title" :subTitulo="subTitle">

        <Head title="Departamento" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Secciones</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link href="#" class="px-1">
                <h3>Assessment -</h3>
                </Link>
                <Link :href="route('seccion.index')" class="px-1">
                <h3>Secciones -</h3>
                </Link>
                <Link :href="route('seccion.edit', seccion.id)" class="active">
                <h3>Nuevo</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                            <div class="mt-4 ">
                                <InputLabel for="titulo" value="Flujo de valor: " />
                                <!-- <TextInput v-model="form.titulo" type="text" class="mt-1 block w-full" /> -->
                                <select ref="departamento_select" class=" border-gray-300 focus:border-indigo-500 focus:ring-indigo-500
                                            rounded-md shadow-sm w-full px-3 py-2 cursor-pointer" v-model="form.titulo"
                                    required>
                                    <option value="" disabled selected>
                                        Seleccione una opcion
                                    </option>
                                    <option v-for="departamento in departamentos" :key="departamento.id"
                                        :value="departamento.nombre">
                                        {{ departamento.nombre }}
                                    </option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="descripcion" value="Descripcion: " />
                                <Textarea v-model="form.descripcion" rows="5" cols="30" />
                            </div>


                        </div>
                        <div class="flex items-center justify-end mt-4">
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
