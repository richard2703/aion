<template>
    <div class="py-2">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-4 py-2 bg-white border-b border-gray-200">
                <div class="container mx-auto">
                    <form @submit.prevent="submit" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <InputLabel for="proposito" value="Proposito: " />
                                <TextInput id="proposito" v-model="form.proposito" type="text" class="mt-1 block w-full"
                                    required autofocus autocomplete="proposito" />
                            </div>

                            <div>
                                <InputLabel for="logo" value="Logo: " />
                                <input id="logo" type="file" @change="onFileChange('logo', $event)"
                                    class="mt-1 block w-full" required autocomplete="logo" />
                            </div>

                            <div>
                                <InputLabel for="banner" value="Banner: " />
                                <input id="banner" type="file" @change="onFileChange('banner', $event)"
                                    class="mt-1 block w-full" required autocomplete="banner" />
                            </div>

                            <div class="col-span-full flex items-center justify-end mt-4">
                                <PrimaryButton class="ms-4" :class="{
                                    'opacity-25': form.processing,
                                }" :disabled="form.processing">
                                    Guardar
                                </PrimaryButton>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from 'axios';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    item: Object || null,
});

const item = ref(props.item);

const form = useForm({
    proposito: item.value?.proposito || '',
    logo: null,
    banner: null,
});

// Function to handle file input change and update form data
const onFileChange = (key, event) => {
    form[key] = event.target.files[0];
};

const submit = async () => {
    try {
        const formData = new FormData();
        formData.append('proposito', form.proposito);
        formData.append('logo', form.logo);
        formData.append('banner', form.banner);

        const response = await axios.post('/dashboard/store', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        console.log('Form submitted successfully:', response.data);

        // Optionally reset form state after successful submission
        form.reset();
    } catch (error) {
        if (error.response) {
            console.error('Error response:', error.response.data);
            // Handle validation errors or other errors from backend
            if (error.response.status === 422) {
                // Handle validation errors in your form
                // Assuming you have a way to display errors in your form
                form.errors = error.response.data.errors;
            }
        } else {
            console.error('Error submitting form:', error);
        }
    }
};
</script>
