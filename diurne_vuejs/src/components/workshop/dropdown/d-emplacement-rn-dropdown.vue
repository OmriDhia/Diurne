<template>
    <div class="row align-items-center">

        <div class="col-4" v-if="!showOnlyDropdown">
            <label class="form-label">
                Emplacement<span class="required" v-if="required">*</span>:
            </label>
        </div>
        <div :class="{'col-md-8':!showOnlyDropdown,'col-md-12':showOnlyDropdown}">
            <select id="droit" :class="{ 'is-invalid': error, 'form-select': true }" :value="discount" @change="handleChange($event.target.value)" :disabled="disabled"> 
                <option v-for="(prof, key) in discounts" :key="key" :value="prof.id">{{ prof.name }}</option>
            </select>
        </div>
        <div v-if="error" class="invalid-feedback">{{ $t('Emplacement rn est obligatoire.') }}</div>
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
        disabled: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            discount: this.modelValue,
            discounts: [
                {id: 1, name: "Gentilly"},
                {id: 2, name: "Jacob 50"},
            ],
        };
    },
    methods: {
        handleChange(newValue) {
            newValue = parseInt(newValue);
            this.$emit("update:modelValue", newValue);
        },
    },
    mounted() {
        
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
