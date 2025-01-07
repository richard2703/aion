<script setup>
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout2.vue";
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
        <div class="sm:rounded-lg overflow-hidden">
            <div class="px-1 breadcrumbsTitulo">
                <h3>Nuevo Rol</h3>
            </div>
            <div class="flex breadcrumbs">
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
            <div class="bg-white overflow-hidden">
                <div>
                    <div class="flex justify-end border-gray-200 bg-white my-4 px-4 py-2 border-b"></div>
                    <div class="border-gray-200 bg-white px-4 py-2 border-b">
                        <div class="mx-auto container">
                            <form @submit.prevent="submit">
                                <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2">
                                    <div class="mt-4">
                                        <InputLabel for="nombre" value="Nombre: " />
                                        <TextInput id="name" v-model="form.name" type="text" class="block mt-1 w-full"
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
                                <div class="flex justify-end border-gray-200 bg-white my-4 px-4 pt-2 border-t">
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
