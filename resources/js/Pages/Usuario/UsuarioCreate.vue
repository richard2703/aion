<script setup>
import { ref } from "vue";
import Layout from "@/Layouts/Layout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    areas: Array,
    departamentos: Array || null,
});

const areas = ref(props.areas);
const departamentos = ref(props.departamentos);

async function getAreas() {
    await axios
        .get("/api/areas")
        .then((response) => (areas.value = response.data.areas))
        .catch((error) => {
            console.log(error);
        });
}

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    area_id: "",
    departamento_id: "",
});

const onChange = async (event) => {
    const taget_id = event.target.value;
    await axios
        .get(route("departamentos.byArea", taget_id))
        .then((response) => (departamentos.value = response.data.departamentos))
        .catch((error) => {
            console.log(error);
        });
};

const submit = () => {
    form.post(route("user.store"), {
        onFinish: () => form.reset(),
    });
};

getAreas();

console.log({ areas, departamentos });
</script>

<template>
    <Head title="Usuario" />

    <Layout>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Usuarios
        </h2>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="area_id" value="Area: " />
                <select
                    ref="area_select"
                    @change="onChange($event)"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                    v-model="form.area_id"
                    required
                >
                    <option value="" disabled selected>
                        Seleccione una opcion
                    </option>
                    <option
                        v-for="area in areas"
                        :key="area.id"
                        :value="area.id"
                    >
                        {{ area.nombre }}
                    </option>
                </select>
            </div>
            <div class="mt-4">
                <InputLabel for="departamento_id" value="Departamento: " />

                <select
                    ref="departamento_select"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full px-3 py-2 cursor-pointer"
                    v-model="form.departamento_id"
                    required
                >
                    <option value="" disabled selected>
                        Seleccione una opcion
                    </option>
                    <option
                        v-for="departamento in departamentos"
                        :key="departamento.id"
                        :value="departamento.id"
                    >
                        {{ departamento.nombre }}
                    </option>
                </select>
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                />
                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div
                v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature"
                class="mt-4"
            >
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox
                            id="terms"
                            v-model:checked="form.terms"
                            name="terms"
                            required
                        />

                        <div class="ms-2">
                            I agree to the
                            <a
                                target="_blank"
                                :href="route('terms.show')"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >Terms of Service</a
                            >
                            and
                            <a
                                target="_blank"
                                :href="route('policy.show')"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >Privacy Policy</a
                            >
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.terms" />
                </InputLabel>
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    guardar
                </PrimaryButton>
            </div>
        </form>
    </Layout>
</template>
