<template>
    <div class="bh-pagination bh-py-5">
      <div class="bh-flex bh-items-center bh-flex-wrap bh-flex-col sm:bh-flex-row bh-gap-4">
        <PaginationInfo
          :startItem="startItem"
          :endItem="endItem"
          :totalItems="totalItems"
          :itemsPerPage="itemsPerPage"
          @page-size-change="handlePageSizeChange"
        />
  
        <PaginationButtons
          :currentPage="currentPage"
          :totalPages="totalPages"
          :visiblePages="visiblePages"
          @page-change="goToPage"
        />
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  import PaginationInfo from './d-pagination-info.vue';
  import PaginationButtons from './d-pagination-buttons.vue';
  
  const props = defineProps({
    currentPage: { type: Number, required: true, validator: (value) => value > 0 },
    totalPages: { type: Number, required: true, validator: (value) => value > 0 },
    totalItems: { type: Number, required: true, validator: (value) => value >= 0 },
    itemsPerPage: { type: Number, required: true, validator: (value) => value > 0 },
  });
  
  const emit = defineEmits(['page-change', 'page-size-change']);
  
  const startItem = computed(() => {
    const value = (props.currentPage - 1) * props.itemsPerPage + 1;
    return value;
  });
  
  const endItem = computed(() => {
    const value = Math.min(props.currentPage * props.itemsPerPage, props.totalItems);
    return value;
  });
  
  const visiblePages = computed(() => {
    const maxVisiblePages = 5; 
    const halfVisiblePages = Math.floor(maxVisiblePages / 2);
  
    let start = Math.max(1, props.currentPage - halfVisiblePages);
    let end = Math.min(props.totalPages, start + maxVisiblePages - 1);
  
    if (end - start + 1 < maxVisiblePages) {
      start = Math.max(1, end - maxVisiblePages + 1);
    }
  
    return Array.from({ length: end - start + 1 }, (_, i) => start + i);
  });
  
  const goToPage = (page) => {
    if (page >= 1 && page <= props.totalPages) {
      emit('page-change', page);
    }
  };
  
  const handlePageSizeChange = (newSize) => {
    emit('page-size-change', newSize);
  };
  </script>