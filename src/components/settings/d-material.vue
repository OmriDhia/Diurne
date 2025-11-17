<template>
    <d-data-grid
        :fetchData="fetchData"
        :saveData="saveData"
        :addData="addData"
        :deleteData="deleteData"
        :columns="columns"
        :rows="rows"
        title="Matériaux"
        rowKey="id"
    />
</template>

<script setup>
    import { ref } from 'vue';
    import axiosInstance from '../../config/http';
    import dDataGrid from '../base/d-data-grid.vue';

    const columns = [
        { key: 'reference', label: 'Référence', type: 'text' },
        { key: 'label', label: 'Description', type: 'text' }
    ];

    const rows = ref([]);

    const transformMaterials = (data = []) => {
        return data.map(item => ({
            id: item.id,
            reference: item.reference ?? '',
            label: item.descriptions?.[0]?.label ?? '',
            descriptions: item.descriptions ?? []
        }));
    };

    const normalizeDescriptions = (row) => {
        if (Array.isArray(row.descriptions) && row.descriptions.length) {
            return row.descriptions.map(desc => ({
                id_lang: desc.id_lang ?? desc.language_id ?? desc.lang_id ?? 1,
                iso_code: desc.iso_code ?? desc.is_code ?? desc.iso ?? '',
                label: desc.label ?? row.label ?? ''
            })).filter(desc => desc.label !== '');
        }

        if (row.label) {
            return [{ id_lang: 1, iso_code: 'fr', label: row.label }];
        }

        return [];
    };

    const fetchData = async ({ page, itemsPerPage }) => {
        try {
            const { data } = await axiosInstance.get('/api/materials', {
                params: { page, itemsPerPage }
            });

            const materialList = data.data ?? data.response?.data ?? [];
            const transformed = transformMaterials(materialList);
            rows.value = transformed;

            return {
                response: {
                    data: transformed,
                    meta: data.meta ?? data.response?.meta ?? {
                        total_items: transformed.length,
                        page,
                        items_per_page: itemsPerPage
                    }
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
                reference: row.reference ?? '',
                descriptions: normalizeDescriptions(row)
            };

            const { data } = await axiosInstance.post('/api/createMaterial', payload);
            const createdMaterial = transformMaterials([data.response?.data ?? data.response ?? data.data ?? data])[0];
            rows.value.push(createdMaterial);
            return createdMaterial;
        } catch (error) {
            console.error('Erreur lors de l\'ajout des données:', error);
            throw new Error('Failed to add data');
        }
    };

    const saveData = async (row) => {
        try {
            const payload = {
                reference: row.reference ?? '',
                descriptions: normalizeDescriptions(row)
            };

            const { data } = await axiosInstance.put(`/api/updateMaterial/${row.id}`, payload);
            const updated = transformMaterials([data.response?.data ?? data.response ?? data.data ?? data])[0];
            const index = rows.value.findIndex(item => item.id === row.id);
            if (index !== -1) {
                rows.value[index] = updated;
            }
            return updated;
        } catch (error) {
            console.error('Erreur lors de la mise à jour des données:', error);
            throw new Error('Failed to save data');
        }
    };

    const deleteData = async (row) => {
        try {
            await axiosInstance.delete(`/api/deleteMaterial/${row.id}`);
            rows.value = rows.value.filter(item => item.id !== row.id);
        } catch (error) {
            console.error('Erreur lors de la suppression des données:', error);
            throw new Error('Failed to delete data');
        }
    };
</script>
