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

    // Base columns (no material-specific columns here)
    const baseColumns = [
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
        // replaced 'year' with tarifGroup selector/display
        { key: 'tarifGroup', label: 'Groupe tarif', type: 'custom', component: 'd-tarif-group-settings' },
        { key: 'isActive', label: 'Afficher', type: 'boolean' }
    ];

    const rows = ref([]);
    const manufacturers = ref([]);
    const qualities = ref([]);
    const materials = ref([]);
    const tarifGroups = ref([]);
    const isMetadataLoaded = ref(false);

    // Build processed columns: inject props and append a column per material
    const processedColumns = computed(() => {
        const cols = baseColumns.map((column) => {
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
            if (column.component === 'd-tarif-group-settings') {
                return {
                    ...column,
                    props: {
                        tarifGroups: tarifGroups.value
                    }
                };
            }
            return column;
        });

        // append columns dynamically for each material
        const materialCols = (materials.value || []).map((m) => ({
            key: `material_${m.id}`,
            label: m.reference || m.name || `Matériau ${m.id}`,
            type: 'number'
        }));

        return [...cols, ...materialCols];
    });

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
        // Display helper: always show two decimals (e.g. "0.00").
        if (value === undefined || value === null || value === '') {
            return '0.00';
        }
        // Accept numbers or numeric strings, accept comma as decimal separator
        const raw = typeof value === 'number' ? value : String(value).replace(',', '.');
        const num = parseFloat(raw);
        if (Number.isNaN(num)) {
            return '0.00';
        }
        // Ensure two decimal places for display
        return num.toFixed(2);
    };
    const formatPricePayload = (value) => {
        // Normalize value for payload: accept numbers or strings (comma allowed), return two-decimal string or null
        if (value === '' || value === null || value === undefined) {
            return null;
        }
        const raw = typeof value === 'number' ? value : String(value).replace(',', '.').trim();
        const num = parseFloat(raw);
        if (Number.isNaN(num)) return null;
        // send as string with two decimals to be consistent with existing behaviour
        return num.toFixed(2);
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

        const tarifGroup = item.tarifGroup ?? item.tarif_group ?? null;

        // build material price fields from item.prices
        const materialPrices = {};
        const pricesArray = Array.isArray(item.prices) ? item.prices : [];
        for (const p of pricesArray) {
            const mid = p?.material?.id ?? p?.material_id ?? p?.materialId;
            if (mid !== undefined && mid !== null) {
                materialPrices[`material_${mid}`] = normalizePrice(p.price ?? p['price']);
            }
        }

        // ensure every known material has a field (default 0.00)
        for (const m of materials.value || []) {
            const key = `material_${m.id}`;
            if (!(key in materialPrices)) {
                materialPrices[key] = '0.00';
            }
        }

        return {
            ...item,
            manufacturer: manufacturer || (item.manufacturer ?? null),
            quality: quality || (item.quality ?? null),
            tarifGroup: tarifGroup || null,
            isActive: Boolean(item.isActive ?? item.show_grid ?? item.showGrid ?? false),
            ...materialPrices
        };
    };

    const buildPayload = (row) => {
        // helper to get current date as YYYY-MM-DD
        const currentDate = () => new Date().toISOString().split('T')[0];

        // include prices array where each element references a material id
        const prices = (materials.value || []).map((m) => {
            const key = `material_${m.id}`;
            return {
                materialId: m.id,
                price: formatPricePayload(row[key]),
                effectiveDate: currentDate()
            };
        });

        return {
            manufacturerId: getEntityId(row.manufacturer),
            qualityId: getEntityId(row.quality),
            tarifGroupId: getEntityId(row.tarifGroup),
            isActive: !!row.isActive,
            prices
        };
    };

    const isValidPrice = (value) => {
        if (value === null || value === '' || value === undefined) return true;
        // allow numeric strings or numbers (optionally with decimal point or comma)
        const s = typeof value === 'number' ? value.toString() : String(value).trim();
        // replace comma with dot then test
        const normalized = s.replace(',', '.');
        return /^\d+(\.\d+)?$/.test(normalized);
    };

    const validatePrices = (row) => {
        for (const m of materials.value || []) {
            const key = `material_${m.id}`;
            const val = row[key];
            if (!isValidPrice(val)) {
                throw new Error(`Prix invalide pour le matériau ${m.reference || m.name || m.id}: ${val}`);
            }
        }
    };

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

    const fetchMaterials = async () => {
        const { data } = await axiosInstance.get('/api/materials', {
            params: { page: 1, itemsPerPage: 1000 }
        });
        const list = data.response?.data ?? data.response ?? data.data ?? [];
        materials.value = Array.isArray(list) ? list : [];
    };

    const fetchTarifGroups = async () => {
        const { data } = await axiosInstance.get('/api/tarifGroups', { params: { page: 1, itemsPerPage: 1000 } });
        const list = data.response?.data ?? data.response ?? data.data ?? [];
        tarifGroups.value = Array.isArray(list) ? list : [];
    };

    onMounted(async () => {
        try {
            await Promise.all([fetchManufacturers(), fetchQualities(), fetchMaterials(), fetchTarifGroups()]);
        } catch (error) {
            console.error('Failed to load metadata for manufacturer price grid:', error);
            manufacturers.value = manufacturers.value || [];
            qualities.value = qualities.value || [];
            materials.value = materials.value || [];
            tarifGroups.value = tarifGroups.value || [];
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
        validatePrices(row);
        const payload = buildPayload(row);
        const { data } = await axiosInstance.post(API_ENDPOINT, payload);
        const responseRow = data.response ?? data;
        const normalized = normalizeRow(responseRow);
        rows.value.push(normalized);
        return normalized;
    };

    const saveData = async (row) => {
        validatePrices(row);
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
