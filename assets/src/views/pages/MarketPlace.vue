<template>
  <n-card style="padding: 4rem" title="Market Place">
    <template #header-extra>
      <n-button
          style="margin: 10px"
          size="small"
          @click="() => $router.push('/')"
          type="primary"
      >
        Go Back
      </n-button>
    </template>
    <label>
      <span>Select A Team To Buy & Sell Players</span>
      <n-select
          v-model:value="selection"
          filterable
          placeholder="Select A Team"
          :options="teams"
          :loading="teamsRef.loading"
          clearable
          remote
          @search="handleSearch"
      />
    </label>
    <n-card style="margin-top: 2rem" v-if="selectedTeam">
      <template #header>
        Buying & Selling As Team: <strong>{{ selectedTeam?.name }}</strong>
      </template>
      <template #header-extra>
        <n-badge style="margin: 10px" :type="selectedTeam?.moneyBalance <= 0 ? 'error' : 'success'">
          <span>Credit: {{ selectedTeam?.moneyBalance }}</span>
        </n-badge>
        <n-button
            size="small"
            type="primary"
            :disabled="selectedTeam?.moneyBalance <= 0"
            @click="() => $router.push('/my-team')"
        >
          Sale Players
        </n-button>
      </template>
      Own Players: {{ selectedTeam?.players?.length }}
    </n-card>
    <n-card style="margin-top: 2rem" v-if="playersRef.data?.length > 0">
      <template #header>
        Players Available For Sale ({{ playersRef.data.length }}) - (Player From Selected Team are not shown here)
      </template>
      <template #header-extra>
        <n-input
            placeholder="Search Players"
            clearable
            size="small"
            style="width: 200px"
            @change="searchPLayers"
        />
      </template>
      <grid-of-players :players="playersRef.data as Player[]"/>
    </n-card>
  </n-card>
</template>
<script setup lang="ts">
import {teamsRef} from "@/api/useTeams";
import {computed, onMounted, provide, ref, watch} from "vue";
import {SelectGroupOption, SelectOption, useLoadingBar} from "naive-ui";
import GridOfPlayers from "@/components/GridOfPlayers.vue";
import {Team} from "@/types/Team";
import {Player} from "@/types/Player";
import {getPlayers, playersRef} from "@/api/usePlayers";

const loadingBar = useLoadingBar()
const selection = ref(0);
const selectedTeam = ref<Team | null>(null);
provide('selectedTeam', selectedTeam)


watch(selection, async (id) => {
  if (id) {
    loadingBar.start()
    await teamsRef.find(id)
        .then(async (res) => {
          selectedTeam.value = res.data as Team;
          loadingBar.finish()
          await getPlayers({team_id: id})
        }).catch(err => {
          console.log(err);
          loadingBar.error()
        })
  } else {
    selectedTeam.value = null;
  }
});

const searchPLayers = async (query: string) => {
  if (selectedTeam.value) {
    selectedTeam.value.players = selectedTeam.value.players.filter(player => {
      return player.name.toLowerCase().includes(query.toLowerCase());
    })
  }
}

const getComputedNameOfTeam = (team: Team)  => {
  return computed(() => `${team.name} - (Credit: ${team.moneyBalance}) - (Players: ${team.players.length})`);
}

const teams = computed<Array<SelectOption | SelectGroupOption>>(() => {
  return teamsRef.teams.data.map(team => {
        return {
          label: getComputedNameOfTeam(team).value,
          value: team.id,
        }
      }
  )
});
const handleSearch = async (query: string) => {
  await teamsRef.search(query);
}

onMounted(async () => {
  await getPlayers({});
})
</script>
