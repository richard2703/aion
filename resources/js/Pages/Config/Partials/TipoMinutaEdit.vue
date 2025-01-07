<script setup>
import { onMounted, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";

import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "@/Pages/utils/SweetAlert.service";

const props = defineProps({
    tipoMinuta: Object
});

const emit = defineEmits(['list']);

const form = useForm({
    titulo: props.tipoMinuta.titulo,
    definicion: props.tipoMinuta.definicion,
});

const submit = async () => {
    try {
        await axios.patch(route("tipo-minuta.update", props.tipoMinuta.id), form.data())
            .then(() => {
                showToast("El registro ha sido creado", "success");
                form.reset();
                emit('list');
            })
    } catch (error) {
        showToast("Ocurrio un error", "error");
        console.error(error);
    }
};

</script>

<template>
    <div class="py-2">
        <div class="bg-white overflow-hidden">
            <div class="border-gray-200 bg-white px-4 py-2 border-b">
                <div class="mx-auto p-2 container">
                    <h2 class="text-2xl text-center">Editar Tipo de Minuta</h2>
                    <form @submit.prevent="submit">

                        <div class="mt-4">
                            <InputLabel for="nombre" value="Nombre:" />
                            <TextInput id="nombre" v-model="form.titulo" type="text" class="block mt-1 w-full" required
                                autocomplete="nombre" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="definicion" value="Definicion:" />
                            <TextInput id="definicion" v-model="form.definicion" type="text" class="block mt-1 w-full"
                                required autocomplete="definicion" />
                        </div>
                        <div class="m-4 py-2">
                            <PrimaryButton class="float-right ms-4 pi pi-save"
                                :class="{ 'opacity-25': form.processing }" :disabled="form.processing">

                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
