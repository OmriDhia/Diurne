<template>
    <div>
        <label v-for="(option, index) in options" :key="index" class="me-3">
            <input
                type="radio"
                :value="index === 0"
                :checked="modelValue === (index === 0)"
                @change="$emit('update:modelValue', index === 0)"
            />
            {{ option.label }}
        </label>
    </div>
</template>

<script setup>
    defineProps({
        modelValue: Boolean,
        options: { type: Array, required: true }
    });
</script>
