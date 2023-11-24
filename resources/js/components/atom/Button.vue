<script setup lang="ts">

const props = withDefaults(defineProps<{
    label: string
    color: 'primary'|'success'|'info'|'warning'|'danger'|'default'
    plain?: boolean
    disabled?: boolean
}>(), {
    color: 'default'
})

</script>

<template>
    <el-button
        :type="color === 'default' ? null : color"
        :plain="plain"
        :disabled="disabled"
    >{{ label }}</el-button>
</template>