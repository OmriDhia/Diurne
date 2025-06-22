<template>
  <div class="layout-px-spacing mt-4">
    <d-page-title :title="'Commande Tapis'"></d-page-title>

    <!-- Radio Filter Section -->

      <div class="panel br-6 p-2">
          <div v-for="(field, index) in fields" :key="index" class="col-md-4">
            <d-input :label="field.label" v-model="filter[field.model]" :as="field.as || 'input'">
        </div>
      </div>
      <div class="panel br-6 p-2 mt-3" id="fullscreen">
        <div class="row mt-2 mb-4">
          <div class="vue3-datatable w-100">
            <div class="row mb-4 relative align-items-center justify-content-between">
              <div class="col-auto">
                <div class="btn-group custom-dropdown me-2 btn-group-lg">
                  <button class="btn btn-outline-custom p-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Cacher / Montrer Colonnes
                  </button>
                  <ul class="dropdown-menu p-2">
                    <li v-for="col in cols" :key="col.field">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" :checked="!col.hide" :id="col.field" @change="col.hide = !$event.target.checked" :name="col.field"/>
                        <label class="custom-control-label text-black" :for="col.field"> {{ col.title }} </label>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <d-btn-fullscreen></d-btn-fullscreen>
            </div>
            <vue3-datatable
              :rows="rows"
              :columns="cols" :loading="loading"
              :isServerMode="true" :sortColumn="params.orderBy"
              :sortDirection="params.orderWay"
              :totalRows="total_rows" :page="params.current_page"
              :pageSize="params.pagesize"
              :pageSizeOptions="[10, 25, 50, 75, 100]"
              noDataContent="Aucune commande trouvée."
              paginationInfo="Affichage de {0} à {1} sur {2} entrées" :sortable="true"
              @change="changeServer" class="advanced-table text-nowrap">
              <template #reference="data">
                <div class="d-flex justify-content-between">
                  <strong>{{ data.value.reference }}</strong>
                  <router-link :to="'/tapis/order/manage/' + data.value.cloned_quote" v-if="$hasPermission('update carpet')">
                    <vue-feather type="search" stroke-width="1" class="cursor-pointer"></vue-feather>
                  </router-link>
                </div>
              </template>
              <template #designation="data">
                <div class="d-flex justify-content-between">
                  <strong>{{ data.value.designation }}</strong>
                  <router-link :to="'/projet/contremarques/manage/' + data.value.contremarque_id" v-if="$hasPermission('update contremarque')">
                    <vue-feather type="search" stroke-width="1" class="cursor-pointer"></vue-feather>
                  </router-link>
                </div>
              </template>
              <template #customer="data">
                <div class="d-flex justify-content-between">
                  <strong>{{ data.value.customer }}</strong>
                </div>
              </template>
              <template #creationDate="data">
                <div class="d-flex justify-content-between">
                  {{ data.value.created_at ? $Helper.FormatDate(data.value.created_at) : '' }}
                </div>
              </template>
            </vue3-datatable>
          </div>
import { ref, reactive, onMounted } from 'vue';
import dBtnFullscreen from '../../../components/base/d-btn-fullscreen.vue';
import VueFeather from 'vue-feather';
import Vue3Datatable from '@bhplugin/vue3-datatable';
const loading = ref(true);
const total_rows = ref(0);

const params = reactive({
  current_page: 1,
  pagesize: 50,
  orderBy: '',
  orderWay: ''
});

const rows = ref(null);

const cols = ref([
  { field: 'reference', title: 'Numéro commande' },
  { field: 'designation', title: 'Contremarque' },
  { field: 'customer', title: 'Client' },
  { field: 'commercial', title: 'Commercial' },
  { field: 'creationDate', title: 'Date création' }
]) || [];
onMounted(() => {
  const saved = Helper.getStorage(FILTER_CARPET_ORDER_STORAGE_NAME);
  if (saved && Helper.hasDefinedValue(saved)) {
    filter.value = saved;
    filterActive.value = true;
  }
  getCarpetOrders();
const getCarpetOrders = async () => {
    loading.value = true;
    let url = `/api/carpetOrders?page=${params.current_page}&limit=${params.pagesize}`;
    if (params.orderBy) {
      url += `&orderBy=${params.orderBy}`;
    }
    if (params.orderWay) {
      url += `&orderWay=${params.orderWay}`;
    }
    const response = await axiosInstance.get(url);
    const data = response.data;
    total_rows.value = data.count;
    rows.value = data.carpetOrders;
  loading.value = false;
const changeServer = (data) => {
  params.current_page = data.current_page;
  params.pagesize = data.pagesize;
  params.orderBy = data.sort_column;
  params.orderWay = data.sort_direction;
  getCarpetOrders();
  getCarpetOrders();

  let param = '';
  const f = filter.value;
  if (f.client) param += `&client=${f.client}`;
  if (f.rn) param += `&rn=${f.rn}`;
  if (f.collection) param += `&collection=${f.collection}`;
  if (f.contremarque) param += `&contremarque=${f.contremarque}`;
  if (f.etatTapis) param += `&etatTapis=${f.etatTapis}`;
  if (f.modele) param += `&modele=${f.modele}`;
  if (f.commercial) param += `&commercial=${f.commercial}`;
  if (f.atelier) param += `&atelier=${f.atelier}`;
  if (f.commande) param += `&commande=${f.commande}`;
  if (f.devis) param += `&devis=${f.devis}`;
  if (f.prescripteur) param += `&prescripteur=${f.prescripteur}`;
  return param;
  getCarpetOrders();
  }
};

const changePage = (page) => {
  paginationData.currentPage = page;
  getOrders();
};

const changePageSize = (size) => {
  paginationData.itemsPerPage = size;
  paginationData.currentPage = 1;
  getOrders();
};

const doSearch = () => {
  filterActive.value = true;
  paginationData.currentPage = 1;
  Helper.setStorage(FILTER_CARPET_ORDER_STORAGE_NAME, filter.value);
  getOrders();
};

const getFilterParams = () => {
  const params = new URLSearchParams();
  Object.entries(filter.value).forEach(([key, val]) => {
    if (val) {
      params.append(key, val);
    }
  });
  const query = params.toString();
  return query ? `&${query}` : '';
};

const doReset = () => {
  filterActive.value = false;
  filter.value = Object.assign({}, filterCarpetOrder);
  Helper.setStorage(FILTER_CARPET_ORDER_STORAGE_NAME, filter.value);
  paginationData.currentPage = 1;
  getOrders();
};

onMounted(() => {
  const saved = Helper.getStorage(FILTER_CARPET_ORDER_STORAGE_NAME);
  if (saved && Helper.hasDefinedValue(saved)) {
    filter.value = saved;
    filterActive.value = true;
  }
  getOrders();
});
</script>

<style scoped>
th input {
  min-width: 100px;
}
.custom-align {
  justify-content: end;
  flex-direction: column;
  align-items: end;
}

.custom-align button {
  width: 350px;
}

.btn-reset {
  box-shadow: none !important;
  margin-right: 5px;
}

.dropdown-item:active,
.dropdown-item:hover {
  background: none !important;
}

.custom-align{
    justify-content: end;
    flex-direction: column;
    align-items: end;
}

.custom-align button{
      width: 350px;
}

</style>
