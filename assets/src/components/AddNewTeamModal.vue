<template>
  <n-modal v-model:show="modal">
    <n-card
        style="width: 600px"
        title="Add New Team"
        :bordered="false"
        size="huge"
        role="dialog"
        aria-modal="true"
    >
      <n-form
          ref="formRef"
          inline
          :label-width="80"
          :model="formValue"
          :rules="rules"
          :size="size"
      >
        <n-space vertical size="large">
          <n-layout>
            <n-layout-header>Team</n-layout-header>
            <n-layout-content content-style="padding: 24px;">
              <n-form-item label="Name" path="team.name">
                <n-input v-model:value="formValue.team.name" placeholder="Input Name"/>
              </n-form-item>
              <n-form-item label="Country" path="team.country">
                <n-input v-model:value="formValue.team.country" placeholder="Input Country"/>
              </n-form-item>
              <n-form-item label="Credit" path="team.moneyBalance">
                <n-input-number v-model:value="formValue.team.moneyBalance" placeholder="Input Credit"/>
              </n-form-item>
            </n-layout-content>
          </n-layout>
          <n-layout>
            <n-layout-header>
              Players
            </n-layout-header>
            <n-layout-content>
              <n-form-item
                  v-for="(item, index) in formValue.team.players"
                  :key="index"
                  :label="`Player (${item.name +' '+ item.surname} )`"
                  :path="`players[${index}].hobby`"
                  :rule="playerRule"
              >
                <n-input v-model:value="item.name" clearable/>
                <n-input v-model:value="item.surname" clearable/>
                <n-button style="margin-left: 12px" @click="removeItem(index)">
                  Remove
                </n-button>
              </n-form-item>

              <n-form-item>
                <n-space>
                  <n-button attr-type="button" @click="addItem">
                    New Player
                  </n-button>
                  <n-button @click="handleValidateClick" type="primary">
                    Submit
                  </n-button>
                </n-space>
              </n-form-item>
            </n-layout-content>
          </n-layout>
        </n-space>
      </n-form>
    </n-card>
  </n-modal>
</template>
<script setup lang="ts">
import {ref, watch} from "vue";
import {FormInst, useMessage} from "naive-ui";
import {Team} from "@/types/Team";
import {addNewTeam} from "@/api/useTeams";

const props = defineProps({
  visible: Boolean,
})
const modal = ref(false);
const formRef = ref<FormInst | null>(null)
const message = useMessage();
const size = ref<'small' | 'medium' | 'large'>('medium')
const initializeForm = {
  team: {
    name: '',
    country: '',
    moneyBalance: 0,
    players: [
      {
        name: '',
        surname: ''
      }
    ]
  }
};
const formValue = ref<{ team: Team }>(initializeForm)
const rules = {
  team: {
    name: [
      {required: true, message: 'Please input name', trigger: 'blur'},
    ],
    country: [
      {required: true, message: 'Please input country', trigger: 'blur'},
    ],
    moneyBalance: [
      {required: true, message: 'Please input credit', trigger: 'blur', type: 'number'},
    ]
  }
}
const playerRule = {
  player: {
    name: [
      {required: true, message: 'Please input name', trigger: 'blur'},
    ],
    surname: [
      {required: true, message: 'Please input surname', trigger: 'blur'},
    ]
  }
}
const removeItem = (index: number) => {
  formValue.value.team.players.splice(index, 1)
}
const addItem = () => {
  formValue.value.team.players.push({
    name: '',
    surname: ''
  })
}

const handleValidateClick = (e: MouseEvent) => {
  e.preventDefault()
  formRef.value?.validate(async (errors) => {
    if (!errors) {
      await addNewTeam(formValue.value.team)
          .then((res) => {
            message.success(res.message as string);
            modal.value = false;
            formValue.value = initializeForm;
          });
    } else {
      errors.forEach((error) => {
        return error.forEach((error) => {
          return message.error(error.message as string);
        })
      })
    }
  })
}

watch(() => props.visible, (newVal) => {
  modal.value = newVal;
});
</script>
