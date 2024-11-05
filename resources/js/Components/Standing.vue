<template>
    <div class="bg-white rounded-lg overflow-hidden">
      <div class="">
        <h2 class="text-md font-medium mt-1 text-gray-800">Team Standings</h2>
      </div>
      <div class="mt-4">
        <table class="w-full">
          <thead>
            <tr class="text-left text-sm font-medium bg-gray-100 text-gray-500 uppercase tracking-wider">
              <th class="p-3">Team</th>
              <th class="p-3 text-center">Wins</th>
              <th class="p-3 text-center">Losses</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="team in teamStandings" :key="team.id" class="">
              <td class="p-3 text-sm font-medium text-gray-900">{{ team.name }}</td>
              <td class="p-3 text-sm text-gray-500 text-center">{{ team.wins }}</td>
              <td class="p-3 text-sm text-gray-500 text-center">{{ team.losses }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  
  const props = defineProps({
    teams: Array,
    results: Array,
  });
  
  const teamStandings = computed(() => {
    const standings = props.teams.map(team => ({
      id: team.id,
      name: team.assigned_team_name,
      wins: 0,
      losses: 0,
    }));
  
    props.results.forEach(result => {
      const winningTeam = standings.find(team => team.id === result.winning_team_id);
      const losingTeam = standings.find(team => team.id === result.losing_team_id);
      
      if (winningTeam) winningTeam.wins++;
      if (losingTeam) losingTeam.losses++;
    });
  
    return standings.sort((a, b) => b.wins - a.wins || a.losses - b.losses);
  });
  </script>