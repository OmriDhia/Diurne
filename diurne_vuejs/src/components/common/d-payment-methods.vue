<template>
  <div class="form-group">
    <label v-if="showLabel && finalLabel" :for="inputName" class="form-label">
      {{ finalLabel }} <span v-if="required" class="text-danger">*</span>
    </label>
    <select
      :id="inputName"
      class="form-select"
      :class="{ 'is-invalid': error }"
      v-model="selected"
      @change="handleChange"
      :disabled="disabled || loading"
      :required="required"
    >
      <option :value="nullValue" disabled>{{ placeholderText }}</option>
      <option v-for="method in paymentMethodsList" :key="method.id" :value="method.id">
        {{ method.label }}
      </option>
    </select>
    <div v-if="error" class="invalid-feedback">{{ error }}</div>
    <div v-if="loading" class="spinner-border spinner-border-sm text-primary mt-1" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from "vue";
import axiosInstance from '../../config/http'; 

const props = defineProps({
    modelValue: [String, Number, null], 
    label: {
        type: String,
        default: 'Type'
    },
    name: { 
        type: String,
        default: 'payment_method_id'
    },
    error: String,
    required: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    },
    placeholder: {
        type: String,
        default: '' // Custom placeholder text
    },
    showLabel: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(["update:modelValue"]);

const selected = ref(props.modelValue);
const paymentMethodsList = ref([]);
const loading = ref(false);

// Determine the actual value for the "disabled" placeholder option
const nullValue = computed(() => {
    // For a numeric ID, null is a good default. For string ID, empty string might be better.
    // Assuming IDs are numbers here.
    return null;
});


const finalLabel = computed(() => props.label);
const inputName = computed(() => props.name || 'payment_method_id_select');
const placeholderText = computed(() => {
    if (props.placeholder) return props.placeholder;
    return `Sélectionner un ${finalLabel.value.toLowerCase()}`;
});


const fetchPaymentMethods = async () => {
    if (loading.value) return;
    loading.value = true;
    try {
        const response = await axiosInstance.get("/api/payment-type");
        // Correctly access the array
        if (response.data && response.data.response && Array.isArray(response.data.response)) {
            paymentMethodsList.value = response.data.response.map((method) => ({
                id: method.id,
                label: method.label, // The API uses 'label' for the text
            }));
        } else {
            console.error("Invalid data structure received for payment methods:", response.data);
            paymentMethodsList.value = [];
        }
    } catch (err) {
        console.error("Error fetching payment methods:", err);
        paymentMethodsList.value = [];
        // Optionally show user message: window.showMessage('Erreur chargement modes paiement', 'error');
    } finally {
        loading.value = false;
    }
};

const handleChange = (event) => {
  // Ensure the emitted value is correctly typed if it's supposed to be a number
  const value = event.target.value;
  const numericValue = parseInt(value, 10);
  emit("update:modelValue", isNaN(numericValue) || value === "" || value === null ? null : numericValue);
};


watch(
  () => props.modelValue,
  (newVal) => {
    selected.value = newVal;
  }
);

onMounted(() => {
  fetchPaymentMethods();
  // Initialize selected value if modelValue is present
  if (props.modelValue !== undefined) {
      selected.value = props.modelValue;
  } else {
      selected.value = nullValue.value; // Set to placeholder value
  }
});
</script>

<style scoped>
.form-select {
  /* Default padding is usually fine, this was specific in your example */
  /* padding: 0.375rem 2.25rem 0.375rem 0.75rem; */
}
.form-label {
    font-weight: 500; /* Common styling for labels */
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}
.invalid-feedback {
    display: block; /* Make sure it shows */
    font-size: 0.8em;
}
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}
</style>