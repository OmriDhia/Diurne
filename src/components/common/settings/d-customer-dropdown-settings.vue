<template>
  <div>
    <template v-if="isEditing">
      <select
        id="customer"
        class="form-select"
        :value="getCurrentValue"
        @input="updateValue($event.target.value)"
      >
        <option value="">Sélectionnez {{ isCommercial ? 'un commercial' : 'un client' }}</option>
        <option
          v-for="item in customers"
          :key="item.id"
          :value="item.id"
        >
          {{ displayEntity(item) }}
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
    idKey: {
      type: String,
      default: 'id'
    },
    nameKey: {
      type: String,
      default: 'name'
    },
    customers: {
      type: Array,
      default: () => []
    },
    isCommercial: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    getCurrentValue() {
      if (!this.modelValue) return '';
      
      const rawValue = toRaw(this.modelValue);
      
      if (typeof rawValue === 'object') {
        return rawValue[this.idKey] || 
              (this.isCommercial ? rawValue.commercialId : rawValue.customerId) || 
              '';
      }
      return rawValue;
    },
    displayValue() {
      if (!this.modelValue) return this.isCommercial ? 'Aucun commercial sélectionné' : 'Aucun client sélectionné';
      
      const rawValue = toRaw(this.modelValue);
      
      // If value is an object with name properties
      if (typeof rawValue === 'object') {
        if (rawValue.customerName || rawValue.commercialName) {
          return rawValue.commercialName ? rawValue.commercialName : rawValue.customerName;
        }
        return this.displayEntity(rawValue) || 
              (rawValue.commercialName ? 'Aucun commercial sélectionné' : 'Aucun client sélectionné');
      }
      
      // If value is an ID
      const entity = this.customers.find(c => c[this.idKey] == rawValue);
      return entity ? this.displayEntity(entity) : 
            (rawValue.commercialName ? 'Commercial #' : 'Client #') + rawValue;
    }
  },
  methods: {
    displayEntity(entity) {
      if (!entity) return '';
      
      const rawEntity = toRaw(entity);
      
      // Check different possible name properties
      if (rawEntity[this.nameKey]) return rawEntity[this.nameKey];
      if (rawEntity.customerName) return rawEntity.customerName;
      if (rawEntity.commercialName) return rawEntity.commercialName;
      if (rawEntity.customer) return rawEntity.customer;
      if (rawEntity.social_reason) {
        return `${rawEntity.social_reason}${rawEntity.code ? ` (${rawEntity.code})` : ''}`.trim();
      }
      return '';
    },
    updateValue(value) {
      const selected = this.customers.find(c => c[this.idKey] == value);
      this.$emit('update:modelValue', selected || value);
    }
  }
};
</script>