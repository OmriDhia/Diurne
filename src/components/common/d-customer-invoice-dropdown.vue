<template>
  <div class="form-group">
    <label v-if="label" class="form-label">{{ label }}</label>
    <multiselect
      v-model="selectedInvoice"
      :options="filteredInvoices"
      :multiple="false"
      :searchable="true"
      :placeholder="placeholder"
      track-by="id"
      label="reference"
      :disabled="loading"
      @update:model-value="onSelect"
      @search-change="handleSearch"
      :class="{ 'is-invalid': error }"
    >
      <template #singlelabel="{ value }">
        <div class="multiselect-single-label">
          {{ value.reference || ('Facture #' + value.id) }} - {{ formatDate(value.createdAt) }} - {{ formatCurrency(value.totalAmountTtc) }}
        </div>
      </template>
      <template #option="{ option }">
        <div>
          <strong>{{ option.reference || ('Facture #' + option.id) }}</strong><br />
          <small class="text-muted">
            {{ formatDate(option.createdAt) }} |
            {{ formatCurrency(option.totalAmountTtc) }} |
            {{ option.customerName || 'N/A' }}
          </small>
        </div>
      </template>
      <template #afterList>
        <div class="row justify-content-between align-items-center p-1">
          <div class="col-6 text-start">
            <a href="#" @click.prevent="prevPage" class="w-100 font-size-0-9" v-if="currentPage > 1">« précédent</a>
          </div>
          <div class="col-6 text-end">
            <a href="#" @click.prevent="nextPage" class="w-100 font-size-0-9" v-if="currentPage < totalPages">suivant »</a>
          </div>
        </div>
      </template>
    </multiselect>
    <div v-if="error" class="invalid-feedback">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';
import axiosInstance from '../../config/http';
import { Helper } from '../../composables/global-methods';

const props = defineProps({
  modelValue: [Object, Number, String, null],
  label: {
    type: String,
    default: ''
  },
  error: String,
  placeholder: {
    type: String,
    default: 'Sélectionner une facture'
  }
});
const emit = defineEmits(['update:modelValue', 'selected']);

const invoices = ref([]);
const selectedInvoice = ref(props.modelValue?.id ? props.modelValue : null);
const currentPage = ref(1);
const itemsPerPage = ref(20);
const totalInvoices = ref(0);
const searchQuery = ref('');
const loading = ref(false);

const formatDate = (dateString) => {
  return Helper.FormatDate(dateString, 'DD/MM/YYYY');
};
const formatCurrency = (amount) => {
  return Helper.FormatNumber(amount) + ' €';
};

const filteredInvoices = computed(() => {
  return invoices.value.map((invoice) => ({
    ...invoice,
    reference: invoice.reference || ('Facture #' + invoice.id),
    createdAt: invoice.createdAt,
    totalAmountTtc: invoice.totalAmountTtc,
    customerName: invoice.customer?.customerName || 'N/A',
  }));
});

const totalPages = computed(() => {
  return Math.ceil(totalInvoices.value / itemsPerPage.value);
});

const fetchInvoices = async () => {
  try {
    loading.value = true;
    let url = `/api/customerInvoices?page=${currentPage.value}&itemsPerPage=${itemsPerPage.value}`;
    if (searchQuery.value) {
      url += `&filter[reference]=${encodeURIComponent(searchQuery.value)}`;
    }
    const response = await axiosInstance.get(url);
    let data = response.data.response || response.data.data || response.data['hydra:member'] || [];
    if (!Array.isArray(data)) data = [];
    invoices.value = data;
    totalInvoices.value = response.data.count || response.data.total || data.length;
  } catch (e) {
    invoices.value = [];
    window.showMessage('Erreur lors du chargement des factures', 'error');
  } finally {
    loading.value = false;
  }
};

const onSelect = (invoice) => {
  emit('update:modelValue', invoice);
  emit('selected', invoice);
};

const handleSearch = (query) => {
  searchQuery.value = query;
  currentPage.value = 1;
  fetchInvoices();
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    fetchInvoices();
  }
};
const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    fetchInvoices();
  }
};

watch(() => props.modelValue, (val) => {
  if (val && typeof val === 'object' && val.id) {
    selectedInvoice.value = val;
  } else {
    selectedInvoice.value = null;
  }
});

onMounted(fetchInvoices);
</script>

<style scoped>
.form-group {
  margin-bottom: 1rem;
}
.multiselect {
  min-width: 180px;
}
.invalid-feedback {
  display: flex !important;
  font-size: 10px;
}
</style> 