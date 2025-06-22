<template>
  <div class="layout-px-spacing mt-4">
    <d-page-title icon="dollar-sign" :title="'Reglement'"></d-page-title>

    <div class="row layout-top-spacing mt-3 p-2">
      <div class="panel br-6 p-2">
        
        <div class="row d-flex justify-content-center align-items-start p-2">
          <div class="col-md-6 col-sm-12">
            <div class="row">
              <d-input label="Client" v-model="filter.customer"></d-input>
            </div>
            <div class="row">
              <d-input label="Commercial" v-model="filter.commercial"></d-input>
            </div>
            <div class="row">
              <d-input label="Contermarque" v-model="filter.contremarque"></d-input>
            </div>
            <div class="row">
              <d-input label="Devis" v-model="filter.devis"></d-input>
            </div>
          </div>
          <div class="col-md-6 col-sm-12">
            <div class="row">
              <d-input label="Prescripteur" v-model="filter.prescriptor"></d-input>
            </div>
            <div class="row">
              <d-input label="Commande" v-model="filter.commande"></d-input>
            </div>
            <div class="row">
              <d-input label="Montant" v-model="filter.amount"></d-input>
            </div>
            <div class="row mt-2 justify-content-center">
              <div class="col-auto" v-if="filterActive">
                <button class="btn btn-outline-secondary btn-reset" @click.prevent="doReset">
                  Reset filtre
                </button>
              </div>
              <div class="col-auto me-2">
                <button class="btn btn-primary pe-3 ps-3" @click.prevent="doSearch">
                  Recherche
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel br-6 p-2 mt-3" id="fullscreen">
      <div class="row p-2">
          <div class="col-auto">
            <button class="btn btn-primary pe-5 ps-5" @click="goToNewReglement">
              Nouveau Réglement
            </button>
          </div>
        </div>
        <d-data-grid ref="dataGrid" :fetchData="fetchData" :saveData="saveData" :addData="addData" :disableAddNew="true"
          :deleteData="deleteData" :columns="processedColumns" :rows="rows" title="Règlements" rowKey="id" :showViewButton="true"
          @view="handleView"  :isServerMode="true" :initialSort="{ field: params.orderBy, direction: params.orderWay }"
          @sort-change="handleSortChange" :loading="loading" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed, toRaw } from "vue";
import dDataGrid from "../../components/base/d-data-grid.vue";
import dInput from "../../components/base/d-input.vue";
import dPageTitle from "../../components/common/d-page-title.vue";
import axiosInstance from "../../config/http";
import { useRouter } from "vue-router";
import {
  filterOrderPayment,
  FILTER_ORDER_PAYMENT_STORAGE_NAME,
} from "../../composables/constants";
import { Helper } from "../../composables/global-methods";

const loading = ref(false);
const rows = ref([]);
const currencies = ref([]);
const customers = ref([]);
const commercials = ref([]);
const dataGrid = ref(null);
const router = useRouter();
const paymentTypes = ref([]);

const customersCache = ref(new Map());
const commercialsCache = ref(new Map());

const params = reactive({
  current_page: 1,
  pagesize: 50,
  orderBy: "dateOfReceipt",
  orderWay: "desc",
});

const filter = ref({ ...filterOrderPayment });
const filterActive = ref(false);

const columns = ref([
  { key: "dateOfReceipt", label: "Date réception", type: "date", editable: false },
  { 
    key: "paymentMethod", 
    label: "Type de règlement", 
    type: "custom", 
    editable: true,
    component: "d-payment-type-dropdown-settings",
    props: {
      paymentTypes: paymentTypes.value
    }
  },
  { key: "accountLabel", label: "Libellé sur le compte", type: "text", editable: true },
  {
    key: "customer",
    label: "Client",
    type: "custom",
    editable: true,
    component: "d-customer-dropdown-settings",
    idKey: "id",
    nameKey: "name",
    props: {
      isCommercial: false
    }
  },
  {
    key: "commercial",
    label: "Commercial",
    type: "custom",
    editable: true,
    component: "d-commercial-dropdown-settings",
    idKey: "id",
    nameKey: "name",
    props: {
      isCommercial: true
    }
  },
  { key: "paymentAmountHt", label: "Montant", type: "number", editable: true },
  {
    key: "currency",
    label: "Devise",
    editable: true,
    type: "custom",
    component: "d-currency-settings",
    idKey: "id",
    nameKey: "name"
  },
]);

