<template>
  <div class="layout-px-spacing mt-4">
    <d-page-title icon="file-text" :title="'Facture Client'"></d-page-title>
    <div class="row layout-top-spacing mt-3 p-2">
      <div class="panel br-6 p-2">
        <div class="row d-flex justify-content-center align-items-start p-2">
          <div class="col-md-6 col-sm-12">
            <div class="row">
              <d-input label="Auteur" v-model="filter.auteur" />
            </div>
            <div class="row">
              <d-input label="RN" v-model="filter.rn" />
            </div>
            <div class="row">
              <d-input label="Date début" type="date" v-model="filter.startDate" />
            </div>
            <div class="row">
              <d-input label="Date fin" type="date" v-model="filter.endDate" />
            </div>
          </div>
          <div class="col-md-6 col-sm-12">
            <div class="row">
              <d-input label="N° facture" v-model="filter.invoiceNumber" />
            </div>
            <div class="row">
              <d-input label="Atelier" v-model="filter.atelier" />
            </div>
            <div class="row mt-2">
              <div class="col-auto" v-if="filterActive">
                <button class="btn btn-outline-secondary btn-reset" @click.prevent="doReset">Reset filtre</button>
              </div>
              <div class="col-auto me-2">
                <button class="btn btn-primary pe-3 ps-3" @click.prevent="doSearch">Recherche</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel br-6 p-2 mt-3" id="fullscreen">
        <d-data-grid ref="dataGrid" :fetchData="fetchData" :saveData="saveData" :deleteData="deleteData" :addData="addData" :columns="columns" :rows="rows" title="Factures" rowKey="id" :disableAddNew="true" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import dInput from '../../components/base/d-input.vue'
import dPageTitle from '../../components/common/d-page-title.vue'
import dDataGrid from '../../components/base/d-data-grid.vue'
import axiosInstance from '../../config/http'
import { filterFactureClient, FILTER_FACTURE_CLIENT_STORAGE_NAME } from '../../composables/constants'
import { Helper } from '../../composables/global-methods'

const dataGrid = ref(null)
const rows = ref([])
const columns = ref([
  { key: 'author', label: 'Auteur' },
  { key: 'rn', label: 'RN' },
  { key: 'date', label: 'Date' },
  { key: 'invoiceNumber', label: 'N° Facture' },
  { key: 'atelier', label: 'Atelier' }
])

const filter = ref({ ...filterFactureClient })
const filterActive = ref(false)

async function fetchData({ page, itemsPerPage }) {
  const url = `/api/customer-invoices?page=${page}&limit=${itemsPerPage}` + getFilterParams()
  const res = await axiosInstance.get(url)
  rows.value = res.data.response?.data || []
  return { response: res.data.response }
}

async function saveData(row) {
  const res = await axiosInstance.put(`/api/customer-invoices/${row.id}`, row)
  return res.data.response
}

async function deleteData(row) {
  await axiosInstance.delete(`/api/customer-invoices/${row.id}`)
}

async function addData(row) {
  const res = await axiosInstance.post('/api/customer-invoices', row)
  return res.data.response
}

function getFilterParams() {
  let param = ''
  if (filter.value.auteur) param += `&filter[auteur]=${filter.value.auteur}`
  if (filter.value.rn) param += `&filter[rn]=${filter.value.rn}`
  if (filter.value.startDate) param += `&filter[startDate]=${filter.value.startDate}`
  if (filter.value.endDate) param += `&filter[endDate]=${filter.value.endDate}`
  if (filter.value.invoiceNumber) param += `&filter[invoiceNumber]=${filter.value.invoiceNumber}`
  if (filter.value.atelier) param += `&filter[atelier]=${filter.value.atelier}`
  return param
}

function doSearch() {
  filterActive.value = true
  Helper.setStorage(FILTER_FACTURE_CLIENT_STORAGE_NAME, filter.value)
  dataGrid.value.fetchData()
}

function doReset() {
  filter.value = { ...filterFactureClient }
  filterActive.value = false
  Helper.removeStorage(FILTER_FACTURE_CLIENT_STORAGE_NAME)
  dataGrid.value.fetchData()
}
</script>
