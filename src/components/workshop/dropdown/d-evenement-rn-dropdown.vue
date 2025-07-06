<template>
    <div class="row align-items-center">
        <div class="col-4" v-if="!showOnlyDropdown"><label class="form-label">Evènement<span class="required" v-if="required">*</span>:</label>
        </div>
        <div :class="{'col-md-8':!showOnlyDropdown,'col-md-12':showOnlyDropdown}">
            <select id="droit" :class="{ 'is-invalid': error, 'form-select': true }" :value="discount" @change="handleChange($event.target.value)">
                <option v-for="(prof, key) in discounts" :key="key" :value="prof.id">{{ prof.name }}</option>
            </select>
            <div v-if="error" class="invalid-feedback">{{ $t('Veuillez choisir un evenement stock.') }}</div>
        </div>
    </div>
</template>

<script>
import axiosInstance from '@/config/http';

export default {
    props: {
        modelValue: {
            type: [Number, null],
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
        showOnlyDropdown: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            discount: this.modelValue,
            discounts: [],
        };
    },
    methods: {
        handleChange(newValue) {
            newValue = parseInt(newValue);
            this.$emit("update:modelValue", newValue);
        },
        async getDiscounts() {
            try {
                const res = await axiosInstance.get('/api/historyEventTypes/category/2');
                this.discounts = res.data.response;
            } catch (error) {
                console.error('Failed to fetch history Event Types:', error);
            }
        },
    },
    mounted() {
        this.getDiscounts();
    },
    watch: {
        modelValue(newValue) {
            this.discount = parseInt(newValue);
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
