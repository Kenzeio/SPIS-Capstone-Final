<template>
    <div>
      <Head :title="sport.sport.name" />
      <AppLayout>
        <template v-slot:default>
          <!-- Header Section -->
          <div class="flex items-center gap-2">
            <h1 class="text-4xl font-semibold">{{ sport.sport.name }}</h1>
            <div>
              <button
                @click="returnToPalakasan"
                type="button"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-3 py-2 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
              >
                <i class="fa-solid fa-right-to-bracket mr-2"></i>
                Return
              </button>
            </div>
          </div>
  
          <!-- Tags Section -->
          <div class="flex gap-2 mt-2">
            <p class="py-1 px-4 rounded-full bg-blue-100 text-blue-700 text-sm">{{ sport.setup }}</p>
            <p class="py-1 px-4 rounded-full bg-blue-100 text-blue-700 text-sm">{{ sport.categories }}</p>
          </div>
  
          <!-- Sport Variations List -->
          <div class=" py-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Sport Variations</h2>
            <div v-if="sportVariations.length === 0" class="text-gray-500">
              No variations added yet.
            </div>
            <ul v-else class="space-y-4">
              <li v-for="variation in sportVariations" :key="variation.id" class="border-b pb-4 last:border-b-0">
                <div class="flex justify-between items-center">
                  <div>
                    <h3 class="text-lg font-semibold">{{ variation.sport_variation_name }}</h3>
                    <p class="text-sm text-gray-600">Venue: {{ getVenueName(variation.sport_variation_venue_id) }}</p>
                  </div>
                </div>
                <button
                  @click="openGenerateTeamsModal(variation)"
                  type="button"
                  class="text-white bg-blue-700 mt-2 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                >
                  Generate Teams
                </button>
              </li>
            </ul>
          </div>
  
          <!-- Add Variation Button -->
          <div class="mt-3">
            <button
              @click="showModal = true"
              type="button"
              class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
            >
              Add Sport Variation
            </button>
          </div>
  
          <!-- Sport Variation Modal -->
          <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
              <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Add Sport Variation</h2>
                <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
                  <i class="fa-solid fa-times"></i>
                </button>
              </div>
  
              <form @submit.prevent="submitForm" class="space-y-4">
                <!-- Sport Name (Readonly) -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Sport</label>
                  <input 
                    type="text" 
                    :value="sport.sport.name" 
                    readonly
                    class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md"
                  >
                </div>
  
                <!-- Variation Name -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Variation Name</label>
                  <input 
                    v-model="form.sport_variation_name"
                    type="text" 
                    required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-500': form.errors.sport_variation_name }"
                  >
                  <p v-if="form.errors.sport_variation_name" class="mt-1 text-sm text-red-600">
                    {{ form.errors.sport_variation_name }}
                  </p>
                </div>
  
                <!-- Venue Selection -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Venue</label>
                  <select 
                    v-model="form.sport_variation_venue_id"
                    required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-500': form.errors.venue_id }"
                  >
                    <option value="" disabled>Select a venue</option>
                    <option v-for="venue in venues" :key="venue.id" :value="venue.id">
                      {{ venue.name }}
                    </option>
                  </select>
                  <p v-if="form.errors.venue_id" class="mt-1 text-sm text-red-600">
                    {{ form.errors.venue_id }}
                  </p>
                </div>
  
                <!-- Modal Buttons -->
                <div class="flex justify-end gap-2">
                  <button 
                    type="button"
                    @click="closeModal"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                  >
                    Cancel
                  </button>
                  <button 
                    type="submit"
                    :disabled="form.processing"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50"
                  >
                    {{ form.processing ? 'Saving...' : 'Save' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
  

 <!-- Generate Teams Modal -->
 <div v-if="showGenerateTeamsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Generate Teams for {{ selectedVariation.sport_variation_name }}</h2>
        <button @click="closeGenerateTeamsModal" class="text-gray-500 hover:text-gray-700">
          <i class="fa-solid fa-times"></i>
        </button>
      </div>

      <form @submit.prevent="generateTeams">
        <div class="mb-4">
          <h3 class="text-lg font-semibold mb-2">Teams:</h3>
          <ul class="space-y-2">
            <li v-for="team in teams" :key="team.id" class="flex items-center">
              <span class="mr-2">{{ team.name }}</span>
              <span class="text-sm text-gray-500">(Rank: Not set)</span>
            </li>
          </ul>
        </div>

        <div class="flex justify-end gap-2">
          <button 
            type="button"
            @click="closeGenerateTeamsModal"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
          >
            Cancel
          </button>
          <button 
            type="submit"
            :disabled="generateTeamsForm.processing"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50"
          >
            {{ generateTeamsForm.processing ? 'Generating...' : 'Generate Teams' }}
          </button>
        </div>
      </form>
    </div>
  </div>
        </template>
      </AppLayout>
    </div>
  </template>
  
  <script setup>
  import { Head, useForm, router } from '@inertiajs/vue3';
  import { ref, onMounted } from 'vue';
  import { route } from 'ziggy-js';
  import AppLayout from '@/Layout/DashboardLayout.vue';
  
  const props = defineProps({
    sport: {
      type: [Array, Object],
      required: true
    },
    tournaments: {
      type: Array,
      default: () => []
    },
    venues: {
      type: Array,
      required: true
    },
    teams: {
      type: Array,
      default: () => []
    },
    allMatches: {
      type: Array,
      default: () => []
    },
    sportVariations: {
      type: Array,
      default: () => []
    }
  });
  
  const showModal = ref(false);
  const showGenerateTeamsModal = ref(false);
  const errorMessage = ref('');
  const selectedVariation = ref(null);
  const selectedTeams = ref([]);
  
  const form = useForm({
    assigned_sport_id: props.sport.id,
    sport_variation_name: '',
    sport_variation_venue_id: '',
  });
  
  const generateTeamsForm = useForm({
    sport_variation_id: '',
    teams: [],
  });

  onMounted(() => {
    form.assigned_sport_id = props.sport.id;
  });
  
  const closeModal = () => {
    showModal.value = false;
    errorMessage.value = '';
    form.reset();
    form.clearErrors();
  };
  
  const submitForm = async () => {
    try {
      await form.post(route('sport-variations.store'), {
        onSuccess: () => {
          form.reset();
          showModal.value = false;
          errorMessage.value = '';
        },
        onError: (errors) => {
          console.error('Validation errors:', errors);
          errorMessage.value = 'Please correct the errors below.';
        },
        onFinish: () => {
          form.processing = false;
        }
      });
    } catch (error) {
      console.error('Submission error:', error);
      errorMessage.value = 'An unexpected error occurred. Please try again.';
      form.processing = false;
    }
  };
  
  const returnToPalakasan = () => {
    router.get(route('palakasan.details', { tab: 'sports' }));
  };
  
  const getVenueName = (venueId) => {
    if (!venueId) return 'No venue assigned';
    const venue = props.venues.find(v => v.id === venueId);
    return venue ? venue.name : 'Unknown venue';
  };
  
  const openGenerateTeamsModal = (variation) => {
    selectedVariation.value = variation;
    generateTeamsForm.sport_variation_id = variation.id;
    generateTeamsForm.teams = props.teams.map(team => team.id);
    showGenerateTeamsModal.value = true;
  };

  const generateTeams = () => {
    generateTeamsForm.post(route('generate-teams'), {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        closeGenerateTeamsModal();
        alert('Teams generated successfully!');
      },
      onError: (errors) => {
        console.error('Error generating teams:', errors);
        alert('An error occurred while generating teams. Please try again.');
      },
    });
  };


const closeGenerateTeamsModal = () => {
  showGenerateTeamsModal.value = false;
  selectedVariation.value = null;
  generateTeamsForm.reset();
};

  </script>
  
  <style scoped>
  /* Component specific styles can be added here */
  </style>