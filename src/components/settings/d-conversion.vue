<template>
    <d-data-grid 
      :fetchData="fetchData" 
      :saveData="saveData" 
      :addData="addData" 
      :deleteData="deleteData"
      :columns="processedColumns" 
      :rows="rows" 
      title="Currency Conversions" 
      rowKey="id" 
    />
  </template>
  
  <script setup>
  import dDataGrid from "../base/d-data-grid.vue";
  import { ref, computed, onMounted } from "vue";
  import axiosInstance from '../../config/http';
  
  const columns = [
    { 
      key: "currency", 
      label: "Devise", 
      type: "custom", 
      component: "d-currency-settings",
      idKey: "id",
      nameKey: "name"
    },
    { key: "conversionDate", label: "Date de conversion", type: "date" },
    { key: "coefficient", label: "Coefficient", type: "text" },
  ];
  
  const rows = ref([]);
  const currencies = ref([]);
  
  onMounted(async () => {
    await Promise.all([fetchCurrencies()]);
  });
  
  async function fetchCurrencies() {
    try {
      const res = await axiosInstance.get('/api/currency');
      currencies.value = res.data.response;
    } catch (error) {
      console.error('Failed to fetch currencies:', error);
    }
  }
  
  const processedColumns = computed(() => {
    return columns.map(col => {
      if (col.component === 'd-currency-settings') { 
        return {
          ...col,
          props: {
            currencies: currencies.value
          }
        };
      }
      return col;
    });
  });
  
  const transformData = (data) => {
    return data.map(item => ({
      ...item,
      currency: currencies.value.find(c => c.id === item.currencyId) || null,
      conversionDate: item.conversionDate || new Date().toISOString(),
      coefficient: item.coefficient || ""
    }));
  };
  
  const fetchData = async ({ page, itemsPerPage }) => {
    try {
      const { data } = await axiosInstance.get('/api/conversions', {
        params: { page, itemsPerPage }
      });
      rows.value = transformData(data.response);
      return data;
    } catch (error) {
      console.error('Erreur lors de la récupération des données:', error);
      throw new Error('Failed to fetch data');
    }
  };
  
  const addData = async (row) => {
    try {
      const postData = {
        currencyId: row.currency?.id || 0,
        conversionDate: row.conversionDate || new Date().toISOString(),
        coefficient: row.coefficient
      };
  
      const { data } = await axiosInstance.post('/api/conversion', postData);
      const newItem = transformData([data.response])[0];
      rows.value.push(newItem);
      return newItem;
    } catch (error) {
      console.error('Erreur lors de l\'ajout des données:', error);
      throw new Error('Failed to add data');
    }
  };
  
  const deleteData = async (row) => {
    try {
      await axiosInstance.delete(`/api/conversion/${row.id}`);
      rows.value = rows.value.filter(item => item.id !== row.id);
    } catch (error) {
      console.error('Erreur lors de la suppression des données:', error);
      throw new Error('Failed to delete data');
    }
  };
  
  const saveData = async (row) => {
    try {
      const updateData = {
        currencyId: row.currency?.id || 0,
        conversionDate: row.conversionDate,
        coefficient: row.coefficient
      };
  
      const { data } = await axiosInstance.put(`/api/conversion/${row.id}`, updateData);
      const updatedItem = transformData([data.response])[0];
      const index = rows.value.findIndex(item => item.id === row.id);
      if (index !== -1) {
        rows.value[index] = updatedItem;
      }
      return updatedItem;
    } catch (error) {
      console.error('Erreur lors de la mise à jour des données:', error);
      throw new Error('Failed to save data');
    }
  };
  </script>