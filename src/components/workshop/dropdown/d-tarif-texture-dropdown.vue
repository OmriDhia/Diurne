<template>
    <div class="row align-items-center">
        <div class="col-4">
            <label for="droit" class="form-label"> Anné grille tarif<span class="required" v-if="required">*</span>: </label>
        </div>
        <div class="col-8">
            <select id="droit" :class="{ 'is-invalid': error, 'form-select': true }" :value="discount" @change="handleChange($event.target.value)">
                <option v-for="(prof, key) in discounts" :key="key" :value="prof.id">{{ prof.year }}</option>
            </select>
        </div>
        <div v-if="error" class="invalid-feedback">{{ $t('tarif texture est obligatoire.') }}</div>
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
                const res = await axiosInstance.get('/api/tarifGroups?page=1&itemsPerPage=100');
                this.discounts = res.data.response.data;
            } catch (error) {
                console.error('Failed to fetch tarifGroups:', error);
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
