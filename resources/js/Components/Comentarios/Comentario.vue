<script setup>
import { onMounted, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import Message from 'primevue/message';
import ComentarioCreate from "./ComentarioCreate.vue";
import ComentarioUpdate from './ComentarioUpdate.vue'
import { confirmDialog } from "@/Pages/utils/SweetAlert.service";

const props = defineProps({
    minuta_id: Number,
});

const minuta_id = props.minuta_id;
const comentarios = ref([]);
const comentario = ref();
const editar = ref(false);

const form = useForm({
    texto: "",
    minuta_id: "",

});

onMounted(() => {
    getComentarios();
});
const getComentarios = async () => {
    const response = await axios.get(route('comentario.getComentarios', minuta_id));
    comentarios.value = response.data;
}

const getComentario = async (comentario_id) => {

    const response = await axios.get(route('comentario.edit', comentario_id));
    comentario.value = response.data;
    form.texto = response.data.texto
    editar.value = true

}

const deleteComentario = async (comentario_id) => {
    try {
        const result = await confirmDialog(
            "Estas seguro?",
            "Ya no podras revertir esto!",
            "warning"
        );
        if (result.isConfirmed) {
            const response = await axios.delete(route('comentario.destroy', comentario_id)).then(() => {
                getComentarios();
                showToast("El registro ha sido eliminado", "success");
            });
        }
    } catch (error) {
        console.error(error);
    }
}
</script>

<template>
    <div class="py-4">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="my-4">
                    <Message class="w-full text-center" closable="true" severity="secondary">
                        ¡Esta observación es propia y no
                        se comparte con nadie!
                    </Message>
                </div>
                <div v-if="comentarios" class="my-4">
                    <ul v-for="comentario in comentarios" :key="comentario.id" class="list-disc list-inside">
                        <div class="flex justify-between">
                            <li class="text-sm text-gray-600
                            text-justify
                            cursor-pointer hover:text-gray-300" @click.stop="getComentario(comentario.id)">
                                {{ comentario.texto }}
                            </li>
                            <button class="float-right mx-4 pi pi-times text-red-500
                            cursor-pointer hover:text-red-300"
                                @click.stop="deleteComentario(comentario.id)">Delete</button>
                        </div>
                    </ul>
                </div>
                <ComentarioCreate v-if="!editar" :minuta_id="minuta_id" @list="getComentarios" />
                <ComentarioUpdate v-if="editar" :comentario="comentario" :minuta_id="minuta_id" @list="getComentarios"
                    @close="editar = false" />
            </div>
        </div>
    </div>
</template>


<style></style>
