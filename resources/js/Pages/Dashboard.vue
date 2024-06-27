<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import Welcome from "@/Components/Welcome.vue";

import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import ColumnGroup from "primevue/columngroup"; // optional
import Row from "primevue/row"; // optional
import { FilterMatchMode } from "@primevue/core/api"; // optional
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";


</script>

<template>
    <Layout title="Home">

        <Head title="Usuarios" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Home</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    <div class="px-4 my-4 py-2 flex justify-end bg-white border-b border-gray-200"></div>
                    <div class="px-4 py-2 bg-white border-b border-gray-200">
                        <div class="container mx-auto">
                            <div class="grid grid-cols-2 bg-red-500">
                                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eveniet non molestias
                                    aliquam neque, libero a, assumenda inventore adipisci ipsam, quibusdam quae dolor?
                                    Qui modi nemo eius iste aperiam, laborum laudantium.</p>
                                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eveniet non molestias
                                    aliquam neque, libero a, assumenda inventore adipisci ipsam, quibusdam quae dolor?
                                    Qui modi nemo eius iste aperiam, laborum laudantium.</p>
                            </div>
                            <br>
                            <div class="grid grid-cols-3 gap-4 bg-red-500">
                                <div class="bg-red-300 ">
                                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eveniet non molestias
                                        aliquam neque, libero a, assumenda inventore adipisci ipsam, quibusdam quae
                                        dolor?
                                        Qui modi nemo eius iste aperiam, laborum laudantium.</p>
                                </div>
                                <div class="bg-red-300 ">
                                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eveniet non molestias
                                        aliquam neque, libero a, assumenda inventore adipisci ipsam, quibusdam quae
                                        dolor?
                                        Qui modi nemo eius iste aperiam, laborum laudantium.</p>
                                </div>
                                <div class="bg-red-300 ">
                                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eveniet non molestias
                                        aliquam neque, libero a, assumenda inventore adipisci ipsam, quibusdam quae
                                        dolor?
                                        Qui modi nemo eius iste aperiam, laborum laudantium.</p>
                                </div>
                            </div>
                            <br>
                            <div class="grid grid-cols-1 gap-4 bg-red-500">
                                <div class="bg-red-300 ">
                                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eveniet non molestias
                                        aliquam neque, libero a, assumenda inventore adipisci ipsam, quibusdam quae
                                        dolor?
                                        Qui modi nemo eius iste aperiam, laborum laudantium.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
