<script setup lang="ts">
import {Team} from "@/types/Team";
import {PropType} from "vue";

const props = defineProps({
  teams: {
    type: Array as PropType<Team[]>,
    required: true,
  },
})
</script>

<template>
  <n-list hoverable clickable>
    <n-list-item v-for="(team, key) in props.teams" :key="key">
      <n-thing :title="team.name" content-style="margin-top: 10px;">
        <template #description>
          <n-space size="small" style="margin-top: 4px">
            <n-tag :bordered="false" type="info" size="small">
              Credit: {{ team.moneyBalance }}
            </n-tag>
          </n-space>
        </template>
      </n-thing>
    </n-list-item>
  </n-list>
</template>

<style scoped>

</style>