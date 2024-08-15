<script setup>
import Layout from "@/Layouts/Layout.vue";
import { Head, Link } from "@inertiajs/vue3";


const props = defineProps({

});

const title = "sysGestion"; // Aquí asegúrate de que esto coincida con el valor que esperas en MainMenu
const subTitle = "dashboard"; // Este segundo es por siu viene de un menu desplegable en MainMenu

</script>

<template>
    <Layout :titulo="title" :subTitulo="subTitle">

        <Head title="Dashboard" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Sistema de Gestion</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link class="active" :href="route('sysgestion.dashboard')">
                <h3>Dashboard</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <iframe title="KPI AION" class="w-full" width="100%" height="541.25"
                src="https://app.powerbi.com/reportEmbed?reportId=179bf37c-7e0d-4b16-8efb-1dd0d892b9d3&autoAuth=true&ctid=10cd56f7-ad91-4efa-afeb-28102e28951c"
                frameborder="0" allowFullScreen="true">
            </iframe>
        </div>
    </Layout>
</template>
