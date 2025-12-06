<template>
    <d-animated-skeleton :loading="loading"></d-animated-skeleton>
</template>

<script setup>
    import { ref, watch, defineEmits, onMounted, onUnmounted } from 'vue';
    import dAnimatedSkeleton from './d-animated-skeleton.vue'
    import { useRouter } from 'vue-router';

    const router = useRouter();
    const loading = ref(false);
    const emit = defineEmits(['loading']);
    let timeout = null;

    watch(
        () => router.currentRoute.value,
        () => {
            startLoading();
        },
        { deep: true }
    );

    function startLoading() {
        loading.value = true;
        emit('loading', true);
    }

    onMounted(() => {
        router.afterEach(() => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                loading.value = false;
                emit('loading', false);
            }, 1000);
        });
    });

    onUnmounted(() => {
        clearTimeout(timeout);
    });
</script>
