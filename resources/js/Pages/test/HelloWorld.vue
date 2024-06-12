<template>
    <!-- <Layout title="Profile"> -->

        <div>
      <h1>{{ message }}</h1>
      <PrimaryButton @click="cambiarMensaje">Cambiar Mensaje</PrimaryButton>
        </div>
    <!-- </Layout> -->

  </template>

  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import Layout from '@/Layouts/Layout.vue';


  const props = defineProps({
    message: String,
  });

  const message = ref(props.message);

  const cambiarMensaje = async () => {
    try {
      const response = await axios.get(route('test.cambiarMensaje'));
      message.value = response.data.message;
    } catch (error) {
      console.error('Error al cambiar el mensaje:', error);
    }
  };


// USAR ESTA FORMA PARA CAMBIO DE VISTA
//   const cambiarMensaje = () => {
//     form.get('/cambiar-mensaje', {
//       preserveState: true,
//       onSuccess: (page) => {
//         // alert(page.props.message);
//         form.message = page.props.message;
//       },
//     });
//   };
  </script>
 <script>
   import Layout from '@/Layouts/Layout.vue';

export default {

  components: {

  },
  layout: Layout,
}
</script>
