<script setup>
import { onMounted, ref } from "vue";
import Layout2 from "@/Layouts/Layout2.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "../utils/SweetAlert.service";
import Textarea from 'primevue/textarea';
import AutoComplete from 'primevue/autocomplete';
import Select from 'primevue/select';
import PilaresSelect from "@/Components/v2/PilaresSelect.vue";


const props = defineProps({
    areas: Array,
    departamentos: Array || null,
    procesos: Array,
    usuarios: Array,
});

const selectedPilar = ref(localStorage.getItem("PILAR_ID") || 1);
const areas = ref(props.areas);
const departamentos = ref(props.departamentos);
const procesos = ref(props.procesos);
const usuarios = ref(props.usuarios);
const filteredUsuarios = ref();
const responsable_id = ref();
const tiposMinutas = ref([]);

const title = "minutero";

const onSelectedPilar = (pilarID) => {
  selectedPilar.value = pilarID;
  form.area_id = pilarID;
  onChange({ target: { value: pilarID } });
};


async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}

async function getProcesos() {
    await axios
        .get("/api/procesosTodo")
        .then((response) => (procesos.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}

async function getUsuarios() {
    await axios
        .get("/api/usuarios/all/todo")
        .then((response) => (usuarios.value = response.data))
        .catch((error) => {
            console.log(error);
        });

}

const getTiposMinuta = async () => {
    try {
        await axios.get(route("tipo-minuta.index"))
            .then((response) => {
                tiposMinutas.value = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
        console.log(tiposMinutas);
    } catch (error) {
        console.error(error);
    }
};

const form = useForm({
    area_id: selectedPilar.value,
    departamento_id: "",
    proceso_id: "",
    lider_id: "",
    tipo: "",
    alias: "",
    notas: "",
    estatus: "",
    privada: 0,
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
    getUsuarios();
    getProcesos();
    getTiposMinuta();
    onChange({ target: { value: selectedPilar.value } });
})
console.log({ procesos: procesos });
const search = (event) => {
    console.log("buscando");
    setTimeout(() => {
        if (!event.query.trim().length) {
            console.log(filteredUsuarios.value);
            filteredUsuarios.value = [...usuarios.value];
        } else {
            filteredUsuarios.value = usuarios.value.filter((usuario) => {
                return usuario.name.toLowerCase().includes(event.query.toLowerCase());
            });
        }
    }, 250);
}

</script>

<template>
    <Layout2 :titulo="title">
        <PilaresSelect :currentPilarID="selectedPilar" :onSelectedPilar="onSelectedPilar"></PilaresSelect>

        <Head title="Minutas" />

        <div class="mt-5 overflow-hidden">
            <div class="breadcrumbsTitulo">
                <h3 class="font-semibold text-xl">Nueva Minuta</h3>
            </div>

            <div class="flex items-center breadcrumbs">
                <Link :href="route('dashboard')">
                </Link>
                <Link :href="route('minutas.index')">
                <h3>Minutas</h3>
                </Link>
                <i class="pi-angle-right mx-2 pi" style="font-size: 1rem"></i>
                <Link :href="route('minutas.create')">
                <b>Nuevo</b>
                </Link>
            </div>
        </div>

        <div class="bg-white py-5 overflow-hidden">
            <div class="bg-white">
                <div class="mx-auto container">
                    <form @submit.prevent="submit">
                        <div class="gap-7 grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 2xl:grid-cols-4">
                            <div class="mt-4">
                                <InputLabel for="departamento_id" value="Flujo de valor: " />

                                <select ref="departamento_select"
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
                            </div>

                            <div class="mt-4">
                                <InputLabel for="tipo" value="Tipo: " />

                                <select ref="tipo_select"
                                    class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                    v-model="form.tipo" required>
                                    <option value="" disabled selected>
                                        Seleccione una opcion
                                    </option>
                                    <option v-for="tipoMinuta in tiposMinutas" :key="tipoMinuta.id"
                                        :value="tipoMinuta.id">
                                        {{ tipoMinuta.titulo }}
                                    </option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="proceso_id" value="Proceso: " />
                                <Select v-model="form.proceso_id" editable :virtualScrollerOptions="{ itemSize: 38 }"
                                    :options="procesos" optionLabel="nombre" placeholder="Seleccione una opcion"
                                    class="w-full" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="lider" value="Lider: " />
                                <AutoComplete v-model="form.lider_id" optionLabel="name" :suggestions="filteredUsuarios"
                                    forceSelection @complete="search" placeholder="" />
                            </div>


                            <div class="mt-4">
                                <InputLabel for="alias" value="Alias: " />
                                <TextInput id="alias" v-model="form.alias" type="text" class="block mt-1 w-full"
                                    required autocomplete="new-challenge" />
                            </div>

                            <!-- <div class="mt-4">
                                        <InputLabel for="estatus" value="Estatus: " />

                                        <select ref="departamento_select"
                                            class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
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
                                    </div> -->
                        </div>

                        <div class="mt-4">
                            <InputLabel for="notas" value="Notas: " />
                            <Textarea v-model="form.notas" rows="3" style="width: 100%; " />
                        </div>

                        <div class="block md:flex justify-between items-center my-10">
                            <div class="mt-4 mb-5">
                                <InputLabel for="Privada" value="Exclusivo para lideres de pilar?: " />
                                <!-- <input type="checkbox" @change="validateTarea(slotProps.data, $event)"
                                                                                :disabled="slotProps.data.validacion ? true : false"
                                                                                :checked="slotProps.data.validacion ? true : false" /> Validar -->
                                <input type="checkbox" v-model="form.privada" /> Exclusivo
                            </div>
                            
                            <PrimaryButton 
                                    class="bg-black hover:bg-gray-700" 
                                    :disabled="form.processing"
                                    >
                                    <i class="mr-2 pi pi-check"></i> Crear nueva 
                            </PrimaryButton>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </Layout2>
</template>
