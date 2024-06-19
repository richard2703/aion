<script setup>
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
import Layout from "@/Layouts/Layout.vue";
import Swal from "sweetalert2";

const props = defineProps({
    usuarios: Object,
});

const usuarios = ref(props.usuarios);
const title = "usuarios";  // Aquí asegúrate de que esto coincida con el valor que esperas en MainMenu
const subTitle = "subTitle2";

async function deleteUser(id) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });

    Swal.fire({
        title: "Estas seguro?",
        text: "Ya no podras revertir esto!",
        icon: "warning",
        position: "center",
        width: 400,
        height: 300,
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Si, Eliminar!",
    }).then(async (result) => {
        if (result.isConfirmed) {
            await axios
                .delete(route("user.destroy", id))
                .then((response) => (usuarios.value = response.data.usuarios))
                .catch((error) => {
                    console.log(error);
                });
            Toast.fire({
                icon: "success",
                title: "El registro ha sido eliminado",
            });
        }
    });
}
</script>

<template>
    <Layout :titulo="title" :subTitulo="subTitle">

        <Head title="Usuarios" />

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Usuarios
        </h2>

        <PrimaryButton class="ms-4 float-right mb-4 px-6 py-2" :href="route('user.create')">
            Registrar usuario
        </PrimaryButton>

        <table class="table-auto w-full">
            <thead>
                <tr class="bg-slate-100">
                    <th class="min-w-[160px] text-lg py-4 lg:py-7 px-3 lg:px-4">
                        ID
                    </th>
                    <th class="bg-slate-100 min-w-[160px] text-lg py-4 lg:py-7 px-3 lg:px-4">
                        Nombre
                    </th>
                    <th class="bg-slate-100 min-w-[160px] text-lg py-4 lg:py-7 px-3 lg:px-4">
                        Email
                    </th>
                    <th class="bg-slate-100 min-w-[160px] text-lg py-4 lg:py-7 px-3 lg:px-4">
                        Area
                    </th>
                    <th class="bg-slate-100 min-w-[160px] text-lg py-4 lg:py-7 px-3 lg:px-4">
                        Departamento
                    </th>
                    <th class="bg-slate-100 min-w-[160px] text-lg py-4 lg:py-7 px-3 lg:px-4"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="usuario in usuarios" :key="usuario.id">
                    <td
                        class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]">
                        {{ usuario.id }}
                    </td>
                    <td
                        class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]">
                        {{ usuario.name }}
                    </td>
                    <td
                        class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]">
                        {{ usuario.email }}
                    </td>
                    <td
                        class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]">
                        {{ usuario.area.nombre }}
                    </td>
                    <td
                        class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]">
                        {{ usuario.departamento.nombre }}
                    </td>
                    <td
                        class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]">
                        <PrimaryButton :href="route('user.edit', usuario.id)" class="mx-1 border py-2 px-6">
                            Editar
                        </PrimaryButton>

                        <PrimaryButton @click.prevent="deleteUser(usuario.id)" class="mx-1 border py-2 px-6">
                            Borrar
                        </PrimaryButton>
                    </td>
                </tr>
            </tbody>
        </table>
    </Layout>
</template>
