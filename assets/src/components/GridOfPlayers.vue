<template>
  <n-grid responsive="self" :cols="4" :x-gap="12" y-gap="12">
    <n-gi
        v-for="player in playersC"
        :key="player.id"
    >
      <n-loading-bar-provider>
        <n-card>
          <template #header>
            {{ player.name }}
          </template>
          <p>Team: {{ player.team?.name }}</p>
          <template #action>
            <n-grid :cols="2">
              <n-gi>
                <n-button
                    size="small"
                    type="primary"
                    :disabled="!selectedTeam || selectedTeam?.moneyBalance <= 0"
                    @click="buyPlayer(player as Player)"
                >
                  Buy
                </n-button>
              </n-gi>
              <n-gi>
                <label>
                  <span>Price</span>
                  <n-input-number
                      size="small"
                      :min="1"
                      :max="selectedTeam?.moneyBalance"
                      :step="1"
                      :precision="0"
                      :controls="false"
                      v-model:value="player.price.value"
                  />
                </label>
              </n-gi>
            </n-grid>
          </template>
        </n-card>
      </n-loading-bar-provider>
    </n-gi>
  </n-grid>
</template>
<script setup lang="ts">
import {computed, inject, PropType, ref} from "vue";
import {Player} from "@/types/Player";
import {Team} from "@/types/Team";
import {Ref} from "@vue/reactivity";
import {buyPlayerFromTeam, getPlayers, playersRef} from "@/api/usePlayers";
import {useLoadingBar, useMessage} from 'naive-ui'

const message = useMessage()
const loadingBar = useLoadingBar()
const selectedTeam = inject('selectedTeam', ref(null)) as Ref<Team | null>;
const props = defineProps({
  players: {
    type: Array as PropType<Array<Player>>,
    required: true
  }
})
const playersC = computed(() => {
  return props.players.map(player => {
    return {
      ...player,
      price: ref(1)
    }
  })
})

const playersListRef = ref<Player[]>(playersC.value)

const buyPlayer = async (player: Player) => {
  loadingBar.start()
  await buyPlayerFromTeam({
    playerId: player.id,
    teamId: selectedTeam.value?.id,
    amount: player.price.value
  }).then(async () => {
    const response = playersRef.value?.response;
    if (response?.status !== 200) {
      loadingBar.error()
      message.error(response?.data?.message)
    } else {
      loadingBar.finish()
      message.success(response?.data?.message)
      selectedTeam.value?.players.push(player)
      selectedTeam.value.moneyBalance -= player.price.value
      playersListRef.value = playersListRef.value.filter(p => p.id !== player.id)
      await getPlayers({team_id: selectedTeam.value?.id})
    }
  })
}
</script>
