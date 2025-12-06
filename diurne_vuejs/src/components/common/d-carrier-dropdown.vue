<template>
    <div class="row align-items-center">
        <div class="col-4">
            <label class="form-label">Transporteur<span class="required" v-if="required">*</span>:</label>
        </div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error }"
                v-model="selected"
                :options="carriers"
                placeholder="Transporteur"
                track-by="id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
                @update:model-value="handleChange"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t('Le transporteur est obligatoire.') }}</div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../config/http';
    import Multiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';

    export default {
        components: { Multiselect },
        props: {
            modelValue: {
                type: [Number, Object, null],
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
        },
        data() {
            return {
                selected: null,
                carriers: [],
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', value ? value.id : null);
            },
            async fetchCarriers() {
                try {
                    const res = await axiosInstance.get('/api/carrier');
                    this.carriers = res.data.response.data;
                    this.selected = this.carriers.find((t) => t.id === this.modelValue) || null;
                } catch (error) {
                    console.error('Failed to fetch carriers:', error);
                }
            },
        },
        mounted() {
            this.fetchCarriers();
        },
        watch: {
            modelValue(newVal) {
                this.selected = this.carriers.find((t) => t.id === newVal) || null;
            },
        },
    };
</script>

<style scoped>
    .invalid-feedback {
        display: flex !important;
        font-size: 10px;
    }
</style>
