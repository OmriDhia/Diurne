<template>
    <div>
        <template v-if="isEditing">
            <select id="collectionspecialshape" class="form-select" :value="modelValue?.id || modelValue"
                @input="updateValue($event.target.value)">
                <option value="">Sélectionnez un special shapes</option>
                <option v-for="specialshape in specialShapes" :key="specialshape.id" :value="specialshape.id">
                    SpecialShape : {{ specialshape.name }}
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
        specialShapes: {
            type: Array,
            default: () => []
        }
    },
    computed: {
        displayValue() {
            if (!this.modelValue) return 'Aucun special shapes sélectionné';

            const actualValue = this.modelValue.__v_raw || this.modelValue;

            if (typeof actualValue === 'object') {
                return actualValue['name'] || 'Aucun special shapes sélectionné';
            }

            const specialshape = this.specialShapes.find(g => g.id === actualValue);

            return specialshape ? specialshape.name : 'Aucun special shapes sélectionné';
        }
    },
    methods: {
        updateValue(value) {
            const selected = this.specialShapes.find(g => g.id === parseInt(value));
            this.$emit('update:modelValue', selected || value);
        }
    }
};
</script>