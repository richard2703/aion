<script>
export default {
    data() {
        return {
            isChatOpen: false, // Controla el estado del chat (abierto o cerrado)
            messages: [], // Almacena los mensajes enviados
            newMessage: '', // Contenido del mensaje actual
        };
    },
    created() {
        // Restaurar el estado desde localStorage al cargar la página
        const savedState = localStorage.getItem('chatbotState');
        if (savedState) {
            const { isChatOpen, messages } = JSON.parse(savedState);
            this.isChatOpen = isChatOpen;
            this.messages = messages;
        }
    },
    methods: {
        toggleChat() {
            if (this.isChatOpen) {
                // Limpiar los mensajes y el estado al cerrar el chat
                this.messages = [];
                localStorage.removeItem('chatbotState'); // Eliminar el estado almacenado
            }
            this.isChatOpen = !this.isChatOpen;
            this.saveState(); // Guardar el nuevo estado
            this.$nextTick(() => this.scrollToBottom());
        },
        sendMessage() {
            if (this.newMessage.trim()) {
                this.messages.push({ text: this.newMessage, sender: 'user' });
                this.newMessage = '';
                this.saveState(); // Guardar el estado en localStorage
                this.$nextTick(() => this.scrollToBottom());

                setTimeout(() => {
                    this.messages.push({ text: "¡Hola! ¿En qué puedo ayudarte?", sender: 'bot' });
                    this.saveState(); // Guardar el estado en localStorage
                    this.$nextTick(() => this.scrollToBottom());
                }, 1000);
            }
        },
        scrollToBottom() {
            const chatBody = this.$refs.chatBody;
            if (chatBody) {
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        },
        saveState() {
            // Guardar el estado actual en localStorage
            const state = {
                isChatOpen: this.isChatOpen,
                messages: this.messages,
            };
            localStorage.setItem('chatbotState', JSON.stringify(state));
        },
    },
    beforeDestroy() {
        // Limpiar el estado del chatbot si se recarga o cierra la aplicación
        localStorage.removeItem('chatbotState');
    },
};
</script>


<template>
    <div>
        <!-- Botón de ChatBot -->
        <div class="right-[40px] bottom-[4rem] z-10 fixed p-4 rounded-full text-sm transition-colors duration-300 cursor-pointer ease-in-out"
            :class="{
                'bg-gray-200 hover:bg-gradient-to-r from-gray-600 to-black hover:text-white': !isChatOpen,
                'bg-red-500 text-white hover:bg-red-700': isChatOpen,
            }" @click="toggleChat">
            <span class="block relative w-6 h-3 transition-transform duration-300 ease-in-out" :class="{
                'rotate-0 opacity-100': !isChatOpen,
                '-rotate-90 opacity-0': isChatOpen,
            }">
                <i class="top-[-1px] left-[-3px] absolute inset-0 shadow-lg pi pi-comment"
                    style="font-size: 2rem;"></i>
            </span>
            <span class="block relative w-7 h-4 transition-transform duration-300 ease-in-out" :class="{
                'rotate-90 opacity-0': !isChatOpen,
                'rotate-0 opacity-100': isChatOpen,
            }">
                <i class="top-[-9px] left-[2px] absolute inset-0 pi pi-times" style="font-size: 1.5rem;"></i>
            </span>
        </div>

        <!-- Transición para el ChatBot -->
        <transition name="fade-chat" appear mode="out-in">
            <div v-if="isChatOpen"
                class="right-[40px] bottom-[9rem] z-10 fixed flex flex-col bg-[#f8f6f6] shadow-md rounded-lg w-[294px] md:w-[380px] h-[440px] md:h-[500px]">

                <!-- Header -->
                <div class="flex justify-between items-center content-center bg-black p-4 rounded-t-md text-white">
                    <div class="flex items-center content-center gap-5">
                        <span class="inline-block relative">
                            <img class="rounded-full size-10"
                                src="/iconChatbot.svg"
                                alt="" />
                            <span
                                class="block right-0 bottom-0 absolute bg-green-400 rounded-full ring-2 ring-white size-2.5" />
                        </span>
                        <p class="font-bold">ChatBot</p>
                    </div>

                    <button @click="toggleChat" class="p-2 rounded-full text-gray-400 hover:text-red-600">
                        <i class="pi pi-times" style="font-size: 1.0rem;"></i>
                    </button>
                </div>

                <!-- Body buble chat-->
                <div ref="chatBody" class="flex-grow space-y-4 p-4 overflow-y-scroll">
                    <div v-for="(message, index) in messages" :key="index" class="flex justify-start">
                        <!-- Mensaje del bot -->
                        <div v-if="message.sender === 'bot'" class="bg-white p-2 rounded-md max-w-[75%] text-black">
                            {{ message.text }}
                        </div>

                        <!-- Mensaje del usuario -->
                        <div v-if="message.sender === 'user'"
                            class="bg-gray-800 ml-auto p-2 rounded-md max-w-[75%] text-white">
                            {{ message.text }}
                        </div>
                    </div>
                </div>


                <!-- Input -->
                <div class="flex p-4 border-t">
                    <input v-model="newMessage" @keyup.enter="sendMessage" type="text"
                        placeholder="Escribe un mensaje..."
                        class="flex-grow border-gray-300 p-2 border rounded-lg focus:ring focus:ring-blue-300 focus:outline-none">
                    <button @click="sendMessage"
                        class="bg-black hover:bg-gray-600 ml-2 p-2 rounded-lg text-white transition">
                        Enviar
                    </button>
                </div>
            </div>
        </transition>
    </div>
</template>

<style>
/* Estilos personalizados para la transición */
.fade-chat-enter-active,
.fade-chat-leave-active {
    transition: all 0.3s ease-in-out;
}

.fade-chat-enter-from,
.fade-chat-leave-to {
    transform: translateY(20px);
    opacity: 0;
}
</style>
