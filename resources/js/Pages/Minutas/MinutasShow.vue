<script setup>
import { ref, onMounted, watch } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { usePage } from '@inertiajs/vue3';
import axios from "axios";
import Fieldset from 'primevue/fieldset';
import AutoComplete from 'primevue/autocomplete';
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import { format } from 'date-fns';
import Layout from "@/Layouts/Layout2.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";
import TareasCreate from "@/Pages/Minutas/Partials/Tareas/TareasCreate.vue";
import TareasEdit from "@/Pages/Minutas/Partials/Tareas/TareasEdit.vue";
import TareasDetail from "@/Pages/Minutas/Partials/Tareas/TareasDetail.vue";
import InputText from "primevue/inputtext";
import TextInput from "@/Components/TextInput.vue";




onMounted(() => {
    getUsuarios();
    getAsistentes(minuta.value.id);
    getTareas(minuta.value.id);
    getAreas();
    getDepartamentos();
});

const props = defineProps({
    minuta: Object,
    area_id: Number,
    departamento_id: Number,

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
const isCreateModalVisible = ref(false);
const isEditModalVisible = ref(false);
const isDetailModalVisible = ref(false);
const tarea = ref({});
const task_id = ref();
const areas = ref();
const departamentos = ref();
const customFilter = ref(false);
const page = usePage();

const area_id = ref(props.area_id);
const departamento_id = ref(props.departamento_id);
const pilar = ref(area_id.value || "");
const flujoValor = ref(departamento_id.value || "");
const responsable = ref("");
const revisor = ref("");
const estatus = ref("");
const desde = ref("");
const hasta = ref("");

//filtro global y paginado
const totalRecords = ref(0);
const rows = ref(10);
const first = ref(0);
const globalFilter = ref("");
const filters = ref({});
const sortField = ref("id");
const sortOrder = ref(1);

const getStatusClass = (status) => {
    switch (status) {
        case 'Terminado':
            return 'bg-green-200 text-green-800';
        case 'Retrasado':
            return 'bg-red-200 text-red-800';
        case 'En proceso':
            return 'bg-yellow-200 text-yellow-800';
        default:
            return 'bg-gray-200 text-gray-800';
    }
};

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

async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => {
            areas.value = response.data;
            formFilter.reset();
        })
        .catch((error) => {
            console.log(error);
        });
}

