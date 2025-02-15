<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout2.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "../utils/SweetAlert.service";

const props = defineProps({
    areas: Array,
    departamentos: Array || null,
    roles: Array,
});

const areas = ref(props.areas);
const departamentos = ref(props.departamentos);
const roles = ref(props.roles);

async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    area_id: "",
    departamento_id: "",
    roles: [],
});

const onChange = async (event) => {
    const target_id = event.target.value;
    await axios
        .get(route("departamentos.byArea", target_id))
        .then((response) => (departamentos.value = response.data.departamentos))
        .catch((error) => {
            console.log(error);
        });
};

const submit = async () => {
    try {
        form.post(route("user.store"), {
            onFinish: () => {
                showToast("El registro ha sido creado", "success");
                form.reset();
            },
        });
    } catch (error) {
        showToast("Ocurrio un error", "error");
        console.error(error);
    }
};

onMounted(() => {
    getAreas();
});
</script>

<template>
    <Layout>

        <Head title="Usuario" />
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Usuarios</h3>
            </div>
            <div class="flex breadcrumbs">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('user.index')" class="px-1">
                <h3>Usuarios -</h3>
                </Link>
                <Link :href="route('user.create')" class="active">
                <h3>Nuevo</h3>
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
                                <div>
                                    <InputLabel for="name" value="Nombre" />
                                    <TextInput id="name" v-model="form.name" type="text" class="block mt-1 w-full"
                                        required autofocus autocomplete="name" />
                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="email" value="Correo electronico" />
                                    <TextInput id="email" v-model="form.email" type="email" class="block mt-1 w-full"
                                        required autocomplete="username" />
                                    <InputError class="mt-2" :message="form.errors.email" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="area_id" value="Pilar: " />
                                    <select ref="area_select" @change="onChange($event)"
                                        class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
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
                                        class="border-gray-300 focus:border-indigo-500 shadow-sm px-3 py-2 rounded-md focus:ring-indigo-500 w-full cursor-pointer"
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

                                <div class="mt-4">
                                    <InputLabel value="Roles: " />
                                    <div>
                                        <div v-for="role in roles" :key="role.id" class="mt-2">
                                            <label class="flex items-center">
                                                <div v-if="$page.props.auth.user.roles.includes('superadmin')">
                                                    <input type="checkbox" v-model="form.roles" :value="role.id"
                                                        class="form-checkbox" />
                                                    <span class="ml-2">{{ role.name }}</span>
                                                </div>
                                                <div v-else-if="$page.props.auth.user.roles.includes('admin')">
                                                    <div v-if="role.name === 'admin'">
                                                        <input type="checkbox" v-model="form.roles" :value="role.id"
                                                            class="form-checkbox" />
                                                        <span class="ml-2">{{ role.name }}</span>
                                                    </div>
                                                </div>
                                                <div v-else>
                                                    <div v-if="role.name === 'user'">
                                                        <input type="checkbox" v-model="form.roles" :value="role.id"
                                                            class="form-checkbox" />
                                                        <span class="ml-2">{{ role.name }}</span>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="password" value="Password" />
                                    <TextInput id="password" v-model="form.password" type="password"
                                        class="block mt-1 w-full" required autocomplete="new-password" />
                                    <InputError class="mt-2" :message="form.errors.password" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="password_confirmation" value="Confirm Password" />
                                    <TextInput id="password_confirmation" v-model="form.password_confirmation"
                                        type="password" class="block mt-1 w-full" required
                                        autocomplete="new-password" />
                                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                                </div>

                                <div class="flex justify-end items-center mt-4">
                                    <PrimaryButton class="ms-4 pi pi-save" :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing">

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
