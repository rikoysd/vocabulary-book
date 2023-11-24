<script setup lang="ts">
import { computed } from 'vue';


const props = withDefaults(defineProps<{
    modelValue: any
    placeholder?: string
    label?: string
    type: 'string' | 'password'
}>(), {
    type: 'string'
})

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
            :type="type"
            :placeholder="placeholder"
            :show-password="type === 'password'"
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