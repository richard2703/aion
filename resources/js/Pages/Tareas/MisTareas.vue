<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import Layout from "@/Layouts/Layout2.vue";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";
import { format } from 'date-fns';
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputText from "primevue/inputtext";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import TareasPorHacer from "@/Pages/Tareas/Partials/TareasPorHacer.vue";
import TareasPorRevisar from "@/Pages/Tareas/Partials/TareasPorRevisar.vue";

const props = defineProps({
    authUser: Object,
});

const title = "tareas";

</script>

<style scoped>
.mb-3 {
    margin-bottom: 1rem;
}
</style>

<template>
    <Layout :titulo="title">

        <Head title="Tareas" />
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Tareas</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('tareas.misTareas')" class="active">
                <h3>Mis Tareas</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden">
                <div>
                    <div class="card">
                        <Tabs value="0">
                            <TabList>
                                <Tab value="0">Tares por hacer</Tab>
                                <Tab value="1">Tares por revisar</Tab>
                            </TabList>
                            <TabPanels>
                                <TabPanel value="0">
                                    <TareasPorHacer :authUser="props.authUser" />
                                </TabPanel>
                                <TabPanel value="1">
                                    <TareasPorRevisar :authUser="props.authUser" />
                                </TabPanel>
                            </TabPanels>
                        </Tabs>
                    </div>

                </div>
            </div>
        </div>
    </Layout>
</template>
