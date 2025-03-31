<template>
  <d-data-grid 
    :fetchData="fetchData" 
    :columns="computedColumns"
    :rows="rows"
    title="Material Descriptions"
    rowKey="id"
  />
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axiosInstance from '../../config/http';
import dDataGrid from "../base/d-data-grid.vue";

const rows = ref([]);
const languages = ref([]);

onMounted(async () => {
  await fetchLanguages();
});

async function fetchLanguages() {
  try {
    const res = await axiosInstance.get('/api/languages');
   
    
    languages.value = res.data.response.languages;
    
    const testTransformed = transformData([{
      id: 999,
      reference: "TEST",
      descriptions: []
    }]);
    console.log('[DEBUG] Transform test:', testTransformed);
    
  } catch (error) {
    console.error('[DEBUG] Fetch error:', error);
    languages.value = [];
  }
}

const transformData = (data) => {
  return data.map(item => ({
    ...item,
    descriptions: item.descriptions?.map(desc => ({
      language_id: desc.id_lang,
      label: desc.label || "",
      iso_code: desc.is_code || ""
    })) || validLanguages.value.map(lang => ({
      language_id: lang.language_id,
      label: "",
      iso_code: lang.is_code
    }))
  }));
};

const computedColumns = computed(() => [
  {
    key: "reference",
    label: "Reference", 
    type: "text"
  },
  {
    key: "descriptions",
    label: "Description",
    type: "text",
  }
]);

const fetchData = async ({ page, itemsPerPage }) => {
  try {
    const { data } = await axiosInstance.get('/api/materials', {
      params: { page, itemsPerPage }
    });
    rows.value = transformData(data.response.data);
    console.log('Materials loaded:', rows.value);
    return data;
  } catch (error) {
    console.error('Error fetching data:', error);
    throw error;
  }
};

/* 
 @TODO terminer les autres fonctions
*/

</script>