<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout2.vue";
import Modalv2 from "@/Components/v2/modal-v2.vue";
import PilaresSelect from "@/Components/v2/PilaresSelect.vue";
import { onMounted, ref, watch } from "vue";
import axios from "axios";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import AutoComplete from 'primevue/autocomplete';
import Textarea from 'primevue/textarea';
import DataTable from "primevue/datatable";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";
import { format } from 'date-fns';
import Column from "primevue/column";
import InputText from "primevue/inputtext";
import InputLabel from "@/Components/InputLabel.vue";

const title = "Tareas";

const props = defineProps({
  authUser: Object,
  area_id: Number,
  departamento_id: Number,
});

const isEditing = ref(false);
const editingTarea = ref({});
const selectedPilar = ref(1);
const areas = ref({});
const minutas = ref({});
const departamentos = ref({});
const usuarios = ref(props.usuarios);
const filteredUsuarios = ref();
const evidencias = ref({});
const tareaDetail = ref({});

const tareas = ref();
const authUser = ref(props.authUser);
const area_id = ref(props.area_id);
const departamento_id = ref(props.departamento_id);

//filtro global y paginado
const customFilter = ref(false);
const totalRecords = ref(0);
const rows = ref(10);
const first = ref(0);
const sortField = ref("id");
const sortOrder = ref(1);
const globalFilter = ref("");
const filters = ref({});

const pilar = ref(area_id.value || "1");
const flujoValor = ref(departamento_id.value || "");
const responsable = ref("");
const revisor = ref("");
const estatus = ref("");
const desde = ref("");
const hasta = ref("");

const formValidate = useForm({
  validacion: "",
});

const formatearFecha = (fecha) => {
  return format(new Date(fecha), 'dd/MM/yyyy');
};

const getTareas = async (
  page = 1,
  rowsPerPage = rows.value,
  filter = "",
  sortField = "id",
  sortOrder = 1
) => {
  await axios
    .get(`/api/tareas/`, {
      params: {
        page,
        rows: rowsPerPage,
        filter,
        sortField,
        sortOrder: sortOrder === 1 ? "asc" : "desc",
      },
    })
    .then((response) => {
      tareas.value = response.data.data
      totalRecords.value = response.data.total
      first.value = (response.data.current_page - 1) * rows.value
      console.log('tareas', tareas.value);

    })
    .catch((error) => {
      console.log(error);
    });
}

const onPage = (event) => {
  const page = event.page + 1;
  rows.value = event.rows;
  getTareas(
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

const onSelectedPilar = (pilarID) => {
  selectedPilar.value = pilarID;
};

let form = useForm({
  area_id: "",
  departamento_id: "",
  minuta_id: "",
  responsable_id: "",
  revisor_id: "",
  tarea: "",
  fecha: "",
  nota: "",
  estatus_id: "",
});

let evidenciaForm = useForm({
  tarea_id: "",
  img_ref: null,
});

onMounted(() => {
  filters.value = {
    area_id: { value: pilar.value, matchMode: "contains" },
    departamento_id: { value: flujoValor.value, matchMode: "contains" },
  };
  getTareas('', '', filters.value, '', '');
  getAreas();
  getDepartamentos(selectedPilar.value);
  getUsuarios();
});

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
    getTareas(1, rows.value, filters.value, sortField.value, sortOrder.value);
  }
);

watch([selectedPilar], (newPilar) => {
  clearFilter();
  area_id.value = newPilar;
  pilar.value = newPilar;
  globalFilter.value = "";
});

const getTarea = async (tareaId) => {
  await axios
    .get(route("tareas.show", tareaId))
    .then((response) => {
      editingTarea.value = response.data
    })
    .catch((error) => {
      console.log(error);
    });
}

const getAreas = async () => {
  try {
    const response = await axios.get("/api/areas");
    areas.value = response.data;
  } catch (error) {
    console.error(error);
  }
};

const onChange = async (event) => {
  await getDepartamentos(event.target.value);
};

const getDepartamentos = async (area_id) => {
  try {
    const response = await axios.get(route("departamentos.byArea", area_id));
    departamentos.value = response.data.departamentos;
  } catch (error) {
    console.error(error);
  }
};

