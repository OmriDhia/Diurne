<template>
  <div>
    <select class="form-select" v-model="selectedId" @change="onSelect">
      <option value="" disabled>Choisir une facture</option>
      <option v-for="invoice in invoices" :key="invoice.id" :value="invoice.id">
        {{ invoice.reference || ('Facture #' + invoice.id) }}
      </option>
    </select>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axiosInstance from '../../config/http';

const props = defineProps({
  modelValue: [Object, Number, String, null],
  label: {
    type: String,
    default: ''
  }
});
const emit = defineEmits(['update:modelValue', 'selected']);

const invoices = ref([]);
const selectedId = ref(props.modelValue?.id || props.modelValue || '');

const fetchInvoices = async () => {
  try {
    const response = await axiosInstance.get('/api/customerInvoices');
    console.log('CustomerInvoices API response:', response.data);
    if (Array.isArray(response.data.data)) {
      invoices.value = response.data.data;
    } else if (Array.isArray(response.data.response)) {
      invoices.value = response.data.response;
    } else if (Array.isArray(response.data)) {
      invoices.value = response.data;
    } else if (Array.isArray(response.data['hydra:member'])) {
      invoices.value = response.data['hydra:member'];
    } else {
      invoices.value = [];
    }
  } catch (e) {
    invoices.value = [];
    window.showMessage('Erreur lors du chargement des factures', 'error');
  }
};

const onSelect = () => {
  const invoice = invoices.value.find(inv => inv.id == selectedId.value);
  emit('update:modelValue', invoice);
  emit('selected', invoice);
};

watch(() => props.modelValue, (val) => {
  if (val && typeof val === 'object' && val.id) {
    selectedId.value = val.id;
  } else {
    selectedId.value = val || '';
  }
});

onMounted(fetchInvoices);
</script>

<style scoped>
.form-select {
  min-width: 180px;
}
</style> 