// Utilisation de shallowRef pour les données qui ne changent pas souvent
const processedColumns = computed(() => {
  return columns.value.map(col => {
    const newCol = { ...col };
    if (newCol.component === 'd-currency-settings') {
      newCol.props = {
        ...(newCol.props || {}),
        currencies: currencies.value
      };
    } else if (newCol.component === 'd-customer-dropdown-settings') {
      newCol.props = {
        ...(newCol.props || {}),
        customers: customers.value
      };
    } else if (newCol.component === 'd-commercial-dropdown-settings') {
      newCol.props = {
        ...(newCol.props || {}),
        commercials: commercials.value
      };
    }  else if (newCol.component === 'd-payment-type-dropdown-settings') {
      newCol.props = {
        ...(newCol.props || {}),
        paymentTypes: paymentTypes.value
      };
    }
    return newCol;
  });
});

// Chargement initial en parallèle
onMounted(async () => {
  try {
    loading.value = true;
    await Promise.all([
      fetchCurrencies(),
      fetchCustomers(),
      fetchPaymentTypes(),
      fetchCommercials(),
      loadSavedFilters()
    ]);
  } finally {
    loading.value = false;
  }
});

async function loadSavedFilters() {
  const savedFilters = Helper.getStorage(FILTER_ORDER_PAYMENT_STORAGE_NAME);
  if (savedFilters && Helper.hasDefinedValue(savedFilters)) {
    filter.value = savedFilters;
    filterActive.value = true;
  }
}

async function fetchPaymentTypes() {
  try {
    const res = await axiosInstance.get('/api/payment-type');
    paymentTypes.value = res.data.response || [];
  } catch (error) {
    console.error('Failed to fetch payment types:', error);
  }
}

async function fetchCurrencies() {
  try {
    const res = await axiosInstance.get('/api/currency');
    currencies.value = res.data.response;
  } catch (error) {
    console.error('Failed to fetch currencies:', error);
  }
}

async function fetchCustomers() {
  try {
    const res = await axiosInstance.get('/api/customers');
    const uniqueCustomers = [];
    const seenIds = new Set();

    (res.data.response.customers || []).forEach(customer => {
      if (!seenIds.has(customer.id)) {
        seenIds.add(customer.id);
        uniqueCustomers.push(customer);
        // Pré-remplir le cache
        customersCache.value.set(customer.id, { response: { customerData: customer } });
      }
    });

    customers.value = uniqueCustomers;
  } catch (error) {
    console.error('Failed to fetch customers:', error);
  }
}

async function fetchCommercials() {
  try {
    const res = await axiosInstance.get('/api/commercials');
    commercials.value = res.data.response.commercials || [];

    // Pré-remplir le cache de manière plus robuste
    commercials.value.forEach(commercial => {
      if (commercial.user_id) {
        commercialsCache.value.set(commercial.user_id, {
          response: {
            commercialData: commercial
          }
        });
      }
    });

    // Attendre que les données soient complètement disponibles
    await new Promise(resolve => {
      if (commercials.value.length > 0) {
        resolve();
      } else {
        setTimeout(resolve, 100);
      }
    });
  } catch (error) {
    console.error('Failed to fetch commercials:', error);
  }
}

const fetchData = async ({ page, itemsPerPage, sort }) => {
  try {
    loading.value = true;
    let url = `/api/order-payments?page=${page}&limit=${itemsPerPage}`;

    if (sort?.field) {
      url += `&orderBy=${sort.field}`;
      params.orderBy = sort.field;
    }
    if (sort?.direction) {
      url += `&orderWay=${sort.direction}`;
      params.orderWay = sort.direction;
    }

    url += getFilterParams();

    const response = await axiosInstance.get(url);
    const data = response.data.response;
    const payments = data.data || data.orderPayments || data;

    // Utilisation de Promise.all avec un nombre limité de requêtes simultanées
    rows.value = await batchTransformPayments(payments, 5); // 5 requêtes simultanées max

    return {
      response: {
        data: rows.value,
        meta: {
          total_items: data.total || data.count || payments.length,
          total_pages: Math.ceil((data.total || data.count || payments.length) / itemsPerPage),
          current_page: page,
          items_per_page: itemsPerPage
        }
      }
    };
  } catch (error) {
    console.error("Error fetching order payments:", error);
    throw error;
  } finally {
    loading.value = false;
  }
};

