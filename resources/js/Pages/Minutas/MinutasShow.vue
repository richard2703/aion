<template>
    <Layout :titulo="title">

        <Head title="Departamento" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Detalles de Minuta</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('minutas.index')" class="px-1">
                <h3>Minutas -</h3>
                </Link>
                <Link class="active">
                <h3>Show</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    <div
                        class="px-4 my-4 py-2 flex justify-end bg-white border-b border-gray-200 grid lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1 gap-4">

                        <div class="px-4 py-2 bg-white">
                            <Fieldset legend="Detalles de Minuta">
                                <div class="grid lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1 gap-4">

                                    <div class="mt-4 flex">
                                        <InputLabel for="alias" value="Minuta: " />&nbsp;
                                        <InputLabel for="alias" :value="minuta.alias" />
                                    </div>

                                    <div class="mt-4 flex">
                                        <InputLabel for="pilar" value="Pilar: " />&nbsp;
                                        <InputLabel for="pilar" :value="minuta.area.nombre" />
                                    </div>
                                    <div class="mt-4 flex">
                                        <InputLabel for="flujo_valor" value="Flujo de valor: " />&nbsp;
                                        <InputLabel for="flujo_valor" :value="minuta.departamento.nombre" />
                                    </div>
                                    <div class="mt-4 flex">
                                        <InputLabel for="lider" value="Lider de Minuta: " />&nbsp;
                                        <InputLabel for="lider" :value="minuta.lider.name" />
                                    </div>
                                    <div class="mt-4 flex">
                                        <InputLabel for="tipo" value="Tipo de Minuta: " />&nbsp;
                                        <InputLabel for="tipo" :value="minuta.tipo" />
                                    </div>
                                    <div class="mt-4 flex">
                                        <InputLabel for="fecha" value="Fecha: " />&nbsp;
                                        <InputLabel for="fecha" :value="minuta.created_at" />
                                    </div>
                                    <div class="mt-4 flex">
                                        <InputLabel for="nota" value="Notas: " />&nbsp;
                                        <InputLabel for="nota" :value="minuta.notas" />
                                    </div>
                                </div>
                            </Fieldset>
                        </div>
                        <div>
                            <Fieldset legend="Asistentes a la minuta">
                                <div
                                    class="grid lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1 gap-4 flex justify-between">

                                    <div class="flex">
                                        <InputLabel for="lider" value="Lider de Minuta: " /> &nbsp;
                                        <InputLabel for="lider" :value="minuta.lider.name" />
                                    </div>
                                    <div>
                                        <PrimaryButton v-if="newAsistente" @click="newAsistente = !newAsistente"
                                            class="float-right">
                                            mas
                                        </PrimaryButton>
                                        <PrimaryButton v-if="!newAsistente" @click="newAsistente = !newAsistente"
                                            class="float-right">
                                            menos
                                        </PrimaryButton>

                                    </div>
                                </div>
                                <div v-if="!newAsistente">
                                    <form @submit.prevent="submit" class="flex">
                                        <!-- <TextInput id="atender" v-model="form.user_id" type="text"
                                            class="mt-1 block w-full" required autocomplete="new-challenge" /> -->
                                        <AutoComplete v-model="form.user_id" optionLabel="name"
                                            :suggestions="filteredUsuarios" forceSelection @complete="search"
                                            placeholder="" />
                                        <PrimaryButton class="float-right">ok</PrimaryButton>
                                    </form>
                                </div>
                                <div class="mt-4">
                                    <InputLabel for="asistentes" value="Asistentes de Minuta: " /> <br>
                                    <div class=" mx-2">
                                        <ul v-for="asistente in asistentes" :key="asistente.id">
                                            <li class=""> {{ asistente.user.name }} &nbsp; <button
                                                    class="float-right mx-4" @click="deleteAsistente(asistente.id)">x
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </Fieldset>
                        </div>
                    </div>
                    <div class="px-4 py-2 bg-white border-b border-gray-200">
                        <div class="container mx-auto">
                            <h2>Tareas

                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </Layout>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import axios from "axios";
import Layout from "@/Layouts/Layout.vue";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputText from "primevue/inputtext";
import Fieldset from 'primevue/fieldset';
import AutoComplete from 'primevue/autocomplete';

onMounted(() => {
    getUsuarios();
    getAsistentes(minuta.value.id);
});

const props = defineProps({
    minuta: Object,
});

const asistente = ref(true);

const form = useForm({
    user_id: "",
    minuta_id: props.minuta.id,
});



const title = "minutero";
const minuta = ref(props.minuta);
const usuarios = ref(props.usuarios);
const asistentes = ref();
console.log({ minuta });
const newAsistente = ref(true);
const filteredUsuarios = ref();

async function getUsuarios() {
    await axios
        .get("/api/usuarios/all/todo")
        .then((response) => (usuarios.value = response.data))
        .catch((error) => {
            console.log(error);
        });
    console.log({ usuarios: usuarios.value });

}

async function getAsistentes(minuta_id) {
    await axios
        .get(`/api/asistentes/${minuta_id}`)
        .then((response) => (asistentes.value = response.data))
        .catch((error) => {
            console.log(error);
        });
    console.log({ asistentes: asistentes.value });

}

const search = (event) => {
    console.log("buscando");
    setTimeout(() => {
        const query = event.query.trim().toLowerCase();
        const asistentesUserIds = asistentes.value.map(a => a.user_id);
        if (!event.query.trim().length) {
            console.log(filteredUsuarios.value);
            filteredUsuarios.value = [...usuarios.value];
        } else {
            filteredUsuarios.value = usuarios.value.filter(usuario =>
                usuario.id !== minuta.value.lider_id &&
                !asistentesUserIds.includes(usuario.id) &&
                usuario.name.toLowerCase().startsWith(query)
            );
        }
    }, 250);
}


const submit = async () => {
    try {
        await axios.post("/api/asistente/store", form.data()).then(() => {
            showToast("El registro ha sido creado", "success");
            form.reset();
            newAsistente.value = true;
            getAsistentes(minuta.value.id);
        });
    } catch (error) {
        showToast("Ocurrio un error", "error");
        console.error(error);
    }
};

const deleteAsistente = async (id) => {
    try {
        await axios.delete(`/api/asistente/${id}`).then(() => {
            showToast("El registro ha sido eliminado", "success");
            getAsistentes(minuta.value.id);
        });
    } catch (error) {
        showToast("Ocurrio un error", "error");
        console.error(error);
    }
};



// watch(newAsistente, () => {
//     if (newAsistente) {
//         form.reset();
//     }
// })


</script>
