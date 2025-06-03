<template>
  <div>
    <template v-if="isEditing">
      <select
        id="paymentType"
        class="form-select"
        :value="getCurrentValue"
        @input="updateValue($event.target.value)"
      >
        <option value="">Sélectionnez un type de paiement</option>
        <option
          v-for="type in paymentTypes"
          :key="type.id"
          :value="type.id"
        >
          {{ type.label }}
        </option>
      </select>
    </template>
    <template v-else>
      {{ displayValue }}
    </template>
  </div>
</template>

<script>
import { toRaw } from 'vue';

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
    paymentTypes: {
      type: Array,
      default: () => []
    }
  },
  computed: {
    getCurrentValue() {
      if (!this.modelValue) return '';
      
      const rawValue = toRaw(this.modelValue);
      
      if (typeof rawValue === 'object') {
        return rawValue.id || '';
      }
      return rawValue;
    },
    displayValue() {
      if (!this.modelValue) return 'Aucun type sélectionné';
      
      const rawValue = toRaw(this.modelValue);
      
      // If value is an object with label property
      if (typeof rawValue === 'object') {
        return rawValue.label || 'Aucun type sélectionné';
      }
      
      // If value is an ID
      const type = this.paymentTypes.find(t => t.id == rawValue);
      return type ? type.label : 'Type #' + rawValue;
    }
  },
  methods: {
    updateValue(value) {
      const selected = this.paymentTypes.find(t => t.id == value);
      this.$emit('update:modelValue', selected || value);
    }
  }
};
</script>