<template>
    <d-data-grid 
        v-if="isCollectionGroupsLoaded" 
        :fetchData="fetchData" 
        :saveData="saveData" 
        :addData="addData"
        :deleteData="deleteData" 
        :columns="processedColumns" 
        :rows="rows" 
        title="Collection de tapis"
        rowKey="id"
    />
</template>

<script setup>
import dDataGrid from "../base/d-data-grid.vue";
import { ref, computed, onMounted } from "vue";
import axiosInstance from '../../config/http';

const columns = [
    { key: "reference", label: "reference", type: "text" },
    { key: "code", label: "Code", type: "text" },
    { key: "show_grid", label: "Display grid", type: "boolean" },
    {
        key: "collection_group_id",
        label: "Groupe de collecte",
        type: "custom",
        component: "d-collection-group-settings",
        idKey: "id",
        nameKey: "name"
    },
    {
        key: "special_shape",
        label: "Formes spéciales",
        type: "custom",
        component: "d-special-shapes-settings",
        idKey: "id",
        nameKey: "name"
    },
    {
        key: "police",
        label: "Police",
        type: "custom",
        component: "d-police-settings",
        idKey: "id",
        nameKey: "name"
    },
    { key: "image_name", label: "Nom d'image", type: "text" },
];

const rows = ref([]);
const collectionGroups = ref([]);
const specialShapes = ref([]);
const polices = ref([]);
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

async function fetchSpecialShapes() {
    try {
        const res = await axiosInstance.get('/api/specialShapes');
        specialShapes.value = res.data.response;
    } catch (error) {
        console.error('Failed to fetch special shapes:', error);
    }
}

async function fetchPolices() {
    try {
        const res = await axiosInstance.get('/api/polices');
        polices.value = res.data.response;
    } catch (error) {
        console.error('Failed to fetch polices:', error);
    }
}

onMounted(async () => {
    await Promise.all([fetchCollectionGroup(), fetchSpecialShapes(), fetchPolices()]);
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
        if (col.component === 'd-special-shapes-settings') { 
            return {
                ...col,
                props: {
                    specialShapes: specialShapes.value
                }
            };
        }
        if (col.component === 'd-police-settings') { 
            return {
                ...col,
                props: {
                    polices: polices.value
                }
            };
        }
        return col;
    });
});

const fetchData = async ({ page, itemsPerPage }) => {
    try {
        const { data } = await axiosInstance.get('/api/collections', {
            params: { page, itemsPerPage }
        });

        const transformedData = data.response.data.map(item => {
            const collectionGroup = collectionGroups.value.find(group => group.id === item.collection_group_id);
            const police = polices.value.find(police => police.id === item.police);
            const specialShape = specialShapes.value.find(shape => shape.id === item.special_shape);
            
            return {
                ...item,
                collection_group_id: collectionGroup || null,
                police: police || null,
                special_shape: specialShape || null,
                show_grid: item.show_grid || false
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
            reference: row.reference,
            code: row.code,
            show_grid: row.show_grid || false,
            collection_group_id: typeof row.collection_group_id === 'object' 
                ? row.collection_group_id.id 
                : row.collection_group_id,
            police_id: typeof row.police === 'object' 
                ? row.police.id 
                : row.police,   
            special_shape: row.special_shape 
                ? (typeof row.special_shape === 'object' 
                    ? row.special_shape.id 
                    : row.special_shape)
                : null,
            police_id: row.police,
            image_name: row.image_name,
            languages: row.languages && row.languages.length > 0 
                ? row.languages 
                : [{ description: "Default Description", languageId: 1 }] 
        };

        const { data } = await axiosInstance.post("/api/carpet-collections", payload);
        
        const newRow = {
            ...data.response,
            collection_group_id: collectionGroups.value.find(g => g.id === data.response.collection_group_id),
            police_id: polices.value.find(g => g.id === data.response.police),
            special_shape: data.response.special_shape 
                ? specialShapes.value.find(g => g.id === data.response.special_shape)
                : null
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
            reference: row.reference,
            code: row.code,
            show_grid: row.show_grid || false,
            collection_group_id: typeof row.collection_group_id === 'object' 
                ? row.collection_group_id.id 
                : row.collection_group_id,
            police_id: typeof row.police === 'object' 
                ? row.police.id 
                : row.police,    
            special_shape: row.special_shape 
                ? (typeof row.special_shape === 'object' 
                    ? row.special_shape.id 
                    : row.special_shape)
                : null,
            image_name: row.image_name,
            languages: row.languages && row.languages.length > 0 
                ? row.languages 
                : [{ description: "Default Description", languageId: 1 }]
        };

        const { data } = await axiosInstance.put(`/api/collections/${row.id}`, payload);
        
        const updatedRow = {
            ...data.response,
            collection_group_id: collectionGroups.value.find(g => g.id === data.response.collection_group_id),
            police_id: polices.value.find(g => g.id === data.response.police_id),
            special_shape: data.response.special_shape 
                ? specialShapes.value.find(g => g.id === data.response.special_shape)
                : null,
            languages: row.languages && row.languages.length > 0 
                ? row.languages 
                : [{ description: "Default Description", languageId: 1 }]
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
        await axiosInstance.delete(`/api/collections/${row.id}`);
        rows.value = rows.value.filter(item => item.id !== row.id);
    } catch (error) {
        console.error("Erreur lors de la suppression des données:", error);
        throw new Error("Failed to delete data");
    }
};
</script>