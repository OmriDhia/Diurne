<template>
    <div class="create-facture-client">
        <d-base-page>
            <template #title>
                <d-page-title title="Nouvelle Facture" />
            </template>

            <template #body>
                <d-panel>
                    <template #panel-body>
                        <div class="row p-2">
                            <div class="col-3">
                                <d-input label="Référence client" v-model="form.customerRef" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <d-panel>
                                    <template #panel-body>
                                        <d-panel-title title="Caractéristiques facture" class-name="ps-2" />
                                        <div class="row">
                                            <div class="col-md-6">
                                                <d-input label="Numéro facture" v-model="form.invoiceNumber" />
                                                <div class="row align-items-center pt-2">
                                                    <label for="date" class="col-4">Date</label>
                                                    <div class="col-8">
                                                        <input id="date" class="form-control custom-date custom-date" type="date" v-model="form.date" />
                                                    </div>
                                                </div>

                                                <d-input label="Projet" v-model="form.project" />
                                                <hr class="mt-3" />

                                                <div class="row">
                                                    <d-contremarque-dropdown v-model="form.contremarque" :customerId="form.customer" class="contremarque" />
                                                    <d-input label="Prescripteur" v-model="form.prescripteur" />
                                                    <d-input label="Description" v-model="form.description" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- <d-input label="Type de facture" v-model="form.invoiceType" /> -->
                                                <div class="row align-items-center">
                                                    <label for="" class="col-4">Type de facture:</label>
                                                    <div class="col-8">
                                                        <multiselect v-model="form.invoiceType" :options="[]" :multiple="false" :placeholder="'Type de facture'" :searchable="true"></multiselect>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-2 mb-1">
                                                    <label for="" class="col-4">TVA:</label>
                                                    <div class="col-8">
                                                        <multiselect v-model="form.tva" :options="[]" :multiple="false" :placeholder="'TVA'" :searchable="true"></multiselect>
                                                    </div>
                                                </div>

                                                <d-currency v-model="form.currency" />
                                                <div class="row align-items-center mt-2">
                                                    <label for="" class="col-4">Tx de conversion:</label>
                                                    <div class="col-8">
                                                        <multiselect v-model="form.rate" :options="[]" :multiple="false" :placeholder="'Tx de conversion'" :searchable="true"></multiselect>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-2">
                                                    <label for="" class="col-4">Langue:</label>
                                                    <div class="col-8">
                                                        <multiselect v-model="form.language" :options="[]" :multiple="false" :placeholder="'Langue'" :searchable="true"></multiselect>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-2">
                                                    <label for="" class="col-4">Unité de mesure:</label>
                                                    <div class="col-8">
                                                        <multiselect v-model="form.language" :options="[]" :multiple="false" :placeholder="'Unité de mesure'" :searchable="true"></multiselect>
                                                    </div>
                                                </div>
                                                <div class="row justify-content-end mt-2">
                                                    <button class="btn btn-link">Appliquer</button>
                                                </div>
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
                                                <div class="row align-items-center mt-2">
                                                    <label for="" class="col-4">Mode de règlement</label>
                                                    <div class="col-8">
                                                        <multiselect v-model="form.reglement" :options="[]" :multiple="false" placeholder="" :searchable="true"></multiselect>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-2">
                                                    <label for="" class="col-4">Tarif d’expédition</label>
                                                    <div class="col-8">
                                                        <multiselect v-model="form.tarifExpedition" :options="[]" :multiple="false" placeholder="" :searchable="true"></multiselect>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-2">
                                                    <label for="" class="col-4">Transporteur</label>
                                                    <div class="col-8">
                                                        <multiselect v-model="form.transporteur" :options="[]" :multiple="false" placeholder="" :searchable="true"></multiselect>
                                                    </div>
                                                </div>

                                                <d-input label="Numéro" v-model="form.numero" />
                                            </div>
                                        </div>
                                        <d-panel-title title="Autre tapis" class-name="ps-2 mt-2" />
                                        <div class="row p-3">
                                            <div class="col-12 bloc-add">
                                                <d-input label="Numéro RN" v-model="form.autreRn" />

                                                <button class="btn btn-add"><vue-feather type="plus" stroke-width="1" class="cursor-pointer"></vue-feather></button>
                                            </div>
                                        </div>
                                    </template>
                                </d-panel>
                            </div>
                        </div> </template
                ></d-panel>

                <div class="mt-3">
                    <d-panel>
                        <template #panel-body>
                            <!-- <d-panel-title title="Détails" class-name="ps-2" /> -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-sm">
                                    <thead>
                                        <tr class="border-top text-black bg-black">
                                            <th rowspan="2" class="text-white">% Prix total</th>
                                            <th rowspan="2" class="text-white">RN</th>
                                            <th rowspan="2" class="text-white">Collection</th>
                                            <th rowspan="2" class="text-white">Modèle</th>
                                            <th rowspan="2" class="text-white">Ref tapis devis</th>
                                            <th rowspan="2" class="text-white">Ref tapis commande</th>
                                            <th rowspan="2" class="border-end text-white">Versement</th>
                                            <th colspan="4" class="border-start border-end text-white text-center">Prix vendu</th>
                                            <th rowspan="2" class="border-start border-end text-white"></th>
                                        </tr>
                                        <tr class="border-top text-black bg-black">
                                            <th class="text-white">m2</th>
                                            <th class="text-white">sqft</th>
                                            <th class="text-white">HT</th>
                                            <th class="text-white">TTC</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(line, index) in lines" :key="index">
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.percent" /></td>
                                            <td><input type="text" class="form-control form-control-sm" v-model="line.rn" /></td>
                                            <td><multiselect v-model="line.collection" :options="[]" :multiple="false" placeholder="" :searchable="true"></multiselect></td>
                                            <td><multiselect v-model="line.model" :options="[]" :multiple="false" placeholder="" :searchable="true"></multiselect></td>
                                            <td><input type="text" class="form-control form-control-sm" v-model="line.refDevis" /></td>
                                            <td><input type="text" class="form-control form-control-sm" v-model="line.refCommande" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.versement" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.priceM2" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.priceSqft" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.priceHt" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.priceTtc" /></td>
                                            <td class="text-center td-actions">
                                                <button class="btn btn-add btn-sm me-1" @click="saveLine(index)">
                                                    <vue-feather type="save" size="16" />
                                                </button>
                                                <button class="btn btn-add btn-sm" @click="removeLine(index)">
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
                                    <d-input label="Montant TVA" v-model="form.montantTva" />
                                    <d-input label="Montant TTC" v-model="form.montantTtc" />
                                </div>
                                <div class="col-md-3 bloc-btns-actions">
                                    <button class="btn btn-custom">RÉPARTITION</button>
                                    <button class="btn btn-custom">CALCULER</button>
                                    <button class="btn btn-custom">ÉDITER</button>
                                    <button class="btn btn-custom">RATTACHER UN RÈGLEMENT</button>
                                </div>
                            </div>
                        </template>
                    </d-panel>
                </div>
            </template>
        </d-base-page>
    </div>
</template>

<script setup>
    import { ref } from 'vue';
    import dBasePage from '../../../components/base/d-base-page.vue';
    import dPanel from '../../../components/common/d-panel.vue';
    import dPanelTitle from '../../../components/common/d-panel-title.vue';
    import dPageTitle from '../../../components/common/d-page-title.vue';
    import dInput from '../../../components/base/d-input.vue';

    import dCurrency from '../../../components/common/d-currency.vue';
    import dContremarqueDropdown from '../../../components/common/d-contremarque-dropdown.vue';

    import VueFeather from 'vue-feather';
    import { useMeta } from '/src/composables/use-meta';
    import Multiselect from 'vue-multiselect';
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
        montantTtc: '',
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
            priceTtc: null,
        },
    ]);

    const saveLine = (index) => {
        console.log('save line', lines.value[index]);
    };

    const removeLine = (index) => {
        lines.value.splice(index, 1);
    };
</script>
