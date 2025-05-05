<template>
    <d-data-grid :fetchData="fetchData" :saveData="saveData" :addData="addData" :deleteData="deleteData"
      :columns="columns" :rows="rows" title="Groupes de collections" rowKey="id" />
  </template>
  
  <script setup>
    import dDataGrid from "../base/d-data-grid.vue";
    import { ref } from "vue";
    import axiosInstance from '../../config/http';
  
    const columns = [
      { key: "groupNumber", label: "Nom", type: "number" },
    ];
  
    const rows = ref([]);
  
    const fetchData = async ({ page, itemsPerPage }) => {
      try {
        const { data } = await axiosInstance.get('/api/collection-groups', {
          params: {
            page,
            itemsPerPage
          }
        });
        return data;
      } catch (error) {
        console.error('Erreur lors de la récupération des données:', error);
        throw new Error('Failed to fetch data');
      }
    };
  
    const addData = async (row) => {
      try {
        const payload = {
            group_number: row.groupNumber
        };
        const { data } = await axiosInstance.post('/api/createCollectionGroup', payload);
        const newRow = {
            ...data.response,
            groupNumber: data.response.group_number,
        };
        rows.value.push(newRow);
        return newRow;
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
      try {
        await axiosInstance.delete(`/api/collection-group/${row.id}`);
        rows.value = rows.value.filter(item => item.id !== row.id);
      } catch (error) {
        console.error('Erreur lors de la suppression des données:', error);
        throw new Error('Failed to delete data');
      }
    };
  
    const saveData = async (row) => {
      try {
        const payload = {
            collection_group_id : row.groupNumber
        };
        const { data } = await axiosInstance.put(`/api/collection-group/${row.id}`, payload);
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