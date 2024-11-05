<template>
    <div v-if="isOpen" class="fixed inset-0 h-screen w-screen flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div class="bg-white rounded-lg shadow-lg max-w-lg w-full z-60 p-6 relative">
        <div class="flex justify-between items-center mb-4 ">
          <h2 class="text-xl font-semibold">{{ title }}</h2>
          <button @click="closeModal" class="text-gray-500 hover:text-gray-700"><i class="fa-solid fa-xmark"></i></button>
        </div>
  
        <!-- Modal Content -->
        <div class="mb-6">
          <slot></slot>
        </div>
  
        <!-- Modal Footer -->
        <div class="mt-2 flex justify-end space-x-2">
          <button @click="closeModal" class="text-gray-800 px-4 py-2.5 text-sm rounded-lg hover:bg-gray-100 transition">Cancel</button>
          <button @click="onSubmit" class="bg-blue-700 text-white px-4 py-2.5 text-sm rounded-lg hover:bg-blue-00/90 transition">Confirm</button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits } from 'vue';
  
  const props = defineProps({
    isOpen: {
      type: Boolean,
      required: true,
    },
    title: {
      type: String,
      default: 'Modal Title',
    },
  });
  
  const emits = defineEmits(['close', 'submit']);
  
  const closeModal = () => {
    emits('close');
  };
  
  const onSubmit = () => {
    emits('submit');
  };
  </script>
  
  <style scoped>
  /* Optional: Add any additional modal styles */
  </style>
  