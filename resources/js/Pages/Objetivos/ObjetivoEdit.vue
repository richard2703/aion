<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout2.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { showToast } from "../utils/SweetAlert.service";
import Textarea from 'primevue/textarea';
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";


const props = defineProps({
    objetivo: Object | null,
});

const objetivo = ref(props.objetivo);
const title = "Objetivos";


const form = useForm({
    meta: objetivo.value.meta,
    objetivo: objetivo.value.objetivo,
});
onMounted(() => {


})

const submit = () => {
    console.log({ id: objetivo.value.id });
    try {
        form.patch(route("objetivo.update", objetivo.value.id), {
            onFinish: () => {
                showToast("El registro ha sido actualizado", "success");
                form.reset();
            },
        });

    } catch (error) {
        showToast("Ocurrio un error", "error");
        console.error(error);
    }
};

</script>

<template>
    <Layout>

        <Head title="Objetivos" />

        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Objetivos</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('objetivo.index')" class="px-1">
                <h3>Objetivos -</h3>
                </Link>
                <Link :href="route('objetivo.update', objetivo.id)" class="active">
                <h3>Editar</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden">
                <div>
                    <div class="flex justify-end border-gray-200 bg-white my-4 px-4 py-2 border-b"></div>
                    <div class="border-gray-200 bg-white px-4 py-2 border-b">
                        <div class="mx-auto container">
                            <form @submit.prevent="submit">
                                <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2">
                                    <div class="mt-4">
                                        <InputLabel for="titulo" value="Objetivo: " />
                                        <TextInput id="objetivo" v-model="form.objetivo" type="text"
                                            class="block mt-1 w-full" required autofocus autocomplete="objetivo" />
                                        <InputError class="mt-2" :message="form.errors.objetivo" />
                                    </div>
                                    <div class="mt-4">
                                        <InputLabel for="meta" value="Meta: " />
                                        <TextInput id="meta" v-model="form.meta" type="text" class="block mt-1 w-full"
                                            required autocomplete="meta" />
                                        <InputError class="mt-2" :message="form.errors.meta" />
                                    </div>
                                </div>

                                <div class="flex justify-end border-gray-200 bg-white my-4 px-4 pt-2 border-t">
                                    <PrimaryButton class="ms-4 pi pi-save" :class="{ 'opacity-25': form.processing, }"
                                        :disabled="form.processing">

                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
