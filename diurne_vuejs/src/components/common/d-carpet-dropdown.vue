<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">{{ label }}<span class="required" v-if="required">*</span>
            :</label></div>
        <div class="col-8">
            <multiselect
                :class="[{ 'is-invalid': error }, rootClass]"
                :model-value="carpertTypeId"
                :options="carpetTypes"
                placeholder="Type de tapis"
                track-by="id"
                label="name"
                selected-label=""
                select-label=""
                deselect-label=""
                @update:model-value="handleChange($event)"
                @search-change="handleSearch($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t('Le champ type de tapis est abligatoire.') }}</div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../config/http';
    import Multiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';
    import store from '../../store/index';

    export default {
        components: {
            Multiselect
        },
        computed: {},
        props: {
            modelValue: {
                type: [Number, null],
                required: true
            },
            error: {
                type: String,
                default: ''
            },
            label: {
                type: String,
                default: 'Type de tapis'
            },
            required: {
                type: Boolean,
                default: false
            },
            rootClass: {
                type: [String, Array, Object],
                default: ''
            }
        },
        data() {
            return {
                carpertTypeId: null,
                carpetTypes: [
                    { id: 1, name: 'tapis' },
                    { id: 2, name: 'échantillon' }
                ]
            };
        },
        methods: {
            handleChange(value) {
                const id = value && value.id !== undefined ? parseInt(value.id, 10) : null;
                this.$emit('update:modelValue', Number.isNaN(id) ? null : id);
                this.setSelectedCarpetType(id);
            },
            async getCarpetTypes() {
                try {
                    let url = '/api/carpet-types';
                    const res = await axiosInstance.get(url);
                    this.carpetTypes = res.data.response.carpet_types;
                    this.setSelectedCarpetType(this.modelValue);
                } catch {
                    console.log('Erreur get carpet type list.');
                }
            },
            setSelectedCarpetType(value) {
                const parsedValue = typeof value === 'string' ? parseInt(value, 10) : value;
                if (Number.isNaN(parsedValue) || parsedValue === null || parsedValue === undefined) {
                    this.carpertTypeId = null;
                    return;
                }
                this.carpertTypeId = this.carpetTypes.find(ad => ad.id === parsedValue) || null;
            }
        },
        mounted() {
            this.getCarpetTypes();
            this.setSelectedCarpetType(this.modelValue);
        },
        watch: {
            modelValue(newValue) {
                this.setSelectedCarpetType(newValue);
            },
            carpetTypes() {
                this.setSelectedCarpetType(this.modelValue);
            }
        }
    };
</script>
