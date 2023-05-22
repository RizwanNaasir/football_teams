<template>
  <n-message-provider>
    <router-view></router-view>
  </n-message-provider>
</template>

<script setup lang="ts">
import {defineComponent} from "vue";

defineComponent({
  name: 'App',
})
</script>