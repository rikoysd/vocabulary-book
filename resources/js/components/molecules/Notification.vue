<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(defineProps<{
    padding: 'small'|'basic'|'large'
}>(), {
    padding: 'basic'
})

const computedPadding = computed(() => {
    if(props.padding === 'small') return '1rem'
    if(props.padding === 'basic') return '1.5rem'
    if(props.padding === 'large') return '2rem'
})

</script>

<template>
    <div class="action__list--alert" >
        <slot/>
    </div>
</template>

<style>
.action__list--alert {
    background-color: #FEEFF0;
    padding: v-bind(computedPadding);
    border-radius: 0.5rem;
    color: #F56C6B;
    font-size: small;
}
</style>