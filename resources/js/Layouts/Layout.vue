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
        <div class="flex flex-1 h-full">
            <!-- Sidebar -->
            <div
                :class="[
                    'bg-gray-800 text-white w-64 p-4 space-y-4',
                    { hidden: !isOpen, block: isOpen },
                    'md:block',
                    'z-50',
                    'md:static',
                    'absolute',
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
                            class="flex items-center justify-between p-2 rounded-md hover:bg-gray-700 hover:text-[#dfb569] w-full"
                        >
                            <div class="flex items-center gap-3">
                                <i :class="item.icon" class="pi w-5 h-5"></i>
                                <span
                                    :class="
                                        openMenus.includes(item)
                                            ? 'text-[#dfb569]'
                                            : ''
                                    "
                                    >{{ item.label }}</span
                                >
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
                            class="pl-6 space-y-2 overflow-y-auto max-h-screen"
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
                icon: "",
            },
            {
                label: "Metas (30 60 90)",
                route: "metas.index",
                icon: "",
            },
            {
                label: "Mis Metas (30 60 90)",
                route: "Mismetas.index",
                icon: "",
            },
            {
                label: "Mis Reportes",
                route: "misreporte.index",
                icon: "",
            },
            {
                label: "Desperdicios",
                route: "desperdicio.index",
                icon: "",
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
                icon: "",
            },
            {
                label: "PDCA",
                route: "sysgestion.index",
                icon: "",
            },
            {
                label: "Minutas",
                route: "minutas.index",
                icon: "",
            },
            {
                label: "Reportes",
                route: "reporte.index",
                icon: "",
            },
            {
                label: "Mis Reportes",
                route: "misreporte.index",
                icon: "",
            },
            {
                label: "ScoreCard",
                route: "scoreCard.index",
                icon: "",
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
                icon: "",
            },
        ],
    },
    {
        label: "Settings",
        icon: "pi pi-cog",
        children: [
            {
                label: "Pilares",
                route: "area.index",
                icon: "",
            },
            {
                label: "Flujos de valor",
                route: "departamento.index",
                icon: "",
            },
            {
                label: "Usuarios",
                route: "user.index",
                icon: "",
            },
            {
                label: "Líderes de flujo",
                route: "encargadoFlujo.index",
                icon: "",
            },
            {
                label: "Tipos de Desperdicios",
                route: "tiposDesperdicios.index",
                icon: "",
            },
            {
                label: "Eventos",
                route: "eventos.index",
                icon: "",
            },
            {
                label: "Secciones",
                route: "seccion.index",
                icon: "",
            },
            {
                label: "Challenges",
                route: "challenge.index",
                icon: "",
            },
            {
                label: "Opciones",
                route: "opcion.index",
                icon: "",
            },
            {
                label: "Configuración de Alineación",
                route: "config.index",
                icon: "",
            },
            {
                label: "Objetivo",
                route: "objetivo.index",
                icon: "",
            },
            {
                label: "Procesos",
                route: "proceso.index",
                icon: "",
            },
            {
                label: "Procedimientos",
                route: "procedimiento.index",
                icon: "",
            },
            {
                label: "Estandares",
                route: "estandar.index",
                icon: "",
            },
            {
                label: "KPI's",
                route: "kpi.index",
                icon: "",
            },
            {
                label: "Roles",
                route: "roles.index",
                icon: "",
            },
            {
                label: "Permisos",
                route: "permisos.index",
                icon: "",
            },
            {
                label: "",
                route: "",
                icon: "",
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
