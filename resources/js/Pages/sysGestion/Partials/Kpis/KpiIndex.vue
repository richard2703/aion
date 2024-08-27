<template>
    <div class="w-full">
        <div class="overflow-x-auto" v-for="kpi in kpis" :key="kpi.id">
            <KpiList class="border-b my-4" :kpi="kpi" />
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import axios from 'axios';
import KpiList from "@/Pages/sysGestion/Partials/Kpis/KpiList.vue";

const props = defineProps({
    departamento_id: Number,
    kpis: Array,
})

const departamento_id = ref(props.departamento_id);
const kpis = ref([]);

onMounted(() => {
    getKpis();
})

watch(() => props.departamento_id, (newDepartamento_id) => {
    departamento_id.value = newDepartamento_id;
    getKpis();
});

const getKpis = async () => {
    await axios
        .get(route("kpis.byDepartamento", departamento_id.value))
        .then((response) => (kpis.value = response.data))
        .catch((error) => {
            console.log(error);
        });
};
</script>
