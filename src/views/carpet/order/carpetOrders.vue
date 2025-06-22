<template>
  <div class="layout-px-spacing mt-4">
    <d-page-title :title="'Tapis'"></d-page-title>



    <div class="row layout-top-spacing mt-2 p-2">


      <div class="panel br-6 p-3 mt-1" id="fullscreen">

        <!-- Radio Filter Section -->

        <div class="d-flex flex-wrap justify-content-start gap-4 mb-4">
          <label class="fw-normal"><input type="radio" name="type" value="echantillon" v-model="filter.type" /> Échantillon</label>
          <label class="fw-normal"><input type="radio" name="type" value="tapis" v-model="filter.type" /> Tapis</label>
          <label class="fw-normal"><input type="radio" name="type" value="tous" v-model="filter.type" /> Tous</label>
          <label class="fw-normal"><input type="radio" name="type" value="dispo_vente" v-model="filter.type" /> Dispo. vente</label>
          <label class="fw-normal"><input type="radio" name="type" value="etat_prod" v-model="filter.type" /> État prod</label>
          <label class="fw-normal"><input type="radio" name="type" value="etat_stock" v-model="filter.type" /> État stock</label>
        </div>
        <!-- FILTER FORM -->
        <div class="row g-3 mb-3">
          <div v-for="(field, index) in fields" :key="index" class="col-md-4">
            <d-input :label="field.label" :type="field.type || 'text'" v-model="filter[field.model]" :as="field.as || 'input'">
              <template v-if="field.model === 'etatTapis'">
                <option value="">--</option>
                <option value="cmd_atelier">Cmd. atelier</option>
              </template>
            </d-input>
          </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="d-flex custom-align gap-2 mb-3">
          <button v-if="filterActive" class="btn btn-outline-secondary btn-reset" @click.prevent="doReset">Reset
            filtre</button>
          <button class="btn btn-custom pe-3 ps-3" @click.prevent="doSearch">Recherche</button>
          <button class="btn btn-outline-secondary">IMPORTER MAJ TAPIS STOCK (EXCEL)</button>
          <button class="btn btn-outline-dark">IMPORTER PR</button>
        </div>

        <!-- TABLE SECTION -->
        <div class="row mb-4 relative align-items-center justify-content-between">
          <div class="col-auto">
            <div class="btn-group custom-dropdown me-2 btn-group-lg">
              <button class="btn btn-outline-custom p-2 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                Cacher / Montrer Colonnes
              </button>
              <ul class="dropdown-menu p-2">
                <li v-for="col in cols" :key="col.field">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" :checked="!col.hide" :id="col.field"
                      @change="col.hide = !$event.target.checked" :name="col.field" />
                    <label class="custom-control-label text-black" :for="col.field"> {{ col.title }} </label>
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <d-btn-fullscreen></d-btn-fullscreen>
        </div>

        <div class="bh-table-responsive">
          <table class="bh-table-striped bh-table-hover">
            <thead>
              <tr>
                <th v-if="!cols.find(c => c.field === 'image')?.hide">Image</th>
                <th v-if="!cols.find(c => c.field === 'contremarque')?.hide">Contremarque/ Emplacement</th>
                <th v-if="!cols.find(c => c.field === 'rn')?.hide">RN</th>
                <th v-if="!cols.find(c => c.field === 'commande')?.hide">Commande / Devis</th>
                <th v-if="!cols.find(c => c.field === 'client')?.hide">Client/Commercial/Prescripteur</th>
                <th v-if="!cols.find(c => c.field === 'etat')?.hide">État</th>
                <th v-if="!cols.find(c => c.field === 'stock')?.hide">Emp. stock</th>
                <th v-if="!cols.find(c => c.field === 'dispo')?.hide">Dispo</th>
                <th v-if="!cols.find(c => c.field === 'prixVente')?.hide">
                  <div>Total :</div>
                  <div><input class="form-control form-control-sm" /></div>
                  <div>Prix de vente</div>
                </th>
                <th v-if="!cols.find(c => c.field === 'prixAchat')?.hide">
                  <div>Total :</div>
                  <div><input class="form-control form-control-sm" /></div>
                  <div>Prix d'achat</div>
                </th>
                <th v-if="!cols.find(c => c.field === 'dates1')?.hide">
                  <div>Début :</div>
                  <input type="date" class="form-control form-control-sm mb-1" />
                  <div>Fin :</div>
                  <input type="date" class="form-control form-control-sm" />
                </th>
                <th v-if="!cols.find(c => c.field === 'dates2')?.hide">
                  <div>Début :</div>
                  <input type="date" class="form-control form-control-sm mb-1" />
                  <div>Fin :</div>
                  <input type="date" class="form-control form-control-sm" />
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in rows" :key="order.id">
                <td v-if="!cols.find(c => c.field === 'image')?.hide" class="text-center">
                  <img src="/label-image.jpg" alt="Carpet" style="width: 60px" />
                </td>
                <td v-if="!cols.find(c => c.field === 'contremarque')?.hide">
                  <strong>Contremarque :</strong> {{ order.designation }}<br />
                  <strong>Emplacement :</strong> {{ order.location || '-' }}
                </td>
                <td v-if="!cols.find(c => c.field === 'rn')?.hide">{{ order.reference }}</td>
                <td v-if="!cols.find(c => c.field === 'commande')?.hide">
                  <strong>Commande :</strong> {{ order.cloned_quote_reference }}<br />
                  <strong>Devis :</strong> {{ order.original_quote_reference }}
                </td>
                <td v-if="!cols.find(c => c.field === 'client')?.hide">
                  <strong>Client :</strong> {{ order.customer }}<br />
                  <strong>Commercial :</strong> {{ order.commercial }}<br />
                  <strong>Prescripteur :</strong> {{ order.prescripteur || '-' }}
                </td>
                <td v-if="!cols.find(c => c.field === 'etat')?.hide">{{ order.state || '-' }}</td>
                <td v-if="!cols.find(c => c.field === 'stock')?.hide" class="text-center">
                  <button class="btn btn-sm btn-outline-secondary">+</button>
                </td>
                <td v-if="!cols.find(c => c.field === 'dispo')?.hide" class="text-center">
                  <input type="radio" />
                </td>
                <td v-if="!cols.find(c => c.field === 'prixVente')?.hide"></td>
                <td v-if="!cols.find(c => c.field === 'prixAchat')?.hide"></td>
                <td v-if="!cols.find(c => c.field === 'dates1')?.hide"></td>
                <td v-if="!cols.find(c => c.field === 'dates2')?.hide"></td>
              </tr>
            </tbody>
          </table>
        </div>
        <pagination v-if="totalRows > paginationData.itemsPerPage" :current-page="paginationData.currentPage"
          :total-pages="totalPages" :total-items="totalRows" :items-per-page="paginationData.itemsPerPage"
          @page-change="changePage" @page-size-change="changePageSize" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import dInput from '../../../components/base/d-input.vue';
