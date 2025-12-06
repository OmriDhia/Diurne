<template>
    <div class="row align-items-center">
        <div class="col-4">
            <label class="form-label">Mode de règlement<span class="required" v-if="required">*</span>:</label>
        </div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error }"
                v-model="selected"
                :options="regulations"
                placeholder="Mode de règlement"
                track-by="id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
                @update:model-value="handleChange"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t('Le tarif d\u2019expédition est obligatoire.') }}</div>
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
                regulations: [],
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', value ? value.id : null);
            },
            async fetchRegulations() {
                try {
                    const res = await axiosInstance.get('/api/regulations');
                    this.regulations = res.data.response;
                    this.selected = this.regulations.find((t) => t.id === this.modelValue) || null;
                } catch (error) {
                    console.error('Failed to fetch regulations expeditions:', error);
                }
            },
        },
        mounted() {
            this.fetchRegulations();
        },
        watch: {
            modelValue(newVal) {
                this.selected = this.regulations.find((t) => t.id === newVal) || null;
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
