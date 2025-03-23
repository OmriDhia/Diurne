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
            }
        },
        data() {
            return {
                value: null,
                data: []
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value.id));
            },
            async getData() {
                try {
                    const res = await axiosInstance.get('/api/models');
                    this.data = res.data.response.data;

                    if(this.modelValue){
                        this.value = this.data.filter(ad => ad.id === this.modelValue)[0]
                    }
                } catch (error) {
                    console.error('Failed to fetch address types:', error);
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
            }
        }
    };
</script>
<style>
.multiselect--error .multiselect__tags {
    border: 1px solid red !important;  /* Use !important to ensure it overrides other styles */
}
</style>
