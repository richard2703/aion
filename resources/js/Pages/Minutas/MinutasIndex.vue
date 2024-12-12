<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import { ref, onMounted, watch } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
import Layout from "@/Layouts/Layout2.vue";
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import ContenidoPanel from "./Partials/TabPanel/ContenidoPanel.vue";


const openFilter = () => {
    customFilter.value = !customFilter.value

};
const props = defineProps({
    area_id: Number,
    departamento_id: Number,
});
const title = "minutero";
const areas = ref([]);
const area_id = props.area_id ? ref(props.area_id - 1) : ref(0);
const departamento_id = ref(props.departamento_id);

onMounted(() => {

    getAreas();

});

const getAreas = async () => {
    try {
        const response = await axios.get("/api/areas");
        areas.value = response.data;
    } catch (error) {
        console.error(error);
    }
};
</script>

<style scoped>
.breadcrumbs {
    display: flex;
    gap: 8px;
    align-items: center;
}

.breadcrumbsTitulo h3 {
    font-size: 24px;
    font-weight: bold;
}

.breadcrumbs h3 {
    font-size: 20px;
}

.breadcrumbs a {
    color: #000;
    text-decoration: none;
}

.breadcrumbs .active h3 {
    font-weight: bold;
}
</style>

<template>
    <Layout :titulo="title">

        <Head title="Minutas" />

        <div class="py-2">
            <div class="bg-white overflow-hidden">
                <div class="border-gray-200 bg-white">
                    <h3 class="px-4 font-semibold text-xl">Minutas</h3>
                    <div class="mx-auto overflow-x-auto">
                        <div class="card">
                            <!-- Filtre input -->
                            <div class="block md:flex justify-between items-end content-center">
                                <div class="flex gap-4">
                                    <InputText v-model="globalFilter" placeholder="Buscar..." class="mb-3 h-11" />
                                    <PrimaryButton
                                        class="border-[#E4E4E7] border-1 bg-black border-solid h-11 text-[#9AA0A7] hover:text-white pi pi-filter"
                                        @click="openFilter">
                                    </PrimaryButton>
                                </div>

                                <PrimaryButton class="bg-black m-4 p-[100px]" :href="route('minutas.create')"
                                    label="Nueva Minuta">
                                    <i class="mr-2 pi pi-plus"></i>
                                </PrimaryButton>
                            </div>
                            <!-- content table -->
                            <Tabs :value="area_id ? area_id : 0">
                                <TabPanels>
                                    <TabPanel v-for="( area, key ) in areas" :key=key :value=key class="overflow-auto">
                                        <ContenidoPanel :area=area :departamento_id=departamento_id />
                                    </TabPanel>
                                </TabPanels>
                            </Tabs>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </Layout>
</template>
