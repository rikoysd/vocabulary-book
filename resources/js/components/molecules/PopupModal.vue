<script setup lang="ts">
import Button from '../atom/Button.vue';

withDefaults(defineProps<{
    isActive: boolean
    leftColor: 'primary'|'success'|'info'|'warning'|'danger'|'default'
    rightColor: 'primary'|'success'|'info'|'warning'|'danger'|'default'
    leftLabel: string
    rightLabel: string
}>(), {
    leftColor: 'default',
    rightColor: 'success',
    leftLabel: 'キャンセル',
    rightLabel: 'OK'
})

const emits = defineEmits<{
    (e: 'close'): void
    (e: 'success'): void
}>()

const close = () => {
    emits('close')
}

const success = () => {
    emits('success')
}

</script>

<template>
    <div
        v-if="isActive"
        id="overlay"
        @click="close"
    >
        <div id="content">
            <div class="content__position">
                <slot/>
            </div>
            <div class="content__position">
                <Button
                    :label="leftLabel"
                    :color="leftColor"
                    @click="close"
                />
                <Button
                    :label="rightLabel"
                    :color="rightColor"
                    @click="success"
                />
            </div>
        </div>
    </div>
</template>

<style>
#overlay {
    z-index:1;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background-color:rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

#content {
    z-index: 2;
    width: 18rem;
    padding: 2em;
    background: #fff;
    border-radius: 0.3rem;
    row-gap: 2rem;
    display: flex;
    flex-direction: column;
}

.content__position {
    display: flex;
    justify-content: center;
}
</style>