<template>
  <div class="layout-px-spacing mt-4">
    <d-page-title icon="file-text" :title="'Facture Client'"></d-page-title>

    <div class="row layout-top-spacing mt-3 p-2">
      <div class="panel br-6 p-2">
        <div class="row p-2">
          <div class="col-auto">
            <button class="btn btn-custom pe-5 ps-5" @click="goToNewInvoice">Nouvelle Facture</button>
          </div>
        </div>
        <div class="row d-flex justify-content-center align-items-start p-2">
          <div class="col-md-6 col-sm-12">
            <d-customer-dropdown v-model="filter.customer" />
            <d-input label="Numéro de facture" v-model="filter.invoiceNumber" />
            <d-input label="RN" v-model="filter.rn" />
          </div>
          <div class="col-md-6 col-sm-12">
            <d-date-picker label="Du" v-model="filter.date_from" />
            <d-date-picker label="Au" v-model="filter.date_to" />
            <d-contremarque-dropdown :customerId="filter.customer" v-model="filter.contremarque" />
            <div class="row mt-2 justify-content-center">
              <div class="col-auto" v-if="filterActive">
                <button class="btn btn-outline-secondary btn-reset" @click.prevent="doReset">
                  Reset filtre
                </button>
              </div>
              <div class="col-auto me-2">
                <button class="btn btn-custom pe-3 ps-3" @click.prevent="doSearch">Recherche</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel br-6 p-2 mt-3" id="fullscreen">
        <div class="row mt-2 mb-4">
          <div class="vue3-datatable w-100">
            <vue3-datatable
              :rows="rows"
              :columns="cols"
              :loading="loading"
              :isServerMode="true"
              :sortColumn="params.orderBy"
              :sortDirection="params.orderWay"
              :totalRows="total_rows"
              :page="params.current_page"
              :pageSize="params.pagesize"
              :pageSizeOptions="[10,25,50,75,100]"
              noDataContent="Aucune facture trouvée."
              paginationInfo="Affichage de {0} à {1} sur {2} entrées"
              :sortable="true"
              @change="changeServer"
              class="advanced-table text-nowrap"
            >
              <template #actions="data">
                <router-link :to="'/facture-client/view/' + data.value.id">
                  <vue-feather type="search" stroke-width="1" class="cursor-pointer"></vue-feather>
                </router-link>
              </template>
            </vue3-datatable>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import VueFeather from 'vue-feather';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import dInput from '../../components/base/d-input.vue';
import dCustomerDropdown from '../../components/common/d-customer-dropdown.vue';
import dContremarqueDropdown from '../../components/common/d-contremarque-dropdown.vue';
import dPageTitle from '../../components/common/d-page-title.vue';
import dDatePicker from '../../components/base/d-date-picker.vue';
import axiosInstance from '../../config/http';
import { useRouter } from 'vue-router';
import { filterClientInvoice, FILTER_CLIENT_INVOICE_STORAGE_NAME } from '../../composables/constants';
import { Helper } from '../../composables/global-methods';
import { useMeta } from '/src/composables/use-meta';

useMeta({ title: 'Facture Client' });

const router = useRouter();
const loading = ref(false);
const rows = ref([]);
const total_rows = ref(0);
const filterActive = ref(false);

const params = reactive({
  current_page: 1,
  pagesize: 50,
  orderBy: 'invoiceNumber',
  orderWay: 'desc'
});

const filter = ref({ ...filterClientInvoice });

const cols = ref([
  { field: 'invoiceNumber', title: 'Numéro facture' },
  { field: 'invoiceDate', title: 'Date de facture' },
  { field: 'customer', title: 'Raison sociale' },
  { field: 'contremarque', title: 'Contremarque' },
  { field: 'amountTtc', title: 'Montant TTC' },
  { field: 'actions', title: '', sort: false }
]);

onMounted(() => {
  const f = Helper.getStorage(FILTER_CLIENT_INVOICE_STORAGE_NAME);
  if (f && Helper.hasDefinedValue(f)) {
    filter.value = f;
    filterActive.value = true;
  }
  getInvoices();
});

const getInvoices = async () => {
  try {
    loading.value = true;
    let url = `/api/client-invoices?page=${params.current_page}&limit=${params.pagesize}`;
    url += `&orderBy=${params.orderBy}&orderWay=${params.orderWay}`;
    url += getFilterParams();
    const res = await axiosInstance.get(url);
    const data = res.data.response || {};
    rows.value = data.invoices || [];
    total_rows.value = data.count || 0;
  } catch (e) {
    console.error(e);
  }
  loading.value = false;
};

const changeServer = (data) => {
  params.current_page = data.current_page;
  params.pagesize = data.pagesize;
  params.orderBy = data.sort_column;
  params.orderWay = data.sort_direction;
  getInvoices();
};

const doSearch = () => {
  filterActive.value = true;
  Helper.setStorage(FILTER_CLIENT_INVOICE_STORAGE_NAME, filter.value);
  getInvoices();
};

const doReset = () => {
  filterActive.value = false;
  filter.value = { ...filterClientInvoice };
  Helper.setStorage(FILTER_CLIENT_INVOICE_STORAGE_NAME, filter.value);
  getInvoices();
};

const getFilterParams = () => {
  let p = '';
  if (filter.value.customer) p += `&customerId=${filter.value.customer}`;
  if (filter.value.invoiceNumber) p += `&invoiceNumber=${filter.value.invoiceNumber}`;
  if (filter.value.rn) p += `&rn=${filter.value.rn}`;
  if (filter.value.date_from) p += `&dateFrom=${filter.value.date_from}`;
  if (filter.value.date_to) p += `&dateTo=${filter.value.date_to}`;
  if (filter.value.contremarque) p += `&contremarque=${filter.value.contremarque}`;
  return p;
};

const goToNewInvoice = () => {
  router.push({ name: 'client-invoice-create' });
};
</script>
<style>
.text-size-16 {
  font-size: 16px !important;
}
</style>
