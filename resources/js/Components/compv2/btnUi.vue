<template>
    <button
      class="relative bg-black text-sm md:text-md  text-white px-5 py-3 rounded-lg font-medium hover:bg-gray-800 transition-colors duration-200 ease-in-out overflow-hidden"
    >
      <span class="relative z-10">
        <slot>{{ text }}</slot>
      </span>
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-gray-800 to-transparent opacity-20"></div>
    </button>
  </template>
  
  <script setup>
  import { defineProps } from 'vue'
  
  const props = defineProps({
    text: {
      type: String,
      default: 'AutoEvaluacion'
    }
  })
  </script>