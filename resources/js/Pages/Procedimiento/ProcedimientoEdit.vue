<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout2.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "../utils/SweetAlert.service";
import Textarea from 'primevue/textarea';

const props = defineProps({
    areas: Array,
    departamentos: Array || null,
    procesos: Array || null,
    procedimiento: Object,
    proceso: Object,
});

const areas = ref(props.areas);
const departamentos = ref(props.departamentos);
const procesos = ref(props.procesos);
const procedimiento = ref(props.procedimiento);
const proceso = ref(props.proceso);

const form = useForm({
    area_id: proceso.value.area_id,
    departamento_id: proceso.value.departamento_id,
    // proceso_id: kpi.value.proceso_id,
    proceso_id: procedimiento.value.proceso_id,
    nombre: procedimiento.value.nombre,
    descripcion: procedimiento.value.descripcion,
    link_externo: procedimiento.value.link_externo,
    link_herramienta: procedimiento.value.link_herramienta,
});

onMounted(() => {
    // console.log('proceso', proceso.value);
    getAreas();
    getDepartamentos(proceso.value.area_id);
    getProcesos(proceso.value.departamento_id);

    // getProcesos();
});

async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}
async function getDepartamentos(eventOrValue) {
    const target_id = eventOrValue && eventOrValue.target ? eventOrValue.target.value : eventOrValue;
    await axios
        .get(route("departamentos.byArea", target_id))
        .then((response) => (departamentos.value = response.data.departamentos))
        .catch((error) => {
            console.log(error);
        });
}


async function getProcesos(eventOrValue) {
    const target_id = eventOrValue && eventOrValue.target ? eventOrValue.target.value : eventOrValue;

    await axios
        .get(route("procesos.byDepartamento", target_id))
        .then((response) => (procesos.value = response.data.procesos))
        .catch((error) => {
            console.log(error);
        });
}

const submit = () => {
    try {
        form.patch(route("procedimiento.update", procedimiento.value.id), {
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

        <Head title="Procedimientos" />
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Procedimientos</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('procedimiento.index')" class="px-1">
                <h3>Procedimientos -</h3>
                </Link>
                <Link :href="route('procedimiento.edit', procedimiento.id)" class="active">
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

                                <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2">
                                    <div class="mt-4">
                                        <InputLabel for="area_id" value="Pilar: " />
                                        <select ref="area_select" @change="getDepartamentos($event)"
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
                                        <InputLabel for="departamento_id" value="Flujo de valor: " />

                                        <select ref="departamento_select" @change="getProcesos($event)"
                                            class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                            v-model="form.departamento_id" required>
                                            <option value="" selected>
                                                Seleccione una opcion
                                            </option>
                                            <option v-for="departamento in departamentos" :key="departamento.id"
                                                :value="departamento.id">
                                                {{ departamento.nombre }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="proceso_id" value="Proceso: " />

                                        <select ref="proceso_select" @change="getProcedimientos($event)"
                                            class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                            v-model="form.proceso_id" required>
                                            <option value="" selected>
                                                Seleccione una opcion
                                            </option>
                                            <option v-for="proceso in procesos" :key="proceso.id" :value="proceso.id">
                                                {{ proceso.nombre }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="my-4">
                                        <InputLabel for="nombre" value="Procedimiento: " />
                                        <TextInput id="nombre" v-model="form.nombre" type="text"
                                            class="block mt-1 w-full" required autocomplete="new-challenge" />
                                    </div>
                                    <div class="my-4">
                                        <InputLabel for="Link Documentacion" value="Link Documentacion: " />
                                        <TextInput id="nombre" v-model="form.link_externo" type="text"
                                            class="block mt-1 w-full" required autocomplete="new-challenge" />
                                    </div>
                                    <div class="my-4">
                                        <InputLabel for="Link Herramienta" value="Link Herramienta: " />
                                        <TextInput id="nombre" v-model="form.link_herramienta" type="text"
                                            class="block mt-1 w-full" required autocomplete="new-challenge" />
                                    </div>
                                </div>
                                <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2">
                                    <div class="my-4">
                                        <InputLabel for="descripcion" value="Descripcion: " />
                                        <Textarea v-model="form.descripcion" rows="5" cols="30" />
                                    </div>
                                    <!-- <div class="my-4">
                                        <InputLabel for="link_externo" value="Link externo: " />
                                        <Textarea v-model="form.link_externo" rows="5" cols="30" />
                                    </div> -->
                                </div>
                                <hr>
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
