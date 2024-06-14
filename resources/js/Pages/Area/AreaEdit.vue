<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    area: Object,
});

const form = useForm({
    nombre: props.area.nombre,
    descripcion: props.area.descripcion,
});

const submit = () => {
    form.patch(route("area.update", props.area.id), {
        onFinish: () => form.reset(),
    });
};
console.log(props.area);
</script>

<template>
    <Layout>
        <Head title="Usuarios" />

        <h1 class="mb-10 text-5xl font-bold">Editar Area</h1>


        <div class="container mx-auto">
            <form @submit.prevent="submit">
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4"
                >
                    <div>
                        <InputLabel for="name" value="Nombre: " />
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
                        <InputLabel for="description" value="Description" />
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
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Guardar
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </Layout>
</template>