import dPageTitle from '../../../components/common/d-page-title.vue';
import dBtnFullscreen from '../../../components/base/d-btn-fullscreen.vue';
import pagination from '../../../components/base/Pagination/d-pagination.vue';
import axiosInstance from '../../../config/http';
import { filterCarpetOrder, FILTER_CARPET_ORDER_STORAGE_NAME } from '../../../composables/constants';
import { Helper } from '../../../composables/global-methods';

const filter = ref(Object.assign({}, filterCarpetOrder));
const filterActive = ref(false);
const loading = ref(true);

const fields = [
  { label: 'Client', model: 'client' },
  { label: 'RN', model: 'rn' },
  { label: 'Collection', model: 'collection' },
  { label: 'Contremarque', model: 'contremarque' },
  { label: 'Etat du tapis', model: 'etatTapis', as: 'select' },
  { label: 'Modèle', model: 'modele' },
  { label: 'Commercial', model: 'commercial' },
  { label: 'Atelier', model: 'atelier' },
  { label: 'Commande', model: 'commande' },
  { label: 'Devis', model: 'devis' },
  { label: 'Prescripteur', model: 'prescripteur' },
  { label: 'Date cmd. client du', model: 'orderDate_from', type: 'date' },
  { label: 'Date cmd. client au', model: 'orderDate_to', type: 'date' },
  { label: 'Date facture client du', model: 'invoiceDate_from', type: 'date' },
  { label: 'Date facture client au', model: 'invoiceDate_to', type: 'date' }
];

