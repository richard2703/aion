<script setup>
import Layout2 from "@/Components/compv2/layout2.vue";
import ListUi from "@/Components/compv2/listUi.vue";
import NavagationMenu from "@/Components/compv2/navigationMenu.vue";
import CardUi  from "@/Components/compv2/cardui.vue"

const columns = [
{ header: 'Procedimientos', key: 'name' },
{ header: 'Ejecución', key: 'execution' },
{ header: 'Documentación', key: 'documentation' },
];

const items = [
{ name: 'Bonos / Comisiones', execution: 'En proceso', documentation: 'Completa' },
{ name: 'Vales de despensa', execution: 'Pendiente', documentation: 'Pendiente' },
{ name: 'Vales de despensa', execution: 'Pendiente', documentation: 'Pendiente' },
{ name: 'Vales de despensa', execution: 'Pendiente', documentation: 'Pendiente' },
{ name: 'Vales de despensa', execution: 'Pendiente', documentation: 'Pendiente' },
];

console.log('Items:', items);


</script>


<template>
  <Layout2>
    <div class="grid grid-cols-3">
      <div class="xl:col-span-1 col-span-3 p-10">
        <div class="text-base">
          <h4 class="font-semibold mb-1">Lista de navegación</h4>
          <p class="text-sm text-gray-500">
            Accede rápidamente a cada nivel de
            información dentro de los pilares
          </p>
        </div>
        <!-- Componente Navegación -->
        <div class="mt-5">
          <NavagationMenu />
        </div>
      </div>
      
      <!-- Tabla de Procedimientos -->
      <div class="xl:col-span-2 col-span-3 p-8">
        <div class="border border-gray-300 p-8 rounded-md">
          <!-- Flujo de valor -->
          <!-- <div>
              <h2 class="text-lg font-medium mb-4">Procedimientos</h2>
              <ListUi :columns="columns" :items="items" />
          </div> -->
       
          <!-- Proceso -->
          <!-- <div class="h-[620px] overflow-scroll">
              <h2 class="text-lg font-medium mb-4">Proceso</h2>
              <div class="mb-5">
                  <input
                      v-model="searchQuery"
                      type="text"
                      placeholder="Buscar"
                      class="w-full border border-gray-300 rounded-md px-4 py-2"
                  />
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <CardUi 
                          title="Administración de Prestaciones y Beneficios Adicionales a la Ley"
                      />
                      <CardUi 
                          title="Administración de Prestaciones y Beneficios De Ley"
                      />
                      <CardUi 
                          title="Administración y legislación laboral"
                      />
                      <CardUi 
                          title="Administración de Prestaciones y Beneficios Adicionales a la Ley"
                      />
                      <CardUi 
                          title="Administración de Prestaciones y Beneficios Adicionales a la Ley"
                      />
                      <CardUi 
                          title="Administración de Prestaciones y Beneficios Adicionales a la Ley"
                      />
                      <CardUi 
                          title="Administración de Prestaciones y Beneficios Adicionales a la Ley"
                      />
                      <CardUi 
                          title="Administración de Prestaciones y Beneficios Adicionales a la Ley"
                      />
                      <CardUi 
                          title="Administración de Prestaciones y Beneficios Adicionales a la Ley"
                      />
                      <CardUi 
                          title="Administración de Prestaciones y Beneficios Adicionales a la Ley"
                      />
              </div>
          </div> -->
      </div>
  </div>
</div>
  </Layout2>
</template>
