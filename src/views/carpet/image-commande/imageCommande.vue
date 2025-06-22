<template>
  <div class="layout-px-spacing mt-4">
    <d-page-title :title="'Image commande'"></d-page-title>

    <!-- Radio Filter Section -->
    <div class="d-flex flex-wrap justify-content-start gap-4 mb-4">
      <label class="fw-normal"><input type="radio" name="type" /> Échantillon</label>
      <label class="fw-normal"><input type="radio" name="type" /> Tapis</label>
      <label class="fw-normal"><input type="radio" name="type" /> Tous</label>
      <label class="fw-normal"><input type="radio" name="type" /> Dispo. vente</label>
      <label class="fw-normal"><input type="radio" name="type" /> État prod</label>
      <label class="fw-normal"><input type="radio" name="type" /> État stock</label>
    </div>

    <div class="row layout-top-spacing mt-2 p-2">
      <div class="panel br-6 p-3 mt-1" id="fullscreen">
        <!-- FILTER FORM -->
        <div class="row g-3 mb-3">
          <div
            v-for="(field, index) in fields"
            :key="index"
            class="col-md-4"
          >
            <d-input
              :label="field.label"
              v-model="filter[field.model]"
              :as="field.as || 'input'"
            >
              <template v-if="field.model === 'etatTapis'">
                <option value="">--</option>
                <option value="cmd_atelier">Cmd. atelier</option>
              </template>
            </d-input>
          </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="d-flex justify-content-end gap-2 mb-3">
          <button v-if="filterActive" class="btn btn-outline-secondary btn-reset" @click.prevent="doReset">Reset filtre</button>
          <button class="btn btn-custom" @click.prevent="doSearch">Recherche</button>
          <button class="btn btn-outline-secondary">IMPORTER MAJ TAPIS STOCK (EXCEL)</button>
          <button class="btn btn-outline-dark">IMPORTER PR</button>
        </div>

        <!-- TABLE SECTION -->
        <div class="table-responsive">
          <table class="table table-bordered text-nowrap align-middle">
            <thead class="bg-dark text-white text-center">
              <tr>
                <th>Image</th>
                <th>Contremarque/ Emplacement</th>
                <th>RN</th>
                <th>Commande / Devis</th>
                <th>Client/Commercial/Prescripteur</th>
                <th>État</th>
                <th>Emp. stock</th>
                <th>Dispo</th>
                <th>
                  <div>Total :</div>
                  <div><input class="form-control form-control-sm" /></div>
                  <div>Prix de vente</div>
                </th>
                <th>
                  <div>Total :</div>
                  <div><input class="form-control form-control-sm" /></div>
                  <div>Prix d'achat</div>
                </th>
                <th>
                  <div>Début :</div>
                  <input type="date" class="form-control form-control-sm mb-1" />
                  <div>Fin :</div>
                  <input type="date" class="form-control form-control-sm" />
                </th>
                <th>
                  <div>Début :</div>
                  <input type="date" class="form-control form-control-sm mb-1" />
                  <div>Fin :</div>
                  <input type="date" class="form-control form-control-sm" />
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in rows" :key="order.id">
                <td class="text-center">
                  <img src="/label-image.jpg" alt="Carpet" style="width: 60px" />
                </td>
                <td>
                  <strong>Contremarque :</strong> {{ order.designation }}<br />
                  <strong>Emplacement :</strong> {{ order.location || '-' }}
                </td>
                <td>{{ order.reference }}</td>
                <td>
                  <strong>Commande :</strong> {{ order.cloned_quote_reference }}<br />
                  <strong>Devis :</strong> {{ order.original_quote_reference }}
                </td>
                <td>
                  <strong>Client :</strong> {{ order.customer }}<br />
                  <strong>Commercial :</strong> {{ order.commercial }}<br />
                  <strong>Prescripteur :</strong> {{ order.prescripteur || '-' }}
                </td>
                <td>{{ order.state || '-' }}</td>
                <td class="text-center">
                  <button class="btn btn-sm btn-outline-secondary">+</button>
                </td>
                <td class="text-center">
                  <input type="checkbox" />
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
        <pagination
          v-if="totalRows > paginationData.itemsPerPage"
          :current-page="paginationData.currentPage"
          :total-pages="totalPages"
          :total-items="totalRows"
          :items-per-page="paginationData.itemsPerPage"
          @page-change="changePage"
          @page-size-change="changePageSize"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import dInput from '../../../components/base/d-input.vue';
import dPageTitle from '../../../components/common/d-page-title.vue';
import pagination from '../../../components/base/Pagination/d-pagination.vue';
import axiosInstance from '../../../config/http';

import { Helper } from '../../../composables/global-methods';
import { filterImageCommande, FILTER_IMAGE_COMMANDE_STORAGE_NAME } from '../../../composables/constants';

const filter = ref(Object.assign({}, filterImageCommande));
const filterActive = ref(false);

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
  { label: 'Prescripteur', model: 'prescripteur' }
];

const rows = ref([]);
const totalRows = ref(0);
const loading = ref(false);

const paginationData = reactive({
  currentPage: 1,
  itemsPerPage: 5
});

const totalPages = computed(() =>
  Math.ceil(totalRows.value / paginationData.itemsPerPage)
);

const getOrders = async () => {
  try {
    loading.value = true;
    let url = `/api/carpetOrders?page=${paginationData.currentPage}&limit=${paginationData.itemsPerPage}`;
    url += getFilterParams();
    const res = await axiosInstance.get(url);
    rows.value = res.data.carpetOrders || [];
    totalRows.value = res.data.count || 0;
  } catch (e) {
    console.error(e);
  }
  loading.value = false;
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
  Helper.setStorage(FILTER_IMAGE_COMMANDE_STORAGE_NAME, filter.value);
  paginationData.currentPage = 1;
  getOrders();
};

const doReset = () => {
  filterActive.value = false;
  filter.value = Object.assign({}, filterImageCommande);
  Helper.setStorage(FILTER_IMAGE_COMMANDE_STORAGE_NAME, filter.value);
  paginationData.currentPage = 1;
  getOrders();
};

const getFilterParams = () => {
  let p = '';
  if (filter.value.client) p += `&client=${filter.value.client}`;
  if (filter.value.rn) p += `&rn=${filter.value.rn}`;
  if (filter.value.collection) p += `&collection=${filter.value.collection}`;
  if (filter.value.contremarque) p += `&contremarque=${filter.value.contremarque}`;
  if (filter.value.etatTapis) p += `&etatTapis=${filter.value.etatTapis}`;
  if (filter.value.modele) p += `&modele=${filter.value.modele}`;
  if (filter.value.commercial) p += `&commercial=${filter.value.commercial}`;
  if (filter.value.atelier) p += `&atelier=${filter.value.atelier}`;
  if (filter.value.commande) p += `&commande=${filter.value.commande}`;
  if (filter.value.devis) p += `&devis=${filter.value.devis}`;
  if (filter.value.prescripteur) p += `&prescripteur=${filter.value.prescripteur}`;
  return p;
};

onMounted(() => {
  const f = Helper.getStorage(FILTER_IMAGE_COMMANDE_STORAGE_NAME);
  if (f && Helper.hasDefinedValue(f)) {
    filter.value = f;
    filterActive.value = true;
  }
  getOrders();
});
</script>

<style scoped>
th input {
  min-width: 100px;
}
</style>
