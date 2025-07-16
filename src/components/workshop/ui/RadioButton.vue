<script setup lang="ts">
import {ref, onMounted, watch} from 'vue';
    const props = defineProps<{
        modelValue: boolean;
        value: boolean;
        label: string;
    }>();

    const emit = defineEmits(['update:modelValue']);
    const value = ref(false);
    const updateValue = (event) => {
        emit('update:modelValue', value.value);
    };
    onMounted(() => {
        value.value = props.modelValue
    })
    watch(
        () => props.modelValue,
        () => {
            value.value = props.modelValue
        },
        { deep: true }
    );
</script>

<template>
    <label class="radio-container">
        <input
            type="checkbox"
            v-model="value"
            @change="updateValue"
        />
        <span class="radio-label px-2">{{ label }}</span>
    </label>
</template>
