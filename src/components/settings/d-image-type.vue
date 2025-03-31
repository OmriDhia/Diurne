<template>
    <d-data-grid :fetchData="fetchData" :saveData="saveData" :addData="addData" :deleteData="deleteData"
      :columns="columns" :rows="rows" title="Type d'image" rowKey="id" />
  </template>
  
  <script setup>
    import dDataGrid from "../base/d-data-grid.vue";
    import { ref } from "vue";
    import axiosInstance from '../../config/http';
  
    const columns = [
      { key: "name", label: "Nom", type: "text" },
      { key: "description", label: "Description", type: "text" },
      { 
        key: "category", 
        label: "Categorie", 
        type: "dropdown",
        options: [
          { value: "Studio", label: "Studio" },
          { value: "Atelier", label: "Atelier" }
        ]
      }
    ];
  
  
    const rows = ref([]);
  
    const fetchData = async ({ page, itemsPerPage }) => {
      try {
        const { data } = await axiosInstance.get('/api/image-types', {
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
        const { data } = await axiosInstance.post('/api/image-type/create', row);
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
      try {
        await axiosInstance.delete(`/api/image-types/${row.id}`);
        rows.value = rows.value.filter(item => item.id !== row.id);
      } catch (error) {
        console.error('Erreur lors de la suppression des données:', error);
        throw new Error('Failed to delete data');
      }
    };
  
    const saveData = async (row) => {
      try {
        const { data } = await axiosInstance.put(`/api/image-type/${row.id}`, row);
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