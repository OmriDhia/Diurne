<template>
  <d-base-page>
    <template #title>
      <d-page-title title="Nouvelle Facture" />
    </template>

    <template #body>
      <div class="row p-2">
        <div class="col-12">
          <d-input label="Référence client" v-model="form.customerRef" />
        </div>
      </div>

      <div class="row">
        <div class="col-md-7">
          <d-panel>
            <template #panel-body>
              <d-panel-title title="Caractéristiques facture" class-name="ps-2" />
              <div class="row p-3 align-items-center">
                <div class="col-md-4 col-sm-6">
                  <d-input label="Numéro facture" v-model="form.invoiceNumber" />
                </div>
                <div class="col-md-4 col-sm-6">
                  <d-date-picker label="Date" v-model="form.date" />
                </div>
                <div class="col-md-4 col-sm-6">
                  <d-input label="Projet" v-model="form.project" />
                </div>
              </div>

              <hr class="m-0" />

              <div class="row p-3">
                <div class="col-md-6">
                  <d-contremarque-dropdown v-model="form.contremarque" :customerId="form.customer" />
                  <d-input label="Prescripteur" v-model="form.prescripteur" />
                  <d-input label="Description" v-model="form.description" />
                </div>
                <div class="col-md-6">
                  <d-input label="Type de facture" v-model="form.invoiceType" />
                  <d-input label="TVA" v-model="form.tva" />
                  <d-currency v-model="form.currency" />
                  <d-input label="Tx de conversion" v-model="form.rate" />
                  <d-input label="Langue" v-model="form.language" />
                  <d-input label="Unité de mesure" v-model="form.unit" />
                </div>
              </div>
            </template>
          </d-panel>
        </div>

        <div class="col-md-5">
          <d-panel>
            <template #panel-body>
              <d-panel-title title="Règlement transporteur" class-name="ps-2" />
              <div class="row p-3">
                <div class="col-12">
                  <d-input label="Mode de règlement" v-model="form.reglement" />
                  <d-input label="Tarif d’expédition" v-model="form.tarifExpedition" />
                  <d-input label="Transporteur" v-model="form.transporteur" />
                  <d-input label="Numéro" v-model="form.numero" />
                </div>
              </div>
              <d-panel-title title="Autre tapis" class-name="ps-2 mt-2" />
              <div class="row p-3">
                <div class="col-12">
                  <d-input label="Numéro RN" v-model="form.autreRn" />
                </div>
              </div>
            </template>
          </d-panel>
        </div>
      </div>

      <div class="mt-3">
        <d-panel>
          <template #panel-body>
            <d-panel-title title="Détails" class-name="ps-2" />
            <div class="table-responsive">
              <table class="table table-striped table-hover table-sm">
                <thead>
                  <tr class="border-top text-black bg-black">
                    <th rowspan="2" class="border-start border-end text-white">% Prix total</th>
                    <th rowspan="2" class="border-start border-end text-white">RN</th>
                    <th rowspan="2" class="border-start border-end text-white">Collection</th>
                    <th rowspan="2" class="border-start border-end text-white">Modèle</th>
                    <th rowspan="2" class="border-start border-end text-white">Ref tapis devis</th>
                    <th rowspan="2" class="border-start border-end text-white">Ref tapis commande</th>
                    <th rowspan="2" class="border-start border-end text-white">Versement</th>
                    <th colspan="4" class="border-start border-end text-white text-center">Prix vendu</th>
                    <th rowspan="2" class="border-start border-end text-white">Actions</th>
                  </tr>
                  <tr class="border-top text-black bg-black">
                    <th class="border-start border-end text-white">m2</th>
                    <th class="border-start border-end text-white">sqft</th>
                    <th class="border-start border-end text-white">HT</th>
                    <th class="border-start border-end text-white">TTC</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(line, index) in lines" :key="index">
                    <td><input type="number" class="form-control form-control-sm" v-model="line.percent" /></td>
                    <td><input type="text" class="form-control form-control-sm" v-model="line.rn" /></td>
                    <td><d-collections-dropdown v-model="line.collection" /></td>
                    <td><d-model-dropdown v-model="line.model" /></td>
                    <td><input type="text" class="form-control form-control-sm" v-model="line.refDevis" /></td>
                    <td><input type="text" class="form-control form-control-sm" v-model="line.refCommande" /></td>
                    <td><input type="number" class="form-control form-control-sm" v-model="line.versement" /></td>
                    <td><input type="number" class="form-control form-control-sm" v-model="line.priceM2" /></td>
                    <td><input type="number" class="form-control form-control-sm" v-model="line.priceSqft" /></td>
                    <td><input type="number" class="form-control form-control-sm" v-model="line.priceHt" /></td>
                    <td><input type="number" class="form-control form-control-sm" v-model="line.priceTtc" /></td>
                    <td class="text-center">
                      <button class="btn btn-dark btn-sm me-1" @click="saveLine(index)">
                        <vue-feather type="save" size="16" />
                      </button>
                      <button class="btn btn-dark btn-sm" @click="removeLine(index)">
                        <vue-feather type="x" size="16" />
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="row mt-3">
              <div class="col-md-3">
                <d-input label="Qte total" v-model="form.qteTotal" />
                <d-input label="Frais port HT" v-model="form.fraisPort" />
              </div>
              <div class="col-md-3">
                <d-input label="Versement" v-model="form.versement" />
                <d-input label="% facturé" v-model="form.percentFacture" />
              </div>
              <div class="col-md-3">
                <d-input label="Total HT" v-model="form.totalHt" />
                <d-input label="Montant HT" v-model="form.montantHt" />
              </div>
              <div class="col-md-3">
                <d-input label="Montant TVA" v-model="form.montantTva" />
                <d-input label="Montant TTC" v-model="form.montantTtc" />
              </div>
            </div>

            <div class="row p-3 justify-content-end">
              <div class="col-auto">
                <button class="btn btn-custom me-2">RÉPARTITION</button>
                <button class="btn btn-custom me-2">CALCULER</button>
                <button class="btn btn-custom me-2">ÉDITER</button>
                <button class="btn btn-custom">RATTACHER UN RÈGLEMENT</button>
              </div>
            </div>
          </template>
        </d-panel>
      </div>
    </template>
  </d-base-page>
