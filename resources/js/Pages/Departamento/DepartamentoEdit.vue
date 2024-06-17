<script setup>
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    areas: Array,
    departamento: Object,
});

const areas = ref(props.areas);

const departamento = ref(props.departamento);

async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data.areas))
        .catch((error) => {
            console.log(error);
        });
}

const form = useForm({
    nombre: departamento.value.nombre,
    area_id: departamento.value.area_id,
    descripcion: departamento.value.descripcion,
});

const submit = () => {
    form.patch(route("departamento.update", props.departamento.id), {
        onFinish: () => form.reset(),
    });
};

getAreas();
</script>

<template>
    <Layout>
        <Head title="Usuarios" />

        <h1 class="mb-10 text-5xl font-bold">Editar Departamento</h1>

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
                        <InputLabel for="area_id" value="Area: " />
                        <select
                            ref="select"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                            v-model="form.area_id"
                            required
                        >
                            <option value="" disabled selected>
                                Seleccione una opcion
                            </option>
                            <option
                                v-for="area in areas"
                                :key="area.id"
                                :value="area.id"
                            >
                                {{ area.nombre }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <InputLabel for="descripcion" value="Descripcion:" />
                        <TextInput
                            id="descripcion"
                            v-model="form.descripcion"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            autocomplete="descripcion"
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
                            Actualizar
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </Layout>
</template>
