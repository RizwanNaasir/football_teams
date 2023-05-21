<template>
  <h1>{{ msg }}</h1>
</template>

<script setup lang="ts">

import {defineComponent} from "vue";

defineComponent({
  name: 'App',
})
const msg = 'Hello Vue 3 + Vite + Typescript'

</script>