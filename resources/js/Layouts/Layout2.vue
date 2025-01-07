<script>
import { ref, onMounted } from "vue";
import axios from "axios";
import ChatBot from '@/Components/ChatBot.vue';
import TabItem from '@/Components/v2/TabItem.vue';
import Sidebar from '@/Components/v2/Sidebar.vue';
import { Link } from "@inertiajs/vue3";

export default {
    components: {
        TabItem,
        Link,
        Sidebar,
        ChatBot,
    },
    props: {
        titulo: String,
        subTitulo: String,
    },
    setup() {
        // Sidebar
        const openSidebar = ref(false);

        const toggleSidebar = () => {
            openSidebar.value = !openSidebar.value;
        };

        const isSidebarOpen = () => {
            return openSidebar.value === true;
        };

        // Notifications
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
            let notifications = document.querySelector('#user-notifications');
            let menu = document.querySelector('#user-menu');

            notifications.addEventListener('blur', () => {
                notifications.classList.add('hidden');
            });

            menu.addEventListener('blur', () => {
                menu.classList.add('hidden');
            });
        });

        return {
            toggleSidebar,
            isSidebarOpen,
            userNotifications,
            countNotifications,
        };
    },
    methods: {
        toggleMenu(force) {
            const menu = document.getElementById('user-menu');
            const isVisible = menu.classList.contains('hidden');
            if (isVisible || force) {
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        },
        toggleNotifications(force) {
            const menu = document.getElementById('user-notifications');
            const isVisible = menu.classList.contains('hidden');
            if (isVisible || force) {
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        },
        async markAsRead() {
            await axios.get(route("notificaciones.markAsRead"));
        },
        async deleteReaded() {
            await axios.get(route("notificaciones.deleteReaded"));
        },
    }
};
</script>

<template>
    <div>
        <!-- Component chat Bot -->
        <ChatBot/>
        <div>
            <!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
            <div class="relative z-50" :class="isSidebarOpen() ? '' : 'hidden'" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-gray-900/80" aria-hidden="true"></div>

                <div class="fixed inset-0 flex">
                    <div class="relative flex flex-1 mr-16 w-full max-w-xs">

                        <div class="top-0 left-full absolute flex justify-center pt-5 w-16">
                            <button @click="toggleSidebar()" type="button" class="-m-2.5 p-2.5">
                                <span class="sr-only">Close sidebar</span>
                                <svg class="text-white size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" aria-hidden="true" data-slot="icon">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div
                            class="flex flex-col gap-y-5 bg-[#17171E] px-6 pb-4 ring-1 ring-white/10 overflow-y-auto grow">
                            <!-- Logo AION -->
                            <Link :href="'/'">
                            <div class="flex items-center mt-5 h-16 shrink-0">
                                <img class="w-auto h-14" src="https://i.ibb.co/rs0PkpC/logoAion.png" alt="Your Company">
                            </div>
                            </Link>
                            <Sidebar :title="titulo" :subTitle="subTitulo"></Sidebar>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Static sidebar for desktop -->
            <div class="lg:z-50 lg:fixed lg:inset-y-0 lg:flex lg:flex-col hidden lg:w-72">
                <!-- Sidebar component, swap this element with another sidebar if you like -->
                <div class="flex flex-col gap-y-5 bg-[#17171E] px-6 pb-4 overflow-y-auto grow">
                    <Link :href="'/'">
                    <div class="flex items-center mt-5 h-16 shrink-0">
                        <img class="w-auto h-14" src="https://i.ibb.co/rs0PkpC/logoAion.png" alt="Your Company">
                    </div>
                    </Link>
                    <Sidebar :title="titulo" :subTitle="subTitulo"></Sidebar>
                </div>
            </div>

            <!-- Header  -->
            <div class="lg:pl-72">
    
               
         
                <div
                    class="top-0 z-40 sticky flex items-center gap-x-4 sm:gap-x-6 border-gray-200 bg-white shadow-sm px-4 sm:px-6 lg:px-8 border-b h-16 shrink-0">
                    <button @click="toggleSidebar()" type="button" class="lg:hidden -m-2.5 p-2.5 text-gray-700">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <!-- Separator -->
                    <div class="lg:hidden bg-gray-900/10 w-px h-6" aria-hidden="true"></div>
                    <div class="flex flex-1 justify-between gap-x-4 lg:gap-x-6 self-stretch">
                        <button id="user-notifications-button" aria-expanded="false" aria-haspopup="true"
                            @click="toggleNotifications()" type="button"
                            class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500">
                            <span class="sr-only">View notifications</span>
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                aria-hidden="true" data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                        </button>

                        <div id="user-notifications"
                            class="top-[45px] left-[33px] z-10 absolute hidden bg-white shadow-lg mt-2.5 py-2 rounded-md ring-1 ring-gray-900/5 w-auto md:w-[180px] origin-top-right"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-notifications-button">
                            <div class="grid">
                                <Link @click="markAsRead" class="px-3 py-2 text-gray-900 text-sm" role="menuitem">
                                Marcar como leido</Link>
                                <Link @click="deleteReaded" class="px-3 py-2 text-gray-900 text-sm" role="menuitem">
                                Borrar leidos</Link>
                            </div>
                            <div v-for="notification in userNotifications">
                                <Link :href="notification.link" class="px-3 py-2 text-gray-900 text-sm" role="menuitem">
                                {{ notification.titulo }}
                                <div v-if="notification.readed == 1">&nbsp;&nbsp;&nbsp; ✅</div>
                                </Link>
                            </div>
                        </div>

                        <div class="flex items-center gap-x-4 lg:gap-x-6">
                            <!-- Separator -->
                            <div class="lg:block hidden lg:bg-gray-900/10 lg:w-px lg:h-6" aria-hidden="true"></div>
                            <!-- Dropdown component-->
                            <div class="relative">
                                <button @click="toggleMenu()" type="button" class="flex items-center -m-1.5 p-1.5"
                                    id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="bg-gray-50 rounded-full w-8 h-8"
                                        src="https://avatar.iran.liara.run/public" alt="">
                                    <span class="lg:flex lg:items-center hidden">
                                        <span class="ml-4 font-semibold text-gray-900 text-sm">{{
                                            $page.props.auth.user.user.name }}</span>
                                        <svg class="ml-2 w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                            aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </button>

                                <div id="user-menu"
                                    class="right-0 z-10 absolute hidden bg-white shadow-lg mt-2.5 py-2 rounded-md ring-1 ring-gray-900/5 w-32 origin-top-right"
                                    role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                                    <Link href="/user/profile" class="block px-3 py-2 text-gray-900 text-sm"
                                        role="menuitem">Perfil</Link>
                                    <Link href="/tareas/mis-tareas" class="block px-3 py-2 text-gray-900 text-sm"
                                        role="menuitem">Mis tareas</Link>
                                    <Link href="/logout" method="post" as="button"
                                        class="block px-3 py-2 text-gray-900 text-sm" role="menuitem">Cerrar sesión
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <main>
                    <div>
                        <!-- Your content -->
                        <div class="p-6">
                            <slot></slot>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