async function getDepartamentos() {
    await axios
        .get(route("departamentos.findAll"))
        .then((response) => (departamentos.value = response.data))
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
                usuario.name.toLowerCase().includes(query)
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

const filterTable = async (minuta_id) => {
    await axios
        .get(`/api/tareas/${minuta_id}`, {
            params: {
                formFilter: formFilter.data(),
            },
        })
        .then((response) => (tareas.value = response.data.data))
        .catch((error) => {
            console.log(error);
        });
};

const openFilter = () => {
    customFilter.value = !customFilter.value

};
const clearFilter = () => {
    pilar.value = '';
    flujoValor.value = '';
    responsable.value = '';
    revisor.value = '';
    estatus.value = '';
    desde.value = '';
    hasta.value = '';
    getTareas(minuta.value.id);
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
        .then((response) => {
            tareas.value = response.data.data;
            totalRecords.value = response.data.total;
            first.value = (response.data.current_page - 1) * rows.value;
        })
        .catch((error) => {
            console.log(error);
        });
}

watch(
    [globalFilter, pilar, flujoValor, responsable, revisor, estatus, desde, hasta],
    ([newGlobalFilter, newPilar, newFlujoValor, newResponsable, newRevisor, newEstatus, newDesde, newHasta]) => {
        filters.value = {
            global: { value: newGlobalFilter, matchMode: "contains" },
            area_id: { value: newPilar, matchMode: "contains" },
            departamento_id: { value: newFlujoValor, matchMode: "contains" },
            responsable_id: { value: newResponsable, matchMode: "contains" },
            revisor_id: { value: newRevisor, matchMode: "contains" },
            estatus_id: { value: newEstatus, matchMode: "contains" },
            desde: { value: newDesde, matchMode: "contains" },
            hasta: { value: newHasta, matchMode: "contains" },
        };
        getTareas(minuta.value.id, 1, rows.value, filters.value, sortField.value, sortOrder.value);
    }
);

const onPage = (event) => {
    const page = event.page + 1;
    rows.value = event.rows;
    getTareas(
        minuta.value.id,
        page,
        rows.value,
        filters.value,
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
        filters.value,
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


const openModal = async (tipo, id) => {
    if (tipo === 'create') {
        isCreateModalVisible.value = true;
    } else if (tipo === 'edit') {
        await axios.get(route("tareas.show", id)).then((response) => {
            tarea.value = response.data;
        })
        isEditModalVisible.value = true;
    } else {
        await axios.get(route("tareas.show", id)).then((response) => {
            tarea.value = response.data;
        })
        isDetailModalVisible.value = true;
    }
};

const closeModal = (tipo) => {
    if (tipo === 'create') {
        isCreateModalVisible.value = false;
    } else {
        isEditModalVisible.value = false;
    }
};

const validateTarea = async (tarea, $event) => {
    try {
        if (tarea.revisor.name !== page.props.auth.user.user.name) {
            await confirmDialog(
                "No autorizado",
                "No eres el cliente de esta tarea!",
                "error"
            );

            return $event.target.checked = false;
        }

        const result = await confirmDialog(
            "Estas seguro?",
            "La tarea se marcara como Terminda y no podras revertir esto!",
            "warning"
        );
        if (result.isConfirmed) {
            await axios.patch(route("tareas.validar", tarea.id), {
                validacion: 1,
                estatus_id: 4
            }).then(() => {
                showToast("El registro ha sido eliminado", "success");
                getTareas();
            });

        } else {
            $event.target.checked = false;
        }
    } catch (error) {
        console.log(error);

    }

};

// TODO: Send mail REMOVE IS NOT USED
const sendMail = async () => {
    try {
        const result = await confirmDialog(
            "Notificacion de Tareas?",
            "Se enviaran las tareas de esta minuta!",
            "warning"
        );
        if (result.isConfirmed) {
            await axios.get(route("mailer.sendTareasByMinuta", minuta.value.id))
                .then(() => {
                    showToast("El correo ha sido enviado", "success");
                });
        }
    } catch (error) {
        console.log(error);
    }
};
</script>

<template>
    <Layout :titulo="title">

        <Head title="Minutas" />
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Detalles de Minuta</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('minutas.index')" class="px-1">
                <h3>Minutas -</h3>
                </Link>
                <Link :href="route('minutas.show', minuta.id)" class="active">
                <h3>Show</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white shadow-xl sm:rounded-lg overflow-hidden">
                <div>
                    <div
                        class="flex justify-end gap-4 border-gray-200 grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 bg-white my-4 px-4 py-2 border-b">
                        <div class="bg-white px-4 py-2">
                            <Fieldset legend="Información general" class="h-80 overflow-y-auto">
                                <div class="gap-4 grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2">
                                    <div class="flex mt-4">
                                        <InputLabel for="alias" value="Titulo: " />&nbsp;
                                        <InputLabel for="alias" :value="minuta.alias" />
                                    </div>
                                    <div class="flex mt-4">
                                        <InputLabel for="pilar" value="Pilar: " />&nbsp;
                                        <InputLabel for="pilar" :value="minuta.area.nombre" />
                                    </div>
                                    <div class="flex mt-4">
                                        <InputLabel for="flujo_valor" value="Flujo de valor: " />&nbsp;
                                        <InputLabel for="flujo_valor" :value="minuta.departamento.nombre" />
                                    </div>
                                    <div class="flex mt-4">
                                        <InputLabel for="tipo" value="Tipo: " />&nbsp;
                                        <InputLabel for="tipo" :value="minuta.tipo_minuta.titulo" />
                                    </div>
                                    <div class="flex mt-4">
                                        <InputLabel for="fecha" value="Fecha: " />&nbsp;
                                        <InputLabel for="fecha" :value="formatearFecha(minuta.created_at)" />
                                    </div>
                                    <div class="flex mt-4">
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
                                    class="flex justify-between gap-4 grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2">
                                    <div class="flex gap-2">
                                        <InputLabel for="lider" value="Lider: " />&nbsp;
                                        <div class="flex">
                                            <InputLabel for="lider" :value="minuta.lider.name" />
                                        </div>
                                        <div v-if="!newAsistente">
                                            <form @submit.prevent="submit" class="flex gap-2">
                                                <AutoComplete v-model="form.user_id" optionLabel="name"
                                                    :suggestions="filteredUsuarios" forceSelection @complete="search"
                                                    placeholder="" />
                                                <PrimaryButton class="float-right pi pi-save"></PrimaryButton>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="asistentes" value="Asistentes: " /><br>
                                    <div class="mx-2">
                                        <ul v-for="asistente in asistentes" :key="asistente.id">
                                            <li>{{ asistente.user.name }}&nbsp;
                                                <button class="float-right mx-4 text-red-500 pi pi-times"
                                                    @click="deleteAsistente(asistente.id)"></button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </Fieldset>
                        </div>
                    </div>
                    <div class="border-gray-200 bg-white px-4 py-2 border-b">
                        <div class="mx-auto container">
                            <div class="flex justify-between">
                                <h2>Tareas</h2>
                            </div>

                            <div class="mx-auto overflow-x-auto container">
                                <div class="flex gap-4">
                                    <InputText v-model="globalFilter" placeholder="Buscar..." class="mb-3" />
                                    <PrimaryButton class="float-right mb-4 pi pi-filter" @click="openFilter">
                                    </PrimaryButton>
                                    <PrimaryButton v-if="customFilter" class="float-right mb-4 pi pi-times"
                                        @click="clearFilter">
                                    </PrimaryButton>
                                    <!-- Trigger to open modal -->
                                    <PrimaryButton class="float-right mb-4 pi pi-plus" @click="openModal('create')">
                                    </PrimaryButton>

                                    <!-- TODO: Send mail REMOVE IS NOT USED -->
                                    <!-- <PrimaryButton v-if="$page.props.auth.user.user.name == minuta.lider.name"
                                        class="float-right mb-4 pi pi-envelope" @click="sendMail()">
                                    </PrimaryButton> -->

                                </div>


                                <!-- formulario de filtrado de tareas -->
                                <div v-if="customFilter" class="">
                                    <form @submit.prevent="filterTable(minuta.id)">
                                        <div class="flex gap-2 border-gray-200 grid grid-cols-4 m-4 border rounded-lg">
                                            <div class="m-4">
                                                <InputLabel for="area_id" value="Area: " />
                                                <select ref="area_select"
                                                    class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                                    v-model="pilar">
                                                    <option value="" selected>
                                                        Seleccione una opcion
                                                    </option>
                                                    <option v-for="area in areas" :key="area.id" :value="area.id">
                                                        {{ area.nombre }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="m-4">
                                                <InputLabel for="departamento_id" value="Departamento: " />
                                                <select ref="departamento_select"
                                                    class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                                    v-model="flujoValor">
                                                    <option value="" selected>
                                                        Seleccione una opcion
                                                    </option>
                                                    <option v-for="departamento in departamentos" :key="departamento.id"
                                                        :value="departamento.id">
                                                        {{ departamento.nombre }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="m-4">
                                                <InputLabel for="responsable_id" value="Responsable: " />
                                                <select ref="responsable_select"
                                                    class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                                    v-model="responsable">
                                                    <option value="" selected>
                                                        Seleccione una opcion
                                                    </option>
                                                    <option v-for="usuario in usuarios" :key="usuario.id"
                                                        :value="usuario.id">{{
                                                            usuario.name }}</option>
                                                </select>
                                            </div>

                                            <div class="m-4">
                                                <InputLabel for="cliente_id" value="Cliente de tarea: " />
                                                <select ref="cliente_select"
                                                    class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                                    v-model="revisor">
                                                    <option value="" selected>
                                                        Seleccione una opcion
                                                    </option>
                                                    <option v-for="usuario in usuarios" :key="usuario.id"
                                                        :value="usuario.id">{{
                                                            usuario.name }}</option>
                                                </select>
                                            </div>

                                            <div class="m-4">
                                                <InputLabel for="estatus_id" value="Estatus: " />
                                                <select ref="estatus_select"
                                                    class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                                    v-model="estatus">
                                                    <option value="" selected>
                                                        Seleccione una opcion </option>
                                                    <option value=1>
                                                        Retrasado </option>
                                                    <option value=2>
                                                        Iniciado </option>
                                                    <option value=3>
                                                        En proceso </option>
                                                    <option value=4>
                                                        Terminado </option>
                                                </select>
                                            </div>

                                            <div class="m-4">
                                                <InputLabel for="fecha" value="Fecha de entrega de: " />
                                                <TextInput id="fecha" v-model="desde" type="date"
                                                    class="block mt-1 w-full" autocomplete="fecha" />
                                            </div>

                                            <div class="m-4">
                                                <InputLabel for="created_at" value="Fecha de entrega hasta: " />
                                                <TextInput id="fecha" v-model="hasta" type="date"
                                                    class="block mt-1 w-full" autocomplete="fecha" />
                                            </div>

                                            <div class="m-4">
                                                <PrimaryButton class="float-right m-4 pi pi-search" type="submit">
                                                </PrimaryButton>
                                            </div>
                                        </div>
                                    </form>
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
                                    class="p-datatable-gridlines p-datatable-sm p-datatable-striped">
                                    <template #empty> No data found. </template>
                                    <Column field="id" header="ID" headerStyle="width:4em;"
                                        bodyStyle="text-align:center;" sortable></Column>
                                    <Column field="tarea" header="Titulo" headerStyle="width:4em;"
                                        bodyClass="text-center" sortable></Column>

                                    <Column field="estatus.titulo" header="Estatus" headerStyle="width:4em;"
                                        bodyStyle="text-align:center;" bodyClass="text-center" sortable>
                                        <template #body="slotProps">
                                            <span :class="getStatusClass(slotProps.data.estatus.titulo)">
                                                {{ slotProps.data.estatus.titulo }}
                                            </span>
                                        </template>
                                    </Column>
                                    <Column field="departamento.nombre" header="Fujo de valor" headerStyle="width:4em;"
                                        bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
                                    <Column field="responsable.name" header="Responsable" headerStyle="width:4em;"
                                        bodyClass="text-center" sortable>
                                        <template #body="slotProps">
                                            <div v-if="slotProps.data.responsable">
                                                {{ slotProps.data.responsable.name }}
                                            </div>
                                            <div v-else class="text-center text-red-500">
                                                Sin responsable
                                            </div>
                                        </template>
                                    </Column>
                                    <Column field="fecha" header="Fecha de entrega" headerStyle="width:4em;"
                                        bodyStyle="text-align:center;" bodyClass="text-center" sortable>
                                        <template #body="slotProps">
                                            {{ formatearFecha(slotProps.data.fecha) }}
                                        </template>
                                    </Column>
                                    <Column field="revisor.name" header="Cliente de la tarea" headerStyle="width:4em;"
                                        bodyClass="text-center" sortable>
                                        <template #body="slotProps">
                                            <div v-if="slotProps.data.revisor">
                                                {{ slotProps.data.revisor.name }}
                                            </div>
                                            <div v-else>
                                                Sin cliente
                                            </div>
                                        </template>
                                    </Column>
                                    <Column header="Validacion" headerStyle="width:4em;" bodyClass="justify-center">
                                        <template #body="slotProps">
                                            <input type="checkbox" @change="validateTarea(slotProps.data, $event)"
                                                :disabled="slotProps.data.validacion ? true : false"
                                                :checked="slotProps.data.validacion ? true : false" /> Validar
                                        </template>
                                    </Column>
                                    <Column header="" headerStyle="width:4em;">
                                        <template #body="slotProps" class="text-center">
                                            <div class="flex justify-center">
                                                <PrimaryButton v-if="slotProps.data.validacion !== 1"
                                                    class="m-2 pi pi-file-edit"
                                                    @click="openModal('edit', slotProps.data.id)">
                                                </PrimaryButton>

                                                <PrimaryButton class="m-2 pi pi-file-check"
                                                    @click="openModal('detail', slotProps.data.id)">
                                                </PrimaryButton>

                                                <PrimaryButton v-if="slotProps.data.validacion !== 1"
                                                    class="m-2 pi pi-trash" @click.prevent="
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

            <Modal :show="isDetailModalVisible" :modalData="{ tarea, minuta }" maxWidth="lg"
                @close="isDetailModalVisible = false">
                <template v-slot="{ modalData }">

                    <TareasDetail class="z-50" :minuta="modalData.minuta" :task="modalData.tarea"
                        @close="isDetailModalVisible = false" @tareaGuardada="actualizarTareas" />
                </template>
            </Modal>
        </div>
    </Layout>
</template>
