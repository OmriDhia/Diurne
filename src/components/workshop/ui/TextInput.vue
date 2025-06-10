<template>
    <d-input
        type="text"
        :value="modelValue"
        @input="updateValue"
        :disabled="disabled"
        :class="['text-input', rootClass]"
        model-value="" />
</template>
<script setup lang="ts">
    import dInput from '../../../components/base/d-input.vue';

    defineProps<{
        modelValue: string;
        disabled?: boolean;
        rootClass?: string;
    }>();

    const emit = defineEmits(['update:modelValue']);

    const updateValue = (event: Event) => {
        const target = event.target as HTMLInputElement;
        emit('update:modelValue', target.value);
    };
</script>
