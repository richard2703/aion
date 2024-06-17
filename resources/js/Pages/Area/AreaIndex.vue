<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from "axios";
import Layout from "@/Layouts/Layout.vue";
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

        <h1 class="mb-10 text-5xl font-bold">Areas</h1>

        <Link
            class="inline-block mb-4 px-6 py-2 bg-yellow-800 rounded hover:text-white float-right"
            :href="route('area.create')"
        >
            Registrar Area
        </Link>

        <table class="table-auto w-full">
            <thead>
                <tr class="bg-slate-100">
                    <th class="min-w-[160px] text-lg py-4 lg:py-7 px-3 lg:px-4">
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
                        class="text-center text-dark font-medium text-base py-5 px-2 bg-[#F3F6FF] border-b border-l border-[#E8E8E8]"
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
                        class="text-center text-dark font-medium text-base py-5 px-2 bg-[#F3F6FF] border-b border-[#E8E8E8]"
                    >
                        {{ area.descripcion }}
                    </td>
                    <td
                        v-if="area"
                        class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-r border-[#E8E8E8]"
                    >
                        <Link
                            :href="route('area.edit', area.id)"
                            class="mx-1 border py-2 px-6 bg-yellow-800 inline-block rounded hover:bg-primary hover:text-white"
                        >
                            Editar
                        </Link>
                        <a
                            @click.prevent="deleteArea(area.id)"
                            class="mx-1 border py-2 px-6 bg-red-300 inline-block rounded hover:bg-primary hover:text-white"
                        >
                            Borrar
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </Layout>
</template>
