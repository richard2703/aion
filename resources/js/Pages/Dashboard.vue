<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import Welcome from "@/Components/Welcome.vue";

import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import ColumnGroup from "primevue/columngroup"; // optional
import Row from "primevue/row"; // optional
import { FilterMatchMode } from "@primevue/core/api"; // optional
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";

const customers = [
    {
        id: 1000,
        name: "James Butt",
        country: {
            name: "Algeria",
            code: "dz",
        },
        company: "Benton, John B Jr",
        date: "2015-09-13",
        status: "unqualified",
        verified: true,
        activity: 17,
        representative: {
            name: "Ioni Bowcher",
            image: "ionibowcher.png",
        },
        balance: 70663,
    },
    {
        id: 1001,
        name: "Josephine Darakjy",
        country: {
            name: "Egypt",
            code: "eg",
        },
        company: "Chanay, Jeffrey A Esq",
        date: "2019-01-29",
        status: "proposal",
        verified: true,
        activity: 25,
        representative: {
            name: "Amy Elsner",
            image: "amyelsner.png",
        },
        balance: 68724,
    },
    {
        id: 1002,
        name: "Art Venere",
        country: {
            name: "Panama",
            code: "pa",
        },
        company: "Chemel, James L Cpa",
        date: "2016-07-09",
        status: "qualified",
        verified: false,
        activity: 15,
        representative: {
            name: "Asiya Javayant",
            image: "asiyajavayant.png",
        },
        balance: 29791,
    },
    {
        id: 1003,
        name: "Lenna Paprocki",
        country: {
            name: "Slovenia",
            code: "si",
        },
        company: "Feltz Printing Service",
        date: "2020-05-23",
        status: "new",
        verified: false,
        activity: 21,
        representative: {
            name: "Xuxue Feng",
            image: "xuxuefeng.png",
        },
        balance: 69162,
    },
    {
        id: 1004,
        name: "Donette Foller",
        country: {
            name: "South Africa",
            code: "za",
        },
        company: "Printing Dimensions",
        date: "2016-06-19",
        status: "negotiation",
        verified: true,
        activity: 13,
        representative: {
            name: "Violet Crawley",
            image: "violetcrawley.png",
        },
        balance: 50839,
    },
    {
        id: 1005,
        name: "Simona Morasca",
        country: {
            name: "Egypt",
            code: "eg",
        },
        company: "Chapman, Ross E Esq",
        date: "2018-02-16",
        status: "renewal",
        verified: false,
        activity: 5,
        representative: {
            name: "Stephen Shaw",
            image: "stephenshaw.png",
        },
        balance: 57363,
    },
    {
        id: 1006,
        name: "Mitsue Tollner",
        country: {
            name: "Paraguay",
            code: "py",
        },
        company: "Morlong Associates",
        date: "2018-02-19",
        status: "renewal",
        verified: true,
        activity: 9,
        representative: {
            name: "Ioni Bowcher",
            image: "ionibowcher.png",
        },
        balance: 75008,
    },
    {
        id: 1007,
        name: "Leota Dilliard",
        country: {
            name: "Serbia",
            code: "rs",
        },
        company: "Commercial Press",
        date: "2019-08-13",
        status: "qualified",
        verified: false,
        activity: 18,
        representative: {
            name: "Asiya Javayant",
            image: "asiyajavayant.png",
        },
        balance: 15300,
    },
    {
        id: 1008,
        name: "Sage Wieser",
        country: {
            name: "South Korea",
            code: "kr",
        },
        company: "Truhlar And Truhlar Attys",
        date: "2018-11-21",
        status: "unqualified",
        verified: true,
        activity: 20,
        representative: {
            name: "Stephen Shaw",
            image: "stephenshaw.png",
        },
        balance: 17773,
    },
    {
        id: 1009,
        name: "Kris Marrier",
        country: {
            name: "Canada",
            code: "ca",
        },
        company: "King, Christopher A Esq",
        date: "2015-07-07",
        status: "negotiation",
        verified: false,
        activity: 6,
        representative: {
            name: "Violet Crawley",
            image: "violetcrawley.png",
        },
        balance: 64750,
    },
];

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
</script>

<template>
    <Layout>
        <Head title="Dashboard" />

        <div class="bg-blue-200 overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Dashboard</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('login')" class="px-1">
                    <h3>pruebas -</h3>
                </Link>
                <Link :href="route('login')" class="active">
                    <h3>pruebas</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="card flex items-center gap-4 justify-center">
                        <div class="flex flex-wrap gap-4 justify-center">
                            <Button icon="pi pi-home" aria-label="Save" />
                            <Button label="Profile" icon="pi pi-user" />
                            <Button
                                label="Save"
                                icon="pi pi-check"
                                iconPos="right"
                            />
                        </div>
                        <div class="flex flex-wrap gap-4 justify-center">
                            <Button
                                label="Search"
                                icon="pi pi-search"
                                iconPos="top"
                                text
                            />
                            <Button
                                label="Update"
                                icon="pi pi-refresh"
                                iconPos="bottom"
                                text
                            />
                        </div>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg"
                    >
                        <DataTable
                            :value="customers"
                            paginator
                            :rows="5"
                            :rowsPerPageOptions="[5, 10, 20, 50]"
                            tableStyle="min-width: 50rem"
                        >
                            <Column
                                field="name"
                                header="Name"
                                style="width: 25%"
                            ></Column>
                            <Column
                                field="country.name"
                                header="Country"
                                style="width: 25%"
                            ></Column>
                            <Column
                                field="company"
                                header="Company"
                                style="width: 25%"
                            ></Column>
                            <Column
                                field="representative.name"
                                header="Representative"
                                style="width: 25%"
                            ></Column>
                        </DataTable>
                    </div>
                    <Welcome />
                </div>
            </div>
        </div>
    </Layout>
</template>
