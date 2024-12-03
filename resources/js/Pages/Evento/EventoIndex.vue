<script setup>
import { ref, onMounted, computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import Layout from "@/Layouts/Layout.vue";
import axios from "axios";
import ColorPicker from "primevue/colorpicker";
import { confirmDialog, showToast } from "../utils/SweetAlert.service";
import PrimaryButton from "@/Components/PrimaryButton.vue";



// Calendar state
const currentYear = ref(new Date().getFullYear());
const currentMonth = ref(new Date().getMonth());
const daysInMonth = computed(() =>
    new Date(currentYear.value, currentMonth.value + 1, 0).getDate()
);
const firstDayOfMonth = computed(() =>
    new Date(currentYear.value, currentMonth.value, 1).getDay()
);
const monthNames = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
];

// Event data
const events = ref({}); // Events organized by date

// Modals
const modal = ref({
    show: false,
    type: "create",
    day: null,
    event: {
        id: null,
        titulo: "",
        descripcion: "",
        fecha_inicio: "",
        fecha_final: "",
        color: "",
    },
});
const viewModal = ref({ show: false, event: null });

const colors = ['purple', 'blue', 'Indigo', 'green', 'teal', 'pink'];
const options = [
    {
        label: 'Gente y Cultura',
        color: ''
    },
    {
        label: 'TI',
        color: ''
    },
    {
        label: 'Ventas',
        color: ''
    },
    {
        label: 'Operaciones',
        color: ''
    },
    {
        label: 'Administración',
        color: ''
    },
    {
        label: 'Nuevos productos',
        color: ''
    }
];
const selectedColor = ref("");

// formatear fecha
const formatDate = (year, month, day) => {
    const paddedMonth = String(month).padStart(2, "0");
    const paddedDay = String(day).padStart(2, "0");
    return `${year}-${paddedMonth}-${paddedDay}`;
};

const getEventos = async () => {
    try {
        const { data } = await axios.get(route("eventos.findAll"), {
            params: {
                year: currentYear.value,
                month: currentMonth.value + 1,
            },
        });

        // Organize events by date
        const organizedEvents = {};
        data.forEach((event) => {
            const dateKey = event.fecha_inicio;
            if (!organizedEvents[dateKey]) {
                organizedEvents[dateKey] = [];
            }
            organizedEvents[dateKey].push(event);
        });
        events.value = organizedEvents;
    } catch (error) {
        console.error("Error fetching events:", error);
    }
};

// cerrar modales
const closeModal = () => {
    modal.value.show = false;
};
const closeviewModal = () => {
    viewModal.value.show = false;
};

const submit = async () => {
    try {
        const formattedDate = formatDate(
            currentYear.value,
            currentMonth.value + 1,
            modal.value.day
        );

        if (modal.value.type === "create") {
            const { data } = await axios.post(route("eventos.store"), {
                titulo: modal.value.event.titulo,
                descripcion: modal.value.event.descripcion,
                fecha_inicio: formattedDate,
                fecha_final: modal.value.event.fecha_final,
                color: modal.value.event.color,
            });

            if (!events.value[formattedDate]) {
                events.value[formattedDate] = [];
            }
            events.value[formattedDate].push(data);
            await getEventos();
        } else {
            await axios.patch(
                route("eventos.update", modal.value.event.id),
                modal.value.event
            );
            await getEventos(); // Refresh events
        }
        closeModal();
    } catch (error) {
        console.error("Error saving event:", error);
    }
};

const deleteEvento = async (event) => {
    try {
        const result = await confirmDialog(
            "Estas seguro?",
            "Ya no podras revertir esto!",
            "warning"
        );
        if (result.isConfirmed) {
            await axios.delete(route("eventos.destroy", event.id));
            showToast("El registro ha sido eliminado", "success");
            closeviewModal();
            await getEventos();
        }
    } catch (error) {
        console.error(error);
    }
};

// Mostrar modales
const showCreateModal = (day) => {
    const formattedDate = formatDate(
        currentYear.value,
        currentMonth.value + 1,
        day
    );
    modal.value = {
        show: true,
        type: "create",
        day,
        event: {
            id: null,
            titulo: "",
            descripcion: "",
            fecha_inicio: formattedDate,
            fecha_final: "",
            color: "",
        },
    };
};

const showEditModal = (event) => {
    modal.value = {
        show: true,
        type: "edit",
        event,
    };
    closeviewModal();
};
const showEvent = (event) => {
    viewModal.value = { show: true, event };
};

onMounted(() => {
    getEventos();
});

// Navegacion entre meses
const changeMonth = (delta) => {
    currentMonth.value += delta;
    if (currentMonth.value < 0) {
        currentMonth.value = 11;
        currentYear.value -= 1;
    } else if (currentMonth.value > 11) {
        currentMonth.value = 0;
        currentYear.value += 1;
    }
    getEventos();
};
</script>

