<template>
    <div>
        <template v-if="isEditing">
            <select id="collectionpolice" class="form-select" :value="modelValue?.id || modelValue"
                @input="updateValue($event.target.value)">
                <option value="">Sélectionnez un police</option>
                <option v-for="police in polices" :key="police.id" :value="police.id">
                    {{ police.label }}
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
            type: [Number, Object],
            required: true
        },
        isEditing: {
            type: Boolean,
            default: false
        },
        polices: {
            type: Array,
            default: () => []
        }
    },
    computed: {
        displayValue() {
            if (!this.modelValue) return 'Aucun policee sélectionné';

            const actualValue = this.modelValue.__v_raw || this.modelValue;

            if (typeof actualValue === 'object') {
                return actualValue['label'] || 'Aucun policee sélectionné';
            }

            const police = this.polices.find(g => g.id === actualValue);

            return police ? police.label : 'Aucun policee sélectionné';
        }
    },
    methods: {
        updateValue(value) {
            const selected = this.polices.find(g => g.id === parseInt(value));
            this.$emit('update:modelValue', selected || value);
        }
    }
};
</script>