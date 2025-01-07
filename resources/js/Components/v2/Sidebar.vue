<script>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

export default {
    components: {
        Link,
    },
    props: {
        title: String,
        subTitle: String,
    },
    setup() {
        const openSubmenu = ref(null);

        const toggleSubmenu = (menu) => {
            openSubmenu.value = openSubmenu.value === menu ? null : menu;
        };

        const isSubmenuOpen = (menu) => {
            return openSubmenu.value === menu;
        };

        return {
            toggleSubmenu,
            isSubmenuOpen,
        };
    },
    methods: {
        getActiveClasses(expectedTitle, expectedSubtitle) {
            const isSubtitleEqual = this.subTitle && expectedSubtitle ? expectedSubtitle == this.subTitle : true
            return expectedTitle == this.title && isSubtitleEqual ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800'
        }
    }
};
</script>
<template>
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-3">
                    <li>
                        <!-- Current: "bg-gray-800 text-white", Default: "text-gray-400 hover:text-white hover:bg-gray-800" -->
                        <Link :href="'/'"
                            class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                            :class="getActiveClasses('dashboard')">
                        <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Home
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('minutas.index')"
                            class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                            :class="getActiveClasses('minutero')">
                        <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        Minutero
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('tareas.index')"
                            class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                            :class="getActiveClasses('tareas')">
                        <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                        </svg>
                        Tareas
                        </Link>
                    </li>
                    <li>
                        <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                            :class="getActiveClasses('sysGestion')"
                            @click="toggleSubmenu('sistema-de-gestion')">
                            <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            Sistema de Gestión
                        </a>
                        <ul v-show="isSubmenuOpen('sistema-de-gestion')" class="px-2">
                            <li>
                                <Link :href="route('sysgestion.dashboard')"
                                    class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold pl-9"
                                    :class="getActiveClasses('sysGestion', 'dashboard')">
                                Dashboard
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('sysgestion.index')"
                                    class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold pl-9"
                                    :class="getActiveClasses('sysGestion', 'pdcs')">
                                PDCA
                                </Link>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                            :class="getActiveClasses('reportes')" @click="toggleSubmenu('reportes')">
                            <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            Reportes
                        </a>
                        <ul v-show="isSubmenuOpen('reportes')" class="px-2">
                            <li>
                                <Link :href="route('reporte.index')"
                                    class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold pl-9"
                                    :class="getActiveClasses('reportes', 'semanales')">
                                Reportes
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('misreporte.index')"
                                    class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold pl-9"
                                    :class="getActiveClasses('reportes', 'pdca')">
                                Mis Reportes
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('metas.index')"
                                    class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold pl-9"
                                    :class="getActiveClasses('reportes', 'pdca')">
                                Metas (30 60 90)
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('Mismetas.index')"
                                    class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold pl-9"
                                    :class="getActiveClasses('reportes', 'pdca')">
                                Mis Metas (30 60 90)
                                </Link>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                            :class="getActiveClasses('assessment')"
                            @click="toggleSubmenu('assessment')">
                            <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            Assessment
                        </a>
                        <ul v-show="isSubmenuOpen('assessment')" class="px-2">
                            <li>
                                <Link :href="route('evaluacion.index')"
                                    class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold pl-9"
                                    :class="getActiveClasses('assessment', 'evaluaciones')">
                                Evaluaciones
                                </Link>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <Link :href="route('desperdicio.index')"
                            class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                            :class="getActiveClasses('Desperdicios')">
                        <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                        </svg>
                        Desperdicios
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('scoreCard.index')"
                            class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                            :class="getActiveClasses('ScoreCard')">
                        <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                        </svg>
                        Score card
                        </Link>
                    </li>

                    <!--- Administrator -->
                    <ul v-if="$page.props.auth.user.roles.includes('admin') || $page.props.auth.user.roles.includes('superadmin')"
                        role="list" class="space-y-3">
                        <li>
                            <div class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-white"
                                :class="getActiveClasses('administracion')">
                                <div>Administración</div>
                            </div>
                        </li>
                        <li>
                            <Link href="/areas"
                                class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                                :class="getActiveClasses('Pilares')">
                            <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                            Pilares
                            </Link>
                        </li>
                        <li>
                            <Link href="/departamentos"
                                class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                                :class="getActiveClasses('Flujos de valor')">
                            <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                            Flujos de valor
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('user.index')"
                                class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                                :class="getActiveClasses('usuarios')">
                            <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                            Usuarios
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('encargadoFlujo.index')"
                                class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                                :class="getActiveClasses('Lideres_flujo')">
                            <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                            Líderes de flujo
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('tiposDesperdicios.index')"
                                class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                                :class="getActiveClasses('tiposDesperdicios')">
                            <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                            Tipos de desperdicios
                            </Link>
                        </li>

                        <ul v-if="$page.props.auth.user.roles.includes('superadmin')" role="list" class="space-y-3">
                            <li>
                                <Link :href="route('seccion.index')"
                                    class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                                    :class="getActiveClasses('Secciones')">
                                <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                    data-slot="icon">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                                </svg>
                                Secciones
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('challenge.index')"
                                    class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                                    :class="getActiveClasses('Challenges')">
                                <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                    data-slot="icon">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                                </svg>
                                Challenges
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('opcion.index')"
                                    class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                                    :class="getActiveClasses('Opciones')">
                                <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                    data-slot="icon">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                                </svg>
                                Opciones
                                </Link>
                            </li>
                        </ul>

                        <li>
                            <Link :href="route('config.index')"
                                class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                                :class="getActiveClasses('Configuraciones')">
                            <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                            Configuraciones
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('objetivo.index')"
                                class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                                :class="getActiveClasses('Objetivos')">
                            <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                            Objetivos
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('proceso.index')"
                                class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                                :class="getActiveClasses('Procesos')">
                            <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                            Procesos
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('procedimiento.index')"
                                class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                                :class="getActiveClasses('Procedimientos')">
                            <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                            Procedimientos
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('estandar.index')"
                                class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                                :class="getActiveClasses('Estandares')">
                            <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                            Estandares
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('kpi.index')"
                                class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                                :class="getActiveClasses('KPI')">
                            <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                            KPI's
                            </Link>
                        </li>
                        <li v-if="$page.props.auth.user.roles.includes('admin') || $page.props.auth.user.roles.includes('superadmin')">
                            <a href="#"
                                class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                                :class="getActiveClasses('rolesypermisos')"
                                @click="toggleSubmenu('rolesypermisos')">
                                <svg class="size-6 shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                    data-slot="icon">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                                </svg>
                                Roles y permisos
                            </a>
                            <ul v-show="isSubmenuOpen('rolesypermisos')" class="px-2">
                                <li>
                                    <Link :href="route('roles.index')"
                                        class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold pl-9"
                                        :class="getActiveClasses('rolesypermisos', 'roles')">
                                    Roles
                                    </Link>
                                </li>
                                <li>
                                    <Link :href="route('permisos.index')"
                                        class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold pl-9"
                                        :class="getActiveClasses('rolesypermisos', 'permisos')">
                                    Permisos
                                    </Link>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </ul>
            </li>
        </ul>
    </nav>
</template>