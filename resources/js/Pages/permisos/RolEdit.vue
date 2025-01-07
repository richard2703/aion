<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout2.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";

const props = defineProps({
    area: Object,
});

const form = useForm({
    nombre: props.area.nombre,
    descripcion: props.area.descripcion,
});

const submit = async () => {
    try {
        const result = await confirmDialog(
            "Estas seguro?",
            "Se modificara el registro!",
            "info"
        );
        if (result.isConfirmed) {
            form.patch(route("area.update", props.area.id), {
                onFinish: () => {
                    showToast("El registro ha sido actualizado", "success");
                    form.reset();
                },
            });
        }
    } catch (error) {
        console.error(error);
    }
};
</script>

<template>
    <Layout title="Area">

        <Head title="Usuarios" />
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Permisos</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('permisos.index')" class="px-1">
                <h3>Permisos -</h3>
                </Link>
                <Link href="#" class="active">
                <h3>Actualizar</h3>
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
                                    <div>
                                        <InputLabel for="name" value="Nombre: " />
                                        <TextInput id="name" v-model="form.nombre" type="text" class="block mt-1 w-full"
                                            required autofocus autocomplete="name" />
                                    </div>

                                    <div>
                                        <InputLabel for="description" value="Description" />
                                        <TextInput id="description" v-model="form.descripcion" type="text"
                                            class="block mt-1 w-full" autocomplete="username" />
                                    </div>

                                    <div class="flex justify-end items-center col-span-full mt-4">
                                        <PrimaryButton class="ms-4 pi pi-save" :class="{
                                            'opacity-25': form.processing,
                                        }" :disabled="form.processing">

                                        </PrimaryButton>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
