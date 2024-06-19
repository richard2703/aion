<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
import Layout from "@/Layouts/Layout.vue";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";

const props = defineProps({
    usuarios: Object,
});

const usuarios = ref(props.usuarios);
const title = "usuarios";
// const subTitle = "index";

async function deleteUser(id) {
    try {
        const result = await confirmDialog(
            "Estas seguro?",
            "Ya no podras revertir esto!",
            "warning"
        );
        if (result.isConfirmed) {
            await axios
                .delete(route("user.destroy", id))
                .then((response) => (usuarios.value = response.data.usuarios))
                .catch((error) => {
                    console.log(error);
                });
            showToast("El registro ha sido eliminado", "success");
        }
    } catch (error) {
        console.error(error);
        showToast("Ocurrio un error", "error");
    }
}
</script>

<template>
    <Layout :titulo="title" :subTitulo="subTitle">
        <Head title="Usuarios" />

        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Usuarios</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                    <h3>Dashboard -</h3>
                </Link>
                <Link class="active">
                    <h3>Usuarios</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    <div
                        class="px-4 py-2 flex justify-end bg-white border-b border-gray-200"
                    >
                        <PrimaryButton :href="route('user.create')"
                            >Nuevo</PrimaryButton
                        >
                    </div>
                    <div class="px-4 py-2 bg-white border-b border-gray-200">
                        <div class="container mx-auto overflow-x-auto">
                            <table class="table-auto w-full">
                                <thead>
                                    <tr class="bg-slate-100">
                                        <th
                                            class="min-w-[160px] text-lg py-4 lg:py-7 px-3 lg:px-4"
                                        >
                                            ID
                                        </th>
                                        <th
                                            class="bg-slate-100 min-w-[160px] text-lg py-4 lg:py-7 px-3 lg:px-4"
                                        >
                                            Nombre
                                        </th>
                                        <th
                                            class="bg-slate-100 min-w-[160px] text-lg py-4 lg:py-7 px-3 lg:px-4"
                                        >
                                            Email
                                        </th>
                                        <th
                                            class="bg-slate-100 min-w-[160px] text-lg py-4 lg:py-7 px-3 lg:px-4"
                                        >
                                            Area
                                        </th>
                                        <th
                                            class="bg-slate-100 min-w-[160px] text-lg py-4 lg:py-7 px-3 lg:px-4"
                                        >
                                            Departamento
                                        </th>
                                        <th
                                            class="bg-slate-100 min-w-[160px] text-lg py-4 lg:py-7 px-3 lg:px-4"
                                        ></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="usuario in usuarios">
                                        <td
                                            v-if="usuario"
                                            class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]"
                                        >
                                            {{ usuario.id }}
                                        </td>
                                        <td
                                            v-if="usuario"
                                            class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]"
                                        >
                                            {{ usuario.name }}
                                        </td>
                                        <td
                                            v-if="usuario"
                                            class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]"
                                        >
                                            {{ usuario.email }}
                                        </td>
                                        <td
                                            v-if="usuario"
                                            class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]"
                                        >
                                            {{ usuario.area.nombre }}
                                        </td>
                                        <td
                                            v-if="usuario"
                                            class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]"
                                        >
                                            {{ usuario.departamento.nombre }}
                                        </td>
                                        <td
                                            v-if="usuario"
                                            class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8] flex gap-2 justify-center"
                                        >
                                            <PrimaryButton
                                                :href="
                                                    route(
                                                        'user.edit',
                                                        usuario.id
                                                    )
                                                "
                                            >
                                                editar
                                            </PrimaryButton>

                                            <PrimaryButton
                                                @click.prevent="
                                                    deleteUser(usuario.id)
                                                "
                                            >
                                                borrar
                                            </PrimaryButton>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
