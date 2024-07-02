<template>
    <div class="py-2">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-4 py-2 bg-white border-b border-gray-200">
                <div class="container mx-auto">
                    <form @submit.prevent="submit" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <InputLabel for="proposito" value="Propósito: " />
                                <TextInput id="proposito" v-model="form.proposito" type="text" class="mt-1 block w-full"
                                    required autofocus autocomplete="proposito" />
                            </div>

                            <div>
                                <InputLabel for="logo" value="Logo: " />
                                <input id="logo" type="file" @change="onFileChange('logo', $event)"
                                    class="mt-1 block w-full" autocomplete="logo" />
                            </div>

                            <div>
                                <InputLabel for="banner" value="Banner: " />
                                <input id="banner" type="file" @change="onFileChange('banner', $event)"
                                    class="mt-1 block w-full" autocomplete="banner" />
                            </div>

                            <div class="col-span-full flex items-center justify-end mt-4">
                                <PrimaryButton class="ms-4" :class="{
                                    'opacity-25': form.processing,
                                }" :disabled="form.processing">
                                    {{ isUpdating ? 'Actualizar' : 'Guardar' }}
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
import { ref, onMounted } from "vue";
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

const isUpdating = ref(false);

const getItem = () => {
    axios
        .get("/api/config-dashboard")
        .then((response) => {
            console.log(response.data);
            item.value = response.data;
            form.proposito = item.value.proposito;
            // Set other form fields here as needed
        })
        .catch((error) => {
            console.error('Error fetching item:', error);
        });
};

// Function to handle file input change and update form data
const onFileChange = (key, event) => {
    form[key] = event.target.files[0];
};

onMounted(() => {
    getItem();  // Fetch item data
});

const submit = async () => {
    const formData = new FormData();
    formData.append('proposito', form.proposito);
    formData.append('logo', form.logo);
    formData.append('banner', form.banner);

    try {
        let response;
        if (item.value.id) {
            // Update existing item
            console.log('Form data update:', formData);
            response = await axios.post(`/dashboard/${item.value.id}/update`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
        } else {
            // Create new item
            console.log('Form data create:', formData);
            response = await axios.post('/dashboard/store', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
        }

        console.log('Form submitted successfully:', response.data);

        // Optionally reset form state after successful submission
        form.reset();
    } catch (error) {
        if (error.response) {
            console.error('Error response:', error.response.data);
            // Handle validation errors or other errors from backend
        } else {
            console.error('Error submitting form:', error);
        }
    }
};
</script>
