<template>
    <div class="row align-items-center pt-2">
        <div class="col-4" v-if="!showOnlyDropdown"><label class="form-label">Modèle<span class="required" v-if="required">*</span>:</label>
        </div>
        <div :class="{'col-md-8':!showOnlyDropdown,'col-md-12':showOnlyDropdown}">
            <multiselect
                :class="{ 'multiselect--error': error }"
                :model-value="value"
                :options="data"
                placeholder="Modèle"
                track-by="id"
                label="code"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
                @update:model-value="handleChange($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champs modèle est obligatoire.") }}</div>
            <div v-if="errorModel" class="invalid-feedback">{{ $t("Le champs modèle est obligatoire.") }}</div>

        </div>
    </div>
    <div class="row align-items-center justify-content-end" v-if="!showOnlyDropdown && !hideBtn">
        <div class="col-8">
            <button class="btn btn-custom pe-2 ps-2 font-size-0-7 w-100" @click="goToSettings">Céer une modèle
            </button>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../../../config/http';
    import Multiselect from 'vue-multiselect'
    import 'vue-multiselect/dist/vue-multiselect.css';

    export default {
        components: {
            Multiselect
        },
        props: {
            modelValue: {
                type: [Number, String, null],
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
            disabled: {
                type: Boolean,
                default: false
            },
            showOnlyDropdown: {
                type: Boolean,
                default: false
            },
            hideBtn: {
                type: Boolean,
                default: false
            },
            errorModel: {
                type: Boolean,
                default: false
            },
            collectionId: {
                type: [Number, String, null],
                default: null
            }
        },
        data() {
            return {
                value: null,
                data: [],
                models: []
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value.id));
            },
            async getData() {
                try {
                    const res = await axiosInstance.get('/api/models');
                    this.models = res.data.response.data;
                    this.filterData();
                } catch (error) {
                    console.error('Failed to fetch address types:', error);
                }
            },
            filterData() {
                if (this.collectionId) {
                    this.data = this.models.filter(m => m.carpet_collection_id === parseInt(this.collectionId));
                } else {
                    this.data = this.models;
                }
                if (this.modelValue) {
                    const found = this.data.find(ad => ad.id === this.modelValue);
                    if (found) {
                        this.value = found;
                    } else {
                        this.value = null;
                        this.$emit('update:modelValue', null);
                    }
                } else {
                    this.value = null;
                }
            },
            goToSettings() {

            }
        },
        mounted() {
            this.getData();
        },
        watch: {
            modelValue(newValue) {
                this.value = this.data.filter(ad => ad.id === newValue)[0]
            },
            collectionId: {
                handler() {
                    this.filterData();
                },
                immediate: true
            }
        }
    };
</script>
<style>
.multiselect--error .multiselect__tags {
    border: 1px solid red !important;  /* Use !important to ensure it overrides other styles */
}
</style>
