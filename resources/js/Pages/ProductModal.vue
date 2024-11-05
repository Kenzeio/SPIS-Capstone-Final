<template>
  <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center z-40">
    <!-- Modal Overlay -->
    <div class="modal-overlay" @click="close"></div>

    <!-- Modal Content -->
    <div class="modal-content bg-white p-4 rounded shadow-lg z-50">
      <h2 class="text-xl font-bold mb-4">Add Product</h2>
      <form @submit.prevent="addProduct">
        <div>
          <label for="name" class="block">Product Name:</label>
          <input type="text" id="name" v-model="form.name" class="input" required />
        </div>
        <div>
          <label for="price" class="block">Price:</label>
          <input type="number" id="price" v-model="form.price" class="input" required />
        </div>
        <div class="mt-4">
          <button type="button" @click="close" class="mr-2 bg-gray-300">Cancel</button>
          <button type="submit" class="bg-blue-500 text-white">Add Product</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, defineEmits } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
  isOpen: Boolean,
});

const emit = defineEmits(['close', 'product-added']);

const form = ref({
  name: '',
  price: '',
});

const close = () => {
  form.value.name = '';
  form.value.price = '';
  emit('close');
};

const addProduct = () => {
  useForm(form.value).post(route('products.store'), {
    onSuccess: () => {
      close();
      emit('product-added');
    },
  });
};
</script>

<style scoped>
.modal-overlay {
  z-index: 40;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
}

.modal-content {
  max-width: 400px;
  width: 100%;
  z-index: 50; /* Ensure the modal content appears above the overlay */
}
</style>
