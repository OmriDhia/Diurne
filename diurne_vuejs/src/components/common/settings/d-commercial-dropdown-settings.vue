<template>
  <div>
    <template v-if="isEditing">
      <select
        id="commercial"
        class="form-select"
        :value="getCurrentValue"
        @input="updateValue($event.target.value)"
      >
        <option value="">Sélectionnez un commercial</option>
        <option
          v-for="commercial in commercials"
          :key="commercial.user_id"
          :value="commercial.user_id"
        >
          {{ displayCommercial(commercial) }}
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
    commercials: {
      type: Array,
      default: () => []
    }
  },
  computed: {
    getCurrentValue() {
      if (!this.modelValue) return '';
      
      const rawValue = toRaw(this.modelValue);
      
      if (typeof rawValue === 'object') {
        return rawValue.user_id || rawValue.commercialId || '';
      }
      return rawValue;
    },
    displayValue() {
      if (!this.modelValue) return 'Aucun commercial sélectionné';
      
      const rawValue = toRaw(this.modelValue);
      if (typeof rawValue === 'object') {
        if (rawValue.commercialName) {
          return rawValue.commercialName;
        }
        if (rawValue.firstname || rawValue.lastname) {
          return this.displayCommercial(rawValue);
        }
        return 'Aucun commercial sélectionné';
      }
      
      const commercial = this.commercials.find(c => c.user_id == rawValue);
      return commercial ? this.displayCommercial(commercial) : 'Commercial #' + rawValue;
    }
  },
  methods: {
    displayCommercial(commercial) {
      if (!commercial) return '';
      
      const rawCommercial = toRaw(commercial);
      
      if (rawCommercial.firstname && rawCommercial.lastname) {
        return `${rawCommercial.firstname} ${rawCommercial.lastname}`;
      }
      if (rawCommercial.commercialName) {
        return rawCommercial.commercialName;
      }
      if (rawCommercial.name) {
        return rawCommercial.name;
      }
      return '';
    },
    updateValue(value) {
      const selected = this.commercials.find(c => c.user_id == value);
      this.$emit('update:modelValue', selected || value);
    }
  }
};
</script>