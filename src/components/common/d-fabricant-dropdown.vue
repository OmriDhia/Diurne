<template>
    <div class="row align-items-center pt-4">
        <div class="col-4" v-if="!hideLabel">
            <label class="form-label">Fabricant<span class="required" v-if="required">*</span> :</label>
        </div>
        <div :class="{ 'col-8': !hideLabel, 'col-12': hideLabel }">
            <Multiselect
                :class="{ 'is-invalid': error }"
                :multiple="isMultiple"
                v-model="selectedManufacturer"
                :options="manufacturers"
                placeholder="Fabricant"
                track-by="id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
                @tag="addTag"
                @update:model-value="handleChange"
                @search-change="handleSearch"
            />
            <div v-if="error" class="invalid-feedback">{{ $t('Le champ fabricant est obligatoire.') }}</div>
        </div>
    </div>
</template>

<script setup>
    import { ref, watchEffect } from 'vue';
    import axiosInstance from '../../config/http';
    import Multiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';

    const props = defineProps({
        modelValue: {
            type: [Array, String, Number, null],
            required: true
        },
        error: {
            type: String,
            default: ''
        },
        required: {
            type: Boolean,
            default: false
        },
        hideLabel: {
            type: Boolean,
            default: false
        },
        isMultiple: {
            type: Boolean,
            default: false
        },
        disabled: {
            type: Boolean,
            default: false
        }
    });

    const emit = defineEmits(['update:modelValue']);

    const selectedManufacturer = ref(null);
    const manufacturers = ref([]);

    const handleChange = (value) => {
        emit('update:modelValue', props.isMultiple ? value.map(e => e.id) : value?.id);
    };

    const addTag = (newTag) => {
        manufacturers.value.push(newTag);
        selectedManufacturer.value.push(newTag);
    };

    const handleSearch = (searchQuery) => {
        getManufacturers(searchQuery);
    };

    const getManufacturers = async (search = '') => {
        try {
            let url = `/api/manufacturer?page=1&itemsPerPage=100`;
            if (search) url += `&filter[name]=${encodeURIComponent(search)}`;
            const res = await axiosInstance.get(url);
            const data = res.data?.response?.data || res.data?.response?.manufacturers || res.data?.response || res.data?.data || [];
            manufacturers.value = (Array.isArray(data) ? data : []).map(e => ({ id: e.id, name: e.name }));
            syncSelected();
        } catch (e) {
            console.error('Erreur get manufacturers list.', e);
        }
    };

    const syncSelected = () => {
        if (props.modelValue !== undefined && props.modelValue !== null && props.modelValue !== '') {
            if (props.isMultiple) {
                // props.modelValue may be array of ids (string/number)
                selectedManufacturer.value = manufacturers.value.filter(f => (props.modelValue || []).some((v) => String(v) === String(f.id)));
            } else {
                selectedManufacturer.value = manufacturers.value.find(f => String(f.id) === String(props.modelValue)) || null;
            }
        } else {
            selectedManufacturer.value = null;
        }
    };

    watchEffect(() => {
        syncSelected();
    });

    getManufacturers();
</script>
