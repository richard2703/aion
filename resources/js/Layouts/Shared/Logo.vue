<template>

    <img :src="logo_path" alt="Logo" />

</template>

<script setup>
import { ref, onMounted } from "vue";

onMounted(() => {
    getItem();
});

const item = ref({});
const logo_path = ref();
const proposito = ref();

const props = defineProps({
    item: Object || null,
});

const getItem = () => {
    axios
        .get("/api/config-dashboard")
        .then((response) => {
            item.value = response.data;
            proposito.value = item.value.proposito;
            logo_path.value = item.value.logo_path;

        })
        .catch((error) => {
            console.error('Error fetching item:', error);
        });
};

</script>
