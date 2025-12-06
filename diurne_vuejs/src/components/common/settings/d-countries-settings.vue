<template>
    <div>
        <template v-if="isEditing">
            <multiselect
                v-model="selectedCountry"
                :options="countries"
                placeholder="Sélectionnez un pays"
                label="name"
                track-by="country_id"
                @update:modelValue="handleChange"
            ></multiselect>
        </template>
        <template v-else>
            {{ displayValue }}
        </template>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';

export default {
    components: { Multiselect },
    props: {
        modelValue: {
            type: [Number, Object, String],
            required: true
        },
        isEditing: {
            type: Boolean,
            default: false
        },
        countries: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            selectedCountry: null
        };
    },
    computed: {
        displayValue() {
            if (!this.modelValue) return 'Aucun pays sélectionné';
            if (typeof this.modelValue === 'object') {
                return this.modelValue.name || 'Aucun pays sélectionné';
            }
            const country = this.countries.find(c => c.country_id === this.modelValue);
            return country ? country.name : 'Aucun pays sélectionné';
        }
    },
    methods: {
        handleChange(value) {
            this.$emit('update:modelValue', value ? value : null);
        },
        updateSelectedCountry() {
            if (!this.modelValue) {
                this.selectedCountry = null;
                return;
            }
            
            if (typeof this.modelValue === 'object') {
                this.selectedCountry = this.modelValue;
                return;
            }
            
            this.selectedCountry = this.countries.find(c => c.country_id === this.modelValue) || null;
        }
    },
    watch: {
        modelValue(newVal) {
            this.updateSelectedCountry();
        },
        countries() {
            this.updateSelectedCountry();
        }
    },
    mounted() {
        this.updateSelectedCountry();
    }
};
</script>
