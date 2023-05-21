<template>
  <n-modal v-model:show="modal">
    <n-card
        style="width: 600px"
        title="Players"
        :bordered="false"
        size="huge"
        role="dialog"
        aria-modal="true"
    >
      <n-data-table
          :columns="columns"
          :data="players"
      />
    </n-card>
  </n-modal>
</template>
<script setup lang="ts">
import {Player} from "@/components/types/Player";
import {watch, ref} from "vue";

const modal = ref(false);
const props = defineProps({
  showModal: {
    type: Boolean,
    required: true
  },
  players: {
    type: Array as () => Player[],
    required: true
  },
});

watch(() => props.showModal, (newVal) => {
    modal.value = newVal;
})
const columns = [
  {
    title: 'Name',
    key: 'name',
    width: 200
  },
  {
    title: 'Surname',
    key: 'surname',
    width: 200
  }
]
</script>
