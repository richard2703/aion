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
                            <Fieldset legend="Información general" class="h-80 overflow-y-auto">
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
                            <Fieldset legend="Asistentes" class="h-80 overflow-y-auto">
                                <div class="float-right">
                                    <PrimaryButton class="pi pi-plus" v-if="newAsistente"
                                        @click="newAsistente = !newAsistente">
                                    </PrimaryButton>
                                    <PrimaryButton class="pi pi-minus" v-if="!newAsistente"
                                        @click="newAsistente = !newAsistente">
                                    </PrimaryButton>
                                </div>
                                <div
                                    class="grid lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1 gap-4 flex justify-between">
                                    <div class="flex gap-2">
                                        <InputLabel for="lider" value="Lider: " />&nbsp;
                                        <div class="flex">
                                            <InputLabel for="lider" :value="minuta.lider.name" />
                                        </div>
                                        <div v-if="!newAsistente">
                                            <form @submit.prevent="submit" class="flex">
                                                <AutoComplete v-model="form.user_id" optionLabel="name"
                                                    :suggestions="filteredUsuarios" forceSelection @complete="search"
                                                    placeholder="" />
                                                <PrimaryButton class="float-right pi pi-check"></PrimaryButton>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="asistentes" value="Asistentes: " /><br>
                                    <div class="mx-2">
                                        <ul v-for="asistente in asistentes" :key="asistente.id">
                                            <li>{{ asistente.user.name }}&nbsp;
                                                <button class="float-right mx-4 pi pi-times text-red-500"
                                                    @click="deleteAsistente(asistente.id)"></button>
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

                                <div class="card flex justify-center">


                                    <Popover ref="op">
                                        <div class="flex flex-col gap-4 w-[25rem]">
                                            <div>
                                                <span class="font-medium block mb-2">Share this document</span>
                                                Seccion 1

                                            </div>
                                            <div>
                                                <span class="font-medium block mb-2">Invite Member</span>
                                                seccion 2
                                            </div>
                                            <div>
                                                <span class="font-medium block mb-2">Team Members</span>
                                                seccion 3
                                            </div>
                                        </div>
                                    </Popover>
                                </div>

                            </div>

                            <div class="container mx-auto overflow-x-auto gap-4">
                                <div class="flex gap-4">
                                    <InputText v-model="globalFilter" placeholder="Buscar..." class="mb-3" />
                                    <PrimaryButton class=" mb-4 float-right pi pi-filter" @click="toggle">
                                    </PrimaryButton>
                                    <!-- Trigger to open modal -->
                                    <PrimaryButton class=" mb-4 float-right pi pi-plus" @click="openModal('create')">
                                    </PrimaryButton>
                                </div>
                                <DataTable :value="tareas" paginator :rows="rows" :totalRecords="totalRecords"
                                    :lazy="true" :first="first" @page="onPage" @sort="onSort"
                                    :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem"
                                    :filters="filters" :globalFilterFields="[
                                        'id',
                                        'tarea',
                                        'area.nombre',
                                        'departamento.nombre',
                                        'responsable.name',
                                        'fecha_entrega',
                                        'estatus.titulo',
                                    ]" :sortField="sortField" :sortOrder="sortOrder"
                                    class="p-datatable-sm p-datatable-striped p-datatable-gridlines">
                                    <template #empty> No data found. </template>
                                    <Column field="id" header="ID" headerStyle="width:4em;"
                                        bodyStyle="text-align:center;" sortable></Column>
                                    <Column field="tarea" header="Titulo" headerStyle="width:4em;"
                                        bodyClass="text-center" sortable></Column>
                                    <Column field="estatus.titulo" header="Estatus" headerStyle="width:4em;"
                                        bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                                    <Column field="departamento.nombre" header="Fujo de valor" headerStyle="width:4em;"
                                        bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                                    <Column field="responsable.name" header="Responsable" headerStyle="width:4em;"
                                        bodyClass="text-center" sortable>
                                    </Column>
                                    <Column field="fecha" header="Fecha de entrega" headerStyle="width:4em;"
                                        bodyStyle="text-align:center;" bodyClass="text-center" sortable>
                                        <template #body="slotProps">
                                            {{ formatearFecha(slotProps.data.fecha) }}
                                        </template>
                                    </Column>
                                    <Column field="nota" header="Notas" headerStyle="width:4em;" bodyClass="text-center"
                                        sortable>
                                    </Column>
                                    <Column header="" headerStyle="width:4em;">
                                        <template #body="slotProps" class="text-center">
                                            <div class="flex justify-center">
                                                <PrimaryButton class="m-2 pi pi-pen-to-square"
                                                    @click="openModal('edit', slotProps.data.id)">
                                                </PrimaryButton>

                                                <PrimaryButton class="m-2 pi pi-trash" @click.prevent="
                                                    deleteTarea(slotProps.data.id)
                                                    ">
                                                </PrimaryButton>
                                            </div>
                                        </template>
                                    </Column>
                                </DataTable>
                            </div>

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
                @close="isEditModalVisible = false">
                <template v-slot="{ modalData }">

                    <TareasEdit class="z-50" :minuta="modalData.minuta" :task="modalData.tarea"
                        @close="isEditModalVisible = false" @tareaGuardada="actualizarTareas" />
                </template>
            </Modal>
        </div>
    </Layout>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
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
import Button from "primevue/button";
import Popover from "primevue/popover";
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import InputText from "primevue/inputtext";



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

const totalRecords = ref(0);
const rows = ref(10);
const first = ref(0);
const globalFilter = ref("");
const filters = ref({});
const sortField = ref("id");
const sortOrder = ref(1);


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

const getTareas = async (
    minuta_id = minuta.value.id,
    page = 1,
    rowsPerPage = rows.value,
    filter = "",
    sortField = "id",
    sortOrder = 1
) => {
    await axios
        .get(`/api/tareas/${minuta_id}`, {
            params: {
                page,
                rows: rowsPerPage,
                filter,
                sortField,
                sortOrder: sortOrder === 1 ? "asc" : "desc",
            },
        })
        .then((response) => (tareas.value = response.data.data))
        .catch((error) => {
            console.log(error);
        });
    console.log({ tareasFunction: tareas.value });
}

watch(globalFilter, (newValue) => {
    filters.value = {
        global: { value: newValue, matchMode: "contains" },
    };
    getTareas(minuta.value.id, 1, rows.value, newValue, sortField.value, sortOrder.value);
});

const onPage = (event) => {
    const page = event.page + 1;
    rows.value = event.rows;
    getTareas(
        minuta.value.id,
        page,
        rows.value,
        globalFilter.value,
        sortField.value,
        sortOrder.value
    );
};

const onSort = (event) => {
    sortField.value = event.sortField || "id";
    sortOrder.value = event.sortOrder;
    getTareas(
        minuta.value.id,
        1,
        rows.value,
        globalFilter.value,
        sortField.value,
        sortOrder.value
    );
};

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

// filter stuff
const op = ref();
const members = ref([
    { name: 'Amy Elsner', image: 'amyelsner.png', email: 'amy@email.com', role: 'Owner' },
    { name: 'Bernardo Dominic', image: 'bernardodominic.png', email: 'bernardo@email.com', role: 'Editor' },
    { name: 'Ioni Bowcher', image: 'ionibowcher.png', email: 'ioni@email.com', role: 'Viewer' }
]);

const toggle = (event) => {
    op.value.toggle(event);
}
</script>
