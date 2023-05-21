<template>
  <n-card style="padding: 4rem" title="Football Teams">
    <template #header-extra>
      <n-button
          style="margin-top: 10px"
          size="small"
      >
        Add Team and Players
      </n-button>
    </template>
    <n-data-table
        :columns="columns()"
        :data="teamsRef.teams.data"
        :pagination="pagination"
        :loading="teamsRef.loading"
    />
    <player-table-modal :showModal="showModal" :players="playersRef"/>
  </n-card>
</template>
<script setup lang="ts">
import {h, onMounted, reactive, ref} from 'vue'
import type {DataTableColumns} from 'naive-ui'
import {NButton, NDataTable} from "naive-ui";
import {getTeams, teamsRef} from "@/api/useTeams";
import {Team} from "@/components/types/Team";
import PlayerTableModal from "@/components/PlayerTableModal.vue";
import {Player} from "@/components/types/Player";
import {PaginationProps} from "naive-ui/es/pagination/src/Pagination";

const showModal = ref(false);
const playersRef = ref<Player[]>([]);
const page = ref(1);
const pagination: PaginationProps = reactive({
  page: page.value,
  itemCount: 0,
  onChange: async (page: number) => {
    await getTeams({page: page})
  },
});
onMounted(async () => {
  await getTeams({page: page.value})
      .finally(() => {
        pagination.itemCount = teamsRef.teams.pagination.total;
      }
  );
});

function viewPlayers(players: Player[]) {
  showModal.value = !showModal.value;
  playersRef.value = players;
}

const columns = (): DataTableColumns<Team> => {
  return [
    {
      title: 'Name',
      key: 'name',
      width: 200
    },
    {
      title: 'Country',
      key: 'country',
      width: 200
    },
    {
      title: 'Money Balance',
      key: 'moneyBalance',
      width: 200
    },
    {
      'title': 'Actions',
      'key': 'actions',
      render: function (row) {
        return h(
            NButton,
            {
              strong: true,
              tertiary: true,
              size: 'small',
              onClick: () => viewPlayers(row.players)
            },
            {default: () => `View Players (${row.players.length})`}
        )
      }
    }
  ]
}
</script>