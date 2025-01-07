<script setup>
import { ref } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout2.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { showToast } from "../utils/SweetAlert.service";
import ConfigDashboard from "@/Pages/Config/Partials/ConfigDashboard.vue";
import ConfigTipoMinuta from "@/Pages/Config/Partials/ConfigTipoMinuta.vue";
import ConfigPortada from "@/Pages/Config/Partials/ConfigPortada.vue";

const count = ref(0);
const template = ref('dashboard');

</script>

<template>
    <Layout title="Configuraciones">

        <Head title="Configuraciones" />
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Configuraciones</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('config.index')" class="px-1">
                <h3>Configuraciones</h3>
                </Link>
            </div>
        </div>

        <div>
            <div class="bg-white overflow-hidden">
                <div>
                    <div class="flex justify-end border-gray-200 bg-white my-4 px-4 py-2 border-b"></div>
                    <div class="border-gray-200 bg-white px-4 py-2 border-b">
                        <div class="mx-auto container">
                            <div class="grid sm:grid-cols-1 md:grid-cols-2 bg-slate-50">
                                <div class="bg-white p-4">

                                    <h2 class="text-2xl">Menu de Configuraciones</h2>

                                    <ul class="space-y-2">
                                        <li class="font-bold text-blue-300 text-xl hover:text-blue-800">
                                            <button @click="template = 'dashboard'">
                                                Dashboard
                                            </button>
                                        </li>
                                        <!-- TODO: eliminar si no se usa -->
                                        <li class="font-bold text-blue-300 text-xl hover:text-blue-800">
                                            <button @click="template = 'tipoMinuta'">
                                                Tipos de minuta
                                            </button>
                                        </li>

                                        <li class="font-bold text-blue-300 text-xl hover:text-blue-800">
                                            <button @click="template = 'Portada'">
                                                Portada de Reporte
                                            </button>
                                        </li>


                                    </ul>
                                </div>
                                <div class="bg-white p-4">

                                    <ConfigDashboard v-if="template === 'dashboard'" />
                                    <ConfigTipoMinuta v-if="template === 'tipoMinuta'" />
                                    <ConfigPortada v-if="template === 'Portada'" />

                                    <div v-if="template === 'seccion3'">
                                        {{ template }}
                                    </div>
                                    <div v-if="template === 'seccion4'">
                                        {{ template }}
                                    </div>
                                    <div v-if="template === 'seccion5'">
                                        {{ template }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
