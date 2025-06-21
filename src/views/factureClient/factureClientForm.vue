<template>
  <d-base-page>
    <template #title>
      <d-page-title title="Facture Client" />
    </template>
    <template #body>
      <d-panel>
        <template #panel-header>
          <d-panel-title title="Informations facture" />
        </template>
        <template #panel-body>
          <div class="row">
            <div class="col-md-6">
              <d-input label="Auteur" v-model="form.auteur" />
            </div>
            <div class="col-md-6">
              <d-input label="RN" v-model="form.rn" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <d-input label="Date" type="date" v-model="form.date" />
            </div>
            <div class="col-md-6">
              <d-input label="N° facture" v-model="form.invoiceNumber" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <d-input label="Atelier" v-model="form.atelier" />
            </div>
          </div>
        </template>
      </d-panel>
    </template>
    <template #footer>
      <div class="row p-2 justify-content-end">
        <div class="col-auto">
          <button type="button" class="btn btn-dark" @click="saveInvoice">Enregistrer</button>
        </div>
      </div>
    </template>
  </d-base-page>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import dBasePage from '../../components/base/d-base-page.vue'
import dPageTitle from '../../components/common/d-page-title.vue'
import dPanel from '../../components/common/d-panel.vue'
import dPanelTitle from '../../components/common/d-panel-title.vue'
import dInput from '../../components/base/d-input.vue'
import axiosInstance from '../../config/http'

const route = useRoute()
const router = useRouter()
const form = ref({
  auteur: '',
  rn: '',
  date: '',
  invoiceNumber: '',
  atelier: ''
})

onMounted(async () => {
  if (route.params.id) {
    const res = await axiosInstance.get(`/api/customer-invoices/${route.params.id}`)
    form.value = res.data.response || form.value
  }
})

async function saveInvoice() {
  if (route.params.id) {
    await axiosInstance.put(`/api/customer-invoices/${route.params.id}`, form.value)
  } else {
    await axiosInstance.post('/api/customer-invoices', form.value)
  }
  router.push({ name: 'facture_client_list' })
}
</script>
