<script setup>
import { ref } from "vue";
import Layout2 from "@/Components/compv2/layout2.vue";
import ListUi from "@/Components/compv2/listUi.vue";
import NavagationMenu from "@/Components/compv2/navigationMenu.vue";
import CardUi from "@/Components/compv2/cardui.vue";

const columns = [
  { header: "Procedimientos", key: "name" },
  { header: "Ejecución", key: "execution" },
  { header: "Documentación", key: "documentation" },
];

const data = {
  flujoDeValor: {
    items: [
      { name: "Bonos / Comisiones", execution: "En proceso", documentation: "Completa" },
      { name: "Vales de despensa", execution: "Pendiente", documentation: "Pendiente" },
    ],
  },
  proceso: {
    cards: [
      { title: "Administración de Prestaciones y Beneficios Adicionales a la Ley" },
      { title: "Administración de Prestaciones y Beneficios De Ley" },
      { title: "Administración y legislación laboral" },
    ],
  },
  procedimientos: {
    description: "Aquí puedes consultar los procedimientos detallados...",
  },
};

const selectedItem = ref("flujoDeValor"); // El ítem seleccionado
</script>

<template>
  <Layout2>
    <div class="grid grid-cols-3">
      <!-- Menú de navegación -->
      <div class="xl:col-span-1 col-span-3 p-10">
        <div class="text-base">
          <h4 class="font-semibold mb-1">Lista de navegación</h4>
          <p class="text-sm text-gray-500">
            Accede rápidamente a cada nivel de información dentro de los pilares
          </p>
        </div>
        <div class="mt-5">
          <NavagationMenu v-model="selectedItem" />
        </div>
      </div>

      <!-- Contenido dinámico -->
      <div class="xl:col-span-2 col-span-3 p-8">
        <!-- Contenido para Flujo de Valor -->
        <div v-if="selectedItem === 'flujoDeValor'" class="border border-gray-300 p-8 rounded-md">
          <h2 class="text-lg font-medium mb-4">Procedimientos</h2>
          <ListUi :columns="columns" :items="data.flujoDeValor.items" />
        </div>

        <!-- Contenido para Proceso -->
        <div v-if="selectedItem === 'proceso'" class="h-[620px] overflow-scroll">
          <h2 class="text-lg font-medium mb-4">Proceso</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <CardUi v-for="(card, index) in data.proceso.cards" :key="index" :title="card.title" />
          </div>
        </div>

        <!-- Contenido para Procedimientos -->
        <div v-if="selectedItem === 'procedimientos'" class="border border-gray-300 p-8 rounded-md">
          <h2 class="text-lg font-medium mb-4">Procedimientos</h2>
          <p>{{ data.procedimientos.description }}</p>
        </div>
      </div>
    </div>
  </Layout2>
</template>
