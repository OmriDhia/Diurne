<template>
  <div class="form-group">
    <label v-if="label" class="form-label">{{ label }}</label>
    <select class="form-select" v-model="selectedOrder" @change="onChange" :disabled="loading">
      <option value="">Sélectionner une commande</option>
      <option v-for="order in filteredOrders" :key="order.id" :value="order.id">
        {{ order.reference }} - {{ formatDate(order.orderDate) }} - {{ formatCurrency(order.totalAmountTtc) }}
      </option>
    </select>
    <small v-if="error" class="text-danger">{{ error }}</small>
    <div v-if="loading" class="spinner-border spinner-border-sm text-primary mt-2"></div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axiosInstance from '../../config/http';
import { Helper } from '../../composables/global-methods';

const props = defineProps({
  modelValue: [String, Number],
  label: String,
  customerId: [String, Number],
  error: String
});

const emit = defineEmits(['update:modelValue']);

const selectedOrder = ref(props.modelValue);
const orders = ref([]);
const filteredOrders = ref([]);
const loading = ref(false);

const formatDate = (dateString) => {
  return Helper.FormatDate(dateString, 'DD/MM/YYYY');
};

const formatCurrency = (amount) => {
  return Helper.FormatNumber(amount) + ' €';
};

const fetchOrders = async () => {
  try {
    loading.value = true;
    const response = await axiosInstance.get('/api/carpetOrders', {
      params: {
        status: 'confirmed',
        orderBy: 'orderDate',
        order: 'desc'
      }
    });
    orders.value = response.data.map(order => ({
      id: order.id,
      reference: order.reference,
      orderDate: order.orderDate,
      totalAmountTtc: order.totalAmountTtc,
      customerId: order.customer?.id
    }));
    filterOrders();
  } catch (error) {
    console.error('Error fetching orders:', error);
  } finally {
    loading.value = false;
  }
};

const filterOrders = () => {
  if (props.customerId) {
    filteredOrders.value = orders.value.filter(order => 
      order.customerId == props.customerId
    );
  } else {
    filteredOrders.value = [...orders.value];
  }
};

const onChange = () => {
  emit('update:modelValue', selectedOrder.value);
};

watch(() => props.customerId, (newVal) => {
  if (newVal) {
    filterOrders();
  }
});

watch(() => props.modelValue, (newVal) => {
  selectedOrder.value = newVal;
});

onMounted(() => {
  fetchOrders();
});
</script>

<style scoped>
.form-select {
  padding: 0.375rem 2.25rem 0.375rem 0.75rem;
}
</style>