<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from "axios";
import Layout from "@/Layouts/Layout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";

const props = defineProps({
    areas: Array,
    areasAPI: Array,
});

const areas = ref(props.areas);
const areasAPI = ref(props.areasAPI);
const title = "areas";

async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => {
            areasAPI.value = response.data.areas;

            console.log(areasAPI.value);
        })
        .catch((error) => {
            console.log(error);
        });
}
getAreas();
const deleteArea = async (id) => {
    try {
        const result = await confirmDialog(
            "Estas seguro?",
            "Ya no podras revertir esto!",
            "warning"
        );
        if (result.isConfirmed) {
            const response = await axios.delete(route("area.destroy", id));
            areas.value = response.data.areas;
            showToast("El registro ha sido eliminado", "success");
        }
    } catch (error) {
        console.error(error);
    }
};
console.log({ log: areasAPI });
const click = (id) => {
    console.log(id);
};
</script>

<template>
    <Layout :titulo="title">
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

        <DataTable
            :value="areasAPI"
            paginator
            :rows="2"
            :rowsPerPageOptions="[2, 10, 20, 50]"
            tableStyle="min-width: 50rem"
            :selection="selectedArea"
            @rowSelect="onRowSelect"
        >
            <Column field="id" header="ID" style="width: 25%"></Column>
            <Column field="nombre" header="Area" style="width: 25%"></Column>
            <Column
                field="descripcion"
                header="Descripcion"
                style="width: 25%"
            ></Column>
            <Column headerStyle="width:4em;" header="">
                <template #body="area">
                    <Button
                        label="Search"
                        type="button"
                        icon="pi pi-search"
                        class="p-button-success"
                        style="margin-right: 0.5em"
                        @click="click(area.data.id)"
                    >
                    </Button>
                </template>
            </Column>
        </DataTable>
    </Layout>
</template>
