<template>
    <Head title="College"/>
    <AppLayout>
        <template v-slot:default>
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
            <!-- Progress Bar -->
            <div class="flex mt-2 flex-col">
                <div class="flex items-center mb-2">
                    <h2 class="text-xs text-gray-600">Sport Progress  </h2>
                    <h2 class="text-xs text-gray-600 px-2">:</h2>
                    <span class="text-xs font-medium">{{ progressPercentage.toFixed(0) }}% completed</span>
                </div>
                <div class="w-1/3 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500 ease-in-out" :style="{ width: `${progressPercentage}%` }"></div>
                </div>
            </div>
            <div class="flex gap-2 mt-2">
                <p class="py-1 px-4 rounded-full bg-blue-100 text-blue-700 text-sm"> {{ sport.setup }}</p>
                <p class="py-1 px-4 rounded-full bg-blue-100 text-blue-700 text-sm">{{ sport.categories }}</p>
            </div>

            <!-- Tournament Winner Display -->
            <div v-if="tournamentWinner" class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <h2 class="text-lg font-semibold text-green-800">Game Winner</h2>
                <p class="text-xl font-bold text-green-700 mt-2">🏆 {{ getTeamName(tournamentWinner) }}</p>
            </div>

            <!-- Tabs Navigation -->
            <nav class="flex relative justify-between mt-4  items-center">
                <div class="bg-gray-100 flex gap-2 rounded-lg p-1.5">
                    <div class="bg-gray-100 flex gap-2 rounded-lg">
                        <button 
                            v-for="tab in ['matches', 'standing', 'brackets', 'players']"
                            :key="tab"
                            @click="activeTab = tab"
                            :class="[
                            'px-5 py-1 text-sm',
                            activeTab === tab
                                ? 'text-gray-800 ring-1 ring-gray-300 bg-white rounded-md'
                                : 'text-gray-500 hover:text-gray-700 border-transparent'
                            ]"
                        >
                            {{ tab.charAt(0).toUpperCase() + tab.slice(1) }}
                        </button>
                        </div>
                </div>
                <div class="flex items-center gap-2">


                    <button 
                        @click="openCreateMatchesModal" 
                        type="button" 
                        class=" flex items-center gap-2 text-white bg-blue-700 hover:bg-blue-700/90 text-sm focus:outline-none focus:ring-4 focus:ring-gray-300 rounded-lg px-4 py-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
                        :disabled="hasExistingMatches"
                        >
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M14.384 14.38a23 23 0 0 1 1.056 4.863l.064.644l.126 1.431a10 10 0 0 1-9.15-.98l2.08-2.087l.246-.24c1.793-1.728 3.41-2.875 5.387-3.566zm6.09-.783l.414.003l.981.014a10 10 0 0 1-4.319 6.704l-.054-.605c-.18-2.057-.55-3.958-1.163-5.814c1.044-.182 2.203-.278 3.529-.298zm-7.869-3.181a25 25 0 0 1 1.052 2.098c-2.276.77-4.142 2.053-6.144 3.967l-.355.344l-2.236 2.24a10 10 0 0 1-2.917-6.741L2 12l.004-.25H3.1l.467-.002c3.547-.026 6.356-.367 8.938-1.295zm9.388 1.202l-1.515-.02c-1.86-.003-3.45.124-4.865.402a26 26 0 0 0-1.163-2.38c1.393-.695 2.757-1.597 4.179-2.75l.428-.354l.816-.682a10 10 0 0 1 2.098 5.409zM7.33 3.158L8.596 4.68c1.145 1.398 2.121 2.713 2.949 3.985c-2.26.766-4.739 1.052-7.883 1.081L3.1 9.75h-.844A10 10 0 0 1 7.33 3.157zM17 3.34q.796.46 1.483 1.046l-1.025.857c-1.379 1.128-2.688 1.993-4.034 2.649c-.89-1.398-1.943-2.836-3.182-4.358l-.474-.574l-.485-.584A10 10 0 0 1 17 3.34"/></svg>                        {{ hasExistingMatches ? 'Game Already Started' : 'Start Game' }}
                    </button>
                </div>
            </nav>

            <div class="mt-4">
                <div v-if="activeTab === 'matches'">
                    <GameSchedule :matches="matches" :teams="teams" :results="results" :venues="venues"  :allMatches="allMatches"/>                
                </div>
                <div v-if="activeTab === 'standing'">
                    <div class="w-full">
                        <Standing :teams="teams" :results="results" />
                    </div>
                </div>

                <div v-if="activeTab === 'brackets'">
                    <!-- Display Bracket -->
                    <div class="mt-2">
                        <div class="flex justify-between">
                            <h2 class="text-md font-semibold mb-4">Tournament Bracket</h2>
                        </div>
                        <div v-if="groupedMatches.length > 0" class="flex gap-8">
                            <div 
                                v-for="(round, index) in groupedMatches" 
                                :key="index" 
                                class="space-y-6"
                            >
 
                                <h3 class="text-center text-sm font-bold">
                                    {{ index + 1 === groupedMatches.length ? 'Championship' : `Round ${index + 1}` }}
                                </h3>
                                <div 
                                    v-for="match in round" 
                                    :key="match.id" 
                                    @click="openScoreModal(match)"
                                    class=" w-56 rounded-lg cursor-pointer space-y-2"
                                    :disabled="!canUpdateMatch(match)"
                                >

                                    <div class="flex justify-between items-center">
                                        <p class="font-medium text-xs flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a5 5 0 0 1 7 0a5 5 0 0 0 7 0v9a5 5 0 0 1-7 0a5 5 0 0 0-7 0zm0 16v-7"/></svg>
                                            {{ match.game }}
                                        </p>
                                    </div>
                                    <div class="shadow border border-gray-300 p-2 rounded-lg" :class="{'ring-1 bg-green-50 ring-green-500': isChampionshipMatch(match)}"
                                    >
                                        <span 
                                            class="mb-1 px-2 text-xs rounded-md flex items-center justify-between"
                                            :class="getTeamBackgroundColor(match.id, match.teamA_id)"
                                        >
                                            <p class="p-2">{{ getTeamName(match.teamA_id) }}</p>
                                            <div v-if="getMatchResult(match.id)" class="p-2 text-center">
                                                <p>{{ getMatchResult(match.id).teamA_score }}</p>
                                            </div>
                                        </span>
                                        <span 
                                            class=" px-2 text-xs rounded-md flex items-center justify-between"
                                            :class="getTeamBackgroundColor(match.id, match.teamB_id)"
                                        >
                                            <p class="p-2">{{ getTeamName(match.teamB_id) }}</p>
                                            <div v-if="getMatchResult(match.id)" class=" p-2 text-center">
                                                <p>{{ getMatchResult(match.id).teamB_score }}</p>
                                            </div>
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div v-else>
                            <p>No matches have been created yet. Click 'Start Tournament' to begin.</p>
                        </div>
                    </div>
                </div>
                <div v-if="activeTab === 'players'">
                    <!--to be displayed-->
                </div>
            </div>

            <!-- Score Setting Modal -->
            <div v-if="showScoreModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg relative w-72">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Add Score
                        </h3>
                        <button @click="closeScoreModal"  type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 ">
                        
                        <form @submit.prevent="submitScore">
                            <div class="mb-4">
                                <label class="block mb-2 font-medium text-sm">
                                    {{ getTeamName(selectedMatch.teamA_id) }}
                                    <input 
                                        v-model="scoreFormData.teamA_score"
                                        type="number"
                                        min="0"
                                        required
                                        class="border px-3 py-2 mt-2  rounded-lg border-gray-300  w-full"
                                    />
                                </label>
                            </div>

                            <div class="mb-4">
                                <label class="block mb-3 font-medium text-sm">
                                    {{ getTeamName(selectedMatch.teamB_id) }}
                                    <input 
                                        v-model="scoreFormData.teamB_score"
                                        type="number"
                                        min="0"
                                        required
                                        class="border mt-2 rounded-lg border-gray-300 px-3 py-2 w-full"
                                    />
                                </label>
                            </div>

                            <p v-if="scoreError" class="text-red-600 mb-4">{{ scoreError }}</p>

                            <div class="grid grid-cols-2 items-center gap-2">
                                <button  
                                    @click="closeScoreModal" 
                                    type="button" class="py-2.5 px-5 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                    Cancel
                                </button>

                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    {{ scoreLoading ? 'Saving...' : 'Save Score' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Create Matches Modal -->
            <div v-if="showCreateMatchesModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg relative w-96">
                    <button 
                        @click="closeCreateMatchesModal" 
                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-800"
                    >
                        &times;
                    </button>
                    <h2 class="text-lg font-bold mb-4">Create Initial Matches</h2>

                    <!-- Form Starts Here -->
                    <form @submit.prevent="createInitialMatches">

                        <!-- Venue Selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select Venue
                            </label>
                            <select 
                                v-model="selectedVenue"
                                class="w-full border rounded-md p-2 mb-4 bg-white"
                                required
                            >
                                <option value="" disabled>Choose a venue</option>
                                <option 
                                    v-for="venue in props.venues" 
                                    :key="venue.id" 
                                    :value="venue.id"
                                >
                                    {{ venue.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Shuffle Teams Option -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input 
                                    v-model="shuffleTeams" 
                                    type="checkbox" 
                                    class="form-checkbox h-5 w-5 text-blue-600"
                                >
                                <span class="ml-2 text-gray-700">Shuffle and randomize teams</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            :disabled="createMatchesLoading || !selectedVenue"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ createMatchesLoading ? 'Creating...' : 'Create Matches' }}
                        </button>

                        <p v-if="createMatchesError" class="text-red-600 mt-2">{{ createMatchesError }}</p>
                    </form>
                    <!-- Form Ends Here -->

                </div>
            </div>

        </template>
    </AppLayout>
</template>
 
<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/Layout/DashboardLayout.vue';
import Standing from '@/Components/Standing.vue';
import GameSchedule from '@/Components/GameSchedule.vue';

const props = defineProps({
    sport: [Array, Object],
    tournaments: Array,
    teams: Array,
    matches: Array,
    results: Array,
    venues: Array,
    allMatches: Array,
});

const activeTab = ref('matches');
const selectedVenue = ref('');
const error = ref('');
const showScoreModal = ref(false);
const scoreLoading = ref(false);
const scoreError = ref('');
const selectedMatch = ref(null);
const showCreateMatchesModal = ref(false);
const shuffleTeams = ref(false);
const createMatchesLoading = ref(false);
const createMatchesError = ref('');

const returnToPalakasan = () => {
    router.get(route('palakasan.details', { tab: 'sports' }));
};

const hasExistingMatches = computed(() => {
    return props.matches.some(match => match.assigned_sport_id === props.sport.id);
});

const openCreateMatchesModal = () => {
    if (hasExistingMatches.value) {
        error.value = 'Tournament has already been started';
        return;
    }
    showCreateMatchesModal.value = true;
};

const closeCreateMatchesModal = () => {
    showCreateMatchesModal.value = false;
    shuffleTeams.value = false;
    selectedVenue.value = '';
    createMatchesError.value = '';
};

const shuffleArray = (array) => {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
};

// In the script setup section, modify the generateMatchStructure function:
const generateMatchStructure = (teams) => {
    const venueId = parseInt(selectedVenue.value, 10);
    if (!venueId) {
        throw new Error('Please select a valid venue');
    }

    const numTeams = teams.length;
    let matches = [];

    if (numTeams === 5) {
        matches = [
            { round: 1, game: 'Game 1', teamA_id: teams[0].id, teamB_id: teams[1].id },
            { round: 2, game: 'Game 2', teamA_id: teams[2].id, teamB_id: teams[3].id },
            { round: 2, game: 'Game 3', teamA_id: null, teamB_id: teams[4].id },
            { round: 3, game: 'Game 4', teamA_id: null, teamB_id: null }
        ];
    } else if (numTeams === 6) {
        matches = [
            { round: 1, game: 'Game 1', teamA_id: teams[0].id, teamB_id: teams[1].id },
            { round: 1, game: 'Game 2', teamA_id: teams[2].id, teamB_id: teams[3].id },
            { round: 2, game: 'Game 3', teamA_id: teams[4].id, teamB_id: null },
            { round: 2, game: 'Game 4', teamA_id: teams[5].id, teamB_id: null },
            { round: 3, game: 'Game 5', teamA_id: null, teamB_id: null }
        ];
    } else if (numTeams === 7) {
        matches = [
            { round: 1, game: 'Game 1', teamA_id: teams[0].id, teamB_id: teams[1].id },
            { round: 1, game: 'Game 2', teamA_id: teams[2].id, teamB_id: teams[3].id },
            { round: 1, game: 'Game 3', teamA_id: teams[4].id, teamB_id: teams[5].id },
            { round: 2, game: 'Game 4', teamA_id: teams[6].id, teamB_id: null },
            { round: 2, game: 'Game 5', teamA_id: null, teamB_id: null },
            { round: 3, game: 'Game 6', teamA_id: null, teamB_id: null }
        ];
    } else {
        const numRounds = Math.ceil(Math.log2(numTeams));
        let gameCounter = 1;

        for (let round = 1; round <= numRounds; round++) {
            const matchesInRound = round === 1
                ? Math.floor(numTeams / 2)
                : Math.pow(2, numRounds - round);

            for (let i = 0; i < matchesInRound; i++) {
                const match = {
                    round: round,
                    game: `Game ${gameCounter}`,
                    teamA_id: round === 1 ? teams[i * 2]?.id || null : null,
                    teamB_id: round === 1 ? teams[i * 2 + 1]?.id || null : null
                };

                matches.push(match);
                gameCounter++;
            }
        }
    }

    // Add required fields to each match
    return matches.map(match => ({
        ...match,
        assigned_sport_id: props.sport.id,
        bracket_type: 'winners',
        elimination_type: props.sport.setup,
        status: 'pending',
        match_venue_id: venueId,  // Make sure this is included
        date: null,
        time: null,
    }));
};

const createInitialMatches = async () => {
    createMatchesError.value = '';

    if (!selectedVenue.value) {
        createMatchesError.value = 'Please select a venue';
        return;
    }

    createMatchesLoading.value = true;

    try {
        let teamsToUse = [...props.teams];
        if (shuffleTeams.value) {
            teamsToUse = shuffleArray([...teamsToUse]);
        }

        const matches = generateMatchStructure(teamsToUse);

        if (!matches.length) {
            throw new Error('No matches were generated');
        }
        console.log("Match data being sent:", JSON.stringify(matches, null, 2));


        // Add logging before sending request
        console.log("Match data being sent:", matches);

        const response = await router.post(route('matchesSingle.store'), matches, {
            preserveScroll: true,
            onSuccess: (page) => {
                console.log('Success response:', page);
                createMatchesLoading.value = false;
                closeCreateMatchesModal();
                // Optionally refresh the page or update the matches list
                router.visit(window.location.pathname);
            },
            onError: (errors) => {
            console.error('Error creating matches:', errors);
            createMatchesError.value = errors.message || 'Failed to create matches';
        }
        });
    } catch (err) {
        console.error('Error in createInitialMatches:', err);
        createMatchesLoading.value = false;
        createMatchesError.value = err.message || 'Failed to create matches';
    }
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

const groupedMatches = computed(() => {
    const rounds = {};
    const sportMatches = props.matches.filter(match => match.assigned_sport_id === props.sport.id);
    
    sportMatches.forEach(match => {
        const round = match.round || 1;
        if (!rounds[round]) {
            rounds[round] = [];
        }
        rounds[round].push(match);
    });
    
    Object.values(rounds).forEach(round => {
        round.sort((a, b) => {
            const gameA = parseInt(a.game.replace('Game ', ''));
            const gameB = parseInt(b.game.replace('Game ', ''));
            return gameA - gameB;
        });
    });
    
    return Object.values(rounds);
});

const canUpdateMatch = (match) => {
    return match.teamA_id && match.teamB_id && 
           !match.teamA_id.toString().startsWith('winner:') && 
           !match.teamB_id.toString().startsWith('winner:');
};

const scoreFormData = ref({
    sport_match_id: '',
    teamA_score: '',
    teamB_score: '',
    winning_team_id: '',
    losing_team_id: ''
});

const getMatchResult = (matchId) => {
    return props.results.find(result => result.sport_match_id === matchId);
};

const openScoreModal = (match) => {
    if (!canUpdateMatch(match)) return;
    
    selectedMatch.value = match;
    const existingResult = getMatchResult(match.id);
    
    scoreFormData.value = {
        sport_match_id: match.id,
        teamA_score: existingResult ? existingResult.teamA_score : '',
        teamB_score: existingResult ? existingResult.teamB_score : '',
        winning_team_id: '',
        losing_team_id: ''
    };
    
    showScoreModal.value = true;
};

const closeScoreModal = () => {
    showScoreModal.value = false;
    selectedMatch.value = null;
    scoreFormData.value = {
        sport_match_id: '',
        teamA_score: '',
        teamB_score: '',
        winning_team_id: '',
        losing_team_id: ''
    };
    scoreError.value = '';
};

const submitScore = async () => {
    scoreLoading.value = true;
    scoreError.value = '';

    const { teamA_score, teamB_score } = scoreFormData.value;
    const teamAId = selectedMatch.value.teamA_id;
    const teamBId = selectedMatch.value.teamB_id;

    if (teamA_score === teamB_score) {
        scoreError.value = 'Scores cannot be tied in this elimination bracket.';
        scoreLoading.value = false;
        return;
    }

    scoreFormData.value.winning_team_id = teamA_score > teamB_score ? teamAId : teamBId;
    scoreFormData.value.losing_team_id = teamA_score > teamB_score ? teamBId : teamAId;

    try {
        await router.post(route('results.store'), scoreFormData.value, {
            onSuccess: () => {
                console.log('Score updated successfully');
                closeScoreModal();
                scoreLoading.value = false;
            },
            onError: (errors) => {
                console.error('Error updating score:', errors);
                scoreError.value = 'Failed to update score';
                scoreLoading.value = false;
            }
        });
    } catch (error) {
        scoreError.value = 'Failed to update score';
        console.error('Error updating score:', error);
        scoreLoading.value = false;
    }
};

const getTeamBackgroundColor = (matchId, teamId) => {
    const result = getMatchResult(matchId);
    if (!result || !teamId) return 'bg-gray-200';

    if (result.winning_team_id === teamId) {
        return 'bg-green-200 text-green-600';
    } else if (result.losing_team_id === teamId) {
        return 'bg-red-200 text-red-600';
    }
    return 'bg-gray-200';
};

const tournamentWinner = computed(() => {
    const lastRound = groupedMatches.value[groupedMatches.value.length - 1];
    if (!lastRound || lastRound.length === 0) return null;
    
    const championshipMatch = lastRound[0];
    const result = getMatchResult(championshipMatch.id);
    
    return result ? result.winning_team_id : null;
});

const isChampionshipMatch = (match) => {
    const lastRound = groupedMatches.value[groupedMatches.value.length - 1];
    return lastRound && lastRound[0]?.id === match.id;
};

const totalMatches = computed(() => props.matches.length);

const completedMatches = computed(() => {
    return props.matches.filter(match => match.status === 'completed').length;
});

const progressPercentage = computed(() => {
    return (completedMatches.value / totalMatches.value) * 100;
});
</script>
 
<style scoped>
/* Home page specific styles */
</style>