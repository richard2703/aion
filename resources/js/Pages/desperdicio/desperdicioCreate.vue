<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout2.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "../utils/SweetAlert.service";

const props = defineProps({
    areas: Array,
    departamentos: Array || null,
    tipos: Array,
});

const areas = ref(props.areas);
const departamentos = ref(props.departamentos);
const tipos = ref(props.tipos);

const title = "Desperdicios";

const form = useForm({
    area_id: "",
    departamento_id: "",
    tipoDesperdicio_id: "",
    descripcion: "",
    monto: "",
    rango: "",
    recurrencia: '',
    detectabilidad: '',
});

async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data))
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

const getTipos = async (event) => {
    // const taget_id = event.target.value;
    const taget_id = 1;

    await axios
        .get(route("desperdicio.byDepartamento", taget_id))
        .then((response) => (tipos.value = response.data.tipos))
        .catch((error) => {
            console.log(error);
        });

};


const submit = () => {
    try {
        form.post(route("desperdicio.store"), {
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
    getTipos(1);
})

const showTooltipRango = ref(false);
const showTooltipRecurrencia = ref(false);
const showTooltipDetectabilidad = ref(false);



</script>

<template>
    <Layout :titulo="title">

        <Head title="Desperdicios" />
        <div class="pl-5 overflow-hidden">
            <div class="mb-3 px-1 breadcrumbsTitulo">
                <h3 class="font-semibold text-xl">Nuevo Desperdicios</h3>
            </div>

            <div class="flex items-center gap-2 breadcrumbs">
                <Link :href="route('desperdicio.index')" class="px-1">
                    <h3>Desperdicios</h3>
                </Link>
                <i class="pi-angle-right pi" style="font-size: 1rem"></i>
                <Link class="active">
                 <h3>Nuevo</h3>
                </Link>
            </div>
        </div>

        <div class="mt-3 p-5">
            <div class="bg-white">
                <div>
                    <div class="flex justify-end"></div>
                    <div class="border-gray-200 bg-white">
                        <div class="mx-auto">
                            <form @submit.prevent="submit">


                                <!-- Primer formulario en 2 columnas en pantallas pequeñas, 12 en pantallas más grandes -->
                                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12">

                                    <div className="col-span-1 sm:col-span-1 lg:col-span-1">

                                    </div>
                                    <div className="col-span-1 sm:col-span-1 lg:col-span-6">

                                    </div>
                                </div>


                                <!-- colum style input form -->
                                <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-12">

                                    <!-- Style edit column -->
                                    <div class="col-span-1 sm:col-span-1 lg:col-span-7">
                                        <div class="gap-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2">
                                            <!--   <div class="mt-4">
                                                <InputLabel for="departamento_id" value="Flujo de valor: " />
    
                                                <select ref="departamento_select" @change="getTipos($event)"
                                                    class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                                    v-model="form.departamento_id" required>
                                                    <option value="" disabled selected>
                                                        Seleccione una opcion
                                                    </option>
                                                    <option v-for="departamento in departamentos" :key="departamento.id"
                                                        :value="departamento.id">
                                                        {{ departamento.nombre }}
                                                    </option>
                                                </select>
                                            </div> -->

                                            <div class="mt-4">
                                                <InputLabel for="departamento_id" value="Tipo desperdicio: " />

                                                <select ref="departamento_select"
                                                    class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                                    v-model="form.tipoDesperdicio_id" required>
                                                    <option value="" disabled selected>
                                                        Seleccione una opcion
                                                    </option>
                                                    <option v-for="tipo in tipos" :key="tipo.id" :value="tipo.id">
                                                        {{ tipo.tipo }} - {{ tipo.nombre }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="mt-4">
                                                <InputLabel for="monto" value="Monto $ : " />
                                                <TextInput id="tarea" v-model="form.monto" type="number" step="any"
                                                    class="block mt-1 w-full" required autocomplete="tarea" />
                                            </div>
                                            <div class="mt-4">
                                                <InputLabel for="Descripcion" value="Descripcion: " />
                                                <TextInput id="descripcion" v-model="form.descripcion" type="text"
                                                    class="block mt-1 w-full" required autocomplete="descripcion"
                                                    maxlength="250" />
                                            </div>
                                            <!-- <div class="mt-4">
                                                <InputLabel for="Rango" value="Rango: " />
                                                <TextInput id="rango" v-model="form.rango" type="number"
                                                    class="block mt-1 w-full" required autocomplete="rango" min="1" max="10" />
                                            </div> -->
                                            <div class="relative mt-4">
                                                <!-- Etiqueta del campo -->
                                                <label for="Rango"
                                                    class="block font-medium text-gray-700 text-sm">Rango:
                                                    <!-- Ícono para el tooltip -->
                                                    <!-- <span class="ml-2 text-blue-500 cursor-pointer"
                                                        @mouseenter="showTooltipRango = true"
                                                        @mouseleave="showTooltipRango = false">
                                                        <i class="pi pi-save"></i>
                                                    </span> -->
                                                    <button @click="showTooltipRango = true"
                                                        class="ml-2 text-blue-500 underline">Ver ejemplo</button>

                                                </label>

                                                <!-- Campo de texto -->
                                                <input id="rango" v-model="form.rango" type="number"
                                                    class="block border-gray-300 focus:border-indigo-500 shadow-sm mt-1 rounded-md focus:ring-indigo-500 w-full sm:text-sm"
                                                    required autocomplete="rango" min="1" max="10" />

                                                <!-- Modal -->
                                                <div v-if="showTooltipRango"
                                                    class="z-50 fixed inset-0 flex justify-center items-center bg-gray-500 bg-opacity-75">
                                                    <div
                                                        class="bg-white shadow-lg p-6 rounded-lg max-w-full max-h-full overflow-y-auto">
                                                        <h3 class="mb-4 font-bold text-lg">Ejemplo de Rango</h3>

                                                        <!-- Contenido de la imagen con tamaño ajustado -->
                                                        <img src="../../../img/amef/rangoAMEF.png"
                                                            alt="Ejemplo de Rango" style="width: 600px;">

                                                        <!-- Botón de cerrar -->
                                                        <button @click="showTooltipRango = false"
                                                            class="bg-red-500 mt-4 px-4 py-2 rounded text-white">
                                                            Cerrar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="relative mt-4">
                                                <!-- Etiqueta del campo -->
                                                <label for="Recurrencia"
                                                    class="block font-medium text-gray-700 text-sm">Recurrencia:
                                                    <!-- Ícono para el tooltip -->
                                                    <button @click="showTooltipRecurrencia = true"
                                                        class="ml-2 text-blue-500 underline">Ver ejemplo</button>
                                                </label>

                                                <!-- Campo de texto -->
                                                <input id="recurrencia" v-model="form.recurrencia" type="number"
                                                    class="block border-gray-300 focus:border-indigo-500 shadow-sm mt-1 rounded-md focus:ring-indigo-500 w-full sm:text-sm"
                                                    required autocomplete="recurrencia" min="1" max="10" />

                                                <!-- Modal -->

                                                <div v-if="showTooltipRecurrencia"
                                                    class="z-50 fixed inset-0 flex justify-center items-center bg-gray-500 bg-opacity-75">
                                                    <div
                                                        class="bg-white shadow-lg p-6 rounded-lg max-w-full max-h-full overflow-y-auto">
                                                        <h3 class="mb-4 font-bold text-lg">Ejemplo de Recurrencia</h3>

                                                        <!-- Contenido de la imagen con tamaño ajustado -->
                                                        <img src="../../../img/amef/ocurrenciaAMEF.png"
                                                            alt="Ejemplo de Rango" style="width: 600px;">

                                                        <!-- Botón de cerrar -->
                                                        <button @click="showTooltipRecurrencia = false"
                                                            class="bg-red-500 mt-4 px-4 py-2 rounded text-white">
                                                            Cerrar
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="relative mt-4">
                                                <!-- Etiqueta del campo -->
                                                <label for="Detectabilidad"
                                                    class="block font-medium text-gray-700 text-sm">Detectabilidad:
                                                    <button @click="showTooltipDetectabilidad = true"
                                                        class="ml-2 text-blue-500 underline">Ver ejemplo</button>
                                                </label>

                                                <!-- Campo de texto -->
                                                <input id="detectabilidad" v-model="form.detectabilidad" type="number"
                                                    class="block border-gray-300 focus:border-indigo-500 shadow-sm mt-1 rounded-md focus:ring-indigo-500 w-full sm:text-sm"
                                                    required autocomplete="detectabilidad" min="1" max="10" />
                                                <!-- Modal -->
                                                <div v-if="showTooltipDetectabilidad"
                                                    class="z-50 fixed inset-0 flex justify-center items-center bg-gray-500 bg-opacity-75">
                                                    <div
                                                        class="bg-white shadow-lg p-6 rounded-lg max-w-full max-h-full overflow-y-auto">
                                                        <h3 class="mb-4 font-bold text-lg">Ejemplo de Detectabilidad
                                                        </h3>

                                                        <!-- Contenido de la imagen con tamaño ajustado -->
                                                        <img src="../../../img/amef/deteccionAMEF.png"
                                                            alt="Ejemplo de Rango" style="width: 600px;">

                                                        <!-- Botón de cerrar -->
                                                        <button @click="showTooltipDetectabilidad = false"
                                                            class="bg-red-500 mt-4 px-4 py-2 rounded text-white">
                                                            Cerrar
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>

                                        <div class="flex justify-end border-gray-200 bg-white my-7 pt-4 border-t">
                                            <PrimaryButton :href="route('desperdicio.create')"
                                                class="bg-black hover:bg-gray-800 px-10">
                                               Guardar
                                            </PrimaryButton>
                                        </div>

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
