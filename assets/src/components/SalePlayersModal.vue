<template>
  <n-modal v-model:show="visibility">
    <n-card
        style="width: 600px"
        title="Sale Players"
        :bordered="false"
        size="huge"
        role="dialog"
        aria-modal="true"
    >
      <n-select
          v-model:value="selectedPlayers"
          placeholder="Select Your Player"
          :options="players"
          clearable
          filterable
          multiple
      >
      </n-select>
      <n-select
          v-model:value="targetTeam"
          v-if="selectedPlayers"
          placeholder="Select a team to sale player"
          :options="teams"
          style="margin-top: 10px"
          clearable
          filterable
      >
      </n-select>
      <n-input-number
          size="small"
          :min="1"
          v-if="targetTeam"
          style="margin-top: 10px"
          :max="selectedTeamCredit"
          :step="1"
          :precision="0"
          :controls="false"
          v-model:value="selectedPrice"
          placeholder="Enter Price"
      />
      <template #footer>
        <n-button
            size="small"
            type="primary"
            style="margin-left: 10px; float: right"
            @click="salePlayers"
        >
          Sale Players
        </n-button>
      </template>
    </n-card>
  </n-modal>
</template>
<script setup lang="ts">

import {computed, inject, PropType, ref, watch} from "vue";
import {Player} from "@/types/Player";
import {Team} from "@/types/Team";
import {SelectGroupOption, SelectOption, useLoadingBar, useMessage} from "naive-ui";
import {getTeams, pageRef, teamsRef} from "@/api/useTeams";
import {getPlayers, playersRef, sellPlayersToTeam} from "@/api/usePlayers";
import {Ref} from "@vue/reactivity";

const visibility = ref(false)
const selectedPlayers = ref<Array<string | number> | string | number>(null)
const targetTeam = ref<Array<string | number> | string | number>(null)
const selectedTeam = inject('selectedTeam', ref(null)) as Ref<Team | null>;
const selectedPrice = ref<number>(1)
const message = useMessage()
const loadingBar = useLoadingBar()
const props = defineProps({
  players: {
    type: Array as PropType<Player[]> | undefined,
    required: true
  },
  visible: {
    type: Boolean,
    required: true
  }
})

function getComputedNameOfPlayer(player: Player) {
  return computed(() => `${player.name} - ${player.surName}`)
}

const players = computed<Array<SelectOption | SelectGroupOption>>(() => {
  return props.players.map(player => {
        return {
          label: getComputedNameOfPlayer(player).value,
          value: player.id,
        }
      }
  )
});
const getComputedNameOfTeam = (team: Team) => {
  return computed(() => `${team.name} - (Credit: ${team.moneyBalance}) - (Players: ${team.players.length})`);
}
const teams = computed<Array<SelectOption | SelectGroupOption>>(() => {
  return teamsRef.teams.data
      .filter(team => team.id !== selectedTeam.value?.id)
      .map(team => {
            return {
              label: getComputedNameOfTeam(team).value,
              value: team.id,
            }
          }
      )
});
const selectedTeamCredit = computed(() => {
  if (targetTeam.value) {
    const team = teamsRef.teams.data.find(team => team.id === targetTeam.value)
    if (team) {
      return team.moneyBalance
    }
  }
  return 0
})
const salePlayers = async () => {
  if (selectedPlayers.value && targetTeam.value && selectedPrice.value) {
    await sellPlayersToTeam({
      playerIds: selectedPlayers.value as Array<number>,
      targetTeamId: targetTeam.value as number,
      sellingTeam: selectedTeam.value,
      amount: selectedPrice.value
    }).then(async () => {
      const response = playersRef.value?.response;
      if (response?.status !== 200) {
        loadingBar.error()
        message.error(response?.data?.message)
      } else {
        loadingBar.finish()
        message.success(response?.data?.message)
        await getPlayers({})
        await getTeams({page: pageRef.value})
        visibility.value = false
        selectedPlayers.value = null
        targetTeam.value = null
        selectedPrice.value = 1
      }
    })
  }
}

watch(() => props.visible, async (value) => {
  visibility.value = value
  loadingBar.start()
  await getTeams({page: 1})
  loadingBar.finish()
})
</script>
