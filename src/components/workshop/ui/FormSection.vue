<script setup lang="ts">
    defineProps<{
        title: string;
    }>();
</script>

<template>
    <div class="form-section">
        <div class="section-header">
            <h3>{{ title }}</h3>
        </div>
        <div class="section-content">
            <slot></slot>
        </div>
    </div>
</template>