const cols = ref([
  { field: 'image', title: 'Image', hide: false },
  { field: 'contremarque', title: 'Contremarque/Emplacement', hide: false },
  { field: 'rn', title: 'RN', hide: false },
  { field: 'commande', title: 'Commande/Devis', hide: false },
  { field: 'client', title: 'Client/Commercial/Prescripteur', hide: false },
  { field: 'etat', title: 'État', hide: false },
  { field: 'stock', title: 'Emp. stock', hide: false },
  { field: 'dispo', title: 'Dispo', hide: false },
  { field: 'prixVente', title: 'Prix de vente', hide: false },
  { field: 'prixAchat', title: 'Prix d\'achat', hide: false },
  { field: 'dates1', title: 'Dates 1', hide: false },
  { field: 'dates2', title: 'Dates 2', hide: false }
]);

const rows = ref([]);
const totalRows = ref(0);

const paginationData = reactive({
  currentPage: 1,
  itemsPerPage: 5,
  orderBy: '',
  orderWay: ''
});

const totalPages = computed(() =>
  Math.ceil(totalRows.value / paginationData.itemsPerPage)
);

const getOrders = async () => {
  try {
    loading.value = true;
    let url = `/api/carpetOrders?page=${paginationData.currentPage}&limit=${paginationData.itemsPerPage}`;

    if (paginationData.orderBy) {
      url += `&orderBy=${paginationData.orderBy}`;
    }
    if (paginationData.orderWay) {
      url += `&orderWay=${paginationData.orderWay}`;
    }

    url += getFilterParams();
    const res = await axiosInstance.get(url);
    rows.value = res.data.carpetOrders || [];
    totalRows.value = res.data.count || 0;
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
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
  let param = '';
  if (filter.value.client) param += `&client=${filter.value.client}`;
  if (filter.value.rn) param += `&rn=${filter.value.rn}`;
  if (filter.value.collection) param += `&collection=${filter.value.collection}`;
  if (filter.value.contremarque) param += `&contremarque=${filter.value.contremarque}`;
  if (filter.value.etatTapis) param += `&etatTapis=${filter.value.etatTapis}`;
  if (filter.value.modele) param += `&modele=${filter.value.modele}`;
  if (filter.value.commercial) param += `&commercial=${filter.value.commercial}`;
  if (filter.value.atelier) param += `&atelier=${filter.value.atelier}`;
  if (filter.value.commande) param += `&commande=${filter.value.commande}`;
  if (filter.value.devis) param += `&devis=${filter.value.devis}`;
  if (filter.value.prescripteur) param += `&prescripteur=${filter.value.prescripteur}`;
  if (filter.value.orderDate_from) param += `&orderDateFrom=${filter.value.orderDate_from}`;
  if (filter.value.orderDate_to) param += `&orderDateTo=${filter.value.orderDate_to}`;
  if (filter.value.invoiceDate_from) param += `&invoiceDateFrom=${filter.value.invoiceDate_from}`;
  if (filter.value.invoiceDate_to) param += `&invoiceDateTo=${filter.value.invoiceDate_to}`;
  if (filter.value.type) param += `&type=${filter.value.type}`;
  return param;
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

.btn-reset {
  box-shadow: none !important;
  margin-right: 5px;
}

.dropdown-item:active,
.dropdown-item:hover {
  background: none !important;
}

.custom-align {
  justify-content: end;
  flex-direction: column;
  align-items: end;
}

.custom-align button {
  width: 350px;
}

.bh-table-responsive table thead tr,
.bh-table-responsive table tfoot tr,
.bh-table-responsive table thead tr th.bh-sticky,
.bh-table-responsive table tbody tr td.bh-sticky {
  background: #eff5ff !important;
}
input[type="radio"]{
    position: unset;
    opacity: 1;
}
</style>