// Fonction pour traiter les paiements par lots
async function batchTransformPayments(payments, batchSize = 5) {
  const result = [];
  for (let i = 0; i < payments.length; i += batchSize) {
    const batch = payments.slice(i, i + batchSize);
    const transformedBatch = await Promise.all(batch.map(payment => transformPayment(payment)));
    result.push(...transformedBatch);
  }
  return result;
}


async function transformPayment(payment) {
  const rawPayment = toRaw(payment);

  // Traitement du client
  const customerData = await processCustomer(rawPayment);

  // Traitement spécial pour les commerciaux - vérification synchrone d'abord
  let commercialData = { commercialId: null, commercialName: 'N/A' };

  if (rawPayment.commercial) {
    if (typeof rawPayment.commercial === 'object') {
      const rawCommercial = toRaw(rawPayment.commercial);
      commercialData = {
        commercialId: rawCommercial.id || rawCommercial.commercialId,
        commercialName: rawCommercial.commercialName || rawCommercial.commercial ||
          `${rawCommercial.firstname || ''} ${rawCommercial.lastname || ''}`.trim()
      };
    } else {
      const commercialId = rawPayment.commercial;
      // Vérification synchrone d'abord dans les données déjà chargées
      const foundCommercial = commercials.value.find(c => c.user_id == commercialId);

      if (foundCommercial) {
        commercialData = {
          commercialId: commercialId,
          commercialName: `${foundCommercial.firstname || ''} ${foundCommercial.lastname || ''}`.trim()
        };
      } else {
        // Fallback asynchrone seulement si nécessaire
        commercialData = await processCommercial(rawPayment);
      }
    }
  }

  let currencyData = rawPayment.currency;
  if (typeof currencyData === 'string') {
    const found = currencies.value.find(c => c.name === currencyData);
    currencyData = found || { name: currencyData };
  }

  let paymentMethod = rawPayment.paymentMethod;
  if (typeof paymentMethod === 'string' && paymentTypes.value.length > 0) {
    const foundType = paymentTypes.value.find(t => t.label === paymentMethod);
    if (foundType) {
      paymentMethod = foundType;
    }
  }

  return {
    id: rawPayment.id,
    dateOfReceipt: rawPayment.dateOfReceipt?.date,
    paymentMethod: paymentMethod,
    accountLabel: rawPayment.accountLabel,
    paymentAmountHt: parseFloat(rawPayment.paymentAmountHt),
    currency: currencyData,
    customer: customerData,
    commercial: commercialData
  };
}

async function processCustomer(payment) {
  if (!payment.customer) return { customerId: null, customerName: 'N/A' };

  if (typeof payment.customer === 'object') {
    const rawCustomer = toRaw(payment.customer);
    return {
      customerId: rawCustomer.id || rawCustomer.customerId,
      customerName: rawCustomer.customerName || rawCustomer.customer ||
        `${rawCustomer.social_reason || ''}${rawCustomer.code ? ` (${rawCustomer.code})` : ''}`.trim()
    };
  }

  const customerId = payment.customer;
  let customer = toRaw(customers.value.find(c => c.id == customerId));

  if (!customer && customerId) {
    const cached = customersCache.value.get(customerId);
    if (cached) {
      customer = cached.response?.customerData;
    } else {
      const customerResponse = await getCustomerOrCommercial(customerId, false);
      customer = customerResponse?.response?.customerData;
    }
  }

  return {
    customerId: customerId,
    customerName: customer ?
      `${customer.social_reason || ''}${customer.customerName ? ` ${customer.customerName}` : ''}`.trim() :
      `Client #${customerId}`
  };
}

async function processCommercial(payment) {
  if (!payment.commercial) return { commercialId: null, commercialName: 'N/A' };

  if (typeof payment.commercial === 'object') {
    const rawCommercial = toRaw(payment.commercial);
    return {
      commercialId: rawCommercial.id || rawCommercial.commercialId,
      commercialName: rawCommercial.commercialName || rawCommercial.commercial ||
        `${rawCommercial.firstname || ''} ${rawCommercial.lastname || ''}`.trim()
    };
  }

  const commercialId = payment.commercial;
  let commercial = toRaw(commercials.value.find(c => c.user_id == commercialId));

  if (!commercial && commercialId) {
    const cached = commercialsCache.value.get(commercialId);
    if (cached) {
      commercial = cached.response?.commercialData;
    } else {
      const commercialResponse = await getCustomerOrCommercial(commercialId, true);
      commercial = commercialResponse?.response?.commercialData;
    }
  }

  return {
    commercialId: commercialId,
    commercialName: commercial ?
      `${commercial.firstname || ''} ${commercial.lastname || ''}`.trim() :
      `Commercial #${commercialId}`
  };
}

