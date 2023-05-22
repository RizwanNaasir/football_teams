<template>
  <n-message-provider>
    <teams-table/>
  </n-message-provider>
</template>

<script setup lang="ts">
import {defineComponent} from "vue";
import TeamsTable from "@/components/TeamsTable.vue";

defineComponent({
  name: 'App',
})
</script>