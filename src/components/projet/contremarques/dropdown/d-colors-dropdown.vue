<template>
    <div class="row align-items-center pt-2">
        <div class="col-4" v-if="!hideLabel"><label class="form-label">Couleur<span class="required" v-if="required">*</span>:</label>
        </div>
        <div :class="{'col-8': !hideLabel,'col-12': hideLabel}">
            <multiselect
                :class="{ 'is-invalid': error}"
                :model-value="value"
                :options="data"
                placeholder="Couleur"
                track-by="id"
                label="reference"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @update:model-value="handleChange($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champs couleur est abligatoire.") }}</div>
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
            hideLabel: {
                type: Boolean,
                default: false
            },
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
                    const res = await axiosInstance.get('/api/color');
                    this.data = res.data.response;
                    
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
