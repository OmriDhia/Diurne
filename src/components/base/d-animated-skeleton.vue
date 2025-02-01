<template>
    <div v-if="props.loading"
         class="skeleton-card-loader"
         :style="{ marginTop: `${props.marginTop}rem`,width:`${props.width}%`  }"
    >
        <div class="skeleton-title"></div>
        <div class="skeleton-image"></div>
        <div class="skeleton-text"></div>
        <div class="skeleton-text"></div>
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
            default: 80,
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
    .skeleton-title {
        width: 60%;
        height: 25px;
        background-color: #e0e0e0;
        border-radius: 4px;
        animation: skeleton 1.5s infinite ease-in-out;
        margin: 10px 0;
        opacity: 0.7;
    }

    /* Text placeholder */
    .skeleton-text {
        width: 100%;
        height: 15px;
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
