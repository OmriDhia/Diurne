<template>
    <div class="">
        <label v-for="option in options" :key="option.value" class="me-3">
            <input
                class=""
                type="radio"
                :value="option.value"
                :checked="modelValue === option.value"
                @change="$emit('update:modelValue', option.value)"
            />
            {{ option.label }}
        </label>
    </div>
</template>

<script setup>
    defineProps({
        modelValue: [String, Number],
        options: Array
    });
</script>
