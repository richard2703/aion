<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from "axios";
import Layout from "@/Layouts/Layout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

import Swal from "sweetalert2";

const props = defineProps({
    areas: Array,
});

const areas = ref(props.areas);

const deleteArea = (id) => {
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
                .delete(route("area.destroy", id))
                .then((response) => (areas.value = response.data.areas))
                .catch((error) => {
                    console.log(error);
                });
            Toast.fire({
                icon: "success",
                title: "El registro ha sido eliminado",
            });
        }
    });
};
</script>

<template>
    <Layout title="Area">
        <Head title="Area" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Areas</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                    <h3>Dashboard -</h3>
                </Link>
                <Link class="active">
                    <h3>Areas</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    <div
                        class="px-4 py-2 flex justify-end bg-white border-b border-gray-200"
                    >
                        <PrimaryButton :href="route('area.create')"
                            >Nuevo</PrimaryButton
                        >
                    </div>
                    <div class="px-4 py-2 bg-white border-b border-gray-200">
                        <div class="container mx-auto">
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
                                            Area
                                        </th>
                                        <th
                                            class="bg-slate-100 min-w-[160px] text-lg py-4 lg:py-7 px-3 lg:px-4"
                                        >
                                            Descripcion
                                        </th>
                                        <th
                                            class="bg-slate-100 min-w-[160px] text-lg py-4 lg:py-7 px-3 lg:px-4"
                                        ></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="area in areas">
                                        <td
                                            v-if="area"
                                            class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]"
                                        >
                                            {{ area.id }}
                                        </td>
                                        <td
                                            v-if="area"
                                            class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]"
                                        >
                                            {{ area.nombre }}
                                        </td>
                                        <td
                                            v-if="area"
                                            class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]"
                                        >
                                            {{ area.descripcion }}
                                        </td>
                                        <td
                                            v-if="area"
                                            class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8] flex gap-2 justify-center"
                                        >
                                            <PrimaryButton
                                                :href="
                                                    route('area.edit', area.id)
                                                "
                                            >
                                                Editar
                                            </PrimaryButton>
                                            <PrimaryButton
                                                @click.prevent="
                                                    deleteArea(area.id)
                                                "
                                            >
                                                Borrar
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
