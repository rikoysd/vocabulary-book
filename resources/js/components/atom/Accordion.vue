<script setup lang="ts">
import { computed, reactive } from 'vue';
import Icon from '@/components/atom/Icon.vue';
import { ArrowDown, ArrowUp } from '@element-plus/icons-vue'

withDefaults(defineProps<{
    noShow: boolean
}>(), {
    noShow: false
})

const data: {
    isOpen: boolean
} = reactive({
    isOpen: false
})

const maxHeight = computed(() => {
    if(data.isOpen) return '24rem'
    else return '0px'
})

const toggleAccordion = () => {
    data.isOpen = !data.isOpen
}
</script>

<template>
    <el-card shadow="always" class="content__card">
        <div class="content__card--inside">
            <slot name="title" />
            <div>
                <slot name="menu" />
                <span v-if="!noShow">
                    <Icon v-if="data.isOpen" @click="toggleAccordion"><ArrowUp /></Icon>
                    <Icon v-else @click="toggleAccordion"><ArrowDown /></Icon>
                </span>
            </div>
        </div>
        <div v-if="data.isOpen">
            <slot name="content" />
        </div>
    </el-card>
</template>

<style>
.content__card {
    margin: 1rem 0;
}

.content__card--inside {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.content__card--iconUpsideDown {
    transform: rotate(180deg);
}
</style>
