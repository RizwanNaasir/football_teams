<template>
  <n-card style="padding: 4rem" title="Football Teams">
    <TeamsTable/>
  </n-card>
</template>

<script setup lang="ts">
import {defineComponent} from "vue";
import TeamsTable from "@/components/TeamsTable.vue";

defineComponent({
  name: 'App',
})
</script>