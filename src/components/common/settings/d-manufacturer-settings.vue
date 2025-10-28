<template>
    <div>
        <template v-if="isEditing">
            <select
                class="form-select"
                :value="currentValue"
                @change="updateValue($event.target.value)"
            >
                <option value="">Sélectionnez un fabricant</option>
                <option
                    v-for="manufacturer in manufacturers"
                    :key="manufacturer.id"
                    :value="manufacturer.id"
                >
                    {{ manufacturer.name || manufacturer.company || `Fabricant #${manufacturer.id}` }}
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
    name: 'DManufacturerSettings',
    props: {
        modelValue: {
            type: [Number, String, Object, null],
            default: null,
        },
        isEditing: {
            type: Boolean,
            default: false,
        },
        manufacturers: {
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
                return 'Aucun fabricant sélectionné';
            }

            if (typeof this.actualValue === 'object') {
                return this.actualValue.name
                    || this.actualValue.company
                    || `Fabricant #${this.actualValue.id}`;
            }

            const manufacturer = this.manufacturers.find(
                (item) => Number(item.id) === Number(this.actualValue),
            );

            if (manufacturer) {
                return manufacturer.name || manufacturer.company || `Fabricant #${manufacturer.id}`;
            }

            return `Fabricant #${this.actualValue}`;
        },
    },
    methods: {
        updateValue(value) {
            if (value === '') {
                this.$emit('update:modelValue', null);
                return;
            }

            const numericValue = Number(value);
            const selected = this.manufacturers.find((item) => Number(item.id) === numericValue);
            this.$emit('update:modelValue', selected || numericValue || null);
        },
    },
};
</script>
