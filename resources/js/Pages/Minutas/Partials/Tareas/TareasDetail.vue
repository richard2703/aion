<script setup>
import { onMounted, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import AutoComplete from 'primevue/autocomplete';
import Textarea from 'primevue/textarea';
import { showToast } from "@/Pages/utils/SweetAlert.service";
import Image from 'primevue/image';

const props = defineProps({
    areas: Array,
    departamentos: Array || null,
    minuta: Object,
    task: Object,
});

// Define emits
const emit = defineEmits(['close']);

const task = ref(props.task);
const tarea = ref({});
const areas = ref(props.areas);
const departamentos = ref(props.departamentos);
const minuta = ref(props.minuta);

const usuarios = ref([]);
const filteredUsuarios = ref([]);
const evidencias = ref({});

const form = useForm({
    area_id: minuta.value.area_id,
    departamento_id: minuta.value.departamento_id,
    minuta_id: minuta.value.id,
    responsable_id: task.value.responsable_id,
    revisor_id: task.value.revisor_id,
    tarea: task.value.tarea,
    fecha: task.value.fecha,
    nota: task.value.nota,
    estatus_id: task.value.estatus ? task.value.estatus.id : 1,
});

onMounted(() => {
    getAreas();
    getDepartamentos(minuta.value.area_id);
    getUsuarios();
    getEvidencias();
});

const onChange = async (event) => {
    await getDepartamentos(event.target.value);
};

const getAreas = async () => {
    try {
        const response = await axios.get("/api/areas");
        areas.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

const getDepartamentos = async (area_id) => {
    try {
        const response = await axios.get(route("departamentos.byArea", area_id));
        departamentos.value = response.data.departamentos;
    } catch (error) {
        console.error(error);
    }
};

const getUsuarios = async () => {
    try {
        const response = await axios.get("/api/usuarios/all/todo");
        usuarios.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

const search = (event) => {
    setTimeout(() => {
        if (!event.query.trim().length) {
            filteredUsuarios.value = [...usuarios.value];
        } else {
            filteredUsuarios.value = usuarios.value.filter((usuario) => {
                return usuario.name.toLowerCase().includes(event.query.toLowerCase());
            });
        }
    }, 250);
};

const submit = async () => {
    try {

        await form.patch(route("tareas.update", task.value.id), {
            onFinish: () => {
                showToast("El registro ha sido creado", "success");
                emit('tareaGuardada');
                closeModal();
            },
        });
    } catch (error) {
        showToast("Ocurrio un error", "error");
        console.error(error);
    }
};

const closeModal = () => {
    // Emit event to close modal in the parent component
    emit('close');
};

const getEvidencias = async () => {
    try {
        const response = await axios.get(route("tareaEvidencia.getByTarea", task.value.id));
        evidencias.value = response.data;
    } catch (error) {
        console.error(error);
    }
};
</script>

<template>
    <div class="py-2">
        <div class="bg-white overflow-hidden">
            <div class="border-gray-200 bg-white px-4 py-2 border-b">
                <div class="mx-auto container">
                    <h2 class="text-2xl text-center">Detalles de Tarea</h2>
                    <form @submit.prevent="submit">
                        <div class="mt-4">
                            <InputLabel for="minuta_id" value="Reunión:" />
                            <TextInput id="minuta_id" type="text" :value="task.minuta.alias" class="block mt-1 w-full"
                                disabled />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="area_id" value="Pilar: " />
                            <TextInput id="pilar" type="text" :value="task.area.nombre" class="block mt-1 w-full"
                                disabled />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="departamento_id" value="Flujo de valor: " />
                            <TextInput id="flujo_valor" type="text" :value="task.area.nombre" class="block mt-1 w-full"
                                disabled />
                        </div>

                        <hr class="my-4">

                        <div class="mt-4">
                            <InputLabel for="tarea" value="Titulo:" />
                            <TextInput id="tarea" type="text" :value="task.tarea" class="block mt-1 w-full" disabled />
                        </div>
                        <div class="z-30 mt-4">
                            <InputLabel for="responsable_id" value="Responsable:" />
                            <div v-if="task.responsable">
                                <TextInput id="responsable" v-model="task.responsable.name" type="text"
                                    class="block mt-1 w-full" disabled />
                            </div>
                            <div v-else>
                                <InputLabel class="text-red-500" value="Sin responsable" />
                            </div>
                        </div>
                        <div class="z-30 mt-4">
                            <InputLabel for="revisor_id" value="Cliente de la tarea:" />
                            <div v-if="task.revisor">
                                <TextInput id="rivisor" v-model="task.revisor.name" type="text"
                                    class="block mt-1 w-full" disabled />
                            </div>
                            <div v-else>
                                <InputLabel class="text-red-500" value="Sin revisor" />
                            </div>
                        </div>
                        <div class="mt-4">
                            <InputLabel for="estatus" value="Estatus: " />
                            <TextInput id="estatus" type="text" :value="task.estatus.titulo" class="block mt-1 w-full"
                                disabled />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="fecha" value="Fecha de entrega:" />
                            <TextInput id="fecha" type="text" :value="task.fecha" class="block mt-1 w-full" disabled />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="nota" value="Notas: " />
                            <Textarea class="block mt-1 w-full" v-model="form.nota" rows="5" cols="30" disabled />
                        </div>

                        <div class="flex justify-end items-center mt-4">
                            <PrimaryButton @click="emit('close')" class="ms-4"
                                :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                cerrar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
                <div class="mx-auto contaier">
                    <div class="gap-4 grid grid-cols-1">
                        <InputLabel for="img_ref" value="Muestra del trabajo realizado: " />
                        <div class="grid grid-cols-1">
                            <div v-for="evidencia in evidencias" class="bg-slate-100 w-60 card">
                                <Image :src="evidencia.img_ref" alt="Image" width="250" preview />
                                <!-- <img :src="evidencia" alt="" srcset=""> -->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