<template>
    <Layout>

        <Head title="Reporte Semanal" />
        <div class="overflow-hidden sm:rounded-lg">
            <div class="breadcrumbsTitulo px-1">
                <h3>Eventos</h3>
            </div>
            <div class="breadcrumbs flex">
                <Link :href="route('dashboard')" class="px-1">
                <h3>Home -</h3>
                </Link>
                <Link href="#" class="active">
                <h3>Eventos</h3>
                </Link>
            </div>
        </div>

        <div class="py-2">
            <div class="max-w-4xl mx-auto py-10">
                <h1 class="text-2xl font-bold text-center mb-6">
                    Calendario de Eventos
                </h1>

                <!-- Month and Year Selector -->
                <div class="flex items-center justify-between mb-4">
                    <button @click="changeMonth(-1)" class="px-4 py-2 bg-gray-200 rounded shadow hover:bg-gray-300">
                        &lt; anterior
                    </button>
                    <div class="text-lg font-semibold">
                        {{ monthNames[currentMonth] }} {{ currentYear }}
                    </div>
                    <button @click="changeMonth(1)" class="px-4 py-2 bg-gray-200 rounded shadow hover:bg-gray-300">
                        siguiente &gt;
                    </button>
                </div>

                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 gap-2">
                    <div class="font-semibold text-center">Do</div>
                    <div class="font-semibold text-center">Lu</div>
                    <div class="font-semibold text-center">Ma</div>
                    <div class="font-semibold text-center">Mi</div>
                    <div class="font-semibold text-center">Ju</div>
                    <div class="font-semibold text-center">Vi</div>
                    <div class="font-semibold text-center">Sa</div>

                    <!-- Empty Days for Offset -->
                    <div v-for="n in firstDayOfMonth" :key="'empty-' + n" class="border p-4 rounded-lg bg-gray-100">
                    </div>

                    <!-- Days of the Month -->
                    <div v-for="day in daysInMonth" :key="'day-' + day"
                        class="border p-4 rounded-lg text-center relative hover:bg-gray-50">
                        <div>{{ day }}</div>
                        <button class="absolute top-2 right-2 bg-slate-600 text-white text-xs px-2 py-1 rounded"
                            @click="showCreateModal(day)">
                            +
                        </button>
                        <ul class="mt-2 text-sm">

                            <li v-for="event in events[
                                formatDate(
                                    currentYear,
                                    currentMonth + 1,
                                    day
                                )
                            ] || []" :key="event.id" class="rounded mb-1 cursor-pointer hover:bg-gray-200 flex"
                                @click="showEvent(event)">
                                <label :for="color"
                                    :style="{ backgroundColor: event.color, color: event.color, width: '20px', height: '20px' }"
                                    class=" rounded-full cursor-pointer border-2 border-transparent peer-checked:border-gray-4 00 transition"></label>{{
                                        event.titulo }}
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Modals -->
                <div v-if="modal.show" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                        <h2 class="text-xl font-semibold mb-4">
                            {{
                                modal.type === "create"
                                    ? "Crear nuevo Evento"
                                    : "Actualizar Evento"
                            }}
                        </h2>
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Titulo</label>
                                <input v-model="modal.event.titulo" type="text" class="w-full border rounded p-2"
                                    required />
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Descripción</label>
                                <textarea v-model="modal.event.descripcion" class="w-full border rounded p-2"
                                    rows="3"></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Fecha</label>
                                <input v-model="modal.event.fecha_inicio" type="date" class="w-full border rounded p-2"
                                    required />
                            </div>
                            <!-- <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Fecha de fin</label>
                                <input v-model="modal.event.fecha_final" type="date" class="w-full border rounded p-2"
                                    required />
                            </div> -->
                            <div class="mb-4">
                                <div class="flex gap-4">
                                    <div v-for="color in colors" :key="color" class="relative">
                                        <!-- Hidden radio input -->
                                        <input type="radio" :id="color" name="color" :value="color"
                                            v-model="selectedColor" class="peer hidden" />
                                        <!-- Styled label -->
                                        <label :for="color"
                                            :style="{ backgroundColor: color, color: color, width: '20px', height: '20px' }"
                                            class="rounded-full cursor-pointer border-2 border-transparent peer-checked:border-gray-4 00 transition">00</label>
                                    </div>
                                </div>
                                <p class="mt-4">Pilar seleccionado:

                                    <span v-if="selectedColor == 'purple'" class="font-bold">Gente y Cultura</span>
                                    <span v-if="selectedColor == 'blue'" class="font-bold">TI</span>
                                    <span v-if="selectedColor == 'Indigo'" class="font-bold">Ventas</span>
                                    <span v-if="selectedColor == 'green'" class="font-bold">Operaciones</span>
                                    <span v-if="selectedColor == 'teal'" class="font-bold">Administración</span>
                                    <span v-if="selectedColor == 'pink'" class="font-bold">Nuevos productos</span>
                                </p>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" class="px-4 py-2 bg-slate-800 text-white rounded pi pi-times"
                                    @click="closeModal">
                                </button>
                                <button type="submit" class="px-4 py-2 bg-[#BEA34B] text-white rounded pi pi-save">
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div v-if="viewModal.show"
                    class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                        <h2 class="text-xl font-semibold mb-4">
                            {{ viewModal.event.titulo }}
                        </h2>
                        <p class="text-sm text-gray-600 mb-4">
                            Descripción: {{ viewModal.event.descripcion }}
                        </p>
                        <p class="text-sm text-gray-600 mb-4">
                            Fecha: {{ viewModal.event.fecha_inicio }}
                        </p>
                        <!-- <p class="text-sm text-gray-600 mb-4">
                            {{ viewModal.event.fecha_final }}
                        </p> -->

                        <ColorPicker v-model="viewModal.event.color" inputId="cp-hex" format="hex" class="mb-4"
                            disabled />
                        <div class="flex justify-end space-x-2">
                            <button class="px-4 py-2 bg-slate-800 text-white rounded pi pi-times"
                                @click="closeviewModal">
                            </button>
                            <button class="px-4 py-2 bg-[#BEA34B] text-white rounded pi pi-pencil"
                                @click="showEditModal(viewModal.event)">
                            </button>
                            <button class="px-4 py-2 bg-red-500 text-white rounded pi pi-trash"
                                @click="deleteEvento(viewModal.event)"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style></style>
