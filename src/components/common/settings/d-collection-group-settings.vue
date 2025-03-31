<template>
    <div>
        <template v-if="isEditing">
            <select id="collectiongroup" class="form-select" :value="modelValue?.id || modelValue"
                @input="updateValue($event.target.value)">
                <option value="">Sélectionnez un groupe de collecte</option>
                <option v-for="group in collectionGroups" :key="group.id" :value="group.id">
                    Group : {{ group.groupNumber }}
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
        collectionGroups: {
            type: Array,
            default: () => []
        }
    },
    computed: {
        displayValue() {
            if (!this.modelValue) return 'Aucun groupe sélectionné';

            const actualValue = this.modelValue.__v_raw || this.modelValue;

            if (typeof actualValue === 'object') {
                return actualValue['groupNumber'] || 'Aucun groupe sélectionné';
            }

            const group = this.collectionGroups.find(g => g.id === actualValue);

            return group ? group.groupNumber : 'Aucun groupe sélectionné aaaa';
        }
    },
    methods: {
        updateValue(value) {
            const selected = this.collectionGroups.find(g => g.id === parseInt(value));
            this.$emit('update:modelValue', selected || value);
        }
    }
};
</script>