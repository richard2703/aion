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
            'inline-flex items-center justify-center py-3 px-4 border rounded-md font-semibold text-white text-[0.9rem] transition duration-150 ease-in-out focus:outline-none bg-black', // Establece el fondo por defecto a #000 (negro)
            customClasses, // Permite cambiar el fondo con una clase personalizada
            { 'opacity-25 cursor-not-allowed': disabled }
        ]"
    >
        <slot /> <!-- Permite pasar iconos o contenido personalizado -->
        <span v-if="label">{{ label }}</span> <!-- Muestra la prop de texto -->
    </component>
</template>
