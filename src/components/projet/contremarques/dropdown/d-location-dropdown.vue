<template>
    <div class="row align-items-center pt-2">
        <div class="col-4" v-if="!showOnlyDropdown">
            <label class="form-label">Emplacement<span class="required" v-if="required">*</span>:</label>
        </div>
        <div :class="{ 'col-md-8': !showOnlyDropdown, 'col-md-12': showOnlyDropdown }">
            <multiselect
                :class="{ 'is-invalid': error }"
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
                this.$emit('update:modelValue', parseInt(value.location_id));
                console.log("location id : " , parseInt(value.location_id));
            },
            async getData() {
                try {
                    if (this.contremarqueId) {
                        this.data = await contremarqueService.getLocationsByContremarque(this.contremarqueId);

                        if (this.modelValue) {
                            this.value = this.data.filter((ad) => ad.location_id === this.modelValue)[0];
                        }
                        // console.log("data retrieved for locations : ", this.data);
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
                this.value = this.data.filter((ad) => ad.location_id === newValue)[0];
            },
            contremarqueId(contremarqueId) {
                this.getData();
            },
        },
    };
</script>
