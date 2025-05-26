<template>
    <div class="row align-items-center">
        <div class="col-4" v-if="!showOnlyDropdown">
            <label class="form-label">Emplacement<span class="required" v-if="required">*</span>:</label>
        </div>
        <div :class="{ 'col-md-8': !showOnlyDropdown, 'col-md-12': showOnlyDropdown }">
            <multiselect
                :class="{ 'multiselect--error': error }"
                :model-value="value"
                :options="data"
                placeholder="Emplacement"
                track-by="location_id"
                label="description"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
                @update:model-value="handleChange($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t('Le champs emplacement est obligatoire.') }}</div>
        </div>
    </div>
</template>

<script>
    import contremarqueService from '../../../../Services/contremarque-service';
    import Multiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';

    export default {
        components: {
            Multiselect,
        },
        props: {
            modelValue: {
                type: [Number, String, null],
                required: true,
            },
            contremarqueId: {
                type: Number,
                required: true,
            },
            error: {
                type: String,
                default: '',
            },
            required: {
                type: Boolean,
                default: false,
            },
            disabled: {
                type: Boolean,
                default: false,
            },
            showOnlyDropdown: {
                type: Boolean,
                default: false,
            },
        },
        data() {
            return {
                value: null,
                data: [],
            };
        },
        methods: {
            handleChange(value) {
                if (value && value.location_id !== undefined) {
                    this.$emit('update:modelValue', parseInt(value.location_id));
                    console.log("location id : ", parseInt(value.location_id));
                } else {
                    this.$emit('update:modelValue', null); 
                }
            },
            async getData() {
                try {
                    if (this.contremarqueId) {
                        this.data = await contremarqueService.getLocationsByContremarque(this.contremarqueId);
                        
                        if (this.modelValue) {
                            this.value = this.data.find((ad) => ad.location_id === this.modelValue) || null;
                        }
                    }
                } catch (error) {
                    console.error('Failed to fetch address types:', error);
                }
            },
            goToSettings() {},
        },
        mounted() {
            this.getData();
        },
        watch: {
            modelValue(newValue) {
                if (this.data.length > 0) {
                    this.value = this.data.find((ad) => ad.location_id === newValue) || null;
                }
            },
            contremarqueId(contremarqueId) {
                this.getData();
            },
        },
    };
</script>
<style>
.multiselect--error .multiselect__tags {
    border: 1px solid red !important;  /* Use !important to ensure it overrides other styles */
}
</style>
