<script setup>
import { onMounted, ref, watch } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import axios from "axios";
import Layout from "@/Layouts/Layout2.vue";
import InputLabel from "@/Components/InputLabel.vue";
import AccionIndex from "@/Pages/sysGestion/Partials/Accion/AccionIndex.vue";
import KpiIndex from "@/Pages/sysGestion/Partials/Kpis/KpiIndex.vue";
import PilaresSelect from "@/Components/v2/PilaresSelect.vue";

const props = defineProps({
    user: Object,
    kpis: Array,
    areas: Array,
    departamentos: Array || null,
});

// tabs scrip
const activeTab = ref();

const title = "sysGestion";
const subTitle = "pdca";
const usuario = ref(props.user);
const selectedPilar = ref(usuario.value.area_id);
const areas = ref(props.areas);
const departamentos = ref([]);
const showCorrectivas = ref(false);
const showPreventivas = ref(false);
const showMejoras = ref(false);
const kpis = ref([]);
const kpi = ref({});

const procesos = ref([]);


const form = useForm({
    area_id: usuario.value.area_id,
    departamento_id: usuario.value.departamento_id,
});

onMounted(() => {
    getAreas();
    getDepartamentos(usuario.value.area_id).then(() => {
        getProcesos();
        getKpis();
    });
});


watch(() => selectedPilar.value, (newVal, oldVal) => {
    getAreas();
    form.area_id = newVal;
    getDepartamentos(newVal).then(() => {
        getProcesos();
        getKpis();
    });
});

// Función para cambiar el tab activo
const setActiveTab = (tab) => {
    activeTab.value = tab;
    getOneKpi(tab);
};


async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}

async function getDepartamentos(userArea) {
    await axios
        .get(route("departamentos.byArea", userArea))
        .then((response) => {
            departamentos.value = response.data.departamentos
            let departamentoIncluded = departamentos.value.find(dep => dep.id == usuario.departamento_id)
            if (!departamentoIncluded) {
                form.departamento_id = departamentos.value[0].id
            }

            activeTab.value = kpi.value[0].id
        })
        .catch((error) => {
            console.log(error);
        });
}

async function onChange(event) {
    const taget_id = event.target.value;
    await axios
        .get(route("departamentos.byArea", taget_id))
        .then((response) => (departamentos.value = response.data.departamentos))
        .catch((error) => {
            console.log(error);
        });
}

async function getProcesos() {
    console.log('departamento_id', form.departamento_id);

    await axios.get(route("procesos.byDepartamento", form.departamento_id))
        .then((response) => {
            procesos.value = response.data.procesos;

            console.log({ procesos: procesos.value });
        })
        .catch((error) => {
            console.log(error);
        });
}

const getKpis = async () => {
    await axios
        .get(route("kpis.byDepartamento", form.departamento_id))
        .then((response) => {
            kpis.value = response.data;

            if (kpis.value.length > 0) {
                activeTab.value = kpis.value[0].id;
                getOneKpi(activeTab.value);
            } else {
                kpi.value = {};
            }
        })
        .catch((error) => {
            console.log(error);
        });
};

async function getOneKpi(id) {
    await axios.get(route("kpis.byId", id)).then((response) => {
        axios.get(route("registros_kpi.registros", id))
        .then((registros) => {
            const record = {
                ...response.data,
                registros: registros.data,
            };

            kpi.value = record;
        });
    })
}

const onSelectedPilar = (pilarID) => {
    selectedPilar.value = pilarID;
};
</script>

