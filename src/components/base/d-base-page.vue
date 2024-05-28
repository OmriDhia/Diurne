<template>
    <div class="layout-px-spacing mt-4">
        <slot name="title"></slot>

        <div class="row layout-top-spacing mt-3">
            <slot name="header"></slot>
        </div>

        <div class="row layout-top-spacing mt-3">
            <slot name="body"></slot>
        </div>
        
        <slot name="footer"></slot>
    </div>
</template>

<script setup>
    
</script>
