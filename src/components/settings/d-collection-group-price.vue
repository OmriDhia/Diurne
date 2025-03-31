<template>
    <d-data-grid 
        v-if="isCollectionGroupsLoaded" 
        :fetchData="fetchData" 
        :saveData="saveData" 
        :addData="addData"
        :deleteData="deleteData" 
        :columns="processedColumns" 
        :rows="rows" 
        title="Prix de groupe de collecte"
        rowKey="id" 
    />
</template>

<script setup>
import dDataGrid from "../base/d-data-grid.vue";
import { ref, computed, onMounted } from "vue";
import axiosInstance from '../../config/http';

const columns = [
    {
        key: "collection_group_id",
        label: "Groupe de collecte",
        type: "custom",
        component: "d-collection-group-settings",
        idKey: "id",
        nameKey: "name"
    },
    { key: "price", label: "Prix", type: "text" },
    { key: "price_max", label: "Prix Max", type: "text" },
    {
        key: "tarif_group_id",
        label: "tarif de collecte",
        type: "custom",
        component: "d-tarif-group-settings",
        idKey: "id",
        nameKey: "name"
    },
];

const rows = ref([]);
const collectionGroups = ref([]);
const tarifGroups = ref([]);
const isCollectionGroupsLoaded = ref(false); 

async function fetchCollectionGroup() {
    try {
        const res = await axiosInstance.get('/api/collection-groups');
        collectionGroups.value = res.data.response;
        isCollectionGroupsLoaded.value = true; 
    } catch (error) {
        console.error('Failed to fetch collection-groups:', error);
    }
}

async function fetchTarifGroups() {
    try {
        const res = await axiosInstance.get('/api/tarifGroups');
        tarifGroups.value = res.data.response;
    } catch (error) {
        console.error('Failed to fetch collection-groups:', error);
    }
}

onMounted(async () => {
    await Promise.all([fetchCollectionGroup(), fetchTarifGroups()]);
});


const processedColumns = computed(() => {
    if (!isCollectionGroupsLoaded.value) return []; 
    
    return columns.map(col => {
        if (col.component === 'd-collection-group-settings') { 
            return {
                ...col,
                props: {
                    collectionGroups: collectionGroups.value
                }
            };
        }
        if (col.component === 'd-tarif-group-settings') { 
            return {
                ...col,
                props: {
                    tarifGroups: tarifGroups.value
                }
            };
        }
        return col;
    });
});


const fetchData = async ({ page, itemsPerPage }) => {
    try {
        const { data } = await axiosInstance.get('/api/collection-groups-prices', {
            params: { page, itemsPerPage }
        });

        const transformedData = data.response.data.map(item => {
            const collectionGroup = collectionGroups.value.find(group => group.id === item.collectionGroup);
            const tarifGroup = tarifGroups.value.find(group => group.id === item.tarifGroup);
            return {
                ...item,
                collection_group_id: collectionGroup || null,  
                tarif_group_id: tarifGroup || null,  
            };
        });

        return {
            ...data, 
            response: {
                ...data.response, 
                data: transformedData 
            }
        };
    } catch (error) {
        console.error('Erreur lors de la récupération des données:', error);
        throw new Error('Failed to fetch data');
    }
};

const addData = async (row) => {
    try {
        const payload = {
            group_number: typeof row.collection_group_id === 'object' 
                ? row.collection_group_id.id 
                : row.collection_group_id,
            collection_group_id: typeof row.collection_group_id === 'object' 
                ? row.collection_group_id.id 
                : row.collection_group_id,
            price: row.price,
            price_max: row.price_max,
            tarif_group_id: typeof row.tarif_group_id === 'object' 
                ? row.tarif_group_id.id 
                : row.tarif_group_id
        };

        const { data } = await axiosInstance.post("/api/createCollectionGroupPrice", payload);
        
        const newRow = {
            ...data.response,
            collection_group_id: collectionGroups.value.find(g => g.groupNumber === data.response.group_number),
            tarif_group_id: tarifGroups.value.find(g => g.id === data.response.tarif_group_id)
        };

        
        rows.value.push(newRow);
        
        return newRow;
    } catch (error) {
        console.error("Erreur lors de l'ajout des données:", error);
        throw new Error("Failed to add data");
    }
};


const saveData = async (row) => {
    try {
        const payload = {
            collection_group_id: typeof row.collection_group_id === 'object' 
                ? row.collection_group_id.id 
                : row.collection_group_id,
            price: row.price,
            price_max: row.price_max,
            tarif_group_id: typeof row.tarif_group_id === 'object' 
                ? row.tarif_group_id.id 
                : row.tarif_group_id
        };

        const { data } = await axiosInstance.put(`/api/collection-group-price/${row.id}`, payload);
        
        const updatedRow = {
            ...data.response,
            collection_group_id: collectionGroups.value.find(g => g.id === data.response.collection_group_id),
            tarif_group_id: tarifGroups.value.find(g => g.id === data.response.tarif_group_id)
        };
        
        const index = rows.value.findIndex(item => item.id === row.id);
        if (index !== -1) {
            rows.value[index] = updatedRow;
        }
        return updatedRow;
    } catch (error) {
        console.error("Erreur lors de la mise à jour des données:", error);
        throw new Error("Failed to save data");
    }
};

const deleteData = async (row) => {
    try {
        await axiosInstance.delete(`/api/collection-group-price/${row.id}`);
        rows.value = rows.value.filter(item => item.id !== row.id);
    } catch (error) {
        console.error("Erreur lors de la suppression des données:", error);
        throw new Error("Failed to delete data");
    }
};

</script>