const getUsuarios = async () => {
  try {
    const response = await axios.get("/api/usuarios/all/todo");
    usuarios.value = response.data;
  } catch (error) {
    console.error(error);
  }
};

const getMinutas = async () => {
  try {
    const response = await axios.get("/api/minutas");
    minutas.value = response.data.data;
  } catch (error) {
    console.error(error);
  }
};

const submit = async () => {
  try {
    if (isEditing.value) {
      await form.patch(route("tareas.update", editingTarea.value.id), {
        onFinish: () => {
          showToast("El registro ha sido actualizado", "success");
          closeModal();
          selectedPilar.value = selectedPilar.value;
        },
      });
    } else {
      await form.post(route("tareas.store"), {
        onFinish: () => {
          showToast("El registro ha sido creado", "success");
          closeModal();
          selectedPilar.value = selectedPilar.value;
          // window.location.href = route('tareas.index');
        },
      });
    }
  } catch (error) {
    showToast("Ocurrio un error", "error");
    console.error(error);
  }
};

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
};

const onFileChange = (key, event) => {
  evidenciaForm[key] = event.target.files[0];
};

const uploadFile = async () => {
  try {

    const formData = new FormData();
    formData.append('tarea_id', evidenciaForm.tarea_id);
    formData.append('img_ref', evidenciaForm.img_ref);

    const response = await axios.post(route("tareas.evidencia.store"), formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    }).then((response) => {
      showToast("La evidencia ha sido subida", "success");
      getEvidencias();
    });
  } catch (error) {
    showToast("Ocurrio un error", "error");
    console.error(error);
  }
};

const getEvidencias = async () => {
  try {
    const response = await axios.get(route("tareaEvidencia.getByTarea", tarea.value.id));
    evidencias.value = response.data;
  } catch (error) {
    console.error(error);
  }
};

const deleteEvidencia = async (id) => {
  await axios
    .delete(route("tareas.evidencia.destroy", id))
    .then((response) => {
      showToast("La evidencia ha sido eliminada", "success");
      getEvidencias();
    })
    .catch((error) => {
      showToast("Ocurrio un error", "error");
      console.error(error);
    });
}

