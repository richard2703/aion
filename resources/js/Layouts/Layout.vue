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
                <Link href="/" class="flex items-center gap-2">
                    <Logo class="h-10" />
                    <span class="text-lg font-bold">AION Business</span>
                </Link>
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
        <div class="flex flex-1 h-full mb-4">
            <!-- Sidebar -->
            <div
                :class="[
                    'bg-gray-800 text-white w-64 p-4',
                    { hidden: !isOpen, block: isOpen },
                    'md:block',
                    'z-50',
                    'md:static',
                    'absolute',
                    'h-screen overflow-auto',
                    'mb-4',
                ]"
            >
                <nav class="flex flex-col space-y-2">
                    <div v-for="item in filteredMenu" :key="item.label">
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
                            class="pl-6 space-y-2 max-h-screen"
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
import { Link, usePage } from "@inertiajs/vue3";

const isOpen = ref(false);
const profileMenuOpen = ref(false);
const openMenus = ref([]);
const userAuth = usePage().props.auth.user.roles;
console.log({ userAuth });

const menuItems = [
    {
        label: "Alineacion",
        route: "dashboard",
        icon: "",
        role: ["admin", "user", "superadmin", "lider_pilar"],
    },
    {
        label: "Identificacion",
        icon: "",
        role: ["admin", "superadmin", "user"],
        children: [
            {
                label: "Evaluaciones",
                route: "evaluacion.index",
                icon: "",
                role: ["lider_pilar", "admin", "superadmin", "user"],
            },
            {
                label: "Metas (30 60 90)",
                route: "metas.index",
                icon: "",
                role: ["admin", "superadmin", "user"],
            },
            {
                label: "Mis Metas (30 60 90)",
                route: "Mismetas.index",
                icon: "",
                role: ["admin", "superadmin", "user"],
            },
            {
                label: "Mis Reportes",
                route: "misreporte.index",
                icon: "",
                role: ["admin", "superadmin", "user"],
            },
            {
                label: "Desperdicios",
                route: "desperdicio.index",
                icon: "",
                role: ["admin", "superadmin", "user"],
            },
        ],
    },
    {
        label: "Optimizacion",
        icon: "",
        role: ["admin", "superadmin", "user"],
        children: [
            {
                label: "Dashboard",
                route: "sysgestion.dashboard",
                icon: "",
                role: ["admin", "superadmin", "user"],
            },
            {
                label: "PDCA",
                route: "sysgestion.index",
                icon: "",
                role: ["admin", "superadmin", "user"],
            },
            {
                label: "Minutas",
                route: "minutas.index",
                icon: "",
                role: ["admin", "superadmin", "user"],
            },
            {
                label: "Reportes",
                route: "reporte.index",
                icon: "",
                role: ["admin", "superadmin", "user"],
            },
            {
                label: "Mis Reportes",
                route: "misreporte.index",
                icon: "",
                role: ["admin", "superadmin", "user"],
            },
            {
                label: "ScoreCard",
                route: "scoreCard.index",
                icon: "",
                role: ["admin", "superadmin", "user"],
            },
        ],
    },
    {
        label: "Networking",
        icon: "",
        role: ["admin", "user", "superadmin"],
        children: [
            {
                label: "Capacitacion",
                route: "https://laproductivitycenter.com",
                icon: "",
                role: ["admin", "user", "superadmin", "lider_pilar"],
            },
        ],
    },
    {
        label: "Configuraciones",
        icon: "pi pi-cog",
        role: ["admin", "superadmin"],
        children: [
            {
                label: "Pilares",
                route: "area.index",
                icon: "",
                role: ["admin", "superadmin"],
            },
            {
                label: "Flujos de valor",
                route: "departamento.index",
                icon: "",
                role: ["admin", "superadmin"],
            },
            {
                label: "Usuarios",
                route: "user.index",
                icon: "",
                role: ["admin", "superadmin"],
            },
            {
                label: "Líderes de flujo",
                route: "encargadoFlujo.index",
                icon: "",
                role: ["admin", "superadmin"],
            },
            {
                label: "Tipos de Desperdicios",
                route: "tiposDesperdicios.index",
                icon: "",
                role: ["admin", "superadmin"],
            },
            {
                label: "Eventos",
                route: "eventos.index",
                icon: "",
                role: ["superadmin"], // TODO: LIMITAR A SUPERADMIN Y LIDER_PILAR ( PRINCIPALMENTE A GYC)
            },
            {
                label: "Secciones",
                route: "seccion.index",
                icon: "",
                role: ["superadmin"],
            },
            {
                label: "Challenges",
                route: "challenge.index",
                icon: "",
                role: ["superadmin"],
            },
            {
                label: "Opciones",
                route: "opcion.index",
                icon: "",
                role: ["superadmin"],
            },
            {
                label: "Configuración de Alineación",
                route: "config.index",
                icon: "",
                role: ["admin", "superadmin"],
            },
            {
                label: "Objetivo",
                route: "objetivo.index",
                icon: "",
                role: ["admin", "superadmin"],
            },
            {
                label: "Procesos",
                route: "proceso.index",
                icon: "",
                role: ["admin", "superadmin"],
            },
            {
                label: "Procedimientos",
                route: "procedimiento.index",
                icon: "",
                role: ["admin", "superadmin"],
            },
            {
                label: "Estandares",
                route: "estandar.index",
                icon: "",
                role: ["admin", "superadmin"],
            },
            {
                label: "KPI's",
                route: "kpi.index",
                icon: "",
                role: ["admin", "superadmin"],
            },
            {
                label: "Roles",
                route: "roles.index",
                icon: "",
                role: ["admin", "superadmin"],
            },
            {
                label: "Permisos",
                route: "permisos.index",
                icon: "",
                role: ["admin", "superadmin"],
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

// const hasRole = (role) => roles.includes(role);
// Función para filtrar el menú según roles
const filterMenuByRole = (menu, roles) => {
    return menu
        .filter((item) => item.role.some((role) => roles.includes(role))) // Filtra elementos con roles válidos
        .map((item) => ({
            ...item,
            children: item.children
                ? filterMenuByRole(item.children, roles)
                : undefined, // Filtra hijos recursivamente
        }))
        .filter((item) => !item.children || item.children.length > 0); // Remueve padres sin hijos válidos
};

// Menú filtrado
const filteredMenu = filterMenuByRole(menuItems, userAuth);
console.log({ filteredMenu });
</script>

<style>
body {
    @apply antialiased;
}
</style>
