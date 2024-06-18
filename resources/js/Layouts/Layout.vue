<template>
    <div class="bg-general">
        <div id="dropdown" />
        <div class="md:flex md:flex-col">
            <div class="md:flex md:flex-col md:h-screen">
                <div class="md:flex md:shrink-0">
                    <div
                        class="flex items-center justify-between px-6 py-4 bg-neutral-900 md:shrink-0 md:justify-center md:w-56"
                    >
                        <Link class="mt-1" href="/">
                            <logo class="fill-white" width="120" height="28" />
                        </Link>
                        <dropdown
                            class="md:hidden float-right"
                            placement="bottom-end"
                        >
                            <template #default>
                                <svg
                                    class="w-6 h-6 fill-white"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"
                                    />
                                </svg>
                            </template>
                            <template #dropdown>
                                <div
                                    class="mt-2 px-8 py-4 bg-neutral-800 rounded shadow-lg"
                                >
                                    <main-menu />
                                </div>
                            </template>
                        </dropdown>
                    </div>
                    <div
                        class="md:text-md flex items-center justify-between p-4 w-full text-sm bg-white border-b md:px-12 md:py-0"
                    >
                        <div class="mr-4 mt-1">{{}}</div>
                        <dropdown
                            class="mt-1 float-right"
                            placement="bottom-end"
                        >
                            <template #default>
                                <div
                                    class="group flex items-center cursor-pointer select-none"
                                >
                                    <div
                                        class="mr-1 text-gray-700 group-hover:text-indigo-600 focus:text-indigo-600 whitespace-nowrap"
                                    >
                                        <!-- <span>{{ auth.user.first_name }}</span> -->
                                        <span>
                                            {{
                                                $page.props.auth.user.name
                                            }}</span
                                        >

                                        <!-- <span class="hidden md:inline">&nbsp;{{ auth.user.last_name }}</span> -->
                                    </div>
                                    <icon
                                        class="w-5 h-5 fill-gray-700 group-hover:fill-indigo-600 focus:fill-indigo-600"
                                        name="cheveron-down"
                                    />
                                </div>
                            </template>
                            <template #dropdown>
                                <div
                                    class="mt-2 py-2 text-sm bg-white rounded shadow-xl"
                                >
                                    <!-- <Link class="block px-6 py-2 hover:text-white hover:bg-neutral-500" :href="`/users/${auth.user.id}/edit`">My Profile</Link> -->

                                    <Link
                                        class="block px-6 py-2 hover:text-white hover:bg-neutral-500"
                                        href="/user/profile"
                                        >Manage Profile</Link
                                    >
                                    <Link
                                        class="block px-6 py-2 w-full text-left hover:text-white hover:bg-neutral-500"
                                        href="/logout"
                                        method="post"
                                        as="button"
                                        >Logout</Link
                                    >
                                </div>
                            </template>
                        </dropdown>
                    </div>
                </div>
                <div class="md:flex md:grow md:overflow-hidden">
                    <main-menu
                        class="hidden shrink-0 p-4 w-56 bg-neutral-800 overflow-y-auto md:block"
                    />
                    <!-- <div class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto" scroll-region> -->
                    <div
                        class="px-2 py-4 md:flex-1 md:p-4 md:overflow-y-auto"
                        scroll-region
                    >
                        <flash-messages />
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import Icon from "@/Layouts/Shared/Icon.vue";
import Logo from "@/Layouts/Shared/Logo.vue";
import Dropdown from "@/Layouts/Shared/Dropdown.vue";
import MainMenu from "@/Layouts/Shared/MainMenu.vue";
//   import FlashMessages from '@/Layouts/Shared/FlashMessages.vue'
import "../../css/general.css";

export default {
    components: {
        Dropdown,
        //   FlashMessages,
        Icon,
        Link,
        Logo,
        MainMenu,
    },
    props: {
        //   auth: Object,
    },
};
</script>
