<script setup>
import { ref } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { showToast } from "../utils/SweetAlert.service";
import ConfigDashboard from "@/Pages/Config/Partials/ConfigDashboard.vue";

const count = ref(0);
const template = ref('dashboard');

</script>

<template>
    <Layout title="Area">

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
                                <div class="bg-red-300 h-96 p-4">

                                    <h2>Menu de Configuraciones</h2>

                                    <ul class="space-y-2">
                                        <li class="text-indigo-300 group-hover:text-white font-bold text-xl">
                                            <button @click="template = 'dashboard'">
                                                Dashboard
                                            </button>
                                        </li>
                                        <li class="text-indigo-300 group-hover:text-white font-bold text-xl">
                                            <button @click="template = 'seccion2'">
                                                Seccion 2
                                            </button>
                                        </li>
                                        <li class="text-indigo-300 group-hover:text-white font-bold text-xl">
                                            <button @click="template = 'seccion3'">
                                                Seccion 3
                                            </button>
                                        </li>
                                        <li class="text-indigo-300 group-hover:text-white font-bold text-xl">
                                            <button @click="template = 'seccion4'">
                                                Seccion 4
                                            </button>
                                        </li>
                                        <li class="text-indigo-300 group-hover:text-white font-bold text-xl">
                                            <button @click="template = 'seccion5'">
                                                Seccion 5
                                            </button>
                                        </li>

                                    </ul>
                                </div>
                                <div class="bg-red-300 p-4">

                                    <ConfigDashboard v-if="template === 'dashboard'" />
                                    <div v-if="template === 'seccion2'">
                                        {{ template }}
                                    </div>
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
