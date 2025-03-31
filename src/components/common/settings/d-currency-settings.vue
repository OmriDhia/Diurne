<template>
    <div>
        <template v-if="isEditing">
            <select 
                id="currency" 
                class="form-select" 
                :value="modelValue?.id || modelValue"
                @input="updateValue($event.target.value)">
                <option value="">Sélectionnez une devise</option>
                <option 
                    v-for="currency in currencies" 
                    :key="currency.id" 
                    :value="currency.id">
                    {{ currency.name }}
                </option>
            </select>
        </template>
        <template v-else>
            {{ displayValue }}
        </template>
    </div>
</template>


<script>
export default {
    props: {
        modelValue: {
            type: [Number, Object, String],
            required: true
        },
        isEditing: {
            type: Boolean,
            default: false
        },
        idKey: {
            type: String,
            default: 'id'
        },
        nameKey: {
            type: String,
            default: 'name'
        },
        currencies: {
            type: Array,
            default: () => []
        }
    },
    computed: {
        displayValue() {
            if (!this.modelValue) return 'Aucune devise sélectionnée';
            if (typeof this.modelValue === 'object') {
                return this.modelValue[this.nameKey] || 'Aucune devise sélectionnée';
            }
            const currency = this.currencies.find(c => c.id === this.modelValue);
            return currency ? currency.name : 'Aucune devise sélectionnée';
        }
    },
    methods: {
        updateValue(value) {
            const selected = this.currencies.find(c => c.id === parseInt(value));
            this.$emit('update:modelValue', selected || parseInt(value));
        }
    }
};
</script>