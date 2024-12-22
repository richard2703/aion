<template>
    <div>
        <ul class="space-y-2">
            <!-- Dashboard Menu Item -->
            <li>
                <div class="mb-4">
                    <Link class="flex items-center py-3 group" href="/">
                    <div :class="title === 'dashboard'
                        ? 'activo flex'
                        : 'inactivo flex'
                        ">
                        <i class="mr-2 w-4 h-4 pi pi-home"></i>
                        Home
                    </div>
                    </Link>
                </div>
            </li>

            <li>
                <div class="mb-4">
                    <Link class="flex items-center py-3 group" :href="route('minutas.index')">
                    <div :class="title === 'minutero'
                        ? 'activo flex'
                        : 'inactivo flex'
                        ">
                        <i class="mr-2 w-4 h-4 pi pi-briefcase"></i>
                        Minutero
                    </div>
                    </Link>
                </div>
            </li>

            <li>
                <div class="mb-4">
                    <Link class="flex items-center py-3 group" :href="route('tareas.index')">
                    <div :class="title === 'tareas'
                        ? 'activo flex'
                        : 'inactivo flex'
                        ">
                        <i class="mr-2 w-4 h-4 pi pi-list"></i>
                        Tareas
                    </div>
                    </Link>
                </div>
            </li>

            <li>
                <a class="flex items-center py-3 cursor-pointer group" @click="toggleMenu('sysGestion')">

                    <i class="mr-2 w-4 h-4 pi pi-building" :class="title === 'sysGestion' ? 'activo flex'
                        : 'inactivo flex'"></i>
                    <div :class="title === 'sysGestion' ? 'activo flex'
                        : 'inactivo flex'">
                        Sistema de Gestion
                    </div>
                    <icon name="cheveron-down" class="ml-auto w-4 h-4" :class="isOpen('sysGestion') ? 'rotate-180' : '', title === 'sysGestion' ? 'activo flex'
                        : 'inactivo flex'" />
                </a>
                <ul v-show="isOpen('sysGestion')" class="space-y-2 pl-6">
                    <li>
                        <Link class="flex items-center py-2 group" :href="route('sysgestion.dashboard')" :class="subTitle === 'dashboard' ? 'activo flex'
                            : 'inactivo flex'">
                        <icon name="circle" class="mr-2 w-3 h-3" />
                        <div>Dashboard</div>
                        </Link>
                    </li>
                    <li>
                        <Link class="flex items-center py-2 group" :href="route('sysgestion.index')" :class="subTitle === 'pdca' ? 'activo flex'
                            : 'inactivo flex'">
                        <icon name="circle" class="mr-2 w-3 h-3" />
                        <div>PDCA</div>
                        </Link>
                    </li>
                </ul>
            </li>
            <!-- //reportes -->
            <li>
                <a class="flex items-center py-3 cursor-pointer group" @click="toggleMenu('reportes')">

                    <i class="mr-2 w-4 h-4 pi pi-building" :class="title === 'reportes' ? 'activo flex'
                        : 'inactivo flex'"></i>
                    <div :class="title === 'reportes' ? 'activo flex'
                        : 'inactivo flex'">
                        Reportes
                    </div>
                    <icon name="cheveron-down" class="ml-auto w-4 h-4" :class="isOpen('reportes') ? 'rotate-180' : '', title === 'reportes' ? 'activo flex'
                        : 'inactivo flex'" />
                </a>
                <ul v-show="isOpen('reportes')" class="space-y-2 pl-6">
                    <li>
                        <Link class="flex items-center py-2 group" :href="route('reporte.index')" :class="subTitle === 'semanales' ? 'activo flex'
                            : 'inactivo flex'">
                        <icon name="circle" class="mr-2 w-3 h-3" />
                        <div>Reportes</div>
                        </Link>
                    </li>
                    <li>
                        <Link class="flex items-center py-2 group" :href="route('misreporte.index')" :class="subTitle === 'pdca' ? 'activo flex'
                            : 'inactivo flex'">
                        <icon name="circle" class="mr-2 w-3 h-3" />
                        <div>Mis Reportes</div>
                        </Link>
                    </li>
                    <li>
                        <Link class="flex items-center py-2 group" :href="route('metas.index')" :class="subTitle === 'pdca' ? 'activo flex'
                            : 'inactivo flex'">
                        <icon name="circle" class="mr-2 w-3 h-3" />
                        <div>Metas (30 60 90)</div>
                        </Link>
                    </li>
                    <li>
                        <Link class="flex items-center py-2 group" :href="route('Mismetas.index')" :class="subTitle === 'pdca' ? 'activo flex'
                            : 'inactivo flex'">
                        <icon name="circle" class="mr-2 w-3 h-3" />
                        <div>Mis Metas (30 60 90)</div>
                        </Link>
                    </li>
                </ul>
            </li>

            <!-- //reportes -->
            <li>
                <a class="flex items-center py-3 cursor-pointer group" @click="toggleMenu('assessment')">

                    <i class="mr-2 w-4 h-4 pi pi-file-check" :class="title === 'assessment' ? 'activo flex'
                        : 'inactivo flex'"></i>
                    <div :class="title === 'assessment' ? 'activo flex'
                        : 'inactivo flex'">
                        Assessment
                    </div>
                    <icon name="cheveron-down" class="ml-auto w-4 h-4" :class="isOpen('assessment') ? 'rotate-180' : '', title === 'assessment' ? 'activo flex'
                        : 'inactivo flex'" />
                </a>
                <ul v-show="isOpen('assessment')" class="space-y-2 pl-6">
                    <li>
                        <Link class="flex items-center py-2 group" :href="route('evaluacion.index')" :class="subTitle === 'evaluaciones' ? 'activo flex'
                            : 'inactivo flex'">
                        <icon name="circle" class="mr-2 w-3 h-3" />
                        <div>Evaluaciones</div>
                        </Link>
                    </li>
                </ul>
            </li>

            <!-- //Desperdicios -->
            <li>
                <div class="mb-4">
                    <Link class="flex items-center py-3 group" :href="route('desperdicio.index')">
                    <div :class="title === 'Desperdicios'
                        ? 'activo flex'
                        : 'inactivo flex'
                        ">
                        <i class="mr-2 w-4 h-4 pi pi-list"></i>
                        Desperdicios
                    </div>
                    </Link>
                </div>
            </li>
            <!-- Scorecard -->
            <li>
                <div class="mb-4">
                    <Link class="flex items-center py-3 group" :href="route('scoreCard.index')">
                    <div :class="title === 'ScoreCard'
                        ? 'activo flex'
                        : 'inactivo flex'
                        ">
                        <i class="mr-2 w-4 h-4 pi pi-chart-bar"></i>
                        ScoreCard
                    </div>
                    </Link>
                </div>
            </li>
            <!-- Usuarios Menu Item with Submenus -->
            <!-- <li>
                <a class="flex items-center py-3 cursor-pointer group" @click="toggleMenu('usuarios')">
                    <icon name="users" class="mr-2 w-4 h-4"
                        :class="title === 'usuarios' ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
                    <div :class="title === 'usuarios' ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                        Usuarios
                    </div>
                    <icon name="cheveron-down" class="ml-auto w-4 h-4"
                        :class="isOpen('usuarios') ? 'rotate-180' : '', title === 'usuarios' ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
                </a>
                <ul v-show="isOpen('usuarios')" class="space-y-2 pl-6">
                    <li>
                        <Link class="flex items-center py-2 group" href="/users"
                            :class="subTitle === 'index' ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                        <icon name="circle" class="mr-2 w-3 h-3" />
                        <div>Index</div>
                        </Link>
                    </li>
                    <li>
                        <Link class="flex items-center py-2 group" href="/users/create"
                            :class="subTitle === 'create' ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                        <icon name="circle" class="mr-2 w-3 h-3" />
                        <div>Crear</div>
                        </Link>
                    </li>
                </ul>
            </li> -->

            <!-- Other Menu Items -->
            <div
                v-if="$page.props.auth.user.roles.includes('admin') || $page.props.auth.user.roles.includes('superadmin')">
                <li>
                    <div class="mb-4 administrador">
                        <div>Administracion</div>
                    </div>
                </li>
                <li>
                    <div class="mb-4">
                        <Link class="flex items-center py-3 group" href="/areas">
                        <div :class="title === 'Pilares'
                            ? 'activo flex'
                            : 'inactivo flex'
                            ">
                            <i class="mr-2 w-4 h-4 pi pi-building-columns"></i>
                            Pilares
                        </div>
                        </Link>
                    </div>
                </li>
                <li>
                    <div class="mb-4">
                        <Link class="flex items-center py-3 group" href="/departamentos">
                        <div :class="title === 'Flujos de valor'
                            ? 'activo flex'
                            : 'inactivo flex'
                            ">
                            <i class="mr-2 w-4 h-4 pi pi-building"></i>
                            Flujos de valor
                        </div>
                        </Link>
                    </div>
                </li>
                <li>
                    <div class="mb-4">
                        <Link class="flex items-center py-3 group" :href="route('user.index')">
                        <div :class="title === 'usuarios'
                            ? 'activo flex'
                            : 'inactivo flex'
                            ">
                            <i class="mr-2 w-4 h-4 pi pi-users"></i>
                            Usuarios
                        </div>
                        </Link>
                    </div>
                </li>
                <li>
                    <div class="mb-4">
                        <Link class="flex items-center py-3 group" :href="route('encargadoFlujo.index')">
                        <div :class="title === 'Lideres_flujo'
                            ? 'activo flex'
                            : 'inactivo flex'
                            ">
                            <i class="mr-2 w-4 h-4 pi pi-users"></i>
                            Líderes de flujo
                        </div>
                        </Link>
                    </div>
                </li>
                <!-- //Tipos de Desperdicios -->
                <li>
                    <div class="mb-4">
                        <Link class="flex items-center py-3 group" :href="route('tiposDesperdicios.index')">
                        <div :class="title === 'tiposDesperdicios'
                            ? 'activo flex'
                            : 'inactivo flex'
                            ">
                            <i class="mr-2 w-4 h-4 pi pi-trash"></i>
                            Tipos de Desperdicios
                        </div>
                        </Link>
                    </div>
                </li>
                <div v-if="$page.props.auth.user.roles.includes('superadmin')">

                    <li>
                        <div class="mb-4">
                            <Link class="flex items-center py-3 group" :href="route('seccion.index')">
                            <div :class="title === 'Secciones'
                                ? 'activo flex'
                                : 'inactivo flex'
                                ">
                                <i class="mr-2 w-4 h-4 pi pi-list"></i>
                                Secciones
                            </div>
                            </Link>
                        </div>
                    </li>

                    <li>
                        <div class="mb-4">
                            <Link class="flex items-center py-3 group" :href="route('challenge.index')">
                            <div :class="title === 'Challenges'
                                ? 'activo flex'
                                : 'inactivo flex'
                                ">
                                <i class="mr-2 w-4 h-4 pi pi-verified"></i>
                                Challenges
                            </div>
                            </Link>
                        </div>
                    </li>

                    <li>
                        <div class="mb-4">
                            <Link class="flex items-center py-3 group" :href="route('opcion.index')">
                            <div :class="title === 'Opciones'
                                ? 'activo flex'
                                : 'inactivo flex'
                                ">
                                <i class="mr-2 w-4 h-4 pi pi-question"></i>
                                Opciones
                            </div>
                            </Link>
                        </div>
                    </li>
                </div>
                <li>
                    <div class="mb-4">
                        <Link class="flex items-center py-3 group" :href="route('config.index')">
                        <div :class="title === 'Configuraciones'
                            ? 'activo flex'
                            : 'inactivo flex'
                            ">
                            <i class="mr-2 w-4 h-4 pi pi-wrench"></i>
                            Configuraciones
                        </div>
                        </Link>
                    </div>
                </li>
                <li>
                    <div class="mb-4">
                        <Link class="flex items-center py-3 group" :href="route('objetivo.index')">
                        <div :class="title === 'Objetivos' ? 'activo flex' : 'inactivo flex'">
                            <i class="mr-2 w-4 h-4 pi pi-flag"></i>
                            Objetivo
                        </div>
                        </Link>
                    </div>
                </li>

                <li>
                    <div class="mb-4">
                        <Link class="flex items-center py-3 group" :href="route('proceso.index')">
                        <div :class="title === 'Procesos' ? 'activo flex' : 'inactivo flex'">
                            <i class="mr-2 w-4 h-4 pi-list-check pi"></i>
                            Procesos
                        </div>
                        </Link>
                    </div>

                </li>

                <li>
                    <div class="mb-4">
                        <Link class="flex items-center py-3 group" :href="route('procedimiento.index')">
                        <div :class="title === 'Procedimientos' ? 'activo flex' : 'inactivo flex'">
                            <i class="mr-2 w-4 h-4 pi pi-check-square"></i>
                            Procedimientos
                        </div>
                        </Link>
                    </div>
                </li>

                <li>
                    <div class="mb-4">
                        <Link class="flex items-center py-3 group" :href="route('estandar.index')">
                        <div :class="title === 'Estandares' ? 'activo flex' : 'inactivo flex'">
                            <i class="mr-2 w-4 h-4 pi pi-file"></i>
                            Estandares
                        </div>
                        </Link>
                    </div>
                </li>

                <li>
                    <div class="mb-4">
                        <Link class="flex items-center py-3 group" :href="route('kpi.index')">
                        <div :class="title === 'KPI' ? 'activo flex' : 'inactivo flex'">
                            <i class="mr-2 w-4 h-4 pi pi-chart-line"></i>
                            KPI's
                        </div>
                        </Link>
                    </div>
                </li>
                <li
                    v-if="$page.props.auth.user.roles.includes('admin') || $page.props.auth.user.roles.includes('superadmin')">
                    <a class="flex items-center py-3 cursor-pointer group" @click="toggleMenu('rolesypermisos')">

                        <i class="mr-2 w-4 h-4 pi pi-lock" :class="title === 'rolesypermisos' ? 'activo flex'
                            : 'inactivo flex'"></i>
                        <div :class="title === 'rolesypermisos' ? 'activo flex'
                            : 'inactivo flex'">
                            Roles y permisos
                        </div>
                        <icon name="cheveron-down" class="ml-auto w-4 h-4" :class="isOpen('rolesypermisos') ? 'rotate-180' : '', title === 'rolesypermisos' ? 'activo flex'
                            : 'inactivo flex'" />
                    </a>
                    <ul v-show="isOpen('rolesypermisos')" class="space-y-2 pl-6">
                        <li>
                            <Link class="flex items-center py-2 group" :href="route('roles.index')" :class="subTitle === 'roles' ? 'activo flex'
                                : 'inactivo flex'">
                            <icon name="circle" class="mr-2 w-3 h-3" />
                            <div>Roles</div>
                            </Link>
                        </li>
                        <li>
                            <Link class="flex items-center py-2 group" :href="route('permisos.index')" :class="subTitle === 'permisos' ? 'activo flex'
                                : 'inactivo flex'">
                            <icon name="circle" class="mr-2 w-3 h-3" />
                            <div>Permisoscalis</div>
                            </Link>
                        </li>
                    </ul>
                </li>
            </div>
        </ul>
    </div>
</template>

<script>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import Icon from "@/Layouts/Shared/Icon.vue";

export default {
    components: {
        Icon,
        Link,
    },
    props: {
        title: String,
        subTitle: String,
    },
    setup() {
        const openMenu = ref(null);

        const toggleMenu = (menu) => {
            openMenu.value = openMenu.value === menu ? null : menu;
        };

        const isOpen = (menu) => {
            return openMenu.value === menu;
        };

        return {
            toggleMenu,
            isOpen,
        };
    },
};
</script>

<style scoped>
.rotate-180 {
    transform: rotate(180deg);
}
</style>
