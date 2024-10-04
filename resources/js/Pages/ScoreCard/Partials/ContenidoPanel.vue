<script setup>
import { onMounted, ref, watch } from "vue";
import axios from 'axios';
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    area: Object,
});

const area = ref(props.area);
const departamentos = ref([]);
const departamento_id = ref()
const kpis = ref([]);

onMounted(() => {
    getDepartamentos(area.value.id);
    getKpis();
})

watch(() => props.area, (newArea) => {
    area.value = newArea;
    // getKpis();
});

const getDepartamentos = async (userArea) => {
    await axios
        .get(route("departamentos.byArea", userArea))
        .then((response) => {
            departamentos.value = response.data.departamentos
        })
        .catch((error) => {
            console.log(error);
        });
}
const getKpis = async () => {
    await axios
        .get(route("kpis.byArea", area.value.id))
        .then((response) => {
            kpis.value = response.data;
        })
        .catch((error) => {
            console.log(error);
        });
}

const getKpisbyDepartamento = async (departamento_id) => {
    console.log({ departamento_id });
    if (departamento_id) {
        await axios
            .get(route("kpis.byDepartamento", departamento_id))
            .then((response) => {
                kpis.value = response.data;
            })
            .catch((error) => {
                console.log(error);
            });
        return
    }
    getKpis();
};

</script>

<template>
    <div class="w-full">
        <div class="p-2">
            <div class="mt-4">
                <InputLabel for="departamento_id" value="Flujo de valor: " />

                <select ref="departamento_select" @change="getKpisbyDepartamento(departamento_id)"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                    v-model="departamento_id">
                    <option value="" selected>
                        Todos
                    </option>
                    <option v-for="departamento in departamentos" :key="departamento.id" :value="departamento.id">
                        {{ departamento.nombre }}
                    </option>
                </select>
            </div>
            departamento: {{ departamento_id }}

            <div v-for="kpi in kpis" class="mt-4">
                {{ kpi }}
                <br>
                <br>
                <br>
                <br>
            </div>
        </div>
    </div>
</template>
