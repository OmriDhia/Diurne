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
        { key: 'labelFr', label: 'Description FR', type: 'text' },
        { key: 'labelEn', label: 'Description EN', type: 'text' }
    ];

    const rows = ref([]);

    // Helper: get label for a given language_id from descriptions array
    const getLabelForLang = (descriptions = [], languageId) => {
        const found = descriptions.find(d => d.language_id === languageId || d.id_lang === languageId || d.lang_id === languageId);
        return found?.label ?? '';
    };

    const transformMaterials = (data = []) => {
        return data.map(item => {
            const descriptions = Array.isArray(item.descriptions) ? item.descriptions : [];
            return {
                id: item.id,
                reference: item.reference ?? '',
                // assuming 1 = FR, 2 = EN
                labelFr: getLabelForLang(descriptions, 1),
                labelEn: getLabelForLang(descriptions, 2),
                // keep raw descriptions from API so we can preserve language_id on edit
                descriptions
            };
        });
    };

    // Build descriptions array exactly as expected by the backend DTO:
    // - field name: language_id (required)
    // - field name: label (required)
    // - no id_lang / iso_code sent
    const normalizeDescriptions = (row) => {
        const result = [];

        // Try to preserve existing language_id when present
        const existing = Array.isArray(row.descriptions) ? row.descriptions : [];

        const upsert = (languageId, labelValue) => {
            if (!labelValue) return;
            const existingDesc = existing.find(d =>
                d.language_id === languageId ||
                d.id_lang === languageId ||
                d.lang_id === languageId
            );
            result.push({
                language_id: existingDesc?.language_id ?? existingDesc?.id_lang ?? existingDesc?.lang_id ?? languageId,
                label: labelValue
            });
        };

        // Assuming language_id 1 = FR, 2 = EN
        upsert(1, row.labelFr);
        upsert(2, row.labelEn);

        return result;
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
