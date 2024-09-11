<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout.vue";
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
    // getAreas();
    getTipos(1);
})

</script>

<template>
    <Layout :titulo="title">

        <Head title="Encargados" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Nuevo desperdicio</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('desperdicio.index')" class="px-1">
                <h3>Desperdicios -</h3>
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
                                    <!--   <div class="mt-4">
                                        <InputLabel for="area_id" value="Pilar: " />
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
                                        <InputLabel for="departamento_id" value="Flujo de valor: " />

                                        <select ref="departamento_select" @change="getTipos($event)"
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
                                    </div> -->

                                    <div class="mt-4">
                                        <InputLabel for="departamento_id" value="Tipo desperdicio: " />

                                        <select ref="departamento_select"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
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
                                        <InputLabel for="monto" value="Monto: " />
                                        <TextInput id="tarea" v-model="form.monto" type="number" step="any"
                                            class="mt-1 block w-full" required autocomplete="tarea" />
                                    </div>
                                    <div class="mt-4">
                                        <InputLabel for="Descripcion" value="Descripcion: " />
                                        <TextInput id="descripcion" v-model="form.descripcion" type="text"
                                            class="mt-1 block w-full" required autocomplete="descripcion"
                                            maxlength="250" />
                                    </div>


                                </div>
                                <div class="px-4 my-4 pt-2 flex justify-end bg-white border-t border-gray-200">
                                    <PrimaryButton class="ms-4 pi pi-save" :class="{ 'opacity-25': form.processing, }"
                                        :disabled="form.processing">

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
