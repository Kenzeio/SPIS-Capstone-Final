<template>
    <Head :title="assignedSport.sport.name"/>
    <AppLayout>
        <div class="min-h-screen p-6">
            <h1>{{ assignedSport.sport.name }}</h1>
            <h1>{{ assignedSport.categories }}</h1>

            <!-- Tournament Bracket -->
            <div v-if="sportMatches.length" class="mt-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Tournament Bracket</h2>
                
                <div class="flex overflow-x-auto pb-8">
                    <!-- Bracket Rounds -->
                    <div v-for="round in groupedMatches" 
                         :key="round.roundNumber" 
                         class="flex-none first:ml-4 px-4 min-w-[320px]">
                        <div class="text-sm font-semibold text-gray-600 mb-4">
                            Round {{ round.roundNumber }}
                        </div>
                        
                        <div class="space-y-8">
                            <!-- Matches in Round -->
                            <div v-for="match in round.matches" 
                                 :key="match.id" 
                                 class="relative">
                                <!-- Match Card -->
                                <div class="bg-white rounded-lg shadow-md hover:shadow-lg ring-1 ring-gray-300 p-4 transition-shadow duration-300">
                                    <!-- Team A -->
                                    <div :class="[
                                        'p-3 rounded-lg transition-colors',
                                        match.match_result && match.match_result.teamA_score > match.match_result.teamB_score 
                                            ? 'bg-green-50 ring-1 ring-green-200' 
                                            : 'bg-gray-50'
                                    ]">
                                        <div v-if="match.team_a" class="space-y-1">
                                            <div class="font-semibold text-gray-900">
                                                {{ match.team_a.assigned_team_name }}
                                            </div>
                                            <div class="text-xs text-gray-600">
                                                {{ match.team_a.college?.shortName || 'No College' }}
                                            </div>
                                            <div v-if="match.match_result" class="text-sm font-bold text-gray-900">
                                                {{ match.match_result.teamA_score }}
                                            </div>
                                        </div>
                                        <div v-else class="text-gray-500 text-center py-2">
                                            Waiting for Winner
                                        </div>
                                    </div>

                                    <!-- VS Divider -->
                                    <div class="py-2 text-center">
                                        <span class="px-3 py-1 text-sm font-bold text-gray-500">VS</span>
                                    </div>

                                    <!-- Team B -->
                                    <div :class="[
                                        'p-3 rounded-lg transition-colors',
                                        match.match_result && match.match_result.teamB_score > match.match_result.teamA_score 
                                            ? 'bg-green-50 ring-1 ring-green-200' 
                                            : 'bg-gray-50'
                                    ]">
                                        <div v-if="match.team_b" class="space-y-1">
                                            <div class="font-semibold text-gray-900">
                                                {{ match.team_b.assigned_team_name }}
                                            </div>
                                            <div class="text-xs text-gray-600">
                                                {{ match.team_b.college?.shortName || 'No College' }}
                                            </div>
                                            <div v-if="match.match_result" class="text-sm font-bold text-gray-900">
                                                {{ match.match_result.teamB_score }}
                                            </div>
                                        </div>
                                        <div v-else class="text-gray-500 text-center py-2">
                                            BYE
                                        </div>
                                    </div>

                                    <!-- Score Button -->
                                    <div class="mt-3 text-center">
                                        <button @click="openScoreModal(match)" 
                                                class="text-sm px-3 py-1.5 bg-blue-700 text-white rounded-md hover:bg-blue-800 transition-colors">
                                            {{ match.match_result ? 'Update Score' : 'Add Score' }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Connector Lines (if not last round) -->
                                <div v-if="round.roundNumber < maxRound" class="absolute top-1/2 -right-4 w-4 h-px bg-gray-300"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="text-center py-12 bg-white rounded-lg shadow">
                <p class="text-gray-500 text-lg">No matches have been created for this sport yet.</p>
            </div>

            <!-- Score Modal (unchanged) -->
            <div v-if="showScoreModal" 
                 class="fixed inset-0 flex items-center justify-center z-50"
                 @click.self="closeScoreModal">
                <div class="fixed inset-0 bg-black opacity-50"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">
                            Add Match Score
                        </h2>

                        <form @submit.prevent="submitScore" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ selectedMatch?.team_a?.assigned_team_name || 'Team A' }} Score
                                    </label>
                                    <input type="number" 
                                           v-model="scoreForm.teamA_score"
                                           min="0"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                           required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ selectedMatch?.team_b?.assigned_team_name || 'Team B' }} Score
                                    </label>
                                    <input type="number" 
                                           v-model="scoreForm.teamB_score"
                                           min="0"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                           required>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button"
                                        @click="closeScoreModal"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800">
                                    Save Score
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { defineProps, ref, computed } from 'vue';
import AppLayout from '@/Layout/DashboardLayout.vue';

const props = defineProps({
    assignedSport: {
        type: Object,
        required: true
    },
    sportMatches: {
        type: Array,
        required: true
    }
});

// Group matches by round
const groupedMatches = computed(() => {
    const rounds = props.sportMatches.reduce((acc, match) => {
        const roundNumber = match.round_number || 1;
        if (!acc[roundNumber]) {
            acc[roundNumber] = {
                roundNumber,
                matches: []
            };
        }
        acc[roundNumber].matches.push(match);
        return acc;
    }, {});
    
    return Object.values(rounds).sort((a, b) => a.roundNumber - b.roundNumber);
});

// Get maximum round number
const maxRound = computed(() => {
    return Math.max(...props.sportMatches.map(m => m.round_number || 1));
});

const showScoreModal = ref(false);
const selectedMatch = ref(null);
const scoreForm = useForm({
    sport_match_id: null,
    teamA_score: '',
    teamB_score: ''
});

const openScoreModal = (match) => {
    selectedMatch.value = match;
    scoreForm.sport_match_id = match.id;
    if (match.match_result) {
        scoreForm.teamA_score = match.match_result.teamA_score;
        scoreForm.teamB_score = match.match_result.teamB_score;
    } else {
        scoreForm.teamA_score = '';
        scoreForm.teamB_score = '';
    }
    showScoreModal.value = true;
};

const closeScoreModal = () => {
    showScoreModal.value = false;
    selectedMatch.value = null;
    scoreForm.reset();
};

const submitScore = () => {
    scoreForm.post('/match-result', {
        onSuccess: () => {
            closeScoreModal();
        },
    });
};
</script>