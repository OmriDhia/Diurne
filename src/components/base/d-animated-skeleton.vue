<template>
    <div v-if="props.loading"
         class="skeleton-card-loader p-2"
         :style="{ marginTop: `${props.marginTop}rem`,width:`${props.width}%`  }"
    >
        <div class="panel br-6 p-2 h100">
            <div class="skeleton-btn"></div>
            <div class="skeleton-title"></div>
            <div class="skeleton-image"></div>
            <div class="skeleton-text skeleton-text-1"></div>
            <div class="skeleton-text skeleton-text-2"></div>
        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        loading: {
            type: Boolean,
            default: false,
        },
        marginTop: {
            type: Number,
            default: 5,
        },
        width: {
            type: Number,
            default: 100,
        }
    });
</script>

<style scoped>
    .skeleton-card-loader {
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 80%;
        gap: 20px;
        margin: auto;
    }

    /* Image placeholder */
    .skeleton-image {
        width: 100%;
        height: 180px;
        background-color: #e0e0e0;
        border-radius: 4px;
        animation: skeleton 1.5s infinite ease-in-out;
        opacity: 0.7;
    }
    /* Title placeholder */
    .skeleton-btn {
        width: 30%;
        height: 40px;
        background-color: #e0e0e0;
        border-radius: 4px;
        animation: skeleton 1.5s infinite ease-in-out;
        margin: 10px 0;
        opacity: 0.7;
    }
    /* Title placeholder */
    .skeleton-title {
        width: 60%;
        height: 25px;
        background-color: #e0e0e0;
        border-radius: 4px;
        animation: skeleton 1.5s infinite ease-in-out;
        margin: 10px 0;
        opacity: 0.7;
    }
    .skeleton-text-1 {
        width: 75%;
    }
    .skeleton-text-2 {
        width: 60%;
    }
    /* Text placeholder */
    .skeleton-text {
        height: 20px;
        background-color: #e0e0e0;
        border-radius: 4px;
        animation: skeleton 1.5s infinite ease-in-out;
        margin: 5px 0;
        opacity: 0.7;
    }

    /* Skeleton Animation */
    @keyframes skeleton {
        0% {
            background-color: #e0e0e0;
        }
        50% {
            background-color: #c9c9c9;
        }
        100% {
            background-color: #e0e0e0;
        }
    }
</style>
