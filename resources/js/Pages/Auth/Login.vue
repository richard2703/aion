<script setup>
import '../../../css/general.css';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
    title: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Inicio de sesion" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div v-if="status" class="mb-4 font-medium text-green-600 text-sm">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="Correo" value="Correo" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="block mt-1 w-full"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="Contrasena" value="Contraseña" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="block mt-1 w-full"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="text-gray-600 text-sm ms-2">Recuerdame</span>
                </label>
            </div>

            <div class="flex justify-end items-center mt-4">
                <!-- <Link v-if="canResetPassword" :href="route('password.request')" class="rounded-md focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-gray-600 text-sm hover:text-gray-900 underline focus:outline-none">
                    Forgot your password?
                </Link> -->

                <PrimaryButton class="bg-black ms-4" :class="{ ' opacity-25': form.processing }" :disabled="form.processing">
                    Entrar
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
