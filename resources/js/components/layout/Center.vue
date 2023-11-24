<script setup lang="ts">

const props = withDefaults(defineProps<{
    max: string
}>(), {
    max: '60ch'
})


</script>

<template>
    <div class="center">
        <slot />
    </div>
</template>

<style>
.center {
    box-sizing: content-box;
    max-width: v-bind(max);
    margin-left: auto;
    margin-right: auto;
    padding-left: var(--s1);
    padding-right: var(--s1);
}
</style>