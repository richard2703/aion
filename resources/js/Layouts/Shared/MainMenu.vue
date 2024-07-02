<template>
    <div>
        <ul class="space-y-2">
            <!-- Dashboard Menu Item -->
            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" href="/">
                    <div :class="title === 'dashboard'
                        ? 'activo flex'
                        : 'inactivo flex'
                        ">
                        <icon name="office" class="mr-2 w-4 h-4" />
                        Home
                    </div>
                    </Link>
                </div>
            </li>

            <!-- Usuarios Menu Item with Submenus -->
            <!-- <li>
                <a class="group flex items-center py-3 cursor-pointer" @click="toggleMenu('usuarios')">
                    <icon name="users" class="mr-2 w-4 h-4"
                        :class="title === 'usuarios' ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
                    <div :class="title === 'usuarios' ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                        Usuarios
                    </div>
                    <icon name="cheveron-down" class="ml-auto w-4 h-4"
                        :class="isOpen('usuarios') ? 'rotate-180' : '', title === 'usuarios' ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
                </a>
                <ul v-show="isOpen('usuarios')" class="pl-6 space-y-2">
                    <li>
                        <Link class="group flex items-center py-2" href="/users"
                            :class="subTitle === 'index' ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                        <icon name="circle" class="mr-2 w-3 h-3" />
                        <div>Index</div>
                        </Link>
                    </li>
                    <li>
                        <Link class="group flex items-center py-2" href="/users/create"
                            :class="subTitle === 'create' ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                        <icon name="circle" class="mr-2 w-3 h-3" />
                        <div>Crear</div>
                        </Link>
                    </li>
                </ul>
            </li> -->

            <!-- Other Menu Items -->
            <li>
                <div class="mb-4 administrador">
                    <div>Administracion</div>
                </div>
            </li>
            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" href="/areas">
                    <div :class="title === 'areas'
                        ? 'activo flex'
                        : 'inactivo flex'
                        ">
                        <icon name="office" class="mr-2 w-4 h-4" />
                        Areas
                    </div>
                    </Link>
                </div>
            </li>
            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" href="/departamentos">
                    <div :class="title === 'departamentos'
                        ? 'activo flex'
                        : 'inactivo flex'
                        ">
                        <icon name="office" class="mr-2 w-4 h-4" />
                        Departamentos
                    </div>
                    </Link>
                </div>
            </li>
            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" :href="route('user.index')">
                    <div :class="title === 'usuarios'
                        ? 'activo flex'
                        : 'inactivo flex'
                        ">
                        <icon name="users" class="mr-2 w-4 h-4" />
                        Usuarios
                    </div>
                    </Link>
                </div>
            </li>

            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" :href="route('challenge.index')">
                    <div :class="title === 'Challenges'
                        ? 'activo flex'
                        : 'inactivo flex'
                        ">
                        <icon name="office" class="mr-2 w-4 h-4" />
                        Challenges
                    </div>
                    </Link>
                </div>
            </li>

            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" :href="route('opcion.index')">
                    <div :class="title === 'Opciones'
                        ? 'activo flex'
                        : 'inactivo flex'
                        ">
                        <icon name="office" class="mr-2 w-4 h-4" />
                        Opciones
                    </div>
                    </Link>
                </div>
            </li>
            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" :href="route('config.index')">
                    <div :class="title === 'Configuraciones'
                        ? 'activo flex'
                        : 'inactivo flex'
                        ">
                        <icon name="office" class="mr-2 w-4 h-4" />
                        Configuraciones
                    </div>
                    </Link>
                </div>
            </li>
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
