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
                            <Fieldset legend="Información general">
                                <div class="grid lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1 gap-4">
                                    <div class="mt-4 flex">
                                        <InputLabel for="alias" value="Titulo: " />&nbsp;
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
                                        <InputLabel for="tipo" value="Tipo: " />&nbsp;
                                        <InputLabel for="tipo" :value="minuta.tipo" />
                                    </div>
                                    <div class="mt-4 flex">
                                        <InputLabel for="fecha" value="Fecha: " />&nbsp;
                                        <InputLabel for="fecha" :value="formatearFecha(minuta.created_at)" />
                                    </div>
                                    <div class="mt-4 flex">
                                        <InputLabel for="nota" value="Notas: " />&nbsp;
                                        <InputLabel for="nota" :value="minuta.notas" />
                                    </div>
                                </div>
                            </Fieldset>
                        </div>
                        <div>
                            <Fieldset legend="Asistentes">
                                <div
                                    class="grid lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1 gap-4 flex justify-between">
                                    <div class="flex">
                                        <InputLabel for="lider" value="Lider: " />&nbsp;
                                        <InputLabel for="lider" :value="minuta.lider.name" />
                                    </div>
                                    <div>
                                        <PrimaryButton v-if="newAsistente" @click="newAsistente = !newAsistente"
                                            class="float-right">mas</PrimaryButton>
                                        <PrimaryButton v-if="!newAsistente" @click="newAsistente = !newAsistente"
                                            class="float-right">menos</PrimaryButton>
                                    </div>
                                    <div v-if="!newAsistente">
                                        <form @submit.prevent="submit" class="flex">
                                            <AutoComplete v-model="form.user_id" optionLabel="name"
                                                :suggestions="filteredUsuarios" forceSelection @complete="search"
                                                placeholder="" />
                                            <PrimaryButton class="float-right">ok</PrimaryButton>
                                        </form>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="asistentes" value="Asistentes: " /><br>
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
                                <PrimaryButton class=" mb-4 float-right" @click="openModal('create')">Nueva Tarea
                                </PrimaryButton>
                            </div>

                            <DataTable :value="tareas" :lazy="true" tableStyle="min-width: 50rem"
                                class="p-datatable-sm p-datatable-striped p-datatable-gridlines">
                                <template #empty> Sin registros </template>
                                <Column field="id" header="ID" headerStyle="width:4em;" bodyStyle="text-align:center;"
                                    sortable></Column>
                                <Column field="tarea" header="Titulo" headerStyle="width:4em;" bodyClass="text-center"
                                    sortable></Column>
                                <Column field="estatus.titulo" header="Estatus" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                                <Column field="departamento.nombre" header="Fujo de valor" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                                <Column field="responsable.name" header="Responsable" headerStyle="width:4em;"
                                    bodyClass="text-center" sortable>
                                </Column>
                                <Column header="Fecha de entrega" headerStyle="width:4em;"
                                    bodyStyle="text-align:center;" bodyClass="text-center" sortable>
                                    <template #body="slotProps">
                                        {{ formatearFecha(slotProps.data.fecha) }}
                                    </template>
                                </Column>
                                <Column header="" headerStyle="width:4em;">
                                    <template #body="slotProps" class="text-center">
                                        <PrimaryButton class="m-2" @click="openModal('edit', slotProps.data.id)">
                                            Editar
                                        </PrimaryButton>

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

            <Modal :show="isCreateModalVisible" :modalData="minuta" maxWidth="lg" @close="isCreateModalVisible = false">
                <template v-slot="{ modalData }">
                    <TareasCreate class="z-50" :minuta="modalData" @close="isCreateModalVisible = false"
                        @tareaGuardada="actualizarTareas" />
                </template>
            </Modal>

            <Modal :show="isEditModalVisible" :modalData="{ tarea, minuta }" maxWidth="lg"
                @close="isEditModalVisible.value = false">
                <template v-slot="{ modalData }">

                    <TareasEdit class="z-50" :minuta="modalData.minuta" :task="modalData.tarea"
                        @close="isEditModalVisible = false" />
                </template>
            </Modal>
        </div>
    </Layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import axios from "axios";
import Fieldset from 'primevue/fieldset';
import AutoComplete from 'primevue/autocomplete';
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import { format } from 'date-fns';
import Layout from "@/Layouts/Layout.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";
import TareasCreate from "@/Pages/Minutas/Partials/Tareas/TareasCreate.vue";
import TareasEdit from "@/Pages/Minutas/Partials/Tareas/TareasEdit.vue";


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


console.log(tareas);
const formatearFecha = (fecha) => {
    return format(new Date(fecha), 'dd/MM/yyyy');
};

const actualizarTareas = () => {
    getTareas(minuta.value.id);
};

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
            console.log(form.data());
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
const isCreateModalVisible = ref(false);
const isEditModalVisible = ref(false);
const tarea = ref({});
const task_id = ref();

const openModal = async (tipo, id) => {
    if (tipo === 'create') {
        isCreateModalVisible.value = true;
    } else {
        await axios.get(route("tareas.show", id)).then((response) => {
            tarea.value = response.data;
        })
        isEditModalVisible.value = true;
    }
};

const closeModal = (tipo) => {
    if (tipo === 'create') {
        isCreateModalVisible.value = false;
    } else {
        isEditModalVisible.value = false;
    }
};
</script>
