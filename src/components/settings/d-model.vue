<template>
  <d-data-grid :fetchData="fetchData" :saveData="saveData" :addData="addData" :deleteData="deleteData"
    :columns="columns" :rows="rows" title="Model" rowKey="id" />
</template>

<script setup>
  import dDataGrid from "../base/d-data-grid.vue";
  import { ref } from "vue";
  import axiosInstance from '../../config/http';

  const columns = [
    { key: "code", label: "Code", type: "text" },
    { key: "number_max", label: "Number Max", type: "number" },
  ];

  const rows = ref([]);

  const fetchData = async ({ page, itemsPerPage }) => {
    try {
      const { data } = await axiosInstance.get('/api/models', {
        params: {
          page,
          itemsPerPage
        }
      });
      console.log("Model:",data);
      return data;
    } catch (error) {
      console.error('Erreur lors de la récupération des données:', error);
      throw new Error('Failed to fetch data');
    }
  };

  const addData = async (row) => {
    try {
      const { data } = await axiosInstance.post('/api/models', row);
      rows.value.push(data.response.data);
      return data.response.data;
    } catch (error) {
      if (error.response && error.response.status === 409) {
          const errorMessage = "Un modèle avec ce code existe déjà.";
          window.showMessage(errorMessage, "error");
      }else{
          window.showMessage("Une erreur inattendue s'est produite lors de l'ajout.", "error");
      }
      console.error('Erreur lors de l\'ajout des données:', error);
      throw new Error('Failed to add data');
    }
  };

  const deleteData = async (row) => {
    console.log(row);
    try {
      await axiosInstance.delete(`/api/models/${row.id}`);
      rows.value = rows.value.filter(item => item.id !== row.id); 
    } catch (error) {
      console.error('Erreur lors de la suppression des données:', error);
      throw new Error('Failed to delete data');
    }
  };

  const saveData = async (row) => {
    try {
      const { data } = await axiosInstance.put(`/api/models/${row.id}`, row);
      const index = rows.value.findIndex(item => item.id === row.id);
      if (index !== -1) {
        rows.value[index] = data.response.data; 
      }
      return data.response.data;
    } catch (error) {
      console.error('Erreur lors de la mise à jour des données:', error);
      throw new Error('Failed to save data');
    }
  };
</script>