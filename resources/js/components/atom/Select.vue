<script setup lang="ts">
import { computed } from 'vue'

interface option型 {
    value: string
    label: string
}

const props = defineProps<{
    modelValue: string
    option: option型[]
}>()

const emits = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()

const computedValue = computed({
    get: () => props.modelValue,
    set: (value) => {
        emits('update:modelValue', value)
    }
})

</script>

<template>
    <el-select v-model="computedValue" class="m-2" placeholder="選択してください">
        <el-option
        v-for="item in option"
        :key="item.value"
        :label="item.label"
        :value="item.value"
        />
    </el-select>
</template>