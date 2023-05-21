<template>
  <n-data-table
      :columns="columns()"
      :data="data"
      :pagination="pagination"
  />
</template>
<script setup lang="ts">
import {ref} from 'vue'
import type {DataTableColumns} from 'naive-ui'

type RowData = {
  key: number
  name: string
  age: number
  address: string
}

const data = Array.from({length: 46}).map((_, index) => ({
  name: `Edward King ${index}`,
  age: 32,
  address: `London, Park Lane no. ${index}`,
  key: index
}))

const columns = (): DataTableColumns<RowData> => {
  return [
    {
      title: 'Name',
      key: 'name'
    },
    {
      title: 'Age',
      key: 'age'
    },
    {
      title: 'Address',
      key: 'address'
    }
  ]
}
const pagination = ref({
  pageSize: 6
})
</script>