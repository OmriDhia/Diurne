<template>
    <div>
        <template v-if="isEditing">
            <select id="collectiongroup" class="form-select" :value="modelValue?.id || modelValue"
                @input="updateValue($event.target.value)">
                <option value="">Sélectionnez un groupe de tarif</option>
                <option v-for="group in tarifGroups" :key="group.id" :value="group.id">
                    Group : {{ group.year }}
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
        tarifGroups: {
            type: Array,
            default: () => []
        }
    },
    computed: {
        displayValue() {

            if (!this.modelValue) return 'Aucun groupe sélectionné';

            const actualValue = this.modelValue.__v_raw || this.modelValue;


            if (typeof actualValue === 'object') {
                return actualValue['year'] || 'Aucun groupe sélectionné';
            }

            const group = this.tarifGroups.find(g => g.id === actualValue);

            return group ? group.year : 'Aucun groupe sélectionné aaaa';
        }
    },
    methods: {
        updateValue(value) {
            const selected = this.tarifGroups.find(g => g.id === parseInt(value));
            this.$emit('update:modelValue', selected || value);
        }
    }
};
</script>