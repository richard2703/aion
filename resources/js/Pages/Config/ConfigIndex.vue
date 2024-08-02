<script setup>
import { ref } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { showToast } from "../utils/SweetAlert.service";
import ConfigDashboard from "@/Pages/Config/Partials/ConfigDashboard.vue";
import ConfigTipoMinuta from "@/Pages/Config/Partials/ConfigTipoMinuta.vue";

const count = ref(0);
const template = ref('dashboard');

</script>

<template>
    <Layout title="Configuraciones">

        <Head title="Usuarios" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Configuraciones</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('config.index')" class="px-1">
                <h3>Configuraciones</h3>
                </Link>
            </div>
        </div>

        <div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    <div class="px-4 my-4 py-2 flex justify-end bg-white border-b border-gray-200"></div>
                    <div class="px-4 py-2 bg-white border-b border-gray-200">
                        <div class="container mx-auto">
                            <div class="grid sm:grid-cols-1 md:grid-cols-2 bg-slate-50">
                                <div class="bg-white p-4">

                                    <h2 class="text-2xl">Menu de Configuraciones</h2>

                                    <ul class="space-y-2">
                                        <li class="text-blue-300 hover:text-blue-800 font-bold text-xl">
                                            <button @click="template = 'dashboard'">
                                                Dashboard
                                            </button>
                                        </li>
                                        <!-- TODO: eliminar si no se usa -->
                                        <li class="text-blue-300 hover:text-blue-800 font-bold text-xl">
                                            <button @click="template = 'tipoMinuta'">
                                                Tipos de minuta
                                            </button>
                                        </li>


                                    </ul>
                                </div>
                                <div class="bg-white p-4">

                                    <ConfigDashboard v-if="template === 'dashboard'" />
                                    <ConfigTipoMinuta v-if="template === 'tipoMinuta'" />

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
