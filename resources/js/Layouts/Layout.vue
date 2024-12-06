<template>
    <div class="flex flex-col h-screen">
        <!-- Header -->
        <header
            class="flex items-center justify-between bg-gray-800 text-white p-4"
        >
            <div class="flex items-center gap-4">
                <button @click="isOpen = !isOpen" class="text-white md:hidden">
                    <i class="pi pi-bars text-2xl"></i>
                </button>
                <Logo class="h-10" />
                <span class="text-lg font-bold">AION Business</span>
            </div>
            <nav class="flex items-center gap-4">
                <button class="relative text-gray-300 hover:text-white">
                    <i class="pi pi-bell text-xl"></i>
                    <span
                        class="absolute top-0 right-0 bg-red-500 text-white text-xs w-4 h-4 flex items-center justify-center rounded-full"
                        >3</span
                    >
                </button>
                <div class="relative">
                    <button
                        @click="toggleProfileMenu"
                        class="flex items-center gap-2"
                    >
                        <!-- <img
                            src="/profile.jpg"
                            alt="Profile"
                            class="w-8 h-8 rounded-full"
                        /> -->

                        <i
                            :class="[
                                'pi',
                                profileMenuOpen
                                    ? 'pi-chevron-up'
                                    : 'pi-chevron-down',
                            ]"
                        ></i>
                    </button>
                    <div
                        v-if="profileMenuOpen"
                        class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded shadow-lg"
                    >
                        <h3
                            class="px-4 py-2 border-b bg-gray-800 text-gray-100"
                        >
                            {{ $page.props.auth.user.user.name }}
                        </h3>
                        <button
                            @click="navigate('/profile')"
                            class="w-full text-left px-4 py-2 hover:bg-gray-100"
                        >
                            Perfil de Usuario
                        </button>
                        <button
                            @click="navigate('/settings')"
                            class="w-full text-left px-4 py-2 hover:bg-gray-100"
                        >
                            Mis Tareas
                        </button>
                        <button
                            @click="logout"
                            class="w-full text-left px-4 py-2 hover:bg-gray-100"
                        >
                            Logout
                        </button>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Content Area -->
        <div class="flex flex-1">
            <!-- Sidebar -->
            <div
                :class="[
                    'bg-gray-800 text-white w-64 p-4 space-y-4',
                    { hidden: !isOpen, block: isOpen },
                    'md:block',
                    'z-50',
                    'md:static',
                    'sm:fixed',
                    'h-screen',
                ]"
            >
                <nav class="flex flex-col space-y-2">
                    <div v-for="item in menuItems" :key="item.label">
                        <button
                            @click="
                                item.children
                                    ? toggleSubmenu(item)
                                    : $inertia.visit(route(`${item.route}`))
                            "
                            class="flex items-center justify-between p-2 rounded-md hover:bg-gray-700 w-full"
                        >
                            <div class="flex items-center gap-3">
                                <i :class="item.icon" class="pi w-5 h-5"></i>
                                <span>{{ item.label }}</span>
                            </div>
                            <i
                                v-if="item.children"
                                :class="[
                                    'pi',
                                    openMenus.includes(item)
                                        ? 'pi-chevron-down'
                                        : 'pi-chevron-right',
                                ]"
                                class="transition-transform"
                            ></i>
                        </button>
                        <div
                            v-if="item.children && openMenus.includes(item)"
                            class="pl-6 space-y-2"
                        >
                            <button
                                v-for="child in item.children"
                                :key="child.label"
                                @click="
                                    child.label === 'Capacitacion'
                                        ? navigate(
                                              'https://laproductivitycenter.com'
                                          )
                                        : $inertia.visit(
                                              route(`${child.route}`)
                                          )
                                "
                                class="flex items-center gap-3 p-2 rounded-md hover:bg-gray-700"
                            >
                                <i :class="child.icon" class="pi w-5 h-5"></i>
                                <span>{{ child.label }}</span>
                            </button>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Main content -->
            <div class="flex-1 bg-gray-100 p-6 overflow-auto">
                <main>
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import Logo from "./Shared/Logo.vue";

const isOpen = ref(false);
const profileMenuOpen = ref(false);
const openMenus = ref([]);

const menuItems = [
    { label: "Alineacion", route: "dashboard", icon: "" },
    {
        label: "Identificacion",
        icon: "",
        children: [
            {
                label: "Evaluaciones",
                route: "evaluacion.index",
                icon: "pi pi-circle",
            },
            {
                label: "Metas (30 60 90)",
                route: "metas.index",
                icon: "pi pi-circle",
            },
            {
                label: "Mis Metas (30 60 90)",
                route: "Mismetas.index",
                icon: "pi pi-circle",
            },
            {
                label: "Mis Reportes",
                route: "misreporte.index",
                icon: "pi pi-circle",
            },
            {
                label: "Desperdicios",
                route: "desperdicio.index",
                icon: "pi pi-circle",
            },
        ],
    },
    {
        label: "Optimizacion",
        icon: "",
        children: [
            {
                label: "Dashboard",
                route: "sysgestion.dashboard",
                icon: "pi pi-circle",
            },
            {
                label: "PDCA",
                route: "sysgestion.index",
                icon: "pi pi-circle",
            },
            {
                label: "Minutas",
                route: "minutas.index",
                icon: "pi pi-circle",
            },
            {
                label: "Reportes",
                route: "reporte.index",
                icon: "pi pi-circle",
            },
            {
                label: "Mis Reportes",
                route: "misreporte.index",
                icon: "pi pi-circle",
            },
            {
                label: "ScoreCard",
                route: "scoreCard.index",
                icon: "pi pi-circle",
            },
        ],
    },
    {
        label: "Networking",
        icon: "",
        children: [
            {
                label: "Capacitacion",
                route: "https://laproductivitycenter.com",
                icon: "pi pi-circle",
            },
        ],
    },
    {
        label: "Administracion",
        icon: "",
        children: [
            {
                label: "ToDo",
                route: "/",
                icon: "pi pi-circle",
            },
        ],
    },
    {
        label: "Settings",
        icon: "pi pi-cog",
        children: [
            {
                label: "Account Settings",
                route: "/settings/account",
                icon: "pi pi-user-edit",
            },
            {
                label: "Privacy Settings",
                route: "/settings/privacy",
                icon: "pi pi-lock",
            },
        ],
    },
];

function navigate(route) {
    window.open(route, "_blank").focus();
    // Use your router here, e.g., router.push(route)
}

function toggleSubmenu(item) {
    if (openMenus.value.includes(item)) {
        openMenus.value = openMenus.value.filter((i) => i !== item);
    } else {
        openMenus.value.push(item);
    }
}

function toggleProfileMenu() {
    profileMenuOpen.value = !profileMenuOpen.value;
}

function logout() {
    console.log("Logging out");
    // Implement logout logic
}
</script>

<style>
body {
    @apply antialiased;
}
</style>
