<template>
    <div class="bh-pagination-number sm:bh-ml-auto bh-inline-flex bh-items-center bh-space-x-1">

        <button 
            type="button" 
            class="bh-page-item btn btn-outline-custom mb-1 me-1 rounded-circle first-page" 
            :class="{ 'disabled': currentPage === 1 }"
            :disabled="currentPage === 1" 
            @click="goToPage(1)">
            <vue-feather type="chevrons-left" :size="14"></vue-feather>
        </button>

        <button type="button" class="bh-page-item btn btn-outline-custom mb-1 me-1 rounded-circle previous-page" :class="{ 'disabled': currentPage === 1 }"
            :disabled="currentPage === 1" @click="goToPage(currentPage - 1)">
            <vue-feather type="chevron-left" :size="14"></vue-feather>
        </button>

        <button v-for="page in visiblePages" :key="page" type="button" class="bh-page-item"
            :class="{ 'bh-active': page === currentPage, 'disabled': page === currentPage }" @click="goToPage(page)">
            {{ page }}
        </button>

        <button type="button" class="bh-page-item btn btn-outline-custom mb-1 me-1 rounded-circle next-page" :class="{ 'disabled': currentPage === totalPages }"
            :disabled="currentPage === totalPages" @click="goToPage(currentPage + 1)">
            <vue-feather type="chevron-right" :size="14"></vue-feather>
        </button>

        <button type="button" class="bh-page-item btn btn-outline-custom mb-1 me-1 rounded-circle last-page" :class="{ 'disabled': currentPage === totalPages }"
            :disabled="currentPage === totalPages" @click="goToPage(totalPages)">
            <vue-feather type="chevrons-right" :size="14"></vue-feather>
        </button>
    </div>
</template>

<script setup>
    import VueFeather from 'vue-feather';

    const props = defineProps({
    currentPage: { type: Number, required: true },
    totalPages: { type: Number, required: true },
    visiblePages: { type: Array, required: true },
    });

    const emit = defineEmits(['page-change']);

    const goToPage = (page) => {
    if (page >= 1 && page <= props.totalPages) {
        emit('page-change', page);
    }
    };
</script>

<style scoped>
    .bh-pagination .bh-page-item {
        background-color: #e0e6ed;
        border-width: 0px !important;
        color: #3b3f5c;
        width: 38px;
        height: 38px;
    }
    .bh-pagination .bh-page-item:hover, .bh-pagination .bh-page-item.bh-active {
        background-color: #4260EB !important;
        color: #fff;
    }
</style>
