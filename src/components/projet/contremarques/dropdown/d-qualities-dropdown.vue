<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">Qualité<span class="required" v-if="required">*</span>:</label>
        </div>
        <div class="col-8">
            <multiselect
                :class="{ 'multiselect--error': error }"
                :model-value="value"
                :options="data"
                placeholder="Qualité"
                track-by="id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
                @update:model-value="handleChange($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champs qualité est obligatoire.") }}</div>

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
            errorQuality: {
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
                    const res = await axiosInstance.get('/api/qualities');
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
<style>
.multiselect--error .multiselect__tags {
    border: 1px solid red !important;  /* Use !important to ensure it overrides other styles */
}
</style>