<template>
    <d-data-grid
        :fetchData="fetchData"
        :saveData="saveData"
        :addData="addData"
        :deleteData="deleteData"
        :columns="processedColumns"
        :rows="rows"
        title="Prix des matériaux"
        rowKey="id"
    />
</template>

<script setup>
    import { computed, onMounted, ref } from 'vue';
    import axiosInstance from '../../config/http';
    import dDataGrid from '../base/d-data-grid.vue';

    const materials = ref([]);
    const rows = ref([]);

    const materialOptions = computed(() => materials.value.map(material => ({
        value: material.id,
        label: material.reference || `Matériau ${material.id}`
    })));

    const materialLabelMap = computed(() => materialOptions.value.reduce((map, option) => {
        map[option.value] = option.label;
        return map;
    }, {}));

    const columns = [
        {
            key: 'materialId',
            label: 'Matériau',
            type: 'dropdown',
            options: [],
            formatter: (value) => materialLabelMap.value[value] || ''
        },
        { key: 'publicPrice', label: 'Prix public', type: 'number' },
        { key: 'bigProjectPrice', label: 'Prix grand projet', type: 'number' }
    ];

    const processedColumns = computed(() => {
        return columns.map(col => {
            if (col.key === 'materialId') {
                return {
                    ...col,
                    options: materialOptions.value
                };
            }
            return col;
        });
    });

    const normalizeMaterials = (data = []) => {
        return data.map(item => ({
            id: item.id,
            reference: item.reference ?? ''
        }));
    };

    const normalizePrices = (data = []) => {
        return data.map(item => {
            const materialId = item.material?.id ?? item.materialId ?? item.material_id ?? null;
            return {
                id: item.id,
                publicPrice: Number(item.publicPrice ?? item.public_price ?? 0),
                bigProjectPrice: Number(item.bigProjectPrice ?? item.big_project_price ?? 0),
                materialId,
                material: item.material ?? materials.value.find(mat => mat.id === materialId) ?? null
            };
        });
    };

    const fetchMaterials = async () => {
        try {
            const { data } = await axiosInstance.get('/api/materials', {
                params: { page: 1, itemsPerPage: 1000 }
            });
            materials.value = normalizeMaterials(data.data ?? data.response?.data ?? []);
        } catch (error) {
            console.error('Erreur lors de la récupération des matériaux:', error);
            materials.value = [];
        }
    };

    const fetchData = async ({ page, itemsPerPage }) => {
        try {
            const { data } = await axiosInstance.get('/api/materialPrice', {
                params: { page, itemsPerPage }
            });

            const transformed = normalizePrices(data.data ?? data.response?.data ?? data.response ?? []);
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
            console.error('Erreur lors de la récupération des prix des matériaux:', error);
            throw new Error('Failed to fetch data');
        }
    };

    const buildPayload = (row) => ({
        materialId: typeof row.materialId === 'object' ? row.materialId.id : row.materialId,
        publicPrice: Number(row.publicPrice ?? 0),
        bigProjectPrice: Number(row.bigProjectPrice ?? 0)
    });

    const addData = async (row) => {
        try {
            const payload = buildPayload(row);
            const { data } = await axiosInstance.post('/api/createMaterialPrice', payload);
            const created = normalizePrices([data.response?.data ?? data.response ?? data.data ?? data])[0];
            rows.value.push(created);
            return created;
        } catch (error) {
            console.error('Erreur lors de l\'ajout des prix:', error);
            throw new Error('Failed to add data');
        }
    };

    const saveData = async (row) => {
        try {
            const payload = buildPayload(row);
            const { data } = await axiosInstance.put(`/api/updateMaterialPrice/${row.id}`, payload);
            const updated = normalizePrices([data.response?.data ?? data.response ?? data.data ?? data])[0];
            const index = rows.value.findIndex(item => item.id === row.id);
            if (index !== -1) {
                rows.value[index] = updated;
            }
            return updated;
        } catch (error) {
            console.error('Erreur lors de la mise à jour des prix:', error);
            throw new Error('Failed to save data');
        }
    };

    const deleteData = async (row) => {
        try {
            await axiosInstance.delete(`/api/deleteMaterialPrice/${row.id}`);
            rows.value = rows.value.filter(item => item.id !== row.id);
        } catch (error) {
            console.error('Erreur lors de la suppression des prix:', error);
            throw new Error('Failed to delete data');
        }
    };

    onMounted(async () => {
        await fetchMaterials();
    });
</script>
