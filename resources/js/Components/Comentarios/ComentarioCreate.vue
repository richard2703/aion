<script setup>
import { onMounted, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import Textarea from "primevue/textarea";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { showToast } from "@/Pages/utils/SweetAlert.service";

const props = defineProps({
    reporte_semanal_id: Number,
});
const reporte_semanal_id = props.reporte_semanal_id;
const emit = defineEmits(["list"]);
const form = useForm({
    texto: "",
    reporte_semanal_id: "",
});
const submit = async (comentario_id) => {
    await axios
        .post(route("comentario.store"), {
            texto: form.texto,
            reporte_semanal_id: reporte_semanal_id,
        })
        .then(() => {
            showToast("Comentario creado correctamente", "success");
            form.reset();
            emit("list");
        })
        .catch((error) => {
            console.log(error);
        });
};
</script>

<template>
    <form @submit.prevent="submit()">
        <div class="mt-4">
            <InputLabel for="texto" value="Deja tu observación o anotación:" />
            <Textarea
                class="p-3 w-full"
                v-model="form.texto"
                rows="5"
                cols="30"
            />
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton
                class="ms-4 pi pi-save"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                <span
                    class="p-1"
                    :style="{
                        fontSize: '10px',
                    }"
                >
                    Guardar</span
                >
            </PrimaryButton>
        </div>
    </form>
</template>

<style></style>
