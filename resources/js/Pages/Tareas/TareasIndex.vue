<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout2.vue";
import Modalv2 from "@/Components/v2/modal-v2.vue";
import PilaresSelect from "@/Components/v2/PilaresSelect.vue";
import { onMounted, ref } from "vue";
import axios from "axios";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import AutoComplete from 'primevue/autocomplete';
import Textarea from 'primevue/textarea';
import { showToast } from "@/Pages/utils/SweetAlert.service";

const title = "Tareas";

const isEditing = ref(false);
const editingTarea = ref({});
const selectedPilar = ref(1);
const areas = ref({});
const minutas = ref({});
const departamentos = ref({});
const usuarios = ref([]);
const filteredUsuarios = ref();
const evidencias = ref({});
const tareaDetail = ref({});

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
  getAreas();
  getUsuarios();
  getMinutas();
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
    console.log(form.data());

    if (isEditing.value) {
      await form.patch(route("tareas.update", editingTarea.value.id), {
        onFinish: () => {
          showToast("El registro ha sido actualizado", "success");
          closeModal();
          // TODO: Fetch list elements
        },
      });
    } else {
      await form.post(route("tareas.store"), {
        onFinish: () => {
          showToast("El registro ha sido creado", "success");
          closeModal();
          // TODO: Fetch list elements
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

/////

const meetings = [
  { id: 1, date: 'January 10th, 2022', datetime: '2022-01-10T17:00', titleTaks: 'Titulo para describir la tarea', state: 'En proceso' },
  { id: 2, date: 'January 10th, 2022', datetime: '2022-01-10T17:00', titleTaks: 'Titulo para describir la tarea', state: 'En proceso' },
  { id: 3, date: 'January 10th, 2022', datetime: '2022-01-10T17:00', titleTaks: 'Titulo para describir la tarea', state: 'En proceso' },
  { id: 4, date: 'January 10th, 2022', datetime: '2022-01-10T17:00', titleTaks: 'Titulo para describir la tarea', state: 'En proceso' },
];

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

    <div class="pl-5 sm:rounded-lg overflow-hidden">
      <!-- Título -->
      <div class="breadcrumbsTitulo">
        <h3 class="font-semibold text-xl">Tareas</h3>
      </div>

      <PilaresSelect :currentPilarID="selectedPilar" :onSelectedPilar="onSelectedPilar"></PilaresSelect>


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
          <div class="border-[#E1E2E5] p-4 border border-solid rounded-md h-[640px] cursor-pointer overflow-scroll">
            <li v-for="meeting in meetings" :key="meeting.id" @click="openModalTask(meeting)" class="flex gap-x-6 py-5">
              <div class="flex-auto">
                <h3 class="mb-2 pr-10 font-semibold text-gray-900">{{ meeting.titleTaks }}</h3>
                <dl class="flex xl:flex-row flex-col mt-2 text-gray-500">
                  <div class="flex items-baseline gap-x-3">
                    <i class="pi pi-calendar" style="font-size: 1rem"></i>
                    <time :datetime="meeting.datetime">{{ meeting.date }}</time>
                  </div>
                  <div class="flex items-start gap-x-3">
                    <div class="pl-3">|<b> Estado:</b></div>
                    <dd>{{ meeting.state }}</dd>
                  </div>
                </dl>
                <hr class="mt-3" />
              </div>
            </li>
          </div>
        </ol>
      </div>
    </div>

    <!-- Modal New Task -->
    <Modalv2 :isOpen="isModalOpen" :title="isEditing ? 'Editar Tarea' : 'Nueva Tarea'" @close="closeModal">
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
              <AutoComplete v-model="form.revisor_id" optionLabel="name" :suggestions="filteredUsuarios" forceSelection
                @complete="search" placeholder="" />
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


        <div class="flex justify-end">
          <button type="button" @click="closeModal"
            class="bg-gray-300 hover:bg-gray-400 mr-2 px-4 py-2 rounded-md text-gray-700">Cancelar</button>
          <button type="submit" class="bg-black hover:bg-gray-800 px-4 py-2 rounded-md text-white"
            :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Guardar</button>
        </div>
      </form>
      <div v-if="isEditing == true">
        <hr class="my-4">
        <form @submit.prevent="uploadFile" enctype="multipart/form-data">
          <div class="grid grid-cols-1 gap-4 mt-5 mb-2">
            <div>
              <label for="task-title" class="block font-medium text-gray-700 text-sm">Muestra del trabajo
                realizado</label>
              <input id="img_ref" type="file" @change="onFileChange('img_ref', $event)" class="mt-1 block w-full"
                autocomplete="img_ref" />
            </div>

            <div class="col-span-full flex items-center justify-end mt-4">
              <button type="submit" class="ms-4 pi pi-upload" :class="{
                'opacity-25': evidenciaForm.processing || form.processing,
              }" :disabled="evidenciaForm.processing || form.processing">
              </button>
            </div>
            <div
              class="grid  grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 gap-4">
              <div v-for="evidencia in evidencias" class="card w-60 bg-slate-100 text-center">
                <Image :src="evidencia.img_ref" alt="Image" width="250" preview />
                <!-- <img :src="evidencia" alt="" srcset=""> -->
                <a class="pi pi-trash text-red-500 cursor-pointer hover:text-red-700 text-2xl"
                  @click="deleteEvidencia(evidencia.id)"></a>
              </div>

            </div>

          </div>
        </form>
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
