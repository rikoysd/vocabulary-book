<script setup lang="ts">

const props = withDefaults(defineProps<{
    justify: 'start'|'center'|'space-between'
    align: 'start'|'center'|'end'
    space: string
}>(), {
    justify: 'start',
    align: 'start',
    space: '1rem'
})

</script>

<template>
    <div class="cluster">
        <slot/>
    </div>
</template>

<style>
.cluster {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space, v-bind(space));
    justify-content: v-bind(justify);
    align-items: v-bind(align);
}
</style>