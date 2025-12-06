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
    const qualities = ref([]);
    const tarifTextures = ref([]);
    const rows = ref([]);

    const materialOptions = computed(() => materials.value.map(material => ({
        value: material.id,
        label: material.reference || `Matériau ${material.id}`
    })));

    const qualityOptions = computed(() => qualities.value.map(quality => ({
        value: quality.id,
        label: quality.name || `Qualité ${quality.id}`
    })));

    const tarifTextureOptions = computed(() => tarifTextures.value.map(tarif => ({
        value: tarif.id,
        label: tarif.year?.toString() || `Année ${tarif.id}`
    })));

    const materialLabelMap = computed(() => materialOptions.value.reduce((map, option) => {
        map[option.value] = option.label;
        return map;
    }, {}));

    const qualityLabelMap = computed(() => qualityOptions.value.reduce((map, option) => {
        map[option.value] = option.label;
        return map;
    }, {}));

    const tarifTextureLabelMap = computed(() => tarifTextureOptions.value.reduce((map, option) => {
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
        {
            key: 'qualityId',
            label: 'Qualité',
            type: 'dropdown',
            options: [],
            formatter: (value) => qualityLabelMap.value[value] || ''
        },
        {
            key: 'tarifTextureId',
            label: 'Année Grille Tarif',
            type: 'dropdown',
            options: [],
            formatter: (value) => tarifTextureLabelMap.value[value] || ''
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
            if (col.key === 'qualityId') {
                return {
                    ...col,
                    options: qualityOptions.value
                };
            }
            if (col.key === 'tarifTextureId') {
                return {
                    ...col,
                    options: tarifTextureOptions.value
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

    const normalizeQualities = (data = []) => {
        return data.map(item => ({
            id: item.id,
            name: item.name ?? item.label ?? ''
        }));
    };

    const normalizeTarifTextures = (data = []) => {
        return data.map(item => ({
            id: item.id,
            year: item.year ?? ''
        }));
    };

    const normalizePrices = (data = []) => {
        return data.map(item => {
            const materialId = item.material?.id ?? item.materialId ?? item.material_id ?? null;
            const quality = item.qualityTarifTexture?.quality ?? item.quality ?? null;
            const qualityId = quality?.id ?? item.qualityTarifTexture?.quality_id ?? item.qualityId ?? item.quality_id ?? null;
            const tarifTexture = item.qualityTarifTexture?.tarifTexture ?? item.tarifTexture ?? null;
            const tarifTextureId = tarifTexture?.id ?? item.tarifTextureId ?? item.tarif_texture_id ?? null;
            const qualityTarifTextureId = item.qualityTarifTexture?.id ?? item.qualityTarifTextureId ?? item.quality_tarif_texture_id ?? null;
            return {
                id: item.id,
                publicPrice: Number(item.publicPrice ?? item.public_price ?? 0),
                bigProjectPrice: Number(item.bigProjectPrice ?? item.big_project_price ?? 0),
                materialId,
                material: item.material ?? materials.value.find(mat => mat.id === materialId) ?? null,
                qualityId,
                quality: quality ?? qualities.value.find(q => q.id === qualityId) ?? null,
                tarifTextureId,
                tarifTexture: tarifTexture ?? tarifTextures.value.find(t => t.id === tarifTextureId) ?? null,
                qualityTarifTextureId
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

    const fetchQualities = async () => {
        try {
            const { data } = await axiosInstance.get('/api/qualities', {
                params: { page: 1, itemsPerPage: 1000 }
            });
            const list = data.response?.data ?? data.response ?? data.data ?? [];
            qualities.value = Array.isArray(list) ? list : [];
        } catch (error) {
            console.error('Erreur lors de la récupération des qualités:', error);
            qualities.value = [];
        }
    };

    const fetchTarifTextures = async () => {
        try {
            const { data } = await axiosInstance.get('/api/tarifTextures', {
                params: { page: 1, itemsPerPage: 1000 }
            });
            tarifTextures.value = normalizeTarifTextures(data.data ?? data.response?.data ?? []);
        } catch (error) {
            console.error('Erreur lors de la récupération des grilles tarifaires:', error);
            tarifTextures.value = [];
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

    // Payload used when creating a new material price (full set of fields)
    const buildCreatePayload = (row) => ({
        materialId: typeof row.materialId === 'object' ? row.materialId.id : row.materialId,
        qualityId: typeof row.qualityId === 'object' ? row.qualityId.id : row.qualityId,
        tarifTextureId: typeof row.tarifTextureId === 'object' ? row.tarifTextureId.id : row.tarifTextureId,
        qualityTarifTextureId: typeof row.qualityTarifTextureId === 'object' ? row.qualityTarifTextureId.id : row.qualityTarifTextureId,
        publicPrice: Number(row.publicPrice ?? 0),
        bigProjectPrice: Number(row.bigProjectPrice ?? 0)
    });

    // Payload used when updating an existing material price (matches UpdateMaterialPriceRequestDto)
    const buildUpdatePayload = (row) => ({
        materialId: typeof row.materialId === 'object' ? row.materialId.id : row.materialId,
        publicPrice: row.publicPrice !== undefined && row.publicPrice !== null
            ? Number(row.publicPrice)
            : null,
        bigProjectPrice: row.bigProjectPrice !== undefined && row.bigProjectPrice !== null
            ? Number(row.bigProjectPrice)
            : null
    });

    const addData = async (row) => {
        try {
            const payload = buildCreatePayload(row);
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
            const payload = buildUpdatePayload(row);
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
        await Promise.all([
            fetchMaterials(),
            fetchQualities(),
            fetchTarifTextures()
        ]);
    });
</script>
