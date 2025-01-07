<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout2.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "../utils/SweetAlert.service";
import Textarea from 'primevue/textarea';
import AutoComplete from 'primevue/autocomplete';


const props = defineProps({
    areas: Array,
    departamentos: Array || null,
    usuarios: Array,
});

const areas = ref(props.areas);
const departamentos = ref(props.departamentos);
const usuarios = ref(props.usuarios);
const filteredUsuarios = ref();
const responsable_id = ref();

const title = "rolesypermisos";


async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data))
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

const form = useForm({
    name: "",
    // departamento_id: "",
    // alias: "",
    // tipo: "",
    // proceso_id: "",
    // procedimientos_id: "",
    // tareas: "",
    // notas: "",
    // estatus: "",
    // responsable_id: "",
});

const onChange = async (event) => {
    const taget_id = event.target.value;
    await axios
        .get(route("departamentos.byArea", taget_id))
        .then((response) => (departamentos.value = response.data.departamentos))
        .catch((error) => {
            console.log(error);
        });

    // await axios
    //     .get(route("usuarios.byArea", taget_id))
    //     .then((response) => (usuarios.value = response.data.usuarios))
    //     .catch((error) => {
    //         console.log(error);
    //     });
};

const submit = () => {
    try {
        form.post(route("permisos.store"), {
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
})

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
    <Layout :titulo="title">

        <Head title="Permisos" />

        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Nuevo Permisos</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('permisos.index')" class="px-1">
                <h3>Permisos -</h3>
                </Link>
                <Link class="active">
                <h3>Nuevo</h3>
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
                                        <InputLabel for="nombre" value="Nombre: " />
                                        <TextInput id="name" v-model="form.name" type="text" class="block mt-1 w-full"
                                            required autocomplete="new-challenge" />
                                    </div>

                                </div>
                                <div class="flex justify-end border-gray-200 bg-white my-4 px-4 pt-2 border-t">
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
