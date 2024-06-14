<script setup>
import { Head } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';
import Welcome from '@/Components/Welcome.vue';
</script>

<template>
    <Layout>
        <Head title="Dashboard" />

        <div class="">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <h3>Dashboard</h3>
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
