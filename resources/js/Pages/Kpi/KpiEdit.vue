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
    procedimientos: Array || null,
    kpi: Object,
});

const areas = ref(props.areas);
const departamentos = ref(props.departamentos);
const procesos = ref(props.procesos);
const procedimientos = ref(props.procedimientos);
const kpi = ref(props.kpi);

const form = useForm({
    titulo: kpi.value.titulo,
    area_id: kpi.value.area_id,
    departamento_id: kpi.value.departamento_id,
    proceso_id: kpi.value.proceso_id,
    procedimiento_id: kpi.value.procedimiento_id,
    actual: kpi.value.actual,
    objetivo: kpi.value.objetivo,
    descripcion: kpi.value.descripcion,
    medicion: kpi.value.medicion,
    regla: kpi.value.regla,
});

onMounted(() => {
    getAreas();
    getDepartamentos(kpi.value.area_id);
    getProcesos(kpi.value.departamento_id);
    getProcedimientos(kpi.value.proceso_id);
});

async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data))
        .catch((error) => {
            console.log(error);
        });
    console.log('areas', areas.value);
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

async function getProcedimientos(eventOrValue) {
    const target_id = eventOrValue && eventOrValue.target ? eventOrValue.target.value : eventOrValue;
    await axios
        .get(route("procedimientos.byProceso", target_id))
        .then((response) => (procedimientos.value = response.data.procedimientos))
        .catch((error) => {
            console.log(error);
        });
}


// const submit = () => {
//     try {
//         form.patch(route("kpi.update", kpi.value.id), {
//             onFinish: () => {
//                 showToast("El registro ha sido creado", "success");
//                 form.reset();
//             },
//         });

//     } catch (error) {
//         showToast("Ocurrio un error", "error");
//         console.error(error);
//     }
// };

async function submit() {
    await axios
        .post(route("kpi.update", kpi.value.id), form)
        .then((response) => {
            console.log('response', response.data.error);
            if (response.data.error) {
                console.log('entro');
                showToast(response.data.error, "error");
            } else {
                showToast("El registro ha sido creado", "success");
                window.location.href = route("kpi.index");

                // setTimeout(() => {
                //     window.location.href = route("kpi.index");
                // }, 2000); // Retraso de 3 segundos para mostrar el toast completo
            }
        })
        .catch((error) => {
            console.log(error);
        });
}

</script>

<template>
    <Layout>

        <Head title="KPI´s" />
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>KPI's</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('kpi.index')" class="px-1">
                <h3>KPI's -</h3>
                </Link>
                <Link :href="route('kpi.create')" class="active">
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
                                            v-model="form.departamento_id">
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
                                            v-model="form.proceso_id">
                                            <option value="" selected>
                                                Seleccione una opcion
                                            </option>
                                            <option v-for="proceso in procesos" :key="proceso.id" :value="proceso.id">
                                                {{ proceso.nombre }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="procedimiento_id" value="Procedimiento: " />

                                        <select ref="procedimiento_select"
                                            class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                            v-model="form.procedimiento_id" require>
                                            <option value="" selected>
                                                Seleccione una opcion
                                            </option>
                                            <option v-for="procedimiento in procedimientos" :key="procedimiento.id"
                                                :value="procedimiento.id">
                                                {{ procedimiento.nombre }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                                    <div class="my-4">
                                        <InputLabel for="titulo" value="Titulo: " />
                                        <TextInput id="titulo" v-model="form.titulo" type="text"
                                            class="block mt-1 w-full" required autocomplete="new-challenge" />
                                    </div>
                                    <div class="my-4">
                                        <InputLabel for="objetivo" value="Objetivo: " />
                                        <TextInput id="objetivo" v-model="form.objetivo" type="number" step="any"
                                            class="block mt-1 w-full" required autocomplete="new-challenge" />
                                    </div>
                                    <div class="my-4">
                                        <InputLabel for="medicion" value="Medicion: " />
                                        <TextInput id="medicion" v-model="form.medicion" type="text"
                                            class="block mt-1 w-full" required autocomplete="new-challenge" />
                                    </div>
                                    <div class="my-4">
                                        <InputLabel for="actual" value="Actual: " />
                                        <TextInput id="actual" v-model="form.actual" type="number" step="any"
                                            class="block mt-1 w-full" required autocomplete="new-challenge" />
                                    </div>
                                    <div class="mt-4">
                                        <InputLabel for="regla_id" value="Regla: " />

                                        <select ref="regla_select"
                                            class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                            v-model="form.regla" require>
                                            <option value=1 selected>
                                                Subir a
                                            </option>
                                            <option value=0 selected>
                                                Bajar a
                                            </option>
                                        </select>
                                    </div>
                                    <div class="my-4">
                                        <InputLabel for="descripcion" value="Descripcion: " />
                                        <Textarea v-model="form.descripcion" rows="5" cols="30" />
                                    </div>
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
