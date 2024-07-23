<template>
    <Layout>

        <Head title="Tareas" />

        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Tareas</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('tareas.index')" class="px-1">
                <h3>Tareas -</h3>
                </Link>
                <Link :href="route('tareas.edit', tarea.id)" class="active">
                <h3>Editar</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    <div class="px-4 my-4 py-2 flex justify-end bg-white border-b border-gray-200"></div>
                    <div class="px-4 py-2 bg-white border-b border-gray-200">
                        <div class="container mx-auto">
                            <form @submit.prevent="submit">
                                <div class="mt-4">
                                    <InputLabel for="minuta_id" value="Reunión:" />
                                    <select ref="departamento_select"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                        v-model="form.minuta_id" required>
                                        <option value="" disabled selected>
                                            Seleccione una opcion
                                        </option>
                                        <option v-for="minuta in minutas" :key="minuta.id" :value="minuta.id">
                                            {{ minuta.alias }}
                                        </option>
                                    </select>
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="area_id" value="Pilar: " />
                                    <select ref="area_select" @change="onChange($event)"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                        v-model="form.area_id" required>
                                        <option value="" disabled selected>
                                            Seleccione una opcion
                                        </option>
                                        <option v-for="area in areas" :key="area.id" :value="area.id">
                                            {{ area.nombre }}
                                        </option>
                                    </select>
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="departamento_id" value="Flujo de valor: " />
                                    <select ref="departamento_select"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                        v-model="form.departamento_id" required>
                                        <option value="" disabled selected>
                                            Seleccione una opcion
                                        </option>
                                        <option v-for="departamento in departamentos" :key="departamento.id"
                                            :value="departamento.id">
                                            {{ departamento.nombre }}
                                        </option>
                                    </select>
                                </div>

                                <hr class="my-4">

                                <div class="mt-4 z-30">
                                    <InputLabel for="responsable_id" value="Responsable:" />
                                    <AutoComplete v-model="form.responsable_id" optionLabel="name"
                                        :suggestions="filteredUsuarios" forceSelection @complete="search"
                                        placeholder="" />
                                    <!-- <select ref="departamento_select"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                        v-model="form.responsable_id" required>
                                        <option value="" disabled selected>
                                            Seleccione una opcion
                                        </option>
                                        <option v-for="usuario in usuarios" :key="usuario.id" :value="usuario.id">
                                            {{ usuario.name }}
                                        </option>
                                    </select> -->
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="tarea" value="Titulo:" />
                                    <TextInput id="tarea" v-model="form.tarea" type="text" class="mt-1 block w-full"
                                        required autocomplete="tarea" />
                                </div>

                                <div class="mt-4 z-30">
                                    <InputLabel for="revisor_id" value="Cliente de la tarea:" />
                                    <AutoComplete v-model="form.revisor_id" optionLabel="name"
                                        :suggestions="filteredUsuarios" forceSelection @complete="search"
                                        placeholder="" />
                                    <!-- <select ref="revisor_select"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                        v-model="form.revisor_id" required>
                                        <option value="" disabled selected>
                                            Seleccione una opcion
                                        </option>
                                        <option v-for="usuario in usuarios" :key="usuario.id" :value="usuario.id">
                                            {{ usuario.name }}
                                        </option>
                                    </select> -->
                                </div>
                                <div class="mt-4">
                                    <InputLabel for="estatus" value="Estatus: " />
                                    <select ref="departamento_select"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                        v-model="form.estatus_id" required>
                                        <option value="" selected disabled>
                                            Seleccione una opcion </option>
                                        <option value=1>
                                            Retrasado </option>
                                        <option value=2>
                                            Iniciado </option>
                                        <option value=3>
                                            En proceso </option>
                                        <option value=4>
                                            Terminado </option>
                                    </select>
                                </div>
                                <div class="mt-4">
                                    <InputLabel for="fecha" value="Fecha de entrega:" />
                                    <TextInput id="fecha" v-model="form.fecha" type="date" class="mt-1 block w-full"
                                        required autocomplete="responsable_id" />
                                </div>
                                <div class="mt-4">
                                    <InputLabel for="nota" value="Notas: " />
                                    <Textarea class="mt-1 block w-full" v-model="form.nota" rows="5" cols="30" />
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing">
                                        Guardar
                                    </PrimaryButton>
                                    <PrimaryButton @click="emit('close')" class="ms-4"
                                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        cerrar
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

<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import axios from "axios";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import AutoComplete from 'primevue/autocomplete';
import Textarea from 'primevue/textarea';
import { showToast } from "@/Pages/utils/SweetAlert.service";

const props = defineProps({
    tarea: Object
});

const tarea = ref(props.tarea);
const areas = ref({});
const minutas = ref({});
const departamentos = ref({});
const usuarios = ref([]);
const filteredUsuarios = ref();

console.log({ tarea: tarea.value });
const form = useForm({
    area_id: tarea.value.area_id,
    departamento_id: tarea.value.departamento_id,
    minuta_id: tarea.value.minuta_id,
    responsable_id: tarea.value.responsable.name,
    revisor_id: tarea.value.revisor.name,
    tarea: tarea.value.tarea,
    fecha: tarea.value.fecha,
    nota: tarea.value.nota,
    estatus_id: tarea.value.estatus_id,
});

onMounted(() => {
    getAreas();
    getDepartamentos(tarea.value.area_id);
    getUsuarios();
    getMinutas();
});

const getAreas = async () => {
    try {
        const response = await axios.get("/api/areas");
        areas.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

const onChange = async (event) => {
    await getDepartamentos(event.target.value);
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

const getMinutas = async () => {
    try {
        const response = await axios.get("/api/minutas");
        minutas.value = response.data.data;
        console.log({ minutas: minutas.value });
    } catch (error) {
        console.error(error);
    }
};

const submit = async () => {
    try {
        console.log(form.data());

        await form.patch(route("tareas.update", tarea.value.id), {
            onFinish: () => {
                showToast("El registro ha sido creado", "success");
                window.location.href = route('tareas.index');
            },
        });
    } catch (error) {
        showToast("Ocurrio un error", "error");
        console.error(error);
    }
};

const search = (event) => {
    console.log("buscando");
    setTimeout(() => {
        if (!event.query.trim().length) {
            console.log(filteredUsuarios.value);
            filteredUsuarios.value = [...usuarios.value];
        } else {
            filteredUsuarios.value = usuarios.value.filter((usuario) => {
                return usuario.name.toLowerCase().startsWith(event.query.toLowerCase());
            });
        }
    }, 250);
}

</script>
