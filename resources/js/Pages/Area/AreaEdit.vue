<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
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
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Areas</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                    <h3>Dashboard -</h3>
                </Link>
                <Link :href="route('area.index')" class="px-1">
                    <h3>Areas -</h3>
                </Link>
                <Link :href="route('area.create')" class="active">
                    <h3>Actualizar</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    <div
                        class="px-4 my-4 py-2 flex justify-end bg-white border-b border-gray-200"
                    ></div>
                    <div class="px-4 py-2 bg-white border-b border-gray-200">
                        <div class="container mx-auto">
                            <form @submit.prevent="submit">
                                <div
                                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4"
                                >
                                    <div>
                                        <InputLabel
                                            for="name"
                                            value="Nombre: "
                                        />
                                        <TextInput
                                            id="name"
                                            v-model="form.nombre"
                                            type="text"
                                            class="mt-1 block w-full"
                                            required
                                            autofocus
                                            autocomplete="name"
                                        />
                                    </div>

                                    <div>
                                        <InputLabel
                                            for="description"
                                            value="Description"
                                        />
                                        <TextInput
                                            id="description"
                                            v-model="form.descripcion"
                                            type="text"
                                            class="mt-1 block w-full"
                                            required
                                            autocomplete="username"
                                        />
                                    </div>

                                    <div
                                        class="col-span-full flex items-center justify-end mt-4"
                                    >
                                        <PrimaryButton
                                            class="ms-4"
                                            :class="{
                                                'opacity-25': form.processing,
                                            }"
                                            :disabled="form.processing"
                                        >
                                            Actualizar
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
