<script setup lang="ts">
import Stack from '../layout/Stack.vue'

</script>

<template>
    <Stack space="3rem">
        <div>
            <slot name="header"/>
        </div>
        <div>
            <slot name="content"/>
        </div>
        <div>
            <slot name="footer"/>
        </div>
    </Stack>
</template>