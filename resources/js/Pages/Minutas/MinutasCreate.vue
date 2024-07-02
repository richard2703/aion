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
    areas: Array,
    departamentos: Array || null,
});

const areas = ref(props.areas);
const departamentos = ref(props.departamentos);
const title = "minutero";


async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}

const form = useForm({
    area_id: "",
    departamento_id: "",
    alias: "",
    tipo: "",
    proceso_id: "",
    procedimientos_id: "",
    tareas: "",
    responsable: "",
    notas: "",
    estatus: "",
});

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
        form.post(route("minutas.store"), {
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
})

</script>

<template>
    <Layout :titulo="title">

        <Head title="Minutas" />

        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Nueva Minuta</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('minutas.index')" class="px-1">
                <h3>Minutas -</h3>
                </Link>
                <Link class="active">
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
                                        <InputLabel for="area_id" value="Area: " />
                                        <select ref="area_select" @change="onChange($event)"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
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
                                        <InputLabel for="departamento_id" value="Pilar: " />

                                        <select ref="departamento_select"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                            v-model="form.departamento_id" required>
                                            <option value="" disabled selected>
                                                Seleccione una opcion
                                            </option>
                                            <option v-for="departamento in departamentos" :key="departamento.id"
                                                :value="departamento.id">
                                                {{ departamento.nombre }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="tipo" value="Tipo: " />

                                        <select ref="departamento_select"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                            v-model="form.tipo" required>
                                            <option value="" selected>
                                                Seleccione una opcion
                                            </option>
                                            <option value="D.D.S" selected>
                                                D.D.S </option>
                                            <option value="W.D.S." selected>
                                                W.D.S. </option>
                                            <option value="M.D.S." selected>
                                                M.D.S. </option>
                                            <option value="R.O.B." selected>
                                                R.O.B. </option>
                                        </select>
                                    </div>

                                    <!-- <div class="mt-4">
                                        <InputLabel for="proceso" value="proceso: " />
                                        <TextInput id="proceso" v-model="form.proceso_id" type="text"
                                            class="mt-1 block w-full" required autocomplete="new-challenge" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="procedimiento" value="procedimiento: " />
                                        <TextInput id="cuerpo" v-model="form.procedimiento_id" type="text"
                                            class="mt-1 block w-full" required autocomplete="new-challenge" />
                                    </div> -->

                                    <div class="mt-4">
                                        <InputLabel for="alias" value="Alias: " />
                                        <TextInput id="alias" v-model="form.alias" type="text" class="mt-1 block w-full"
                                            required autocomplete="new-challenge" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="tarea" value="tareas: " />
                                        <TextInput id="tareas" v-model="form.tareas" type="text"
                                            class="mt-1 block w-full" required autocomplete="new-challenge" />
                                    </div>

                                    <!-- <div class="mt-4">
                                        <InputLabel for="responsable" value="Responsable: " />
                                        <TextInput id="Explicacion" v-model="form.responsable" type="text"
                                            class="mt-1 block w-full" required autocomplete="new-challenge" />
                                    </div> -->

                                    <div class="mt-4">
                                        <InputLabel for="notas" value="Notas: " />
                                        <Textarea v-model="form.notas" rows="3" style="width: 100%; " />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="estatus" value="Estatus: " />

                                        <select ref="departamento_select"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                            v-model="form.estatus" required>
                                            <option value="0" selected>
                                                Retrasado </option>
                                            <option value="1" selected>
                                                Iniciado </option>
                                            <option value="2" selected>
                                                En proceso </option>
                                            <option value="3" selected>
                                                Terminado </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="px-4 my-4 pt-2 flex justify-end bg-white border-t border-gray-200">
                                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing, }"
                                        :disabled="form.processing">
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
