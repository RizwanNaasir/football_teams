<template>
  <n-card style="padding: 4rem" :title="`Football Teams (${teamsRef.teams.pagination.total})`">
    <template #header-extra>
      <n-button
          style="margin-top: 10px"
          size="small"
          @click="() => toggleAddNewModal = !toggleAddNewModal"
      >
        Add Team and Players
      </n-button>
    </template>
    <n-data-table
        :columns="columns"
        :data="teamsRef.teams.data"
        :pagination="pagination"
        :loading="teamsRef.loading"
        pagination-behavior-on-filter="first"
        :remote="true"
    />
    <player-table-modal :showModal="showModal" :players="playersRef"/>
    <add-new-team-modal :visible="toggleAddNewModal" :players="playersRef"/>
  </n-card>
</template>
<script setup lang="ts">
import {h, onMounted, reactive, ref} from 'vue'
import type {DataTableColumns} from 'naive-ui'
import {NButton, NDataTable, useMessage} from "naive-ui";
import {getTeams, pageRef, teamsRef} from "@/api/useTeams";
import {Team} from "@/types/Team";
import PlayerTableModal from "@/components/PlayerTableModal.vue";
import {Player} from "@/types/Player";
import {PaginationProps} from "naive-ui/es/pagination/src/Pagination";
import AddNewTeamModal from "@/components/AddNewTeamModal.vue";

const showModal = ref(false);
const toggleAddNewModal = ref(false);
const playersRef = ref<Player[]>([]);
const message = useMessage();

async function updateTable(page: number) {
  await getTeams({page: page});
  pagination.page = page;
  pagination.itemCount = teamsRef.teams.pagination.total;
}

const pagination: PaginationProps = reactive({
  page: pageRef.value,
  itemCount: 0,
  onChange: async (page: number) => {
    await updateTable(page);
  },
});
onMounted(async () => {
  await getTeams({page: pageRef.value})
      .finally(() => {
        pagination.itemCount = teamsRef.teams.pagination.total;
      }).catch((err) => {
        message.error(err.message)
      });
});

function viewPlayers(players: Player[]) {
  showModal.value = !showModal.value;
  playersRef.value = players;
}

const columns: DataTableColumns<Team> =
    [
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
    ];

</script>