<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from "axios";
import Layout from "@/Layouts/Layout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";

const props = defineProps({
    departamentos: Array,
});

const departamentos = ref(props.departamentos);

const deleteDepartamento = async (id) => {
    try {
        const result = await confirmDialog(
            "Estas seguro?",
            "Ya no podras revertir esto!",
            "warning"
        );
        if (result.isConfirmed) {
            await axios
                .delete(route("departamento.destroy", id))
                .then((response) => {
                    departamentos.value = response.data.departamentos;
                    showToast("El registro ha sido eliminado", "success");
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    } catch (error) {
        console.error(error);
    }
};
</script>

<template>
    <Layout title="Departamento">
        <Head title="Departamento" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Departamentos</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                    <h3>Dashboard -</h3>
                </Link>
                <Link class="active">
                    <h3>Departamentos</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    <div
                        class="px-4 py-2 flex justify-end bg-white border-b border-gray-200"
                    >
                        <PrimaryButton :href="route('departamento.create')"
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
                                            Departamento
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
                                    <tr v-for="departamento in departamentos">
                                        <td
                                            v-if="departamento"
                                            class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]"
                                        >
                                            {{ departamento.id }}
                                        </td>
                                        <td
                                            v-if="departamento"
                                            class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]"
                                        >
                                            {{ departamento.nombre }}
                                        </td>
                                        <td
                                            v-if="departamento"
                                            class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]"
                                        >
                                            {{ departamento.area.nombre }}
                                        </td>
                                        <td
                                            v-if="departamento"
                                            class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]"
                                        >
                                            {{ departamento.descripcion }}
                                        </td>
                                        <td
                                            v-if="departamento"
                                            class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-r border-[#E8E8E8] flex gap-2 justify-center"
                                        >
                                            <PrimaryButton
                                                :href="
                                                    route(
                                                        'departamento.edit',
                                                        departamento.id
                                                    )
                                                "
                                            >
                                                Editar
                                            </PrimaryButton>
                                            <PrimaryButton
                                                @click.prevent="
                                                    deleteDepartamento(
                                                        departamento.id
                                                    )
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
