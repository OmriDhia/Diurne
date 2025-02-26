<template>
    <div class="col-auto">
        <button :disabled="disabled" :class="['btn', 'ms-0', 'btn-outline-custom','d-flex', buttonClass]" v-on:click="$emit('click', $event)">
            <span style="text-align: center;">{{ label }}</span>
            <vue-feather :type="icon" size="14"  class="d-flex align-items-center"></vue-feather>
        </button>
    </div>
</template>

<script setup>
    import VueFeather from 'vue-feather';

    defineProps({
        label: {
            type: [String, null],
            required: true
        },
        buttonClass: {
            type: [String, null],
            default: ''
        },
        icon: {
            type: String,
            required: true
        },
        disabled: {
            type: Boolean,
            default: false
        }
    });
</script>
