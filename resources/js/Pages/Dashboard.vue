<script setup>
import { ref, onMounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import Chart from 'primevue/chart';


onMounted(() => {
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
});


const chartData = ref();
const chartOptions = ref();

const setChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');

    return {
        labels: ['Eating', 'Drinking', 'Sleeping', 'Designing', 'Coding', 'Cycling', 'Running'],
        datasets: [
            {
                label: 'My First dataset',
                borderColor: documentStyle.getPropertyValue('--p-gray-400'),
                pointBackgroundColor: documentStyle.getPropertyValue('--p-gray-400'),
                pointBorderColor: documentStyle.getPropertyValue('--p-gray-400'),
                pointHoverBackgroundColor: textColor,
                pointHoverBorderColor: documentStyle.getPropertyValue('--p-gray-400'),
                data: [65, 59, 90, 81, 56, 55, 40]
            },
            {
                label: 'My Second dataset',
                borderColor: documentStyle.getPropertyValue('--p-pink-400'),
                pointBackgroundColor: documentStyle.getPropertyValue('--p-pink-400'),
                pointBorderColor: documentStyle.getPropertyValue('--p-pink-400'),
                pointHoverBackgroundColor: textColor,
                pointHoverBorderColor: documentStyle.getPropertyValue('--p-pink-400'),
                data: [28, 48, 40, 19, 96, 27, 100]
            }
        ]
    };
};
const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');

    return {
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            r: {
                grid: {
                    color: textColorSecondary
                }
            }
        }
    };
}


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
                            <div class="grid sm:grid-cols-1 md:grid-cols-2 bg-red-500">
                                <div class="bg-red-300 h-96">

                                    <img class="h-96"
                                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3a/M%C3%BCnster%2C_LVM%2C_B%C3%BCrogeb%C3%A4ude_--_2013_--_5149-51.jpg/1200px-M%C3%BCnster%2C_LVM%2C_B%C3%BCrogeb%C3%A4ude_--_2013_--_5149-51.jpg"
                                        alt="">
                                </div>
                                <div class="bg-red-300 ">
                                    <h2 class="text-center">Lorem</h2>
                                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eveniet non molestias
                                        aliquam neque, libero a, assumenda inventore adipisci ipsam, quibusdam quae
                                        dolor?
                                        Qui modi nemo eius iste aperiam, laborum laudantium.</p>
                                </div>
                            </div>
                            <br>
                            <div class="grid sm:grid-cols-1 md:grid-cols-3 gap-4 bg-red-500">
                                <div class="bg-red-300 ">
                                    <Chart type="radar" :data="chartData" :options="chartOptions" class="" />
                                </div>
                                <div class="bg-red-300 text-center ">
                                    <img class="w-full h-full"
                                        src="https://www.conceptdraw.com/How-To-Guide/picture/Metro-Map-ELR-Route-Map.png"
                                        alt="">
                                </div>
                                <div class="bg-red-300 ">
                                    <h2 class="text-center">Lorem</h2>
                                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eveniet non molestias
                                        aliquam neque, libero a, assumenda inventore adipisci ipsam, quibusdam quae
                                        dolor?
                                        Qui modi nemo eius iste aperiam, laborum laudantium.</p>
                                </div>
                            </div>
                            <br>
                            <div class="grid  grid-cols-1 gap-4 bg-red-500">
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
