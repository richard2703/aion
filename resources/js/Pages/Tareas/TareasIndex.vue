<script setup>
import { Head } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout2.vue";
import Modalv2 from "@/Components/v2/modal-v2.vue";

const title = "Tareas";

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

import { ref } from "vue";
const isModalOpen = ref(false);

const openModal = () => {
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

// -------
const isModalOpenTaks = ref(false);

const openModalTaks = () => {
  isModalOpenTaks.value = true;
};

const closeModalTaks = () => {
  isModalOpenTaks.value = false;
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
            <div>M</div><div>T</div><div>W</div><div>T</div><div>F</div><div>S</div><div>S</div>
          </div>

          <!-- Días del mes -->
          <div class="gap-px grid grid-cols-7 bg-gray-200 shadow mt-2 rounded-lg ring-1 ring-gray-200 text-sm">
            <button
              v-for="(day, dayIdx) in days"
              :key="day.date"
              class="focus:z-10 hover:bg-gray-100 py-1.5"
              :class="[
                day.isCurrentMonth ? 'bg-white' : 'bg-gray-50',
                (day.isSelected || day.isToday) && 'font-semibold',
                day.isSelected ? 'text-white' : 'text-gray-900'
              ]"
            >
              <time :datetime="day.date" class="flex justify-center items-center mx-auto rounded-full w-7 h-7">
                {{ day.date.split('-').pop() }}
              </time>
            </button>
          </div>

          <button type="button" @click="openModal" class="bg-black hover:bg-gray-800 shadow mt-8 px-3 py-3 rounded-md w-full font-semibold text-md text-white">
            Nueva tarea
          </button>
        </div>

        <!-- Lista de Reuniones -->
        <ol class="lg:col-span-7 xl:col-span-8 mt-3 divide-y divide-gray-100 text-md">
          <div class="border-[#E1E2E5] p-4 border border-solid rounded-md h-[640px] cursor-pointer overflow-scroll">
            <li v-for="meeting in meetings" :key="meeting.id" @click="openModalTaks" class="flex gap-x-6 py-5">
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

    <!-- Modal New Taks -->
    <Modalv2 :isOpen="isModalOpen" title="Nueva Tarea" @close="closeModal">
      <form>
        <!-- tittle -->
        <div class="mb-5">
          <label for="task-title" class="block font-medium text-gray-700 text-sm">Título de la tarea</label>
          <input id="task-title" type="text" class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full sm:text-sm" />
        </div>
       
        <!-- tipo de reunion -->
         <div class="mb-5">
         <label for="task-status" class="block font-medium text-gray-700 text-sm">Tipo de reunion</label>
            <select id="task-status" class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full sm:text-sm">
                <option value="En proceso">Reunion diaria de productividad matinal</option>
                <option value="Completado">Completado</option>
                <option value="Pendiente">Pendiente</option>
            </select>
          </div>

           <!-- flujo de valor -->
         <div class="mb-5">
         <label for="task-status" class="block font-medium text-gray-700 text-sm">Flujo de valor</label>
            <select id="task-status" class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full sm:text-sm">
                <option value="En proceso">Direccion general</option>
                <option value="Completado">Completado</option>
                <option value="Pendiente">Pendiente</option>
            </select>
          </div>

        <!-- responsable y cliente de tarea -->
        <div class="mb-5">
            <div class="gap-4 grid grid-cols-1 md:grid-cols-2">
                <div class="mb-4">
                    <label for="task-title" class="block font-medium text-gray-700 text-sm">Responsable</label>
                    <input id="task-title" type="text" class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full sm:text-sm" />
                </div>

                <div>
                    <label for="task-status" class="block font-medium text-gray-700 text-sm">Cliente de tarea</label>
                    <select id="task-status" class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full sm:text-sm">
                      <option value="En proceso">En proceso</option>
                      <option value="Completado">Completado</option>
                      <option value="Pendiente">Pendiente</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Estado y fecha entrega -->
        <div class="mb-5">
            <div class="gap-4 grid grid-cols-1 md:grid-cols-2">
                <div class="mb-4">
                    <label for="task-title" class="block font-medium text-gray-700 text-sm">Título de la tarea</label>
                    <input id="task-title" type="text" class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full sm:text-sm" />
                </div>

                <div class="mb-5">
                    <label for="task-date" class="block font-medium text-gray-700 text-sm">Fecha</label>
                    <input id="task-date" type="date" class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full sm:text-sm" />
                </div>
                
            </div>
        </div>

        <div class="mb-5">
            <label for="task-title" class="block font-medium text-gray-700 text-sm">Notas</label>
            <Textarea class="block border-gray-300 shadow-sm mt-1 py-3 focus:border-black rounded-md focus:ring-black w-full sm:text-sm"/>
        </div>


        <div class="flex justify-end">
          <button type="button" @click="closeModal" class="bg-gray-300 hover:bg-gray-400 mr-2 px-4 py-2 rounded-md text-gray-700">Cancelar</button>
          <button type="submit" class="bg-black hover:bg-gray-800 px-4 py-2 rounded-md text-white">Guardar</button>
        </div>
      </form>
    </Modalv2>

    <!-- modal vew taks -->
    <Modalv2 :isOpen="isModalOpenTaks" @close="closeModal">
      <form>   
            <!-- icons group -->
            <div class="flex justify-end gap-7 mb-8">
                <i class="pi-pen-to-square cursor-pointer pi" style="color: black; font-size: 1.2rem"></i>
                <i class="cursor-pointer pi pi-trash" style="color: red; font-size: 1.2rem"></i>
            </div>
            <h2 class="mb-2 text-xl">ID:50 - Titulo para describir la tarea</h2>
            <div class="flex">
                <p class="pr-3">Status: En proceso</p>  |
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
                <hr/>  
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
                <hr/>  
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
