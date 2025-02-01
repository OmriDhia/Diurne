<template>
    <div class="layout-px-spacing mt-4">
        <slot name="title"></slot>

        <div class="row layout-top-spacing mt-3">
            <slot name="header"></slot>
        </div>

        <div class="row layout-top-spacing mt-3">
            <d-animated-skeleton :loading="loading" :marginTop="1" :width="95"/>
            <slot name="body" v-if="!loading"></slot>
        </div>

        <div class="row mt-3">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script setup>
    import dAnimatedSkeleton from './d-animated-skeleton.vue';

    const props = defineProps({
        loading: {
            type: Boolean,
            default: false
        }
    });
</script>
