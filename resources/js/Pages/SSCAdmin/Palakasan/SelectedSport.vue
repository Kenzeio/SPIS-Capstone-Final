<template>
    <Palakasan>
      <!-- Search bar and button to add new teams -->
      <div class="mb-4 flex justify-between items-center">
        <!-- Search Input -->
        <div class="relative">
          <!-- Search Icon (Left) -->
          <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
            <i class="fas fa-search"></i>
          </span>
  
          <!-- Input Field -->
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search sport..."
            class="w-64 pl-10 pr-10 py-2 bg-white shadow-sm border border-gray-300 focus:border-blue-500 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
          />
  
          <!-- Clear Button (Right) -->
          <button
            v-if="searchQuery"
            @click="clearSearch"
            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>
  
        <!-- Button to open the modal for adding new team -->
        <button @click="openTeamsModal" class="bg-blue-700 text-sm text-white px-4 py-2 rounded-lg">
          <i class="fa-solid fa-square-plus mr-1"></i>
          Sport
        </button>
      </div>
  
      <!-- Display Selected Sports -->
      <div class="mt-4">
        <div v-if="filteredAssignedSports.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="assigned in filteredAssignedSports"
            :key="assigned.id"
            class="bg-white border border-gray-300 rounded-lg p-4 shadow-sm"
          >
            <h3 class="font-bold text-xl">{{ assigned.sport.name }}</h3>
            <div class="flex mt-3 gap-2">
              <p class="py-1 px-3 rounded-full bg-blue-100/80 text-sm text-blue-700">{{ assigned.categories }}</p>
              <p class="py-0.5 px-3 rounded-full bg-blue-100/80 text-sm text-blue-700">{{ assigned.setup }}</p>

            </div>

          </div>
        </div>
        <div v-else class=" text-center rounded-lg border-2 border-blue-500 border-dashed p-10 bg-blue-100 text-blue-700">
          <h1 class="text-xl font-semibold">You need to select sports for this Palakasan.</h1> 
          <p class="text-sm">In selecting a sport, just click the <span class="font-bold">Sport</span> button at the top right of the screen to start.</p>
        </div>
      </div>
  
      <!-- Modal for adding new team -->
      <div v-if="isTeamsModalOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
          <h3 class="text-lg font-semibold mb-4">Add New Team</h3>
  
          <form @submit.prevent="submitTeam">
            <!-- Hidden Palakasan ID input -->
            <input type="text" v-model="form.palakasan_sport_id" class="hidden" />
            <!-- Select Sport -->
            <div class="mb-4">
              <label for="sport" class="block text-sm font-medium">Select Sport</label>
              <select v-model="form.sport_id" id="sport" class="w-full border px-3 py-2 rounded-lg">
                <option value="" disabled>Select a sport</option>
                <option v-for="sport in availableSports" :key="sport.id" :value="sport.id">
                  {{ sport.name }}
                </option>
              </select>
              <span v-if="form.errors.sport_id" class="text-red-500">{{ form.errors.sport_id }}</span>
            </div>
  
            <!-- Select Category -->
            <div class="mb-4">
              <label for="category" class="block text-sm font-medium">Select Category</label>
              <select v-model="form.categories" id="category" class="w-full border px-3 py-2 rounded-lg">
                <option value="" disabled>Select a category</option>
                <option value="Men">Men</option>
                <option value="Women">Women</option>
                <option value="Mixed">Mixed</option>
              </select>
              <span v-if="form.errors.categories" class="text-red-500">{{ form.errors.categories }}</span>
            </div>
  
            <!-- Select Setup -->
            <div class="mb-4">
              <label for="setup" class="block text-sm font-medium">Select Setup</label>
              <select v-model="form.setup" id="setup" class="w-full border px-3 py-2 rounded-lg">
                <option value="" disabled>Select a setup</option>
                <option value="Double Elimination">Double Elimination</option>
                <option value="Single Elimination">Single Elimination</option>
                <option value="Free for All">Free for All</option>
              </select>
              <span v-if="form.errors.setup" class="text-red-500">{{ form.errors.setup }}</span>
            </div>
  
            <!-- Modal Buttons -->
            <div class="flex justify-end">
              <button type="button" @click="closeTeamsModal" class="mr-2 px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
              <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition relative"
            >
                  <span v-if="!form.processing">
                      Confirm
                  </span>
                  <span v-else>
                      <svg class="animate-spin h-4 w-4 mr-3 border-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.969 7.969 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Processing...
                  </span>
              </button>  
            </div>
          </form>
        </div>
      </div>
    </Palakasan>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import Palakasan from '@/Layout/PalakasanLayout.vue';
  
  // Define props
  const props = defineProps({
    sports: Array,
    palakasans: Array,
    latestPalakasan: Object,
    assignedSports: Array, // Add this line to accept assigned sports
  });
  
  // Reactive variables
  const searchQuery = ref('');
  const isTeamsModalOpen = ref(false);
  
  // Initialize the form with useForm
  const form = useForm({
    palakasan_sport_id: props.latestPalakasan?.id || '',
    sport_id: '',
    categories: '',
    setup: '',
  });
  
  // Available sports
  const availableSports = ref(props.sports);
  
  // Open Teams modal
  const openTeamsModal = () => {
    isTeamsModalOpen.value = true;
  };
  
  // Close Teams modal and reset form
  const closeTeamsModal = () => {
    isTeamsModalOpen.value = false;
    form.reset();
  };
  
  // Submit form for adding a new team
  const submitTeam = () => {
    form.post('/palakasan/sportselection', {
      onSuccess: () => {
        closeTeamsModal();
      },
      onError: () => {
        console.error('Form submission error');
      },
    });
  };
  
  // Clear search query
  const clearSearch = () => {
    searchQuery.value = '';
  };
  
  // Filter assigned sports for the latest Palakasan
  const filteredAssignedSports = computed(() => {
    return props.assignedSports.filter(
      assigned => assigned.palakasan_sport_id === props.latestPalakasan?.id
    );
  });
  </script>
  
  <style scoped>
  /* Add any additional styles here if needed */
  </style>
  