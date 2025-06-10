<script setup lang="ts">
    defineProps<{
        modelValue: boolean;
        value: boolean;
        label: string;
    }>();

    const emit = defineEmits(['update:modelValue']);

    const updateValue = () => {
        emit('update:modelValue', true);
    };
</script>

<template>
    <label class="radio-container">
        <input
            type="checkbox"
            :checked="modelValue === value"
            @change="updateValue"
        />
        <span class="radio-label px-2">{{ label }}</span>
    </label>
</template>
