<template>
    <div>
        <h2 class="text-center text-xl">Nueva acción</h2>
        <form @submit.prevent="submit">

            <div class="mt-4">
                <InputLabel for="nombre" value="Nombre:" />
                <TextInput id="nombre" v-model="form.titulo" type="text" class="mt-1 block w-full" required
                    autocomplete="nombre" />
            </div>
            <div class="mt-4">
                <InputLabel for="proceso" value="Proceso:" />
                <select ref="proceso_select"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                    v-model="form.proceso_id" required>
                    <option value="" disabled selected>
                        Seleccione una opcion
                    </option>
                    <option v-for="proceso in procesos" :key="proceso.id" :value="proceso.id">
                        {{ proceso.nombre }}
                    </option>
                </select>
            </div>
            <div class="mt-4">
                <InputLabel for="link" value="Link:" />
                <TextInput id="link" v-model="form.link" type="text" class="mt-1 block w-full" required
                    autocomplete="link" />
            </div>
            <div class="m-4 py-2">
                <PrimaryButton class="ms-4 pi pi-save float-right" :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">

                </PrimaryButton>
            </div>
        </form>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";

import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "@/Pages/utils/SweetAlert.service";

const props = defineProps({
    tipo: String,
    procesos: Array,
    area_id: Number
});

const emit = defineEmits(['list']);

const procesos = ref(props.procesos);
const area_id = ref(props.area_id);
const tipo = ref(props.tipo);

const form = useForm({
    tipo_id: "",
    proceso_id: "",
    area_id: area_id.value,
    titulo: "",
    link: "",
});

const submit = async () => {
    try {
        switch (tipo.value) {
            case 'correctiva':
                form.tipo_id = 1;
                break;

            case 'preventiva':
                form.tipo_id = 2;
                break;

            case 'mejora':
                form.tipo_id = 3;
                break;

            default:
                form.tipo_id = 1;
                break;
        }
        await axios.post(route("acciones.store"), form.data())
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
