<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    modelValue: any
    placeholder?: string
    label: string
}>()

const emits = defineEmits<{
    (e: 'update:modelValue', value: any): void
}>()

const computedData = computed({
    get: () => props.modelValue,
    set: (value) => {
        emits('update:modelValue', value)
    }
})

</script>

<template>
    <div>
        <div class="label">{{ label }}</div>
        <el-input
            v-model="computedData"
            :rows="2"
            type="textarea"
            :placeholder="placeholder"
        />
    </div>
</template>

<style>
.label {
    font-size: small;
    padding-left: 0.5rem;
    padding-bottom: 0.3rem;
    color: gray;
}
</style>