const saveData = async (row) => {
  try {
    console.log(row.customer)
    const payload = {
      dateOfReceipt: row.dateOfReceipt || null,
      paymentMethod: row.paymentMethod?.id ?? row.paymentMethod ?? null, 
      accountLabel: row.accountLabel || null,
      paymentAmountHt: row.paymentAmountHt !== null && row.paymentAmountHt !== undefined
        ? String(row.paymentAmountHt)
        : null,
      currency: row.currency?.id ?? row.currency ?? null,
      customerId: row.customer?.id ?? null,
      commercialId: row.commercial?.user_id ?? null
    };

    const { data } = await axiosInstance.put(`/api/order-payments/${row.id}/update`, payload);
    return data.response;
  } catch (error) {
    console.error("Error updating payment:", error);
    throw error;
  }
};

const addData = async (row) => {
  try {
    const payload = {
      dateOfReceipt: row.dateOfReceipt || null,
      paymentMethod: row.paymentMethod || null,
      accountLabel: row.accountLabel || null,
      paymentAmountHt: row.paymentAmountHt !== null && row.paymentAmountHt !== undefined
        ? String(row.paymentAmountHt)
        : null,
      currency: row.currency?.id ?? row.currency ?? null,
      customer: row.customer?.customerId ?? row.customer ?? null,
      commercial: row.commercial?.commercialId ?? row.commercial ?? null
    };

    const { data } = await axiosInstance.post("/api/order-payments", payload);
    return data.response;
  } catch (error) {
    console.error("Error adding payment:", error);
    throw error;
  }
};

const deleteData = async (row) => {
  try {
    await axiosInstance.delete(`/api/order-payments/${row.id}`);
    return true;
  } catch (error) {
    console.error("Error deleting payment:", error);
    throw error;
  }
};

const getCustomerOrCommercial = async (id, isCommercial = false) => {
  try {
    if (!id) return null;

    const cache = isCommercial ? commercialsCache.value : customersCache.value;
    const cached = cache.get(id);
    if (cached) return cached;

    const endpoint = isCommercial ? `/api/commercial/${id}` : `/api/customer/${id}`;
    const response = await axiosInstance.get(endpoint);

    cache.set(id, response.data);
    return response.data;
  } catch (e) {
    console.error(`Error fetching ${isCommercial ? 'commercial' : 'customer'}:`, e);
    return null;
  }
};

const getFilterParams = () => {
  let param = "";
  const filters = filter.value;
  for (const key in filters) {
    if (filters[key]) {
      param += `&${key}=${encodeURIComponent(filters[key])}`;
    }
  }
  return param;
};

const doSearch = async () => {
  Helper.setStorage(FILTER_ORDER_PAYMENT_STORAGE_NAME, filter.value);
  filterActive.value = true;
  if (dataGrid.value?.refresh) {
    await dataGrid.value.refresh();
  }
};

const doReset = async () => {
  filter.value = { ...filterOrderPayment };
  filterActive.value = false;
  Helper.removeStorage(FILTER_ORDER_PAYMENT_STORAGE_NAME);
  if (dataGrid.value?.refresh) {
    await dataGrid.value.refresh();
  }
};

const goToNewReglement = () => {
  router.push({ name: 'reglement_create' });
};

const handleView = (row) => {
  console.log('View clicked, row:', row); 
  if (!row?.id) {
    console.error('Invalid row data:', row);
    return;
  }
  router.push({ name: 'reglement_view', params: { id: row.id } });
};

const handleSortChange = (sort) => {
  params.orderBy = sort.field;
  params.orderWay = sort.direction;
  dataGrid.value?.refresh();
};
</script>

<style>
.advanced-table .progress-bar {
  width: 80%;
  min-width: 120px;
  height: 8px;
  background-color: #ebedf2;
  border-radius: 9999px;
  display: flex;
}

.advanced-table .progress-line {
  height: 8px;
  border-radius: 9999px;
}

.btn-reset {
  box-shadow: none !important;
  margin-right: 5px;
}

.dropdown-item:active,
.dropdown-item:hover {
  background: none !important;
}
</style>