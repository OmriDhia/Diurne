<template>
  <d-base-page>
    <template #title>
      <d-page-title title="Nouvelle Facture" />
    </template>

    <template #body>
      <d-panel>
        <template #panel-body>
          <d-panel-title title="Facture" class-name="ps-2" />
          <div class="row  align-items-center">
            <div class="col-md-3 col-sm-6">
              <d-input label="Référence client" v-model="form.invoiceNumber" />
            </div>
            <div class="col-md-3 col-sm-6">
              <!-- <d-date-picker label="Date" v-model="form.date" /> -->
               <div class="row">
                <div class="col-4"> <label for="date_from">date</label></div>
                <div class="col-8"> <input id="date_from" class="form-control custom-date" type="date" v-model="form.date" /></div>
               </div>
             
            </div>
            <div class="col-md-3 col-sm-6">
              <d-input label="Projet" v-model="form.project" />
            </div>
            <div class="col-md-3 col-sm-6">
              <d-input label="Type de facture" v-model="form.invoiceType" />
            </div>
          </div>
          <div class="row  align-items-center">
            <div class="col-md-3 col-sm-6">
              <d-input label="TVA" v-model="form.tva" />
            </div>
            <div class="col-md-3 col-sm-6">
              <d-currency v-model="form.currency" />
            </div>
            <div class="col-md-3 col-sm-6">
              <d-input label="Taux de conversion" v-model="form.rate" />
            </div>
            <div class="col-md-3 col-sm-6">
              <d-input label="Langue" v-model="form.language" />
            </div>
          </div>
          <div class="row  align-items-center">
            <div class="col-md-3 col-sm-6">
              <d-input label="Unité de mesure" v-model="form.unit" />
            </div>
            <div class="col-md-3 col-sm-6">
              <d-contremarque-dropdown v-model="form.contremarque" :customerId="form.customer" />
            </div>
            <div class="col-md-3 col-sm-6">
              <d-input label="Prescripteur" v-model="form.prescripteur" />
            </div>
            <div class="col-md-3 col-sm-6">
              <d-input label="Description" v-model="form.description" />
            </div>
          </div>
        </template>
      </d-panel>

      <div class="row px-3 mt-3">
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
              <thead>
                <tr class="border-top text-black bg-black">
                  <th class="border-start border-end text-white">RN</th>
                  <th class="border-start border-end text-white">N° Tapis</th>
                  <th class="border-start border-end text-white">Prix M</th>
                  <th class="border-start border-end text-white">Devis</th>
                  <th class="border-start border-end text-white">Commande</th>
                  <th class="border-start border-end text-white">Facture</th>
                  <th class="border-start border-end text-white">Répartition (%)</th>
                  <th class="border-start border-end text-white">Affecté TTC</th>
                  <th class="border-start border-end text-white">Total Document TTC</th>
                  <th class="border-start border-end text-white">Restant TTC</th>
                  <th class="border-start border-end text-white">Affecté HT</th>
                  <th class="border-start border-end text-white">TVA</th>
                  <th class="border-start border-end text-white">Soldé</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(line, index) in lines" :key="index">
                  <td><input type="text" class="form-control form-control-sm" v-model="line.rn" /></td>
                  <td><input type="text" class="form-control form-control-sm" v-model="line.tapis" /></td>
                  <td><input type="number" class="form-control form-control-sm" v-model="line.prix" /></td>
                  <td><input type="text" class="form-control form-control-sm" v-model="line.devis" /></td>
                  <td><input type="text" class="form-control form-control-sm" v-model="line.commande" /></td>
                  <td><input type="text" class="form-control form-control-sm" v-model="line.facture" /></td>
                  <td><input type="number" class="form-control form-control-sm" v-model="line.repartition" /></td>
                  <td><input type="number" class="form-control form-control-sm" v-model="line.affecteTtc" /></td>
                  <td><input type="number" class="form-control form-control-sm" v-model="line.totalTtc" /></td>
                  <td><input type="number" class="form-control form-control-sm" v-model="line.restantTtc" /></td>
                  <td><input type="number" class="form-control form-control-sm" v-model="line.affecteHt" /></td>
                  <td><input type="number" class="form-control form-control-sm" v-model="line.tva" /></td>
                  <td><input type="checkbox" v-model="line.solde" /></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </template>
  </d-base-page>
</template>

<script setup>
import { ref } from 'vue';
import dBasePage from '../../../components/base/d-base-page.vue';
import dPanel from '../../../components/common/d-panel.vue';
import dPanelTitle from '../../../components/common/d-panel-title.vue';
import dPageTitle from '../../../components/common/d-page-title.vue';
import dInput from '../../../components/base/d-input.vue';
import dDatePicker from '../../../components/base/d-date-picker.vue';
import dCurrency from '../../../components/common/d-currency.vue';
import dContremarqueDropdown from '../../../components/common/d-contremarque-dropdown.vue';
import { useMeta } from '/src/composables/use-meta';

useMeta({ title: 'Nouvelle Facture' });

const form = ref({
  invoiceNumber: '',
  date: '',
  project: '',
  invoiceType: '',
  tva: '',
  currency: null,
  rate: '',
  language: '',
  unit: '',
  contremarque: null,
  prescripteur: '',
  description: ''
});

const lines = ref([
  { rn: '', tapis: '', prix: null, devis: '', commande: '', facture: '', repartition: null, affecteTtc: null, totalTtc: null, restantTtc: null, affecteHt: null, tva: null, solde: false }
]);
</script>
