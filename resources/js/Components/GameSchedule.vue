<template>
  <div class="game-schedule">
    <h2 class="text-md font-medium mt-1 mb-4">Match Schedule</h2>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      <div v-for="match in sortedMatches" 
           :key="match.id" 
           class="bg-white shadow rounded-lg p-4 border border-gray-300 hover:shadow-md transition-all duration-200">
        <div class="flex justify-between items-center mb-3">
          <div class="flex flex-col">
            <span class="font-semibold text-md">{{ match.game }}</span>
            <span class="text-xs text-gray-500">Round {{ match.round }}</span>
          </div>
          <div class="flex">
            <button 
              type="button" 
              class="p-2 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg  hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
            >
              <svg  class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18.5L9 17l-6 3V7l6-3l6 3l6-3v7M9 4v13m6-10v5m6.121 8.121a3 3 0 1 0-4.242 0Q17.506 20.749 19 22q1.577-1.335 2.121-1.879M19 18v.01"/></svg>
            </button>
            <button 
              @click="openTimeModal(match)" 
              type="button" 
              class="p-2 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg  hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
              :disabled="match.status === 'completed'"
            >
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M21 12a9 9 0 1 0-9.972 8.948q.48.051.972.052"/>
                <path d="M12 7v5l2 2m4.42 1.61a2.1 2.1 0 0 1 2.97 2.97L18 22h-3v-3z"/></g>
              </svg>
            </button>
          </div>
        </div>

        <div class="grid grid-cols-4 gap-3 items-center">
          <div class=" col-span-2 flex justify-center bg-gray-100 p-2 rounded-md items-center">
            <span class="font-medium mr-3">{{ getTeamName(match.teamA_id) }}</span>
            <span :class="getScoreClass(match, 'teamA')">{{ getScore(match, 'teamA') }}</span>
          </div>
          <div class=" col-span-2 flex justify-center bg-gray-100 p-2 rounded-md items-center">
            <span :class="getScoreClass(match, 'teamB')">{{ getScore(match, 'teamB') }}</span>
            <span class="font-medium ml-3">{{ getTeamName(match.teamB_id) }}</span>
          </div>
        </div>

        <div class="flex items-center mt-4 gap-2.5">
          <div class="text-xs text-gray-600 ">
            <div class="flex items-center gap-1.5">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>{{ getVenueName(match.match_venue_id) }}</span>
            </div>
          </div>
          <p class="text-xs text-gray-400">|</p>
          <div class="text-xs text-gray-600">
            <div class="flex items-center gap-1.5">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span :class="{'text-red-500': !match.date || !match.time}">
                {{ formatDateTime(match.date, match.time) }}
              </span>
            </div>
          </div>
          <p class="text-xs text-gray-400">|</p>
          <div class=" text-xs flex justify-between gap-1.5 items-center">
            <svg xmlns="http://www.w3.org/2000/svg"  class="h-3 w-3"  viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0-18 0m5 0v.01m4-.01v.01m4-.01v.01"/></svg>            
            <span :class="getStatusClass(match.status)">Status: {{ match.status }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Updated Modal -->
    <div v-if="isTimeModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="relative p-4 w-full max-w-[30rem] max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
          <!-- Modal header -->
          <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              Schedule match
            </h3>
            <button @click="closeTimeModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
              </svg>
              <span class="sr-only">Close modal</span>
            </button>
          </div>
          <!-- Modal body -->
          <div class="p-4 pt-0">
            <label class="text-sm font-medium text-gray-900 mt-5 dark:text-white mb-2 block">
                Pick your date
              </label>
            <div class="mx-auto sm:mx-0 flex justify-center mb-5">
              <input
                type="date"
                v-model="selectedDate"
                :min="getCurrentDate()"
                @change="updateAvailableTimeSlots"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <label class="text-sm font-medium text-gray-900 dark:text-white mb-2 block">
              Pick your time
            </label>
            <ul id="timetable" class="grid w-full grid-cols-4 gap-2 mb-5">
              <li v-for="slot in availableTimeSlots" :key="slot.value">
                <input 
                  type="radio" 
                  :id="slot.value" 
                  :value="slot.value" 
                  v-model="selectedTime" 
                  class="hidden peer" 
                  name="timetable"
                  :disabled="slot.disabled"
                >
                <label :for="slot.value"
                  :class="[
                    'inline-flex items-center justify-center w-full px-2 py-1.5 text-sm font-medium text-center',
                    slot.disabled ? 'cursor-not-allowed bg-gray-100 text-gray-400' : 'hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-800 border rounded-lg cursor-pointer text-gray-500 border-gray-300 dark:border-gray-700 dark:peer-checked:border-blue-500 peer-checked:border-blue-700 dark:hover:border-gray-600 dark:peer-checked:text-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-600 dark:peer-checked:bg-blue-900'
                  ]"
                >
                  {{ slot.label }}
                </label>
              </li>
            </ul>
            <div v-if="formError" class="mb-4 text-red-500 text-sm">{{ formError }}</div>
            <div class="grid grid-cols-2 gap-2">
              <button @click="closeTimeModal" type="button" class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Discard</button>
              <button @click="saveDateTime" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
  
const props = defineProps({
  matches: {
    type: Array,
    required: true
  },
  teams: {
    type: Array,
    required: true
  },
  results: {
    type: Array,
    required: true
  },
  venues: {
    type: Array,
    required: true
  },
  allMatches: {
    type: Array,
    required: true
  }
});
  
const isTimeModalOpen = ref(false);
const selectedMatch = ref(null);
const selectedDate = ref('');
const selectedTime = ref('');
const formError = ref('');
const availableTimeSlots = ref([]);

const form = useForm({
  matchId: '',
  date: '',
  time: '',
});

