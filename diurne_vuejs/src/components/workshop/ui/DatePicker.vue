<template>
    <div class="date-picker" :class="rootClass">
        <d-input type="date"
                 :value="modelValue"
                 @input="updateValue"
                 class="date-input"
        />
    </div>
</template>
<script setup lang="ts">
    import dInput from '../../../components/base/d-input.vue';

    defineProps<{
        modelValue,
        rootClass
    }>();

    const emit = defineEmits(['update:modelValue']);

    const updateValue = (event: Event) => {
        const target = event.target as HTMLInputElement;
        emit('update:modelValue', target.value);
    };
</script>
