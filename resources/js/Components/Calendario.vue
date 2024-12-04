<script setup>
import { ref } from 'vue';

const date = ref(new Date());
</script>

<template>
    <div class="flex justify-center max-w-3/4 h-[500px]">
        <div class="justify-center">
            <h1>Calendario de eventos</h1>
            <!-- <VDatePicker v-model="date" /> -->
            <VCalendar v-model="date" />

        </div>
    </div>
</template>

<style></style>
