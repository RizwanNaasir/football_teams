<template>
  <n-message-provider placement="top-right">
    <n-loading-bar-provider>
      <router-view></router-view>
    </n-loading-bar-provider>
  </n-message-provider>
</template>

<script setup lang="ts">
import {defineComponent} from "vue";

defineComponent({
  name: 'App',
})
</script>