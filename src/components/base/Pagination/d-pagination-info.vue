<template>
    <div class="bh-pagination-info bh-flex bh-items-center">
        <span class="bh-mr-2">
            Affichage de {{ startItem }} à {{ endItem }} sur {{ totalItems }} entrées
        </span>
        <select :value="itemsPerPage" @change="handlePageSizeChange" class="bh-pagesize">
            <option v-for="size in pageSizes" :key="size" :value="size">{{ size }}</option>
        </select>
    </div>
</template>

<script setup>
    defineProps({
        startItem: { type: Number, required: true },
        endItem: { type: Number, required: true },
        totalItems: { type: Number, required: true },
        itemsPerPage: { type: Number, required: true },
    });

    const emit = defineEmits(['page-size-change']);

    const pageSizes = [10, 25, 50, 75, 100]; 

    const handlePageSizeChange = (event) => {
        const newSize = parseInt(event.target.value, 10);
        emit('page-size-change', newSize);
    };
</script>

<style scoped>
    .bh-pagesize {
        padding: 0.25rem 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>