const filterTable = async () => {
  await axios
    .get(`/api/tareas`, {
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
  // customFilter.value = !customFilter.value
  getTareas();
};

const validateTarea = async (tarea, $event) => {
  try {
    if (tarea.revisor.name !== authUser.value.name) {
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

const sendMail = async () => {
  try {
    const result = await confirmDialog(
      "Notificacion de Tareas?",
      "Se enviaran sus tareas a todos los colaboradores!",
      "warning"
    );
    if (result.isConfirmed) {
      await axios.get(route("mailer.recordatorioTarea"))
        .then(() => {
          showToast("El correo ha sido enviado", "success");
        });
    }
  } catch (error) {
    console.log(error);
  }
};

/////

const days = [
  { date: '2021-12-27' },
  { date: '2021-12-28' },
  { date: '2021-12-29' },
  { date: '2021-12-30' },
  { date: '2021-12-31' },
  { date: '2022-01-01', isCurrentMonth: true },
  { date: '2022-01-02', isCurrentMonth: true },
  { date: '2022-01-12', isCurrentMonth: true, isToday: true },
  { date: '2022-01-22', isCurrentMonth: true, isSelected: true },
];

const isModalOpen = ref(false);

const openModal = (edit = false, tareaId = null) => {
  isModalOpenTask.value = false
  isEditing.value = edit

  console.log(edit);
  if (edit) {
    getTarea(tareaId).then(() => {
      form.reset();
      form.area_id = editingTarea.value.area_id;
      getDepartamentos(editingTarea.value.area_id);

      form.departamento_id = editingTarea.value.departamento_id;
      form.minuta_id = editingTarea.value.minuta_id;
      form.responsable_id = editingTarea.value.responsable ? editingTarea.value.responsable.name : '';
      form.revisor_id = editingTarea.value.revisor ? editingTarea.value.revisor.name : '';
      form.tarea = editingTarea.value.tarea;
      form.fecha = editingTarea.value.fecha;
      form.nota = editingTarea.value.nota;
      form.estatus_id = editingTarea.value.estatus_id;

      evidenciaForm.reset();
      evidenciaForm.tarea_id = editingTarea.value.id;

      isModalOpen.value = true;
    });
  } else {
    form.reset();
    isModalOpen.value = true;
    editingTarea.value = {};
  }
};

const closeModal = () => {
  isModalOpen.value = false;
  editingTarea.value = {};
};

const isModalOpenTask = ref(false);

const openModalTask = (tarea) => {
  tareaDetail.value = tarea
  console.log('Tarea', tareaDetail.value);
  isModalOpenTask.value = true;
};

const closeModalTask = () => {
  isModalOpenTask.value = false;
  tareaDetail.value = {};
};

</script>

<template>
  <Layout :titulo="title">

    <Head title="Tareas" />

    <PilaresSelect :currentPilarID="selectedPilar" :onSelectedPilar="onSelectedPilar"></PilaresSelect>

    <div class="pl-5 sm:rounded-lg overflow-hidden">

      <!-- Título -->
      <div class="mt-5 breadcrumbsTitulo">
        <h3 class="font-semibold text-[1.4rem]">Tareas</h3>
      </div>


      <!-- Componente de Calendario y Lista de Reuniones -->
      <div class="lg:gap-x-16 lg:grid lg:grid-cols-12 mt-6">
        <!-- Sección del Calendario -->
        <div class="lg:col-start-8 xl:col-start-9 lg:col-end-13 lg:row-start-1 mt-10 lg:mt-9 text-center">
          <!-- Navegación del mes -->
          <div class="flex items-center text-gray-900">
            <button type="button" class="-m-1.5 p-1.5 text-gray-400 hover:text-gray-500">
              <i class="pi-chevron-left pi" style="font-size: 1rem"></i>
            </button>
            <div class="flex-auto font-semibold text-sm">January</div>
            <button type="button" class="-m-1.5 p-1.5 text-gray-400 hover:text-gray-500">
              <i class="pi-chevron-right pi" style="font-size: 1rem"></i>
            </button>
          </div>

          <!-- Días de la semana -->
          <div class="grid grid-cols-7 mt-6 text-gray-500 text-xs">
            <div>M</div>
            <div>T</div>
            <div>W</div>
            <div>T</div>
            <div>F</div>
            <div>S</div>
            <div>S</div>
          </div>

          <!-- Días del mes -->
          <div class="gap-px grid grid-cols-7 bg-gray-200 shadow mt-2 rounded-lg ring-1 ring-gray-200 text-sm">
            <button v-for="(day, dayIdx) in days" :key="day.date" class="focus:z-10 hover:bg-gray-100 py-1.5" :class="[
              day.isCurrentMonth ? 'bg-white' : 'bg-gray-50',
              (day.isSelected || day.isToday) && 'font-semibold',
              day.isSelected ? 'text-white' : 'text-gray-900'
            ]">
              <time :datetime="day.date" class="flex justify-center items-center mx-auto rounded-full w-7 h-7">
                {{ day.date.split('-').pop() }}
              </time>
            </button>
          </div>

          <button type="button" @click="openModal(false)"
            class="bg-black hover:bg-gray-800 shadow mt-8 px-3 py-3 rounded-md w-full font-semibold text-md text-white">
            Nueva tarea
          </button>
        </div>

        <!-- Lista de Reuniones -->
        <ol class="lg:col-span-7 xl:col-span-8 mt-3 divide-y divide-gray-100 text-md">
          <div class="col-span-12">
            <div class="flex gap-4">
              <InputText v-model="globalFilter" placeholder="Buscar..." class="mb-3" />
              <PrimaryButton class="float-right bg-black mb-4 pi pi-filter" @click="openFilter">
              </PrimaryButton>
              <!-- <PrimaryButton v-if="customFilter" class="float-right bg-black mb-4 pi pi-times" @click="clearFilter">
              </PrimaryButton> -->
            </div>


            <div v-if="customFilter" class="">
              <form @submit.prevent="filterTable()">
                <div class="gap-2 border-gray-200 grid grid-cols-3 my-5 border rounded-lg">
                  <div class="m-4">
                    <InputLabel for="departamento_id" value="Flujo de valor: " />
                    <select ref="departamento_select"
                      class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                      v-model="flujoValor">
                      <option value="" selected>
                        Seleccione una opcion
                      </option>
                      <option v-for="departamento in departamentos" :key="departamento.id" :value="departamento.id">
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
                      <option v-for="usuario in usuarios" :key="usuario.id" :value="usuario.id">{{
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
                      <option v-for="usuario in usuarios" :key="usuario.id" :value="usuario.id">{{
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
                    <TextInput id="fecha" v-model="desde" type="date" class="block mt-1 w-full" autocomplete="fecha" />
                  </div>

                  <div class="m-4">
                    <InputLabel for="created_at" value="Fecha de entrega hasta: " />
                    <TextInput id="fecha" v-model="hasta" type="date" class="block mt-1 w-full" autocomplete="fecha" />
                  </div>
                </div>
              </form>
            </div>
          </div>

          <DataTable :value="tareas" paginator :rows="rows" :totalRecords="totalRecords" :lazy="true" :first="first"
            @page="onPage" @sort="onSort" :rowsPerPageOptions="[5, 10, 20, 50]" :filters="filters" :globalFilterFields="[
              'id',
              'tarea',
              'departamento.nombre',
              'responsable.name',
              'revisor.name',
              'fecha_entrega',
              'estatus.titulo',
            ]" :sortField="sortField" :sortOrder="sortOrder"
            class="p-datatable-gridlines p-datatable-sm p-datatable-striped">
            <template #empty> No data found. </template>
            <Column field="id" header="ID" headerStyle="width:4em;" bodyStyle="text-align:center;" sortable></Column>
            <!-- <Column field="tarea" header="Titulo" headerStyle="width:4em;" bodyClass="text-center" sortable></Column> -->
            <Column field="estatus.titulo" header="Estatus" headerStyle="width:4em;" bodyStyle="text-align:center;"
              bodyClass="text-center" sortable></Column>
            <Column field="departamento.nombre" header="Fujo de valor" headerStyle="width:4em;"
              bodyStyle="text-align:center;" bodyClass="text-center" sortable></Column>
            <Column field="responsable.name" header="Responsable" headerStyle="width:4em;" bodyClass="text-center"
              sortable>
              <template #body="slotProps">
                <div v-if="slotProps.data.responsable">
                  {{ slotProps.data.responsable.name }}
                </div>
                <div v-else class="text-center text-red-500">
                  Sin responsable
                </div>
              </template>
            </Column>
            <Column field="fecha" header="Fecha de entrega" headerStyle="width:4em;" bodyStyle="text-align:center;"
              bodyClass="text-center" sortable>
              <template #body="slotProps">
                {{ formatearFecha(slotProps.data.fecha) }}
              </template>
            </Column>
            <!-- <Column field="nota" header="Notas" headerStyle="width:4em;" bodyClass="text-center"
                                    sortable>
                                </Column> -->
            <!-- <Column field="revisor.name" header="Cliente de la tarea" headerStyle="width:4em;" bodyClass="text-center"
              sortable>
              <template #body="slotProps">
                <div v-if="slotProps.data.revisor">
                  {{ slotProps.data.revisor.name }}
                </div>
                <div v-else>
                  Sin cliente
                </div>
              </template>
            </Column> -->
            <!-- <Column header="Validacion" headerStyle="width:4em;" bodyClass="justify-center">
              <template #body="slotProps">
                <input type="checkbox" @change="validateTarea(slotProps.data, $event)"
                  :disabled="slotProps.data.validacion ? true : false"
                  :checked="slotProps.data.validacion ? true : false" /> Validar
              </template>
            </Column> -->
            <Column header="" headerStyle="width:4em;">
              <template #body="slotProps" class="text-center">
                <div class="flex justify-center">
                  <PrimaryButton v-if="slotProps.data.validacion !== 1" class="pi-pen-to-square m-2 pi"
                    @click="openModal(true, slotProps.data.id)">
                  </PrimaryButton>

                  <PrimaryButton class="bg-black m-2 pi pi-file-check"
                    :href="route('tareas.detail', slotProps.data.id)">
                  </PrimaryButton>

                  <PrimaryButton v-if="slotProps.data.validacion !== 1" class="bg-black m-2 pi pi-trash" @click.prevent="
                    deleteTarea(slotProps.data.id)
                    ">
                  </PrimaryButton>
                </div>
              </template>
            </Column>
          </DataTable>
        </ol>
      </div>
    </div>

    <!-- Modal New Task -->
    <Modalv2 :isOpen="isModalOpen" :title="isEditing ? 'Editar Tarea' : 'Nueva Tarea'" @close="closeModal">
    
      <!-- Content modal -->
      <div class="h-[500px] overflow-scroll">
        <form @submit.prevent="submit">
          <!-- tittle -->
          <div class="mb-5">
            <label for="task-title" class="block font-medium text-gray-700 text-sm">Título de la tarea</label>
            <TextInput id="task-title" type="text"
              class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full sm:text-sm"
              v-model="form.tarea" />
          </div>

          <!-- tipo de reunion -->
          <div class="mb-5">
            <label for="task-status" class="block font-medium text-gray-700 text-sm">Reunión</label>
            <select ref="departamento_select"
              class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full sm:text-sm"
              v-model="form.minuta_id" required>
              <option value="" disabled selected>
                Seleccione una opcion
              </option>
              <option v-for="minuta in minutas" :key="minuta.id" :value="minuta.id">
                {{ minuta.alias }}
              </option>
            </select>
          </div>

          <!-- Pilar -->
          <div class="mb-5">
            <label for="task-status" class="block font-medium text-gray-700 text-sm">Pilar</label>
            <select ref="departamento_select"
              class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full sm:text-sm"
              v-model="form.area_id" @change="onChange($event)" required>
              <option value="" disabled selected>
                Seleccione una opcion
              </option>
              <option v-for="area in areas" :key="area.id" :value="area.id">
                {{ area.nombre }}
              </option>
            </select>
          </div>

          <!-- flujo de valor -->
          <div class="mb-5">
            <label for="task-status" class="block font-medium text-gray-700 text-sm">Flujo de valor</label>
            <select ref="departamento_select"
              class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full sm:text-sm"
              v-model="form.departamento_id" required>
              <option value="" disabled selected>
                Seleccione una opcion
              </option>
              <option v-for="departamento in departamentos" :key="departamento.id" :value="departamento.id">
                {{ departamento.nombre }}
              </option>
            </select>
          </div>

          <!-- responsable y cliente de tarea -->
          <div class="mb-5">
            <div class="gap-4 grid grid-cols-1 md:grid-cols-2">
              <div class="mb-4">
                <label for="task-title" class="block font-medium text-gray-700 text-sm">Responsable</label>
                <AutoComplete v-model="form.responsable_id" optionLabel="name" :suggestions="filteredUsuarios"
                  forceSelection @complete="search" placeholder="" />
              </div>

              <div>
                <label for="task-status" class="block font-medium text-gray-700 text-sm">Cliente de tarea</label>
                <AutoComplete v-model="form.revisor_id" optionLabel="name" :suggestions="filteredUsuarios"
                  forceSelection @complete="search" placeholder="" />
              </div>
            </div>
          </div>

          <!-- Estado y fecha entrega -->
          <div class="mb-5">
            <div class="gap-4 grid grid-cols-1 md:grid-cols-2">
              <div class="mb-4">
                <label for="task-title" class="block font-medium text-gray-700 text-sm">Estatus</label>
                <select id="task-status" v-model="form.estatus_id" required
                  class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full sm:text-sm">
                  <option value="" selected disabled>
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

              <div class="mb-5">
                <label for="task-date" class="block font-medium text-gray-700 text-sm">Fecha</label>
                <TextInput id="fecha" v-model="form.fecha" type="date"
                  class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full sm:text-sm"
                  required autocomplete="fecha" />
              </div>

            </div>
          </div>

          <div class="mb-5">
            <label for="task-title" class="block font-medium text-gray-700 text-sm">Notas</label>
            <Textarea
              class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full sm:text-sm"
              v-model="form.nota" rows="3" cols="30" />
          </div>
        </form>
        <div v-if="isEditing == true">
          <hr class="my-4">
          <form @submit.prevent="uploadFile" enctype="multipart/form-data">
            <div class="gap-4 grid grid-cols-1 mt-5 mb-2">
              <div>
                <label for="task-title" class="block font-medium text-gray-700 text-sm">Muestra del trabajo
                  realizado</label>
                <input id="img_ref" type="file" @change="onFileChange('img_ref', $event)" class="block mt-1 w-full"
                  autocomplete="img_ref" />
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Footer -->
      <div>
        <div class="flex justify-between items-center col-span-full mt-4">
          <button type="submit" class="ms-4 pi pi-upload" :class="{
            'opacity-25': evidenciaForm.processing || form.processing,
          }" :disabled="evidenciaForm.processing || form.processing">
          </button>

          <div class="flex justify-end">
            <button type="button" @click="closeModal"
              class="bg-gray-300 hover:bg-gray-400 mr-2 px-4 py-2 rounded-md text-gray-700">Cancelar</button>
            <button type="submit" class="bg-black hover:bg-gray-800 px-4 py-2 rounded-md text-white"
              :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Guardar</button>
          </div>
          
        </div>

        <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4">
          <div v-for="evidencia in evidencias" class="bg-slate-100 w-60 text-center card">
            <Image :src="evidencia.img_ref" alt="Image" width="250" preview />
            <!-- <img :src="evidencia" alt="" srcset=""> -->
            <a class="text-2xl text-red-500 hover:text-red-700 cursor-pointer pi pi-trash"
              @click="deleteEvidencia(evidencia.id)"></a>
          </div>

        </div>
      </div>
    </Modalv2>

    <!-- modal vew Task -->
    <Modalv2 :isOpen="isModalOpenTask" @close="closeModalTask">
      <form>
        <!-- icons group -->
        <div class="flex justify-end gap-3 mb-8">

          <i class="hover:bg-gray-100 pi-pen-to-square p-3 hover:rounded-md cursor-pointer pi"
            style="color: black; font-size: 1.3rem" @click="openModal(true, tareaDetail.id)"></i>
          <i class="hover:bg-red-100 p-3 hover:rounded-md cursor-pointer pi pi-trash"
            style="color: red; font-size: 1.3rem"></i>
        </div>
        <h2 class="mb-2 text-xl">ID:50 - Titulo para describir la tarea</h2>
        <div class="flex">
          <p class="pr-3">Status: En proceso</p> |
          <p class="pl-3"> <i class="pi pi-calendar" style="font-size: 1rem"></i> Diciembre 10, 2024</p>
        </div>

        <div class="mb-5">
          <div class="block md:flex gap-32 my-5">
            <div class="mt-5">
              <p>Flujo de valor</p>
              <p class="text-gray-500">Dirección General</p>
            </div>
            <div class="mt-5">
              <p>Responsable</p>
              <p class="text-gray-500">Moises breton</p>
            </div>
          </div>
          <hr />
          <div class="block md:flex gap-32 my-5">
            <div class="mt-5">
              <p>Cliente de la tarea </p>
              <p class="text-gray-500">********</p>
            </div>
            <div class="mt-5">
              <p>Validación</p>
              <p class="text-gray-500">*********</p>
            </div>
          </div>
          <hr />
          <div class="block md:flex gap-32 my-5">
            <div class="mt-5">
              <p>Pilar</p>
              <p class="text-gray-500">TI</p>
            </div>
            <div class="mt-5">
              <p>Tipo de reunion</p>
              <p class="text-gray-500">Reunion diaria de productividad matinal</p>
            </div>
          </div>
        </div>
      </form>
    </Modalv2>


  </Layout>
</template>

<style scoped>
.mb-3 {
  margin-bottom: 1rem;
}
</style>
