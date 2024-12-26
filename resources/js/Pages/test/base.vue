<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout2.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";


// import as component
import Badge from 'primevue/badge';
import OverlayBadge from 'primevue/overlaybadge';
import Button from 'primevue/button';
import { ref } from 'vue';

const badge = ref('1');

const form = useForm({
    nombre: "",
    descripcion: "",
});
const submit = () => {
    form.post(route("area.store"), {
        onFinish: () => form.reset(),
    });
};

const title = "dashboard";  // Aquí asegúrate de que esto coincida con el valor que esperas en MainMenu
const subTitle = "subTitle2"; // Este segundo es por siu viene de un menu desplegable en MainMenu

</script>

<template>
    <Layout :titulo="title" :subTitulo="subTitle">

        <Head title="Dashboard" />
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Dashboard</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Dashboard -</h3>
                </Link>
                <Link class="active">
                <h3>Test</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden">
                <div>
                    <div class="flex justify-end border-gray-200 bg-white px-4 py-2 border-b">
                        <PrimaryButton>Nuevo</PrimaryButton>
                    </div>
                    <div class="flex justify-end border-gray-200 bg-white px-4 py-2 border-b">
                        <PrimaryButton :href="route('mailer.sendMailWithAttachment')">Enviar</PrimaryButton>
                    </div>
                    <div class="border-gray-200 bg-white px-4 py-2 border-b">

                        <div class="mx-auto container">
                            <form @submit.prevent="submit">
                                <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2">
                                    <div>
                                        <InputLabel for="name" value="Nombre: " />
                                        <TextInput id="name" v-model="form.nombre" type="text" class="block mt-1 w-full"
                                            required autofocus autocomplete="name" />
                                    </div>

                                    <div>
                                        <InputLabel for="area_id" value="Area: " />
                                        <select ref="select"
                                            class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
                                            v-model="form.area_id" required>
                                            <option value="" disabled selected>
                                                Seleccione una opcion
                                            </option>
                                            <option v-for="area in areas" :key="area.id" :value="area.id">
                                                {{ area.nombre }}
                                            </option>
                                        </select>
                                    </div>

                                    <div>
                                        <InputLabel for="descripcion" value="Descripcion:" />
                                        <TextInput id="descripcion" v-model="form.descripcion" type="text"
                                            class="block mt-1 w-full" required autocomplete="descripcion" />
                                    </div>

                                    <div class="flex justify-end items-center col-span-full mt-4">
                                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }"
                                            :disabled="form.processing">
                                            Guardar
                                        </PrimaryButton>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <hr>

                        <div class="border-gray-200 bg-white px-4 py-2">

                            <div class="flex justify-center card">
                                <Badge value="2"></Badge>
                            </div>
                            <br>
                            <div class="flex flex-wrap justify-center gap-2 card">
                                <Badge value="2"></Badge>
                                <Badge value="6" severity="secondary"></Badge>
                                <Badge value="8" severity="success"></Badge>
                                <Badge value="4" severity="info"></Badge>
                                <Badge value="9" severity="warn"></Badge>
                                <Badge value="3" severity="danger"></Badge>
                                <Badge value="5" severity="contrast"></Badge>
                            </div>
                            <br>
                            <div class="flex flex-wrap justify-center items-end gap-2 card">
                                <Badge value="8" size="xlarge" severity="success"></Badge>
                                <Badge value="6" size="large" severity="warn"></Badge>
                                <Badge value="4" severity="info"></Badge>
                                <Badge value="2" size="small"></Badge>
                            </div>
                            <br>
                            <div class="flex flex-wrap justify-center gap-6 card">
                                <OverlayBadge value="2">
                                    <i className="pi pi-bell" />
                                </OverlayBadge>
                                <OverlayBadge value="4" severity="danger">
                                    <i className="pi pi-calendar" />
                                </OverlayBadge>
                                <OverlayBadge severity="danger">
                                    <i className="pi pi-envelope" badge="2" />
                                </OverlayBadge>
                            </div>


                            <br>
                            <div class="flex flex-wrap justify-center gap-4 card">
                                <Button type="button" icon="pi pi-bell" severity="contrast" :badge="badge" link />
                                <Button type="button" icon="pi pi-bell" :badge="badge" severity="contrast" />
                            </div>
                            <br>
                            <div class="flex flex-wrap justify-center card">
                                <!-- <Badge value="3" severity="danger"></Badge> -->
                            </div>

                            <div class="inline-flex relative m-6 w-fit">
                                <div
                                    class="inline-block top-0 right-0 bottom-auto left-auto z-10 absolute bg-red-600 p-2.5 rounded-full skew-x-0 skew-y-0 text-xs -translate-y-1/2 translate-x-2/4 rotate-0 scale-x-100 scale-y-100">
                                </div>
                                <div
                                    class="flex justify-center items-center bg-primary-500 shadow-lg px-8 py-6 rounded-lg text-center text-white dark:text-gray-200">
                                    <span class="[&>svg]:h-10 [&>svg]:w-10">
                                        <i class="text-red-500 pi pi-bell" style="font-size: 2rem" />
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <iframe title="KPI AION" class="w-full" width="100%" height="541.25"
                        src="https://app.powerbi.com/reportEmbed?reportId=179bf37c-7e0d-4b16-8efb-1dd0d892b9d3&autoAuth=true&ctid=10cd56f7-ad91-4efa-afeb-28102e28951c"
                        frameborder="0" allowFullScreen="true">
                    </iframe>
                </div>
            </div>
        </div>
    </Layout>
</template>
