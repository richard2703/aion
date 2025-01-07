<!-- Modal.vue -->
<template>
  <div
    v-if="isOpen"
    class="z-50 fixed inset-0 flex justify-center items-center"
  >
    <div class="bg-white shadow-2xl rounded-lg w-[90%] md:w-[60%] lg:w-[30%] max-w-2xl">
      <!-- Header del Modal -->
      <div class="flex justify-between items-center p-4 border-b">
        <h3 class="pl-3 font-semibold text-lg">{{ title }}</h3>
        <button
          class="text-gray-400 hover:text-gray-600"
          @click="onClose"
        >
          ✕
        </button>
      </div>

      <!-- Contenido -->
      <div class="p-7">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  isOpen: Boolean,
  title: String,
  modalId: String, // Añadimos un id único para cada modal
})

const emit = defineEmits(['close'])

const onClose = () => {
  emit('close', props.modalId) // Emitimos el id del modal que se está cerrando
}
</script>