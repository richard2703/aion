
<script setup>
import { ref, computed } from 'vue';

// Props
const props = defineProps({
  items: {
    type: Array,
    default: () => [],
  },
  columns: {
    type: Array,
    default: () => [
      { field: 'name', header: 'Nombre' },
      { field: 'execution', header: 'Ejecución' },
      { field: 'documentation', header: 'Documentación' },
    ],
  },
});

// Reactive state
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 7;

// Filter items based on search query
const filteredItems = computed(() => {
  return props.items.filter((item) =>
    props.columns.some((col) =>
      String(item[col.field]).toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  );
});

// Paginate filtered items
const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredItems.value.slice(start, end);
});

// Calculate total pages
const totalPages = computed(() => Math.ceil(filteredItems.value.length / itemsPerPage));

// Pagination functions
const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};
</script>

<template>
  <div>
    <!-- Search Input -->
    <div class="mb-4">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Buscar"
        class="w-full border border-gray-300 rounded-md px-4 py-2"
      />
    </div>

    <div class="md:w-auto w-[330px] overflow-scroll">
      <!-- Table Headers -->
      <div class="grid grid-cols-3 mb-4 text-gray-600">
        <div v-for="(col, index) in columns" :key="index" class="px-4">
          {{ col.header }}
        </div>
      </div>
  
      <!-- Table Rows -->
      <div
        v-for="(item, index) in paginatedItems"
        :key="index"
        class="grid grid-cols-3 items-center py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100"
      >
        <div class="px-4">{{ item.name }}</div>
        <div class="px-4">{{ item.execution }}</div>
        <div class="px-4">{{ item.documentation }}</div>
      </div>
    </div>
    
    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
      <button
        @click="prevPage"
        :disabled="currentPage === 1"
        class="px-4 py-2 border rounded-md bg-gray-200 disabled:bg-gray-100"
      >
        Anterior
      </button>

      <p>
        Página {{ currentPage }} de {{ totalPages }}
      </p>

      <button
        @click="nextPage"
        :disabled="currentPage === totalPages"
        class="px-4 py-2 border rounded-md bg-gray-200 disabled:bg-gray-100"
      >
        Siguiente
      </button>
    </div>
  </div>
</template>
