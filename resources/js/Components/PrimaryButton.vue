<!-- <script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center bg-gray-800 hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 disabled:opacity-50 px-4 py-2 border border-transparent rounded-md focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 font-semibold text-white text-xs uppercase tracking-widest transition duration-150 ease-in-out focus:outline-none">
        <slot />
    </button>
</template> -->

<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
    href: {
        type: String,
        default: null,
    },
    label: {
        type: String,
        default: '', 
    },
    disabled: {
        type: Boolean,
        default: false, 
    },
    customClasses: {
        type: String,
        default: '', 
    },
});
</script>

<template>
    <component
        :is="href ? 'a' : 'button'"
        :type="href ? null : type"
        :href="href"
        :disabled="disabled"
        :class="[
            'inline-flex items-center justify-center py-3 px-4 border border-transparent rounded-md font-semibold text-white text-[0.9rem]  transition duration-150 ease-in-out focus:outline-none',
            customClasses,
            { 'opacity-25': disabled }
        ]"
    >
        <slot /> <!-- Permite pasar iconos y otros elementos personalizados -->
        <span v-if="label">{{ label }}</span> <!-- Muestra la prop de texto -->
    </component>
</template>

