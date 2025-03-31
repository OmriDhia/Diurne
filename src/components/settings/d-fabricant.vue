<template>
    <d-data-grid 
        :fetchData="fetchData" 
        :saveData="saveData" 
        :addData="addData" 
        :deleteData="deleteData"
        :columns="processedColumns" 
        :rows="rows" 
        title="Fabricants" 
        rowKey="id" 
    />
</template>

<script setup>
import dDataGrid from "../base/d-data-grid.vue";
import { ref, computed, onMounted } from "vue";
import axiosInstance from '../../config/http';

const columns = [ 
    { key: "name", label: "Nom", type: "text" },
    { key: "company", label: "Entreprise", type: "text" },
    { key: "carpetPrefix", label: "Préfixe tapis", type: "text" },
    { key: "samplePrefix", label: "Préfixe échantillon", type: "text" },
    { key: "creditAmount", label: "Montant du crédit", type: "number" },
    { key: "complexityBonus", label: "Bonus de complexité", type: "number" },
    { key: "oversizeBonus", label: "Surcoût taille", type: "number" },
    { key: "oversizeMohaiBonus", label: "Surcoût Mohair", type: "number" },
    { key: "multiLevelBonus", label: "Bonus multi-niveau", type: "number" },
    { key: "specialFormBonus", label: "Bonus forme spéciale", type: "number" },
    { 
        key: "carpetCountry", 
        label: "Pays", 
        type: "custom", 
        component: "d-countries-settings",
        idKey: "id",
        nameKey: "name"
    },
    { 
        key: "currency", 
        label: "Devise", 
        type: "custom", 
        component: "d-currency-settings",
        idKey: "id",
        nameKey: "name"
    },
];

const rows = ref([]);
const currencies = ref([]);
const countries = ref([]);

onMounted(async () => {
    await Promise.all([fetchCurrencies(), fetchCountries()]);
});

async function fetchCurrencies() {
    try {
        const res = await axiosInstance.get('/api/currency');
        currencies.value = res.data.response;
    } catch (error) {
        console.error('Failed to fetch currencies:', error);
    }
}

async function fetchCountries() {
    try {
        const res = await axiosInstance.get('/api/countries');
        countries.value = res.data.response.countries;
    } catch (error) {
        console.error('Failed to fetch countries:', error);
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
        if (col.component === 'd-countries-settings') {
            return {
                ...col,
                props: {
                    countries: countries.value
                }
            };
        }
        return col;
    });
});

const transformData = (data) => {
    return data.map(item => ({
        ...item,
        carpetCountry: item.carpetCountry || null,
        currency: item.currency || null
    }));
};

const fetchData = async ({ page, itemsPerPage }) => {
    try {
        const { data } = await axiosInstance.get('/api/manufacturer', {
            params: { page, itemsPerPage }
        });
        
        return data;
    } catch (error) {
        console.error('Erreur lors de la récupération des données:', error);
        throw new Error('Failed to fetch data');
    }
};

const addData = async (row) => {
    try {
        const postData = {
            name: row.name,
            company: row.company,
            carpetPrefix: row.carpetPrefix,
            samplePrefix: row.samplePrefix,
            creditAmount: parseFloat(row.creditAmount) || 0,
            complexityBonus: parseFloat(row.complexityBonus) || 0,
            oversizeBonus: parseFloat(row.oversizeBonus) || 0,
            oversizeMohaiBonus: parseFloat(row.oversizeMohaiBonus) || 0,
            multiLevelBonus: parseFloat(row.multiLevelBonus) || 0,
            specialFormBonus: parseFloat(row.specialFormBonus) || 0,
            carpetCountryID: row.carpetCountry?.id || 0,
            currencyID: row.currency?.id || 0
        };

        const { data } = await axiosInstance.post('/api/createManufacturer', postData);
        const newItem = transformData([data.response.data])[0];
        rows.value.push(newItem);
        return newItem;
    } catch (error) {
        console.error('Erreur lors de l\'ajout des données:', error);
        throw new Error('Failed to add data');
    }
};

const deleteData = async (row) => {
    try {
        await axiosInstance.delete(`/api/manufacturer/${row.id}`);
        rows.value = rows.value.filter(item => item.id !== row.id);
    } catch (error) {
        console.error('Erreur lors de la suppression des données:', error);
        throw new Error('Failed to delete data');
    }
};

const saveData = async (row) => {
    try {
        const updateData = {
            name: row.name,
            company: row.company,
            carpetPrefix: row.carpetPrefix,
            samplePrefix: row.samplePrefix,
            creditAmount: parseFloat(row.creditAmount) || 0,
            complexityBonus: parseFloat(row.complexityBonus) || 0,
            oversizeBonus: parseFloat(row.oversizeBonus) || 0,
            oversizeMohaiBonus: parseFloat(row.oversizeMohaiBonus) || 0,
            multiLevelBonus: parseFloat(row.multiLevelBonus) || 0,
            specialFormBonus: parseFloat(row.specialFormBonus) || 0,
            carpetCountryID: row.carpetCountry?.id || 0,
            currencyID: row.currency?.id || 0
        };

        const { data } = await axiosInstance.put(`/api/updateManufacturer/${row.id}`, updateData);
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