</template>

<script setup>
import { ref } from 'vue';
import dBasePage from '../../components/base/d-base-page.vue';
import dPanel from '../../components/common/d-panel.vue';
import dPanelTitle from '../../components/common/d-panel-title.vue';
import dPageTitle from '../../components/common/d-page-title.vue';
import dInput from '../../components/base/d-input.vue';
import dDatePicker from '../../components/base/d-date-picker.vue';
import dCurrency from '../../components/common/d-currency.vue';
import dContremarqueDropdown from '../../components/common/d-contremarque-dropdown.vue';
import dCollectionsDropdown from '../../components/projet/contremarques/dropdown/d-collections-dropdown.vue';
import dModelDropdown from '../../components/projet/contremarques/dropdown/d-model-dropdown.vue';
import VueFeather from 'vue-feather';
import { useMeta } from '/src/composables/use-meta';

useMeta({ title: 'Nouvelle Facture' });

const form = ref({
  customerRef: '',
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
  description: '',
  reglement: '',
  tarifExpedition: '',
  transporteur: '',
  numero: '',
  autreRn: '',
  qteTotal: '',
  fraisPort: '',
  versement: '',
  percentFacture: '',
  totalHt: '',
  montantHt: '',
  montantTva: '',
  montantTtc: ''
});

const lines = ref([
  {
    percent: null,
    rn: '',
    collection: null,
    model: null,
    refDevis: '',
    refCommande: '',
    versement: null,
    priceM2: null,
    priceSqft: null,
    priceHt: null,
    priceTtc: null
  }
]);

const saveLine = (index) => {
  console.log('save line', lines.value[index]);
};

const removeLine = (index) => {
  lines.value.splice(index, 1);
};
</script>
