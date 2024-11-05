<!-- Alert.vue -->
<template>
    <transition name="fade">
      <div v-if="show" class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
        {{ message }}
        <button @click="$emit('close')" class="ml-2 text-white hover:text-gray-200">&times;</button>
      </div>
    </transition>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue';
  
  const props = defineProps({
    message: {
      type: String,
      required: true
    },
    show: {
      type: Boolean,
      required: true
    }
  });
  
  defineEmits(['close']);
  </script>
  
  <style scoped>
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
  }
  .fade-enter-from, .fade-leave-to {
    opacity: 0;
  }
  </style>