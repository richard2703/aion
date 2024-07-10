<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "../utils/SweetAlert.service";
import Textarea from 'primevue/textarea';

const props = defineProps({
    procesos: Array
});

const procesos = ref(props.procesos);

const form = useForm({
    proceso_id: "",
    nombre: "",
    descripcion: "",
    link_externo: "",
});

onMounted(() => {
    getProcesos();
});

async function getProcesos() {
    await axios
        .get("/api/procesos")
        .then((response) => (procesos.value = response.data.data))
        .catch((error) => {
            console.log(error);
        });
}


const onChange = async (event) => {
    const taget_id = event.target.value;
    await axios
        .get(route("departamentos.byArea", taget_id))
        .then((response) => (departamentos.value = response.data.departamentos))
        .catch((error) => {
            console.log(error);
        });
};

const submit = () => {
    try {
        form.post(route("procedimiento.store"), {
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

</script>

<template>
    <Layout>

        <Head title="Procesos" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Procedimientos</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('procedimiento.index')" class="px-1">
                <h3>Procedimientos -</h3>
                </Link>
                <Link :href="route('procedimiento.create')" class="active">
                <h3>Nuevo</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    <div class="px-4 my-4 py-2 flex justify-end bg-white border-b border-gray-200"></div>
                    <div class="px-4 py-2 bg-white border-b border-gray-200">
                        <div class="container mx-auto">
                            <form @submit.prevent="submit">

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                                    <div class="mt-4">
                                        <InputLabel for="proceso_id" value="Proceso: " />
                                        <select ref="area_select" @change="onChange($event)"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                            v-model="form.proceso_id" required>
                                            <option value="" disabled selected>
                                                Seleccione una opcion
                                            </option>
                                            <option v-for="proceso in procesos" :key="proceso.id" :value="proceso.id">
                                                {{ proceso.nombre }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="my-4">
                                        <InputLabel for="nombre" value="Nombre: " />
                                        <TextInput id="nombre" v-model="form.nombre" type="text"
                                            class="mt-1 block w-full" required autocomplete="new-challenge" />
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                                    <div class="my-4">
                                        <InputLabel for="descripcion" value="Descripcion: " />
                                        <Textarea v-model="form.descripcion" rows="5" cols="30" />
                                    </div>
                                    <div class="my-4">
                                        <InputLabel for="link_externo" value="Link externo: " />
                                        <Textarea v-model="form.link_externo" rows="5" cols="30" />
                                    </div>
                                </div>
                                <hr>
                                <div class="flex items-center justify-end mt-4">
                                    <PrimaryButton class="ms-4" :class="{
                                        'opacity-25': form.processing,
                                    }" :disabled="form.processing">
                                        guardar
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
