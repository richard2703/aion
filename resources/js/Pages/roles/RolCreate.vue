<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { showToast } from "../utils/SweetAlert.service";

const props = defineProps({
    permisos: {
        type: Array,
        default: () => []
    },
});

const permisos = ref(props.permisos);

const title = "rolesypermisos";

async function getPermisos() {
    await axios
        .get("/api/permisos/all")
        .then((response) => (permisos.value = response.data))
        .catch((error) => {
            console.log(error);
        });

    console.log(permisos.value);
}

const form = useForm({
    name: "",
    selectedPermisos: []
});

const submit = () => {
    try {
        form.post(route("roles.store"), {
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
    getPermisos();
})

</script>

<template>
    <Layout :titulo="title">

        <Head title="Minutas" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Nuevo Rol</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link :href="route('roles.index')" class="px-1">
                <h3>Roles -</h3>
                </Link>
                <Link class="active">
                <h3>Nuevo</h3>
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
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                                    <div class="mt-4">
                                        <InputLabel for="nombre" value="Nombre: " />
                                        <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full"
                                            required autocomplete="new-challenge" />
                                    </div>
                                    <div class="mt-4">

                                    </div>
                                    <div class="mt-4">
                                        <InputLabel value="Permisos: " />
                                        <div>
                                            <div v-for="permiso in permisos" :key="permiso.id" class="mt-2">
                                                <label class="flex items-center">
                                                    <input type="checkbox" v-model="form.selectedPermisos"
                                                        :value="permiso.id" class="form-checkbox" />
                                                    <span class="ml-2">{{ permiso.name }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 my-4 pt-2 flex justify-end bg-white border-t border-gray-200">
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
