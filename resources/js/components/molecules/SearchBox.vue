<script setup lang="ts">
import { Search } from '@element-plus/icons-vue'
import { reactive } from 'vue'

defineProps<{
    placeholder?: string
}>()

const emits = defineEmits<{
    (e: 'click', value: string): void
}>()

const data: {
    検索文字列: string
} = reactive({
    検索文字列: ''
})

const onClick = () => {
    emits('click', data.検索文字列)
}

</script>

<template>
    <el-input
        v-model="data.検索文字列"
        :placeholder="placeholder"
        class="input-with-select"
    >
        <template #append>
            <el-button :icon="Search" @click="onClick" />
        </template>
    </el-input>
</template>