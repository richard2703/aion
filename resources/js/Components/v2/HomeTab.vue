<script lang="ts">
import { defineComponent, ref } from "vue";

export default defineComponent({
  name: "HomeTab",
  setup() {
    const activeTab = ref<"metroMap" | "autoEval">("metroMap");

    return {
      activeTab,
    };
  },
});
</script>

<template>
  <div class="w-full">
    <!-- Tab Buttons -->
    <div class="flex border-gray-300 border-b">
   
      <button
        class="px-4 py-2 font-medium text-sm"
        :class="activeTab === 'metroMap' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500'"
        @click="activeTab = 'metroMap'"
      >
        Metro Map
      </button>

      <button
        class="px-4 py-2 font-medium text-sm"
        :class="activeTab === 'autoEval' ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500'"
        @click="activeTab = 'autoEval'"
      >
        Autoevaluación
      </button>
    </div>

    <!-- Tab Content -->
    <div class="mt-4">
      <div v-if="activeTab === 'autoEval'">
        <slot name="autoEval" />
      </div>
      <div v-if="activeTab === 'metroMap'">
        <slot name="metroMap" />
      </div>
    </div>
  </div>
</template>
