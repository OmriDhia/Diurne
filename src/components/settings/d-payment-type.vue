<template>
    <d-data-grid :fetchData="fetchData" :saveData="saveData" :addData="addData" :deleteData="deleteData"
      :columns="columns" :rows="rows" title="Type de paiement" rowKey="id" />
  </template>
  
  <script setup>
    import dDataGrid from "../base/d-data-grid.vue";
    import { ref } from "vue";
    import axiosInstance from '../../config/http';
  
    const columns = [
      { key: "label", label: "Label", type: "text" }
    ];
  
  
    const rows = ref([]);
    const totalItems = ref(null);
    const totalPages = ref(null);
  
    const fetchData = async ({ page, itemsPerPage } = {}) => {
      try {
        const params = {};
        if (page !== undefined && itemsPerPage !== undefined) {
          params.page = page;
          params.itemsPerPage = itemsPerPage;
        }
  
        const { data } = await axiosInstance.get('/api/payment-type', { params });
  
        return data;
        
      } catch (error) {
        console.error('Erreur lors de la récupération des données:', error);
        throw new Error('Failed to fetch data');
      }
    };
  
    const addData = async (row) => {
      try {
        const { data } = await axiosInstance.post('/api/payment-type', row);
        rows.value.push(data.response);
        return data.response;
      } catch (error) {
        if (error.response && error.response.status === 409) {
          const errorMessage = "Un Transporteur avec ce code existe déjà.";
          window.showMessage(errorMessage, "error");
        } else {
          window.showMessage("Une erreur inattendue s'est produite lors de l'ajout.", "error");
        }
  
        console.error('Erreur lors de l\'ajout des données:', error);
        throw new Error('Failed to add data');
      }
    };
  
    const deleteData = async (row) => {
      console.log(row);
      try {
        await axiosInstance.delete(`/api/payment-type/${row.id}`);
        rows.value = rows.value.filter(item => item.id !== row.id);
      } catch (error) {
        console.error('Erreur lors de la suppression des données:', error);
        throw new Error('Failed to delete data');
      }
    };
  
    const saveData = async (row) => {
      try {
        const { data } = await axiosInstance.put(`/api/payment-type/${row.id}`, row);
        const index = rows.value.findIndex(item => item.id === row.id);
        if (index !== -1) {
          rows.value[index] = data.response;
        }
        return data.response;
      } catch (error) {
        console.error('Erreur lors de la mise à jour des données:', error);
        throw new Error('Failed to save data');
      }
    };
  </script>