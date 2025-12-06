<template>
    <div class="row align-items-center">
        <div class="col-4">
            <label for="droit" class="form-label"> Année grille tarif<span class="required" v-if="required">*</span>:
            </label>
        </div>
        <div class="col-8">
            <select id="droit" :class="{ 'is-invalid': !!error, 'form-select': true }" :value="discountValue"
                    @change="handleChange($event.target.value)">
                <option value="">--</option>
                <option v-for="(prof, key) in discounts" :key="prof.id" :value="String(prof.id)">{{ prof.year }}
                </option>
            </select>
        </div>
        <div v-if="error" class="invalid-feedback">{{ error }}</div>
    </div>
</template>

<script>
    import axiosInstance from '@/config/http';

    export default {
        props: {
            modelValue: {
                type: [Number, String, null],
                required: false
            },
            error: {
                type: String,
                default: ''
            },
            required: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                discounts: []
            };
        },
        computed: {
            discountValue() {
                // return string value for the select
                if (this.modelValue === null || this.modelValue === undefined) return '';
                return String(this.modelValue);
            }
        },
        methods: {
            handleChange(newValue) {
                // Accept empty value
                if (newValue === '') {
                    this.$emit('update:modelValue', null);
                    return;
                }
                const intVal = parseInt(newValue, 10);
                this.$emit('update:modelValue', Number.isNaN(intVal) ? newValue : intVal);
            },
            async getDiscounts() {
                try {
                    const res = await axiosInstance.get('/api/tarifGroups?page=1&itemsPerPage=100');
                    this.discounts = res.data.response?.data || res.data?.data || [];
                } catch (error) {
                    console.error('Failed to fetch tarifGroups:', error);
                }
            }
        },
        mounted() {
            this.getDiscounts();
        },
        watch: {
            modelValue(newValue) {
                // no local state needed, computed reads modelValue
            }
        }
    };
</script>
<style scoped>
    .invalid-feedback {
        display: flex !important;
        font-size: 10px;
    }
</style>
