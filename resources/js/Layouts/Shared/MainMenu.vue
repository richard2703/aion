<template>
    <div>
        <ul class="space-y-2">
            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" href="/">
                    <icon name="dashboard" class="mr-2 w-4 h-4"
                        :class="title === 'dashboard' ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
                    <div :class="title === 'dashboard' ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                        Dashboard
                    </div>
                    </Link>
                </div>
            </li>

            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" href="/areas">
                    <icon name="office" class="mr-2 w-4 h-4"
                        :class="title === 'areas' ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
                    <div :class="title === 'areas' ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                        Areas
                    </div>
                    </Link>
                </div>
            </li>

            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" href="/departamentos">
                    <icon name="office" class="mr-2 w-4 h-4"
                        :class="title === 'departamentos' ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
                    <div :class="title === 'departamentos' ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                        Departamentos
                    </div>
                    </Link>
                </div>
            </li>

            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" href="/users">
                    <icon name="users" class="mr-2 w-4 h-4"
                        :class="title === 'usuarios' ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
                    <div :class="title === 'usuarios' ? 'text-white' : 'text-indigo-300 group-hover:text-white'">
                        Usuarios
                    </div>
                    </Link>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
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
};
</script>
