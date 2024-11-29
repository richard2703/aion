<template>
    <nav class="max-w-xl">
      <!-- Flujo de valor section -->
      <div class="mb-6 transition ease-in-out delay-0 hover:bg-gray-100 rounded-r-lg p-2">
        <button 
          @click="toggleSection('flujo')" 
          class="flex items-center text-md font-regular mb-2 hover:text-gray-600"
        >
          <svg 
            :class="{'rotate-180': sections.flujo}" 
            class="w-5 h-5 transform transition-transform duration-200" 
            viewBox="0 0 24 24"
          >
            <path 
              fill="currentColor" 
              d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"
            />
          </svg>
          <span class="ml-2">Flujo de valor</span>
        </button>
        <div 
          v-if="sections.flujo" 
          class="ml-7 pl-4 border-l text-sm text-gray-600"
        >
          <p>Relaciones Laborales<br>y Nóminas</p>
        </div>
      </div>
  
      <!-- Proceso section -->
      <div class="mb-6 transition ease-in-out delay-0 hover:bg-gray-100 rounded-r-lg p-2">
        <button 
          @click="toggleSection('proceso')" 
          class="flex items-center text-md font-regular mb-2 hover:text-gray-600"
        >
          <svg 
            :class="{'rotate-180': sections.proceso}" 
            class="w-5 h-5 transform transition-transform duration-200" 
            viewBox="0 0 24 24"
          >
            <path 
              fill="currentColor" 
              d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"
            />
          </svg>
          <span class="ml-2">Proceso</span>
        </button>
        <div 
          v-if="sections.proceso" 
          class="ml-7 pl-4 border-l text-gray-600 text-sm"
        >
          <p>Administración de Prestaciones<br>y Beneficios Adicionales a la Ley</p>
        </div>
      </div>
  
      <!-- Procedimientos section -->
      <div class="transition ease-in-out delay-0 hover:bg-gray-100 rounded-r-lg p-2">
        <button 
          @click="toggleSection('procedimientos')" 
          class="flex items-center text-md font-regular hover:text-gray-600"
        >
          <svg 
            :class="{'rotate-90': sections.procedimientos}" 
            class="w-5 h-5 transform transition-transform duration-200" 
            viewBox="0 0 24 24"
          >
            <path 
              fill="currentColor" 
              d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"
            />
          </svg>
          <span class="ml-2">Procedimientos</span>
        </button>
        <div 
          v-if="sections.procedimientos" 
          class="ml-7 pl-4 border-l text-gray-600"
        >
          <!-- Add content for procedimientos if needed -->
        </div>
      </div>
    </nav>
  </template>
  
  <script setup>
    import { ref } from 'vue'
    
    const sections = ref({
        flujo: true,
        proceso: true,
        procedimientos: false
    })
    
    const toggleSection = (section) => {
        sections.value[section] = !sections.value[section]
    }
  </script>