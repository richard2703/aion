<script setup>
import { Head } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import Welcome from "@/Components/Welcome.vue";
</script>

<template>
    <Layout title="Dashboard">
        <Head title="Dashboard" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
                </div>
            </div>
        </div>
    </Layout>
</template>
