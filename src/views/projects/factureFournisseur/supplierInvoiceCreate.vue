<template>
    <div class="create-fournisseur-invoice">
        <d-base-page>
            <template #title>
                <d-page-title title="Nouvelle Facture Fournisseur" />
            </template>

            <template #body>
                <d-panel>
                    <template #panel-body>
                        <d-panel-title title="Caractéristiques facture" class-name="ps-2" />
                        <div class="row">
                            <div class="col-md-6">
                                <d-input label="Numéro facture" v-model="form.invoiceNumber" />
                                <div class="row align-items-center pt-2">
                                    <label for="invoice-date" class="col-4">Date facture</label>
                                    <div class="col-8">
                                        <input id="invoice-date" class="form-control custom-date" type="date" v-model="form.invoiceDate" />
                                    </div>
                                </div>
                                <d-input label="Fournisseur" v-model="form.supplier" />
                                <d-input label="Packing list" v-model="form.packingList" />
                                <d-input label="Air way bill" v-model="form.airWayBill" />
                                <d-input label="Fret total" v-model="form.totalFreight" />
                                <d-currency v-model="form.currency" />
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="radio" id="freight-included" v-model="form.freightIncluded" />
                                    <label class="form-check-label" for="freight-included">compris dans la facture</label>
                                </div>
                            </div>
                        </div>
                    </template>
                </d-panel>

                <div class="mt-3">
                    <d-panel>
                        <template #panel-body>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-sm">
                                    <thead>
                                        <tr class="border-top text-black bg-black text-white">
                                            <th>RN</th>
                                            <th>N° tapis</th>
                                            <th>Prix m²</th>
                                            <th>Surface facture</th>
                                            <th>Prix de la facture</th>
                                            <th>Prix théorique</th>
                                            <th>Pénalité</th>
                                            <th>Surface produite</th>
                                            <th>Montant réel avoir</th>
                                            <th>Avoir théorique</th>
                                            <th>Montant réel avoir</th>
                                            <th>Montant final tapis</th>
                                            <th>Poids</th>
                                            <th>% poids</th>
                                            <th>Fret</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(line, index) in lines" :key="index">
                                            <td><multiselect v-model="line.rn" :options="[]" :multiple="false" placeholder="" :searchable="true"></multiselect></td>
                                            <td><input type="text" class="form-control form-control-sm" v-model="line.numeroTapis" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.prixM2" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.surfaceFacture" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.prixFacture" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.prixTheorique" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.penalite" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.surfaceProduite" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.montantReelAvoir" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.avoirTheorique" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.montantReelAvoir2" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.montantFinalTapis" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.poids" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.pourcentPoids" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.fret" /></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <d-input label="Autre montant" v-model="form.autreMontant" />
                                    <d-input label="Poids" v-model="form.poids" />
                                </div>
                                <div class="col-md-4">
                                    <d-input textarea label="Description" v-model="form.description" />
                                </div>
                                <div class="col-md-4">
                                    <h6>Avoir sur cette facture</h6>
                                    <div class="row">
                                        <div class="col">
                                            <d-input label="Montant théorique" v-model="form.avoirMontantTheo" />
                                        </div>
                                        <div class="col">
                                            <d-input label="Montant réel" v-model="form.avoirMontantReel" />
                                        </div>
                                    </div>
                                    <d-input label="Numéro de l'avoir" v-model="form.numeroAvoir" />
                                    <div class="row align-items-center pt-2">
                                        <label for="date-avoir" class="col-4">Date de l'avoir</label>
                                        <div class="col-8">
                                            <input id="date-avoir" class="form-control custom-date" type="date" v-model="form.dateAvoir" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <d-input label="Total facture" v-model="form.totalFacture" />
                                    <d-input label="Total théorique" v-model="form.totalTheorique" />
                                    <d-input label="Total surface" v-model="form.totalSurface" />
                                    <d-input label="Total Poids" v-model="form.totalPoids" />
                                    <h6 class="mt-2">Paiement</h6>
                                    <div class="row">
                                        <div class="col">
                                            <d-input label="Montant théorique" v-model="form.paiementMontantTheo" />
                                        </div>
                                        <div class="col">
                                            <d-input label="Montant réel" v-model="form.paiementMontantReel" />
                                        </div>
                                        <div class="col">
                                            <d-date-picker label="Date de paiement" v-model="form.datePaiement" />
                                        </div>
                                    </div>
                                    <d-input label="Valeur de la commande" v-model="form.valeurCommande" />
                                </div>
                                <div class="col-md-6">
                                    <h6>Suivi avoir fournisseur</h6>
                                    <d-input label="Antérieur" v-model="form.suiviAnterieur" />
                                    <d-input label="Restant" v-model="form.suiviRestant" />
                                </div>
                            </div>

                            <div class="row mt-3 justify-content-end">
                                <div class="col-auto">
                                    <button class="btn btn-custom me-2">Valider</button>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-outline-secondary">Annuler</button>
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
import dDatePicker from '../../../components/base/d-date-picker.vue';
import dCurrency from '../../../components/common/d-currency.vue';
import Multiselect from 'vue-multiselect';
import { useMeta } from '/src/composables/use-meta';

useMeta({ title: 'Nouvelle Facture Fournisseur' });

const form = ref({
    invoiceNumber: '',
    invoiceDate: '',
    supplier: '',
    packingList: '',
    airWayBill: '',
    totalFreight: '',
    currency: null,
    freightIncluded: false,
    autreMontant: '',
    poids: '',
    description: '',
    avoirMontantTheo: '',
    avoirMontantReel: '',
    numeroAvoir: '',
    dateAvoir: '',
    totalFacture: '',
    totalTheorique: '',
    totalSurface: '',
    totalPoids: '',
    paiementMontantTheo: '',
    paiementMontantReel: '',
    datePaiement: '',
    valeurCommande: '',
    suiviAnterieur: '',
    suiviRestant: '',
});

const lines = ref([
    {
        rn: null,
        numeroTapis: '',
        prixM2: null,
        surfaceFacture: null,
        prixFacture: null,
        prixTheorique: null,
        penalite: null,
        surfaceProduite: null,
        montantReelAvoir: null,
        avoirTheorique: null,
        montantReelAvoir2: null,
        montantFinalTapis: null,
        poids: null,
        pourcentPoids: null,
        fret: null,
    },
]);
</script>

<style scoped>
.custom-date {
    width: 100%;
}
</style>

