<template>
    <div>
        <template v-if="isEditing">
            <select
                class="form-select"
                :value="currentValue"
                @change="updateValue($event.target.value)"
            >
                <option value="">Sélectionnez une qualité</option>
                <option
                    v-for="quality in qualities"
                    :key="quality.id"
                    :value="quality.id"
                >
                    {{ quality.name || `Qualité #${quality.id}` }}
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
    name: 'DQualitySettings',
    props: {
        modelValue: {
            type: [Number, String, Object, null],
            default: null,
        },
        isEditing: {
            type: Boolean,
            default: false,
        },
        qualities: {
            type: Array,
            default: () => [],
        },
    },
    emits: ['update:modelValue'],
    computed: {
        actualValue() {
            if (!this.modelValue) {
                return null;
            }

            if (typeof this.modelValue === 'object') {
                return this.modelValue.__v_raw || this.modelValue;
            }

            const parsed = Number(this.modelValue);
            return Number.isNaN(parsed) ? null : parsed;
        },
        currentValue() {
            if (!this.actualValue) {
                return '';
            }
            if (typeof this.actualValue === 'object') {
                return this.actualValue.id ?? '';
            }
            return this.actualValue;
        },
        displayValue() {
            if (!this.actualValue) {
                return 'Aucune qualité sélectionnée';
            }

            if (typeof this.actualValue === 'object') {
                return this.actualValue.name || `Qualité #${this.actualValue.id}`;
            }

            const quality = this.qualities.find(
                (item) => Number(item.id) === Number(this.actualValue),
            );

            if (quality) {
                return quality.name || `Qualité #${quality.id}`;
            }

            return `Qualité #${this.actualValue}`;
        },
    },
    methods: {
        updateValue(value) {
            if (value === '') {
                this.$emit('update:modelValue', null);
                return;
            }

            const numericValue = Number(value);
            const selected = this.qualities.find((item) => Number(item.id) === numericValue);
            this.$emit('update:modelValue', selected || numericValue || null);
        },
    },
};
</script>
