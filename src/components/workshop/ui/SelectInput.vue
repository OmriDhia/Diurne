<script setup lang="ts">
    defineProps<{
        modelValue?: string;
        options?: Array<{ value: string, label: string }>;
        rootClass?: string;
    }>();

    const emit = defineEmits(['update:modelValue']);

    const updateValue = (event: Event) => {
        const target = event.target as HTMLSelectElement;
        emit('update:modelValue', target.value);
    };
</script>

<template>
    <div class="select-wrapper" :class="rootClass">
        <select

            :value="modelValue"
            @change="updateValue"
            class="form-select select-input"
        >
            <option v-if="!options || options.length === 0" value="" disabled selected>Select an option</option>
            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>
    </div>
</template>
