<template>
    <d-data-grid
        v-if="isMetadataLoaded"
        :fetchData="fetchData"
        :saveData="saveData"
        :addData="addData"
        :deleteData="deleteData"
        :columns="processedColumns"
        :rows="rows"
        title="Grille tarifaire fournisseur"
        rowKey="id"
    />
</template>

<script setup>
    import { computed, onMounted, ref } from 'vue';
    import dDataGrid from '../base/d-data-grid.vue';
    import axiosInstance from '../../config/http';

    const API_ENDPOINT = '/api/manufacturer-price-grids';

    const columns = [
        {
            key: 'manufacturer',
            label: 'Fabricant',
            type: 'custom',
            component: 'd-manufacturer-settings'
        },
        {
            key: 'quality',
            label: 'Qualité',
            type: 'custom',
            component: 'd-quality-settings'
        },
        { key: 'year', label: 'Année', type: 'number' },
        { key: 'isActive', label: 'Afficher', type: 'boolean' },
        { key: 'silkPrice', label: 'Prix Soie', type: 'number' },
        { key: 'woolPrice', label: 'Prix Laine', type: 'number' }
    ];

    const rows = ref([]);
    const manufacturers = ref([]);
    const qualities = ref([]);
    const isMetadataLoaded = ref(false);

    const processedColumns = computed(() => columns.map((column) => {
        if (column.component === 'd-manufacturer-settings') {
            return {
                ...column,
                props: {
                    manufacturers: manufacturers.value
                }
            };
        }
        if (column.component === 'd-quality-settings') {
            return {
                ...column,
                props: {
                    qualities: qualities.value
                }
            };
        }
        return column;
    }));

    const getEntityId = (value) => {
        if (!value && value !== 0) {
            return null;
        }

        if (typeof value === 'object') {
            const raw = value.__v_raw || value;
            return raw?.id ?? null;
        }

        const parsed = Number(value);
        return Number.isNaN(parsed) ? null : parsed;
    };

    const normalizePrice = (value) => {
        if (value === undefined || value === null) {
            return '';
        }
        return typeof value === 'number' ? value.toString() : value;
    };
    const formatPricePayload = (value) => {
        if (value === '' || value === null || value === undefined) {
            return null;
        }
        return typeof value === 'number' ? value.toString() : value;
    };
    const findEntity = (collection, id) => {
        if (!id && id !== 0) {
            return null;
        }
        return collection.find((item) => Number(item.id) === Number(id)) || null;
    };

    const normalizeRow = (item) => {
        if (!item) {
            return item;
        }

        const manufacturer = findEntity(
            manufacturers.value,
            getEntityId(item.manufacturer ?? item.manufacturerId ?? item.manufacturer_id)
        ) || (typeof item.manufacturer === 'object' ? item.manufacturer : null);

        const quality = findEntity(
            qualities.value,
            getEntityId(item.quality ?? item.qualityId ?? item.quality_id)
        ) || (typeof item.quality === 'object' ? item.quality : null);

        return {
            ...item,
            manufacturer: manufacturer || (item.manufacturer ?? null),
            quality: quality || (item.quality ?? null),
            year: item.year ?? '',
            isActive: Boolean(item.isActive ?? item.show_grid ?? item.showGrid ?? false),
            silkPrice: normalizePrice(item.silkPrice ?? item.silk_price),
            woolPrice: normalizePrice(item.woolPrice ?? item.wool_price)
        };
    };

    const buildPayload = (row) => ({
        manufacturerId: getEntityId(row.manufacturer),
        qualityId: getEntityId(row.quality),
        year: row.year ? Number(row.year) : null,
        isActive: !!row.isActive,
        silkPrice: formatPricePayload(row.silkPrice),
        woolPrice: formatPricePayload(row.woolPrice)
    });

    const fetchManufacturers = async () => {
        const { data } = await axiosInstance.get('/api/manufacturer', {
            params: { page: 1, itemsPerPage: 1000 }
        });
        const list = data.response?.data ?? data.response ?? data.data ?? [];
        manufacturers.value = Array.isArray(list) ? list : [];
    };

    const fetchQualities = async () => {
        const { data } = await axiosInstance.get('/api/qualities', {
            params: { page: 1, itemsPerPage: 1000 }
        });
        const list = data.response?.data ?? data.response ?? data.data ?? [];
        qualities.value = Array.isArray(list) ? list : [];
    };

    onMounted(async () => {
        try {
            await Promise.all([fetchManufacturers(), fetchQualities()]);
        } catch (error) {
            console.error('Failed to load metadata for manufacturer price grid:', error);
            manufacturers.value = manufacturers.value || [];
            qualities.value = qualities.value || [];
        } finally {
            isMetadataLoaded.value = true;
        }
    });

    const fetchData = async ({ page, itemsPerPage }) => {
        const { data } = await axiosInstance.get(API_ENDPOINT, {
            params: { page, itemsPerPage }
        });

        const response = data.response ?? data;
        let dataset = Array.isArray(response?.data) ? response.data : Array.isArray(response) ? response : [];

        const normalized = dataset.map((item) => normalizeRow(item));
        rows.value = normalized;

        if (Array.isArray(response?.data)) {
            return {
                ...data,
                response: {
                    ...response,
                    data: normalized
                }
            };
        }

        return {
            ...data,
            response: normalized
        };
    };

    const addData = async (row) => {
        const payload = buildPayload(row);
        const { data } = await axiosInstance.post(API_ENDPOINT, payload);
        const responseRow = data.response ?? data;
        const normalized = normalizeRow(responseRow);
        rows.value.push(normalized);
        return normalized;
    };

    const saveData = async (row) => {
        const payload = buildPayload(row);
        const { data } = await axiosInstance.put(`${API_ENDPOINT}/${row.id}`, payload);
        const responseRow = data.response ?? data;
        const normalized = normalizeRow(responseRow);
        const index = rows.value.findIndex((item) => item.id === row.id);
        if (index !== -1) {
            rows.value[index] = normalized;
        }
        return normalized;
    };

    const deleteData = async (row) => {
        await axiosInstance.delete(`${API_ENDPOINT}/${row.id}`);
        rows.value = rows.value.filter((item) => item.id !== row.id);
    };
</script>
