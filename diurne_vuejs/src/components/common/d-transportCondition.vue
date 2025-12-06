<template>
    <div class="row align-items-center">
        <div class="col-4"><label for="droit" class="form-label">Conditions de transports<span class="required" v-if="required">*</span>:</label></div>
        <div class="col-8">
            <select
                id="droit"
                :class="{ 'is-invalid': error, 'form-select': true }"
                :value="discount"
                @input="handleChange($event.target.value)"
            >
                <option v-for="(prof, key) in filteredDiscounts" :key="key" :value="prof.id">{{ prof.name }}</option>
            </select>
            <div v-if="error" class="invalid-feedback">{{ $t('Conditions de transports est abligatoire.') }}</div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../config/http';

    export default {
        props: {
            modelValue: {
                type: [Number, null],
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
            languageId: {
                type: [Number, null],
                required: true
            }
        },
        data() {
            return {
                discount: this.modelValue,
                discounts: []
            };
        },
        computed: {
            filteredDiscounts() {
                if (!this.languageId) {
                    return [];
                }
                return this.discounts
                    .filter(tc => tc.languages && tc.languages.some(l => l.lang_id === this.languageId))
                    .map(tc => {
                        const lang = tc.languages.find(l => l.lang_id === this.languageId);
                        return { id: tc.id, name: lang ? lang.label : tc.name };
                    });
            }
        },
        methods: {
            handleChange(newValue) {
                const value = newValue ? parseInt(newValue) : null;
                this.discount = value;
                this.$emit('update:modelValue', value);
            },
            async getDiscounts() {
                try {
                    const res = await axiosInstance.get('/api/transportCondition');
                    this.discounts = res.data.response;
                } catch (error) {
                    console.error('Failed to fetch transport condition:', error);
                }
            }
        },
        mounted() {
            this.getDiscounts();
        },
        watch: {
            modelValue(newValue) {
                this.discount = newValue ? parseInt(newValue) : null;
            },
            languageId() {
                if (!this.filteredDiscounts.find(tc => tc.id === this.discount)) {
                    this.discount = null;
                    this.$emit('update:modelValue', null);
                }
            }
        }
    };
</script>
<style scoped>
    .invalid-feedback{
        display: flex !important;
        font-size: 10px;
    }
</style>
