<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';
import Welcome from '@/Components/Welcome.vue';
</script>

<template>
    <Layout>
        <Head title="Dashboard" />

        <div class="bg-blue-200 overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Dashboard</h3>
            </div>
            <div class="breadcrumbs flex ">
                <Link :href="route('login')" class="px-1" >
                    <h3>pruebas -</h3>
                </Link>
                <Link :href="route('login')" class="active" >
                    <h3>pruebas</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
                </div>
            </div>
        </div>
    </Layout>
</template>
