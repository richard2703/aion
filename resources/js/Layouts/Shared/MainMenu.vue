<template>
    <div>
        <ul class="space-y-2">
            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" href="/">
                        <icon
                            name="dashboard"
                            class="mr-2 w-4 h-4"
                            :class="
                                isUrl('')
                                    ? 'fill-white'
                                    : 'fill-indigo-400 group-hover:fill-white'
                            "
                        />
                        <div
                            :class="
                                isUrl('')
                                    ? 'text-white'
                                    : 'text-indigo-300 group-hover:text-white'
                            "
                        >
                            Dashboard
                        </div>
                    </Link>
                </div>
            </li>

            <li>
                <div class="mb-4">
                    <Link class="group flex items-center py-3" href="/areas">
                        <icon
                            name="office"
                            class="mr-2 w-4 h-4"
                            :class="
                                isUrl('areas')
                                    ? 'fill-white'
                                    : 'fill-indigo-400 group-hover:fill-white'
                            "
                        />
                        <div
                            :class="
                                isUrl('areas')
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
                                isUrl('departamentos')
                                    ? 'fill-white'
                                    : 'fill-indigo-400 group-hover:fill-white'
                            "
                        />
                        <div
                            :class="
                                isUrl('departamentos')
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
                    <Link class="group flex items-center py-3" href="/users">
                        <icon
                            name="users"
                            class="mr-2 w-4 h-4"
                            :class="
                                isUrl('users')
                                    ? 'fill-white'
                                    : 'fill-indigo-400 group-hover:fill-white'
                            "
                        />
                        <div
                            :class="
                                isUrl('users')
                                    ? 'text-white'
                                    : 'text-indigo-300 group-hover:text-white'
                            "
                        >
                            Usuarios
                        </div>
                    </Link>
                </div>
            </li>

            <!-- <div class="mb-4">
      <Link class="group flex items-center py-3" href="/contacts">
        <icon name="users" class="mr-2 w-4 h-4" :class="isUrl('contacts') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('contacts') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Contacts</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/usuario" >
        <icon name="printer" class="mr-2 w-4 h-4" :class="isUrl('test') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('test') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Reports</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/reports" >
        <icon name="printer" class="mr-2 w-4 h-4" :class="isUrl('reports') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('reports') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Reports</div>
      </Link>
    </div> -->
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
    methods: {
        isUrl(...urls) {
            let currentUrl = this.$page.url.substr(1);
            if (urls[0] === "") {
                return currentUrl === "";
            }
            return urls.filter((url) => currentUrl.startsWith(url)).length;
        },
    },
};
</script>
