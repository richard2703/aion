<script setup>
import { ref, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { showToast } from "../../utils/SweetAlert.service";


const item = ref([]);

const form = useForm({
    nombre_IA: "",
    link_IA: "",
});

onMounted(() => {
    getItem();
});

const getItem = () => {
    axios
        .get("/api/config-dashboard")
        .then((response) => {
            item.value = response.data;
            form.nombre_IA = item.value[2];
            form.link_IA = item.value[3];
        })
        .catch((error) => {
            console.error("Error fetching item:", error);
        });
};

const submit = async () => {
    try {
        if (item.value[0].id) {
            // Update existing item
            await axios.post(
                `/dashboard/${item.value[0].id}/update`,
                form.data()
            );
        } else {
            // Create new item
            await axios.post("/dashboard/store", formData);
        }
        showToast("El registro ha sido creado", "success");

        getItem();
    } catch (error) {
        console.error(error);
    }
};
</script>

<template>
    <div class="py-2">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-4 py-2 bg-white border-b border-gray-200">
                <div class="container mx-auto">
                    <h2 class="text-center text-2xl">
                        Configuración de Inteligencia Artificial
                    </h2>
                    <!-- {{ form.titulo_evento || "Calendario de eventos" }} -->
                    <form @submit.prevent="submit" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <InputLabel for="titulo_evento" value="Nombre de la IA: " />
                                <TextInput id="titulo_evento" v-model="form.nombre_IA" type="text"
                                    class="mt-1 block w-full" required autofocus autocomplete="titulo_evento" />
                            </div>
                            <div>
                                <InputLabel for="titulo_evento" value="Link de la IA: " />
                                <TextInput id="titulo_evento" v-model="form.link_IA" type="text"
                                    class="mt-1 block w-full" required autofocus autocomplete="titulo_evento" />
                            </div>

                            <div class="col-span-full flex items-center justify-end mt-4">
                                <PrimaryButton class="ms-4 pi pi-save" :class="{
                                    'opacity-25': form.processing,
                                }" :disabled="form.processing">
                                    <span class="p-1" :style="{
                                        fontSize: '10px',
                                    }">
                                        Guardar</span>
                                </PrimaryButton>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style></style>
