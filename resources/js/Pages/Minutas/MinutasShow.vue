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
                                        <InputLabel for="lider" value="Lider de Minuta: " />&nbsp;
                                        <InputLabel for="lider" :value="minuta.lider.name" />
                                    </div>
                                    <div>
                                        <PrimaryButton v-if="newAsistente" @click="newAsistente = !newAsistente"
                                            class="float-right">mas</PrimaryButton>
                                        <PrimaryButton v-if="!newAsistente" @click="newAsistente = !newAsistente"
                                            class="float-right">menos</PrimaryButton>
                                    </div>
                                </div>
                                <div v-if="!newAsistente">
                                    <form @submit.prevent="submit" class="flex">
                                        <AutoComplete v-model="form.user_id" optionLabel="name"
                                            :suggestions="filteredUsuarios" forceSelection @complete="search"
                                            placeholder="" />
                                        <PrimaryButton class="float-right">ok</PrimaryButton>
                                    </form>
                                </div>
                                <div class="mt-4">
                                    <InputLabel for="asistentes" value="Asistentes de Minuta: " /><br>
                                    <div class="mx-2">
                                        <ul v-for="asistente in asistentes" :key="asistente.id">
                                            <li>{{ asistente.user.name }}&nbsp;
                                                <button class="float-right mx-4"
                                                    @click="deleteAsistente(asistente.id)">x</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </Fieldset>
                        </div>
                    </div>
                    <div class="px-4 py-2 bg-white border-b border-gray-200">
                        <div class="container mx-auto">
                            <div class="flex justify-between">
                                <h2>Tareas</h2>
                                <!-- Trigger to open modal -->
                                <PrimaryButton class=" mb-4 float-right" @click="openModal">Nueva Tarea</PrimaryButton>
                            </div>

                            <DataTable :value="tareas" :lazy="true" tableStyle="min-width: 50rem" sortOrder="desc"
                                class="p-datatable-sm p-datatable-striped p-datatable-gridlines">
                                <template #empty> Sin registros </template>
                                <Column field="id" header="ID" headerStyle="width:4em;" bodyStyle="text-align:center;"
                                    sortable></Column>
                                <Column field="area.nombre" header="Area" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                                <Column field="departamento.nombre" header="Fujo de valor" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                                <Column field="minuta.alias" header="Minuta" headerStyle="width:4em;"
                                    bodyClass="text-center" sortable></Column>
                                <Column field="fecha" header="Fecha de entrega" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                                <Column header="" headerStyle="width:4em;">
                                    <template #body="slotProps" class="text-center">
                                        <!-- <PrimaryButton class="m-2" :href="route('minutas.edit', slotProps.data.id)">
                                            Editar
                                        </PrimaryButton> -->

                                        <PrimaryButton class="m-2" @click.prevent="
                                            deleteTarea(slotProps.data.id)
                                            ">
                                            Borrar
                                        </PrimaryButton>
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>


            <!-- Modal component -->
            <Modal :show="isModalVisible" :modalData="minuta" maxWidth="lg" @close="isModalVisible.value = false">
                <template v-slot="{ modalData }">
                    <TareasCreate class="z-50" :minuta="modalData" @close="isModalVisible = false" />
                </template>
            </Modal>
        </div>
    </Layout>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import axios from "axios";
import Layout from "@/Layouts/Layout.vue";
import Fieldset from 'primevue/fieldset';
import AutoComplete from 'primevue/autocomplete';
import Modal from "@/Components/Modal.vue";
import TareasCreate from "@/Pages/Minutas/Partials/Tareas/TareasCreate.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";

onMounted(() => {
    getUsuarios();
    getAsistentes(minuta.value.id);
    getTareas(minuta.value.id);
});

const props = defineProps({
    minuta: Object,
});

const form = useForm({
    user_id: "",
    minuta_id: props.minuta.id,
});

const title = "minutero";
const minuta = ref(props.minuta);
const usuarios = ref(props.usuarios);
const asistentes = ref();
const newAsistente = ref(true);
const filteredUsuarios = ref();
const tareas = ref();

async function getUsuarios() {
    await axios
        .get("/api/usuarios/all/todo")
        .then((response) => (usuarios.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}

async function getAsistentes(minuta_id) {
    await axios
        .get(`/api/asistentes/${minuta_id}`)
        .then((response) => (asistentes.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}

const search = (event) => {
    setTimeout(() => {
        const query = event.query.trim().toLowerCase();
        const asistentesUserIds = asistentes.value.map(a => a.user_id);
        if (!event.query.trim().length) {
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

const getTareas = async (minuta_id) => {
    await axios
        .get(`/api/tareas/${minuta_id}`)
        .then((response) => (tareas.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}

const deleteTarea = async (id) => {
    try {
        const result = await confirmDialog(
            "Estas seguro?",
            "Ya no podras revertir esto!",
            "warning"
        );
        if (result.isConfirmed) {
            await axios.delete(route("tarea.destroy", id));
            tareas.value = tareas.value.filter((tarea) => tarea.id !== id);
            showToast("El registro ha sido eliminado", "success");

        }
    } catch (error) {
        console.log(error);

    }
}
console.log({ tareas: tareas });
const isModalVisible = ref(false);

const openModal = () => {
    isModalVisible.value = true;
};

const closeModal = () => {
    isModalVisible.value = false;
};
</script>