<template>
    <Layout :titulo="title" :subTitulo="subTitle">
        <PilaresSelect :currentPilarID="selectedPilar" :onSelectedPilar="onSelectedPilar"></PilaresSelect>

        <div class="p-5">
            <div class="block md:flex justify-between items-center content-center">
                <!-- Sub Header -->
                <div class="sm:rounded-lg overflow-hidden">
                    <div class="flex items-center gap-3 breadcrumbs">
                        <Link href="#" class="active">
                        <h3>Sistema de gestion</h3>
                        </Link>

                        <i class="pi-angle-right pi" style="font-size: 1rem"></i>
                        <Link :href="route('sysgestion.index')" class="active">
                        <b>PDCA</b>
                        </Link>
                    </div>
                </div>

                <!-- Select Flujo de valor -->
                <div class="my-10 md:mt-0">
                    <InputLabel for="departamento_id" value="Flujo de valor: " />

                    <select ref="departamento_select" @change="getProcesos()"
                        class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                        v-model="form.departamento_id" required>
                        <option value="" disabled selected>
                            Seleccione una opcion
                        </option>
                        <option v-for="departamento in departamentos" :key="departamento.id" :value="departamento.id">
                            {{ departamento.nombre }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Content -->
            <div class="bg-white overflow-hidden">
                <div class="mx-auto mt-5 w-full">
                    <!-- Tabs Header -->
                    <div class="flex border-gray-200 border-b">
                        <button v-for="kpi in kpis" @click="setActiveTab(kpi.id)" :class="[
                            'px-4 py-2 text-sm font-medium',
                            activeTab === kpi.id ? 'border-b-2 border-blue-500 text-blue-500' : 'text-gray-500'
                        ]">
                            {{ kpi.titulo }}
                        </button>
                    </div>

                    <!-- Tabs Content -->
                    <div>
                        <div>

                            <!-- Plan and  check  -->
                            <div>
                                <!-- <div class="gap-4 hidden grid sm:grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-2">
                                <span class="font-bold text-2xl text-center">Plan</span>
                                <span class="font-bold text-2xl text-center">Check</span>
                            </div> -->
                                <KpiIndex :departamento_id="form.departamento_id" :kpi="kpi" />
                            </div>

                            <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2">
                                <!-- Procesos -->
                                <div class="m-4">
                                    <div class="max-h-48 overflow-y-auto">
                                        <div class="text-center">
                                            <span class="font-bold text-2xl">Do</span>
                                        </div>
                                        <table class="border-collapse min-w-full">
                                            <thead>
                                                <tr>
                                                    <th class="px-4 py-2 border" colspan="2">
                                                        Procesos
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <div v-for="proceso in procesos" class="w-full">
                                                    <tr v-for="procedimiento in proceso.procedimientos">
                                                        <td class="px-4 py-2">
                                                            <div v-if="procedimiento.link_herramienta">
                                                                <a target="blank" :href="procedimiento.link_herramienta"
                                                                    class="text-blue-500 hover:underline curson-pointer">
                                                                    {{ procedimiento.nombre || '-' }}
                                                                </a>
                                                            </div>
                                                            <div v-else>
                                                                <a href="#"
                                                                    class="text-slate-400 hover:underline cursor-not-allowed">
                                                                    {{ procedimiento.nombre || '-' }}
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </div>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Acciones -->
                                <div class="m-4">
                                    <div class="text-center">
                                        <span class="font-bold text-2xl">Act</span>
                                    </div>
                                    <table class="border-collapse min-w-full">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-2 border" colspan="2">
                                                    Acciones
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="px-4 py-2 border">
                                                    <div>
                                                        <span>Correctivas</span>
                                                        <button v-if="!showCorrectivas"
                                                            class="float-right mx-2 pi pi-angle-down"
                                                            @click="showCorrectivas = !showCorrectivas">
                                                        </button>
                                                        <button v-if="showCorrectivas"
                                                            class="float-right mx-2 pi pi-angle-up"
                                                            @click="showCorrectivas = !showCorrectivas">
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <AccionIndex v-if="showCorrectivas === true"
                                                            :area_id="form.area_id" :tipo="'correctiva'"
                                                            :departamento_id="form.departamento_id" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 border">
                                                    <div>
                                                        <span>Preventivas</span>
                                                        <button v-if="!showPreventivas"
                                                            class="float-right mx-2 pi pi-angle-down"
                                                            @click="showPreventivas = !showPreventivas">
                                                        </button>
                                                        <button v-if="showPreventivas"
                                                            class="float-right mx-2 pi pi-angle-up"
                                                            @click="showPreventivas = !showPreventivas">
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <AccionIndex v-if="showPreventivas === true"
                                                            :tipo="'preventiva'" :area_id="form.area_id"
                                                            :departamento_id="form.departamento_id" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 border">
                                                    <div>
                                                        <span>De Mejora</span>
                                                        <button v-if="!showMejoras"
                                                            class="float-right mx-2 pi pi-angle-down"
                                                            @click="showMejoras = !showMejoras">
                                                        </button>
                                                        <button v-if="showMejoras"
                                                            class="float-right mx-2 pi pi-angle-up"
                                                            @click="showMejoras = !showMejoras">
                                                        </button>
                                                    </div>
                                                    <div>
                                                        <AccionIndex v-if="showMejoras === true" :tipo="'mejora'"
                                                            :area_id="form.area_id"
                                                            :departamento_id="form.departamento_id" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 border">
                                                    <Link
                                                        :href="route('minutas.index', { area_id: form.area_id, departamento_id: form.departamento_id })"
                                                        class="text-blue-500 hover:underline">
                                                    Minutas
                                                    </Link>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 border">
                                                    <Link
                                                        :href="route('tareas.index', { area_id: form.area_id, departamento_id: form.departamento_id })"
                                                        class="text-blue-500 hover:underline">
                                                    Tareas
                                                    </Link>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

        </div>
    </Layout>
</template>
