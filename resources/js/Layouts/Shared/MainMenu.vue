<template>
    <div>
        <ul class="space-y-2">
            <!-- Dashboard Menu Item -->
            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" href="/">
                        <icon
                            name="dashboard"
                            class="mr-2 w-4 h-4"
                            :class="
                                title === 'dashboard'
                                    ? 'fill-white'
                                    : 'fill-indigo-400 group-hover:fill-white'
                            "
                        />
                        <div
                            :class="
                                title === 'dashboard'
                                    ? 'text-white'
                                    : 'text-indigo-300 group-hover:text-white'
                            "
                        >
                            Dashboard
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
                    <div>
                        Administracion
                    </div>
                </div>
            </li>
            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" href="/areas">
                        <icon
                            name="office"
                            class="mr-2 w-4 h-4"
                            :class="
                                title === 'areas'
                                    ? 'fill-white'
                                    : 'fill-indigo-400 group-hover:fill-white'
                            "
                        />
                        <div
                            :class="
                                title === 'areas'
                                    ? 'text-white'
                                    : 'text-indigo-300 group-hover:text-white'
                            "
                        >
                            Areas
                        </div>
                    </Link>
                </div>
            </li>
            <li>
                <div class="mb-4">
                    <Link
                        class="group flex items-center py-3"
                        href="/departamentos"
                    >
                        <icon
                            name="office"
                            class="mr-2 w-4 h-4"
                            :class="
                                title === 'departamentos'
                                    ? 'fill-white'
                                    : 'fill-indigo-400 group-hover:fill-white'
                            "
                        />
                        <div
                            :class="
                                title === 'departamentos'
                                    ? 'text-white'
                                    : 'text-indigo-300 group-hover:text-white'
                            "
                        >
                            Departamentos
                        </div>
                    </Link>
                </div>
            </li>
            <li>
                <div class="mb-4">
                    <Link
                        class="group flex items-center py-3"
                        :href="route('user.index')"
                    >
                        <icon
                            name="users"
                            class="mr-2 w-4 h-4"
                            :class="
                                title === 'usuarios'
                                    ? 'fill-white'
                                    : 'fill-indigo-400 group-hover:fill-white'
                            "
                        />
                        <div
                            :class="
                                title === 'usuarios'
                                    ? 'text-white'
                                    : 'text-indigo-300 group-hover:text-white'
                            "
                        >
                            Usuarios
                        </div>
                    </Link>
                </div>
            </li>

            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" :href="route('challenge.index')">
                    <div :class="title === 'Chanllenges' ? 'activo flex' : 'inactivo flex'">
                        <icon name="office" class="mr-2 w-4 h-4" />
                        Challenges
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
