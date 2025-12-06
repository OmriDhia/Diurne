<template>
    <div class="d-flex d-menu-item text-uppercase align-items-center gap-2">
        <span class="menu-img" v-if="props.icon"><img :src="`/assets/images/menu/${props.icon}.png`"></span>
        <span>{{ $t(props.name)}}</span>
    </div>
</template>

<script setup>
    const props = defineProps({
        icon: {
            type: String,
            default: null
        },
        name: {
            type: String,
            default: ""
        },
    })
</script>
