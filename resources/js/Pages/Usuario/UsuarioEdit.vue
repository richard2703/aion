<script setup>
import { ref } from "vue";
import Layout from "@/Layouts/Layout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import SectionBorder from "@/Components/SectionBorder.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";

const props = defineProps({
    usuario: Object,
    areas: Array,
    departamentos: Array || null,
});
const usuario = ref(props.usuario);
const areas = ref(props.areas);
const departamentos = ref(props.departamentos);
const userArea = usuario.value.area_id;
const profileForm = useForm({
    name: usuario.value.name,
    email: usuario.value.email,
    area_id: usuario.value.area_id,
    departamento_id: usuario.value.departamento_id,
});
const passwordForm = useForm({
    password: "",
    password_confirmation: "",
});

async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data))
        .catch((error) => {
            console.log(error);
        });
}

async function getDepartamentos(userArea) {
    await axios
        .get(route("departamentos.byArea", userArea))
        .then((response) => (departamentos.value = response.data.departamentos))
        .catch((error) => {
            console.log(error);
        });
}

async function onChange(event) {
    const taget_id = event.target.value;
    await axios
        .get(route("departamentos.byArea", taget_id))
        .then((response) => (departamentos.value = response.data.departamentos))
        .catch((error) => {
            console.log(error);
        });
}

async function submitProfile() {
    try {
        const result = await confirmDialog(
            "Estas seguro?",
            "Se modificara el registro!",
            "info"
        );
        console.log(result.isConfirmed);
        if (result.isConfirmed) {
            profileForm.patch(route("user.update", usuario.value.id), {
                onFinish: () => {
                    showToast("El registro ha sido actualizado", "success");
                    profileForm.reset();
                },
            });
        }
    } catch (error) {
        console.error(error);
        showToast("Ocurrio un error", "error");
    }
}

async function submitPassword() {
    try {
        const result = await confirmDialog(
            "Estas seguro?",
            "Se modificara la contraseña del usuario!",
            "warning"
        );
        if (result.isConfirmed) {
            passwordForm.patch(route("user.updatePassword", usuario.value.id), {
                onFinish: () => {
                    showToast("La contraseña ha sido actualizada", "success");
                    passwordForm.reset();
                },
            });
        }
    } catch (error) {
        console.error(error);
        showToast("Ocurrio un error", "error");
    }
}

getAreas();
getDepartamentos(userArea);
</script>

<template>
    <Layout>

        <Head title="Perfil" />

        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Usuarios</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('user.index')" class="px-1">
                <h3>Usuarios -</h3>
                </Link>
                <Link :href="route('user.create')" class="active">
                <h3>Actualizar</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    <div class="px-4 my-4 py-2 flex justify-end bg-white border-b border-gray-200"></div>
                    <div class="px-4 py-2 bg-white border-b border-gray-200">
                        <div class="container mx-auto">
                            <form @submit.prevent="submitProfile">
                                <!-- Name -->
                                <div class="col-span-6 sm:col-span-4">
                                    <InputLabel for="name" value="Name" />
                                    <TextInput id="name" v-model="profileForm.name" type="text"
                                        class="mt-1 block w-full" required autocomplete="name" />
                                    <InputError :message="profileForm.errors.name" class="mt-2" />
                                </div>

                                <!-- Email -->
                                <div class="col-span-6 sm:col-span-4">
                                    <InputLabel for="email" value="Email" />
                                    <TextInput id="email" v-model="profileForm.email" type="email"
                                        class="mt-1 block w-full" required autocomplete="username" />
                                    <InputError :message="profileForm.errors.email" class="mt-2" />

                                    <div v-if="
                                        $page.props.jetstream
                                            .hasEmailVerification &&
                                        user.email_verified_at === null
                                    ">
                                        <p class="text-sm mt-2">
                                            Your email address is unverified.

                                            <Link :href="route('verification.send')
                                                " method="post" as="button"
                                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                @click.prevent="sendEmailVerification
                                                    ">
                                            Click here to re-send the
                                            verification email.
                                            </Link>
                                        </p>

                                        <div v-show="verificationLinkSent"
                                            class="mt-2 font-medium text-sm text-green-600">
                                            A new verification link has been
                                            sent to your email address.
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="area_id" value="Area: " />
                                    <select ref="area_select" @change="onChange($event)"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                        v-model="profileForm.area_id" required>
                                        <option value="" disabled selected>
                                            Seleccione una opcion
                                        </option>
                                        <option v-for="area in areas" :key="area.id" :value="area.id">
                                            {{ area.nombre }}
                                        </option>
                                    </select>
                                </div>
                                <div class="mt-4">
                                    <InputLabel for="departamento_id" value="Departamento: " />

                                    <select ref="departamento_select"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                                        v-model="profileForm.departamento_id" required>
                                        <option value="" disabled selected>
                                            Seleccione una opcion
                                        </option>
                                        <option v-for="departamento in departamentos" :key="departamento.id"
                                            :value="departamento.id">
                                            {{ departamento.nombre }}
                                        </option>
                                    </select>
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <PrimaryButton class="ms-4" :class="{
                                        'opacity-25':
                                            profileForm.processing,
                                    }" :disabled="profileForm.processing">
                                        Actualizar
                                    </PrimaryButton>
                                </div>
                            </form>

                            <SectionBorder />

                            <form @submit.prevent="submitPassword">
                                <div class="col-span-6 sm:col-span-4">
                                    <InputLabel for="password" value="New Password" />
                                    <TextInput id="password" ref="passwordInput" v-model="passwordForm.password"
                                        type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                    <InputError :message="passwordForm.errors.password" class="mt-2" />
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <InputLabel for="password_confirmation" value="Confirm Password" />
                                    <TextInput id="password_confirmation" v-model="passwordForm.password_confirmation
                                        " type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                    <InputError :message="passwordForm.errors
                                            .password_confirmation
                                        " class="mt-2" />
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <PrimaryButton class="ms-4" :class="{
                                        'opacity-25':
                                            passwordForm.processing,
                                    }" :disabled="passwordForm.processing">
                                        Actualizar
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