const timeSlots = [
  { value: '6:00 AM', label: '6:00 AM' },
  { value: '6:30 AM', label: '6:30 AM' },
  { value: '7:00 AM', label: '7:00 AM' },
  { value: '7:30 AM', label: '7:30 AM' },
  { value: '8:00 AM', label: '8:00 AM' },
  { value: '8:30 AM', label: '8:30 AM' },
  { value: '09:00  AM', label: '9:00 AM' },
  { value: '09:30 AM', label: '9:30 AM' },
  { value: '10:00 AM', label: '10:00 AM' },
  { value: '10:30 AM', label: '10:30 AM' },
  { value: '11:00 AM', label: '11:00 AM' },
  { value: '11:30 AM', label: '11:30 AM' },
  { value: '12:00 PM', label: '12:00 PM' },
  { value: '01:00 PM', label: '1:00 PM' },
  { value: '01:30 PM', label: '1:30 PM' },
  { value: '02:00 PM', label: '2:00 PM' },
  { value: '02:30 PM', label: '2:30 PM' },
  { value: '03:00 PM', label: '3:00 PM' },
  { value: '03:30 PM', label: '3:30 PM' },
  { value: '04:00 PM', label: '4:00 PM' },
  { value: '04:30 PM', label: '4:30 PM' },
  { value: '05:00 PM', label: '5:00 PM' },
  { value: '05:30 PM', label: '5:30 PM' },
  { value: '06:00 PM', label: '6:00 PM' }
];

const getVenueName = (venueId) => {
  if (!venueId) return 'No venue assigned';
  const venue = props.venues.find(v => v.id === venueId);
  return venue ? venue.name : 'Unknown venue';
};

const getTeamName = (teamId) => {
  if (!teamId) return 'TBD';
  if (typeof teamId === 'string' && teamId.startsWith('winner:')) {
    const [, round, game] = teamId.split(':');
    return `Winner of R${round} G${game}`;
  }
  const team = props.teams.find(t => t.id === teamId);
  return team ? team.assigned_team_name : 'Unknown';
};

const getScore = (match, team) => {
  const result = props.results.find(r => r.sport_match_id === match.id);
  if (!result) return '-';
  return team === 'teamA' ? result.teamA_score : result.teamB_score;
};

const getScoreClass = (match, team) => {
  const result = props.results.find(r => r.sport_match_id === match.id);
  if (!result) return 'font-medium text-gray-500';
  
  const scoreA = result.teamA_score;
  const scoreB = result.teamB_score;
  
  if (team === 'teamA') {
    return scoreA > scoreB ? 'font-bold text-green-600' : 'font-medium';
  }
  return scoreB > scoreA ? 'font-bold text-green-600' : 'font-medium';
};

const getStatusClass = (status) => {
  const classes = {
    'scheduled': 'text-blue-600',
    'in_progress': 'text-green-600',
    'completed': 'text-gray-600',
    'cancelled': 'text-red-600'
  };
  return `${classes[status?.toLowerCase()] || 'text-gray-600'}`;
};

const getCurrentDate = () => {
  const today = new Date();
  return today.toISOString().split('T')[0];
};

const formatDateTime = (date, time) => {
  if (!date || !time) return 'Not scheduled';
  return `${new Date(date).toLocaleDateString()} ${time}`;
};

const updateAvailableTimeSlots = () => {
  if (!selectedDate.value || !selectedMatch.value) return;

  const usedTimeSlots = props.allMatches
    .filter(match => 
      match.date === selectedDate.value && 
      match.match_venue_id === selectedMatch.value.match_venue_id &&
      match.id !== selectedMatch.value.id
    )
    .map(match => match.time);

  availableTimeSlots.value = timeSlots.map(slot => ({
    ...slot,
    disabled: usedTimeSlots.includes(slot.value)
  }));
};

const openTimeModal = (match) => {
  selectedMatch.value = match;
  selectedDate.value = match.date || '';
  selectedTime.value = match.time || '';
  formError.value = '';
  isTimeModalOpen.value = true;
  updateAvailableTimeSlots();
};

const closeTimeModal = () => {
  isTimeModalOpen.value = false;
  selectedMatch.value = null;
  selectedDate.value = '';
  selectedTime.value = '';
  formError.value = '';
  availableTimeSlots.value = [];
};

const saveDateTime = () => {
  if (!selectedDate.value || !selectedTime.value) {
    formError.value = 'Please select date and time';
    return;
  }

  const isTimeSlotAvailable = availableTimeSlots.value.find(
    slot => slot.value === selectedTime.value && !slot.disabled
  );

  if (!isTimeSlotAvailable) {
    formError.value = 'Selected time slot is not available';
    return;
  }

  form.matchId = selectedMatch.value.id;
  form.date = selectedDate.value;
  form.time = selectedTime.value;

  form.post(route('matches.updateDateTime'), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      selectedMatch.value.date = selectedDate.value;
      selectedMatch.value.time = selectedTime.value;
      closeTimeModal();
    },
    onError: (errors) => {
      formError.value = Object.values(errors)[0];
    }
  });
};

const sortedMatches = computed(() => {
  return [...props.matches].sort((a, b) => {
    const getGameNumber = (game) => {
      const number = parseInt(game.match(/\d+/));
      return isNaN(number) ? 0 : number;
    };
    
    const gameA = getGameNumber(a.game);
    const gameB = getGameNumber(b.game);
    
    if (gameA !== gameB) {return gameA - gameB;
    }
    
    if (a.date && b.date) {
      const dateCompare = new Date(a.date) - new Date(b.date);
      if (dateCompare !== 0) return dateCompare;
    }
    
    return a.round - b.round;
  });
});
</script>