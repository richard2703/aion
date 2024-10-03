<template>
    <div class="bg-general">
        <div id="dropdown" />
        <div class="md:flex md:flex-col">
            <div class="md:flex md:flex-col md:h-screen">
                <div class="md:flex md:shrink-0">
                    <div
                        class="flex items-center justify-between  bg-neutral-900 md:shrink-0 md:justify-center md:w-56">
                        <Link class="mt-1" href="/">
                        <logo class="fill-white m-1" width="150" height="150" />
                        </Link>
                        <dropdown class="md:hidden float-right" placement="bottom-end">
                            <template #default>
                                <svg class="w-6 h-6 fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                                </svg>
                            </template>
                            <template #dropdown>
                                <div class="mt-2 px-8 py-4 bg-neutral-800 rounded shadow-lg">
                                    <main-menu :title="titulo" :subTitle="subTitulo" />
                                </div>
                            </template>
                        </dropdown>
                    </div>
                    <!-- notifications -->
                    <div
                        class="md:text-md flex items-center justify-between p-2 w-full text-sm bg-white border-b md:px-2 md:py-0 ">
                        <div>
                            <dropdown class="mt-1 float-right" placement="bottom-end">
                                <template #default>
                                    <div class="group flex items-center cursor-pointer select-none">
                                        <div class="relative m-6 inline-flex w-fit">
                                            <!-- bolita roja -->
                                            <div v-if="countNotifications > 0"
                                                class="absolute bottom-auto left-auto right-0 top-0 z-10 inline-block -translate-y-1/2 translate-x-2/4 rotate-0 skew-x-0 skew-y-0 scale-x-50 scale-y-50 rounded-full bg-red-600 p-2.5 text-xs">
                                            </div>

                                            <div
                                                class="flex items-center justify-center rounded-lg bg-primary-500 p-2 text-center text-white shadow-lg dark:text-gray-200">
                                                <span class="[&>svg]:h-10 [&>svg]:w-10">
                                                    <i class="pi pi-bell text-slate-600" style="font-size: 2vh" />

                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <template #dropdown>
                                    <div class="mt-2 text-sm bg-white rounded shadow-xl">
                                        <div class="block flex justify-end">
                                            <Link
                                                class="px-2 py-1 hover:bg-blue-50 text-sm text-blue-500 position-fixed hover:text-blue-300"
                                                @click="markAsRead">
                                            marcarcon como leido
                                            </Link>
                                            <Link
                                                class="px-2 py-1 hover:bg-blue-50 text-sm text-blue-500 position-fixed hover:text-blue-300"
                                                @click="deleteReaded">
                                            Borrar leidos
                                            </Link>
                                        </div>
                                        <div v-for="notification in userNotifications">

                                            <Link v-if="notification.readed == 0"
                                                class="block px-6 py-2 hover:text-white hover:bg-neutral-500"
                                                :href="notification.link">
                                            {{ notification.titulo }}
                                            </Link>
                                            <Link v-if="notification.readed == 1"
                                                class="block px-6 py-2 text-white bg-neutral-400 hover:bg-neutral-500"
                                                :href="notification.link">
                                            {{ notification.titulo }} &nbsp;&nbsp;&nbsp; ✅
                                            </Link>
                                        </div>
                                    </div>
                                </template>
                            </dropdown>
                        </div>
                    </div>
                    <!-- original -->
                    <div
                        class="md:text-md flex items-center justify-between p-4 w-full text-sm bg-white border-b md:px-12 md:py-0">
                        <div class="mr-4 mt-1">{{ }}</div>
                        <dropdown class="mt-1 float-right" placement="bottom-end">
                            <template #default>
                                <div class="group flex items-center cursor-pointer select-none">
                                    <div
                                        class="mr-1 text-gray-700 group-hover:text-indigo-600 focus:text-indigo-600 whitespace-nowrap">
                                        <!-- <span>{{ auth.user.first_name }}</span> -->
                                        <span>
                                            {{ $page.props.auth.user.user.name }}
                                        </span>

                                        <!-- <span class="hidden md:inline">&nbsp;{{ auth.user.last_name }}</span> -->
                                    </div>
                                    <icon
                                        class="w-5 h-5 fill-gray-700 group-hover:fill-indigo-600 focus:fill-indigo-600"
                                        name="cheveron-down" />
                                </div>
                            </template>
                            <template #dropdown>
                                <div class="mt-2 py-2 text-sm bg-white rounded shadow-xl">
                                    <!-- <Link class="block px-6 py-2 hover:text-white hover:bg-neutral-500" :href="`/users/${auth.user.id}/edit`">My Profile</Link> -->

                                    <Link class="block px-6 py-2 hover:text-white hover:bg-neutral-500"
                                        href="/user/profile"><i class="pi pi-user mr-2"></i> Perfil</Link>
                                    <Link class="block px-6 py-2 hover:text-white hover:bg-neutral-500"
                                        href="/tareas/mis-tareas"><i class="pi pi-list mr-2"></i> Mis Tareas</Link>
                                    <Link class="block px-6 py-2 w-full text-left hover:text-white hover:bg-neutral-500"
                                        href="/logout" method="post" as="button"><i
                                        class="pi pi-sign-out mr-2"></i>Cerrar sesión</Link>
                                </div>
                            </template>
                        </dropdown>
                    </div>
                </div>
                <div class="md:flex md:grow md:overflow-hidden">
                    <main-menu class="hidden shrink-0 p-4 w-56 bg-neutral-800 overflow-y-auto no-scrollbar md:block"
                        :title="titulo" :subTitle="subTitulo" />
                    <div class="px-2 py-4 md:flex-1 md:p-4 md:overflow-y-auto" scroll-region>
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";
import { Link } from "@inertiajs/vue3";
import Icon from "@/Layouts/Shared/Icon.vue";
import Logo from "@/Layouts/Shared/Logo.vue";
import Dropdown from "@/Layouts/Shared/Dropdown.vue";
import MainMenu from "@/Layouts/Shared/MainMenu.vue";
import "../../css/general.css";

export default {
    components: {
        Dropdown,
        Icon,
        Link,
        Logo,
        MainMenu,
    },
    props: {
        titulo: String,
        subTitulo: String,
    },
    setup() {
        const userNotifications = ref([]);
        const countNotifications = ref(0);
        const getNotifications = async () => {
            try {
                const response = await axios.get(route("notificaciones.getByUser"));
                userNotifications.value = response.data;
                countNotifications.value = response.data.filter((notification) => notification.readed == 0).length

            } catch (error) {
                console.error("Error fetching notifications:", error);
            }
        };

        onMounted(() => {
            getNotifications();
        });

        return {
            userNotifications, countNotifications
        };
    },
    methods: {
        async markAsRead() {
            await axios.get(route("notificaciones.markAsRead"));
        },
        async deleteReaded() {

            await axios.get(route("notificaciones.deleteReaded"));
        },
    },

};
</script>
