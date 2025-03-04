<template>
    <d-base-page>
        <template v-slot:title>
            <d-page-title title="Devis"></d-page-title>
        </template>
        <template v-slot:header>
            <d-panel>
                <template v-slot:panel-header>
                    <div class="row p-2 align-items-center">
                        <div class="col-md-4 col-sm-12">
                            <d-input label="Numéro de devis" v-model="quoteNumber" :disabled="true"></d-input>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <d-input type="date" label="Date de création" v-model="createdDate"
                                :disabled="true"></d-input>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <d-contremarque-dropdown v-model="contremarqueId"
                                :disabled="true"></d-contremarque-dropdown>
                        </div>
                    </div>
                </template>
            </d-panel>
        </template>
        <template v-slot:body>
            <d-panel>
                <template v-slot:panel-body>
                    <div class="row mt-3 mb-3 pe-0">
                        <div class="col-lg-4 col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Image" className="ps-2"></d-panel-title>
                            <div class="row">
                                <d-collections-dropdown :required="true" :hideBtn="true"
                                    v-model="data.carpetSpecification.collectionId"
                                    :error="validationSubmitErrors.collectionId"></d-collections-dropdown>
                            </div>
                            <div class="row">
                                <d-model-dropdown :required="true" :hideBtn="true"
                                    v-model="data.carpetSpecification.modelId"
                                    :error="validationSubmitErrors.modelId"></d-model-dropdown>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Tapis de projet" className="ps-2"></d-panel-title>
                            <d-imageDevisAttribution v-if="quoteDetailId"
                                :collection="quoteDetail?.carpetSpecification?.collection?.reference"
                                :image="quoteDetail.vignettePath" :quoteDetailId="quoteDetailId"
                                :contremarqueId="contremarqueId" />

                            <!-- <div class="row pe-2 ps-0"></div> 
                            <div class="row pe-2 ps-0 align-items-center"></div> -->
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Information complémentaire" className="ps-2"></d-panel-title>
                            <div class="row pe-2 ps-0">
                                <d-location-dropdown :error="error.quoteDetail_locationId"
                                    :contremarqueId="contremarqueId"
                                    v-model="data.quoteDetail.locationId"></d-location-dropdown>
                            </div>
                            <div class="row pe-2 ps-0 align-items-center">
                                <div class="row align-items-center pt-2">
                                    <div class="col-8"><label class="form-label" for="shippingDelay">Délais livraison
                                            (semaine):</label></div>
                                    <div class="col-4"><input type="number" id="shippingDelay"
                                            v-model="data.quoteDetail.estimatedDeliveryTime" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="row pe-2 ps-0 align-items-center">
                                <d-input :disabled="true" label="N° Tapis dans le devis"
                                    v-model="carpetNumber"></d-input>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3 pe-0">
                        <div class="col-lg-4 col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Prix" className="ps-2"></d-panel-title>
                            <div class="row">
                                <d-tarifs :required="true" v-model="data.quoteDetail.TarifId"
                                    :error="validationSubmitErrors.tarifId" @confirm-change="openModal"></d-tarifs>
                            </div>
                            <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">Changer le tarif affectera le tarif par défaut du
                                            client. Voulez-vous continuer ?</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-bs-dismiss="modal">Annuler</button>
                                            <button type="button" class="btn btn-primary"
                                                @click="confirmTarifChange">Confirmer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <d-qualities-dropdown :required="true" v-model="data.carpetSpecification.qualityId"
                                    :error="validationSubmitErrors.qualityId"></d-qualities-dropdown>
                            </div>
                            <div class="row">
                                <d-special-shapes v-model="data.carpetSpecification.specialShapeId"></d-special-shapes>
                            </div>
                            <div class="row ps-2 pt-2">
                                <div class="custom-control custom-radio">
                                    <input type="checkbox" class="custom-control-input" id="useSpecialShape"
                                        name="useSpecialShape" v-model="data.carpetSpecification.hasSpecialShape" />
                                    <label class="custom-control-label text-black" for="useSpecialShape"> Forme spéciale
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Matières" className="ps-2"></d-panel-title>
                            <div class="row pe-2 ps-2 align-items-center align-items-center">
                                <d-materials-list :showTitle="false"
                                    :materialsProps="quoteDetail.carpetSpecification?.carpetMaterials"></d-materials-list>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 pe-sm-0 mt-sm-4" v-if="quoteDetailId">
                            <div class="row ps-2 pe-4">
                                <div class="custom-control custom-radio pb-2 ps-4">
                                    <input type="checkbox" class="custom-control-input" id="specialTreatment"
                                        name="specialTreatment" v-model="specialTreatment.trait" />
                                    <label class="custom-control-label text-black" for="specialTreatment"> Traitement
                                        particulier </label>
                                </div>
                                <d-specific-treatment-form :treatments="quoteDetail?.carpetSpecificTreatments"
                                    :quoteDetailId="quoteDetailId" :quantity="data.quoteDetail.wantedQuantity"
                                    @addTreatment="UpdateTreatments"></d-specific-treatment-form>
                            </div>
                        </div>
                    </div>
                    <template v-if="quoteDetailId">
                        <div class="row mt-3 mb-3 pe-0 align-items-center">
                            <div class="col-md-8 col-sm-12">
                                <d-panel-title title="Dimensions" className="ps-2"></d-panel-title>
                                <d-mesurement-quote :areaSquareFeet="quoteDetail?.areaSquareFeet"
                                    :areaSquareMeter="quoteDetail?.areaSquareMeter"
                                    :dimensionsProps="quoteDetail?.carpetSpecification?.carpetDimensions"
                                    :totalHt="prices?.tarif_avant_remise_complementaire?.total_ht"
                                    :currencyId="data.quoteDetail.currencyId"
                                    :calculateHt="data.quoteDetail.calculateFromTotalExcludingTax"
                                    :quoteDetailId="quoteDetailId"
                                    :globalWeight="quoteDetail?.carpetSpecification?.weight"
                                    @changePrices="changePrices" @changeWeight="changeWeight"></d-mesurement-quote>
                            </div>
                            <div class="col-md-4 col-sm-12 p-4">
                                <d-input label="Quantité de tapis" v-model="data.quoteDetail.wantedQuantity"></d-input>
                                <d-input label="RN" v-model="data.quoteDetail.rn"></d-input>
                            </div>
                        </div>
                        <div class="row mt-3 mb-3 pe-0 align-items-center">
                            <div class="col-md-6 col-sm-12">
                                <d-panel-title title="Tarif" className="ps-2"></d-panel-title>
                                <div class="row pe-4">
                                    <div class="col-md-6 col-sm-12">
                                        <d-input label="HT/m²" :disabled="true"
                                            v-model="prices.tarif.ht_per_meter"></d-input>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <d-input label="Total HT" :disabled="true"
                                            v-model="prices.tarif.total_ht"></d-input>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <d-input label="Net/sqft" :disabled="true"
                                            v-model="prices.tarif.ht_per_sqft"></d-input>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <d-panel-title title="Tarif grand projet" className="ps-2">
                                    <template v-slot:extraBtn>
                                        <div class="custom-control custom-radio">
                                            <input type="checkbox" class="custom-control-input"
                                                id="applyLargeProjectRate" name="tarifBigProject"
                                                v-model="data.quoteDetail.applyLargeProjectRate" />
                                            <label class="custom-control-label text-black" for="applyLargeProjectRate">
                                                Appliquer tarif grand projet </label>
                                        </div>
                                    </template>
                                </d-panel-title>
                                <div class="row pe-4">
                                    <div class="col-md-6 col-sm-12">
                                        <d-input label="HT/m²" :disabled="true"
                                            v-model="prices.grand_public.ht_per_meter"></d-input>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <d-input label="Total HT" :disabled="true"
                                            v-model="prices.grand_public.total_ht"></d-input>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <d-input label="Net/sqft" :disabled="true"
                                            v-model="prices.grand_public.ht_per_sqft"></d-input>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 mb-3 pe-0 align-items-center">
                            <div class="col-md-12">
                                <d-panel-title title="Remise proposée" className="ps-2"></d-panel-title>
                                <div class="row pe-4 align-items-center">
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="HT/m²" :disabled="true"
                                                    v-model="prices.remise.ht_per_meter"></d-input>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="TTC/m²" :disabled="true"
                                                    v-model="prices.remise.ht_per_meter"></d-input>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="Total HT" :disabled="true"
                                                    v-model="prices.remise.total_ht"></d-input>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="Total TTC" :disabled="true"
                                                    v-model="prices.remise.total_ttc"></d-input>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="Net/sqft" :disabled="true"
                                                    v-model="prices.remise.ht_per_sqft"></d-input>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="IAT/sqft" :disabled="true"
                                                    v-model="prices.remise.ht_per_sqft"></d-input>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-radio">
                                            <input type="checkbox" class="custom-control-input"
                                                id="applyProposedDiscount" name="applyProposedDiscount"
                                                v-model="data.quoteDetail.applyProposedDiscount" value="true" />
                                            <label class="custom-control-label text-black" for="applyProposedDiscount">
                                                Appliquer remise
                                                proposée </label>
                                        </div>
                                        <d-input v-model="data.quoteDetail.proposedDiscountRate"
                                            :disabled="!data.quoteDetail.applyProposedDiscount"></d-input>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 mb-3 pe-0 align-items-center">
                            <div class="col-md-12">
                                <d-panel-title title="Prix proposée avant remise complémentaire"
                                    className="ps-2"></d-panel-title>
                                <div class="row pe-4 align-items-center">
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="HT/m²" :disabled="true"
                                                    v-model="prices.tarif_avant_remise_complementaire.ht_per_meter"></d-input>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="TTC/m²" :disabled="true"
                                                    v-model="prices.tarif_avant_remise_complementaire.ht_per_meter"></d-input>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="Total HT"
                                                    :disabled="!data.quoteDetail.calculateFromTotalExcludingTax"
                                                    v-model="prices.tarif_avant_remise_complementaire.total_ht"></d-input>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="Total TTC" :disabled="true"
                                                    v-model="prices.tarif_avant_remise_complementaire.total_ttc"></d-input>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="Net/sqft" :disabled="true"
                                                    v-model="prices.tarif_avant_remise_complementaire.ht_per_sqft"></d-input>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="IAT/sqft" :disabled="true"
                                                    v-model="prices.tarif_avant_remise_complementaire.ht_per_sqft"></d-input>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-radio">
                                            <input type="checkbox" class="custom-control-input"
                                                id="calculateFromTotalExcludingTax"
                                                name="calculateFromTotalExcludingTax"
                                                v-model="data.quoteDetail.calculateFromTotalExcludingTax"
                                                value="true" />
                                            <label class="custom-control-label text-black"
                                                for="calculateFromTotalExcludingTax"> Calculer a
                                                partir de total HT </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 mb-3 pe-0 align-items-center">
                            <div class="col-md-12">
                                <d-panel-title title="Prix proposée" className="ps-2"></d-panel-title>
                                <div class="row pe-4 align-items-center">
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="HT/m²" :disabled="true"
                                                    v-model="prices.tarif_propose.ht_per_meter"></d-input>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="TTC/m²" :disabled="true"
                                                    v-model="prices.tarif_propose.ht_per_meter"></d-input>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="Total HT" :disabled="true"
                                                    v-model="prices.tarif_propose.total_ht"></d-input>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="Total TTC" :disabled="true"
                                                    v-model="prices.tarif_propose.total_ttc"></d-input>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="Net/sqft" :disabled="true"
                                                    v-model="prices.tarif_propose.ht_per_sqft"></d-input>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <d-input label="IAT/sqft" :disabled="true"
                                                    v-model="prices.tarif_propose.ht_per_sqft"></d-input>
                                            </div>
                                        </div>
                                    </div>
                                    <!--div class="col-md-3">
                                        <d-currency v-model="data.quoteDetail.currencyId"></d-currency>
                                        <div class="row align-items-center justify-content-center pt-1">
                                            <div class="col-auto">
                                                <button class="btn btn-custom ps-4 pe-4 font-size-0-6">Calculer</button>
                                            </div>
                                        </diiv>
                                    </div-->
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 mb-3 pe-0">
                            <div class="col-md-4 col-sm-12">
                                <div class="row align-items-center p-2">
                                    <div class="col-md-12">
                                        <d-input label="% prix total"
                                            v-model="data.quoteDetail.impactOnTheQuotePrice"></d-input>
                                    </div>
                                </div>
                                <div class="row align-items-center p-2">
                                    <div class="col-sm-12 col-md-4 text-black">Commentaire:</div>
                                    <div class="col-sm-12 col-md-8">
                                        <textarea v-model="data.quoteDetail.comment"
                                            class="block-custom-border w-100 h-200-forced"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <d-panel-title title="Acompte" className="ps-2"></d-panel-title>
                                <div class="row align-items-center p-2">
                                    <div class="col-md-6 col-sm-12">
                                        <d-input :disabled="true" label="N° tapis dans la commande"
                                            v-model="data.withoutDiscountPrice"></d-input>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <d-input :disabled="true" label="RN" v-model="data.totalTaxExcluded"></d-input>
                                    </div>
                                </div>
                                <div class="row align-items-center p-2">
                                    <div class="col-md-6 col-sm-12">
                                        <d-input :disabled="true" label="N° tapis dans la commande"
                                            v-model="data.cumulatedDiscountAmount"></d-input>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <d-input :disabled="true" label="Délai SUP (modifie client)"
                                            v-model="data.totalTaxIncluded"></d-input>
                                    </div>
                                </div>
                                <div class="row align-items-center p-2">
                                    <div class="col-md-6 col-sm-12">
                                        <d-input :disabled="true" label="Date de livraison prévu"
                                            v-model="data.additionalDiscount"></d-input>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <d-input :disabled="true" label="Acompte réçu HT" v-model="data.tax"></d-input>
                                    </div>
                                </div>
                                <div class="row align-items-center p-2">
                                    <div class="col-md-6 col-sm-12">
                                        <d-input :disabled="true" label="Date de l'acompte"
                                            v-model="data.totalDiscountAmount"></d-input>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <d-input :disabled="true" label="Comp.prescr. acompte"
                                            v-model="data.otherTva"></d-input>
                                    </div>
                                </div>
                                <div class="row align-items-center justify-content-end p-2">
                                    <div class="col-md-12">
                                        <d-input :disabled="true" label="Solde"
                                            v-model="data.totalTaxIncluded"></d-input>
                                    </div>
                                </div>
                                <div class="row align-items-center justify-content-end p-2">
                                    <div class="col-md-12">
                                        <div class="custom-control custom-radio">
                                            <input disabled type="checkbox" class="custom-control-input"
                                                id="quoteSentToCustomer" v-model="data.quoteSentToCustomer"
                                                name="quoteSentToCustomer" />
                                            <label class="custom-control-label text-black" for="quoteSentToCustomer">
                                                commande sans acompte
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </template>
            </d-panel>
        </template>
        <template v-slot:footer>
            <div class="row p-2 justify-content-between">
                <div class="col-auto">
                    <button class="btn btn-custom pe-5 ps-5" @click="goToDevis">Retour à la page devis</button>
                </div>
                <div class="col-auto">
                    <div class="row">
                        <div class="col-auto" v-if="quoteDetailId">
                            <button class="btn btn-custom pe-5 ps-5" @click="saveDevisDetails(false)">Enregistrer &
                                Rester</button>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-custom pe-5 ps-5"
                                @click="saveDevisDetails(true)">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </d-base-page>
</template>

<script setup>
import moment from 'moment';
import { useStore } from 'vuex';
import VueFeather from 'vue-feather';
import { useRoute, useRouter } from 'vue-router';
import { ref, onMounted, watch } from 'vue';
import { useMeta } from '/src/composables/use-meta';
import { Helper, formatErrorViolations, formatErrorViolationsComposed } from '../../../composables/global-methods';
import axiosInstance from '../../../config/http';
import contremarqueService from '../../../Services/contremarque-service';
import quoteService from '../../../Services/quote-service';
import dContremarqueDropdown from '../../../components/common/d-contremarque-dropdown.vue';
import dPrescripteurDropdown from '../../../components/common/d-prescripteur-dropdown.vue';
import dBaseDropdown from '../../../components/base/d-base-dropdown.vue';
import dDiscount from '../../../components/common/d-discount.vue';
import dBasePage from '../../../components/base/d-base-page.vue';
import dInput from '../../../components/base/d-input.vue';
import dPageTitle from '../../../components/common/d-page-title.vue';
import dPanelTitle from '../../../components/common/d-panel-title.vue';
import dPanel from '../../../components/common/d-panel.vue';
import dCommertialHistories from '../../../components/contacts/_partial/d-commertial-histories.vue';
import dCustomerDropdown from '../../../components/common/d-customer-dropdown.vue';
import dEventHistories from '../../../components/contacts/_partial/d-event-histories.vue';
import dLocations from '../../../components/projet/contremarques/d-locations.vue';
import dConversions from '../../../components/common/d-conversions.vue';
import dCurrency from '../../../components/common/d-currency.vue';
import dLangages from '../../../components/common/d-langages.vue';
import dUnitMeasurements from '../../../components/common/d-unit-measurements.vue';
import dTarifs from '../../../components/common/d-tarifs.vue';
import dTransportCondition from '../../../components/common/d-transportCondition.vue';
import dQuoteDetails from '../../../components/projet/devis/d-quote-details.vue';
import dCollectionsDropdown from '../../../components/projet/contremarques/dropdown/d-collections-dropdown.vue';
import dModelDropdown from '../../../components/projet/contremarques/dropdown/d-model-dropdown.vue';
import dLocationDropdown from '../../../components/projet/contremarques/dropdown/d-location-dropdown.vue';
import dQualitiesDropdown from '../../../components/projet/contremarques/dropdown/d-qualities-dropdown.vue';
import dMaterialsList from '../../../components/projet/contremarques/_Partials/d-materials-list.vue';
import dSpecialShapes from '../../../components/common/d-special-shapes.vue';
import dMesurementQuote from '../../../components/projet/devis/d-mesurement-quote.vue';
import dSpecialTreatment from '../../../components/common/d-specialTreatment.vue';
import dSpecificTreatmentForm from '../../../components/projet/devis/d-specific-treatment-form.vue';
import dImageDevisAttribution from './d-imageDevisAttribution.vue';
useMeta({ title: 'Gestion Devis' });

const route = useRoute();
const router = useRouter();
const store = useStore();
const contremarqueId = ref(0);
const quote_id = route.params.qouteId;
const quoteDetailId = route.params.id;
const quoteNumber = ref('');
const carpetNumber = ref('');
const createdDate = ref(moment().format('YYYY-MM-DD'));
const quote = ref({});
const quoteDetail = ref([]);
const useSpecialShape = ref(false);
const error = ref({});
const specialTreatment = ref({
    treatmentId: 0,
    unitPrice: '',
    totalPrice: '',
    trait: false,
});
const data = ref({
    quoteDetail: {
        locationId: 0,
        reference: '',
        TarifId: 0,
        currencyId: 0,
        totalPriceRate: '',
        isValidated: true,
        validatedAt: null,
        wantedQuantity: 0,
        estimatedDeliveryTime: 0,
        applyLargeProjectRate: null,
        applyProposedDiscount: null,
        proposedDiscountRate: null,
        calculateFromTotalExcludingTax: null,
        inStockCarpet: null,
        comment: null,
        rn: '',
        specificTreatmentIds: [],
        impactOnTheQuotePrice: '',
    },
    carpetSpecification: {
        reference: '',
        description: '',
        collectionId: 0,
        modelId: 0,
        qualityId: 0,
        hasSpecialShape: false,
        isOversized: false,
        specialShapeId: 0,
        dimensions: [],
        materials: [],
        randomWeight: 0,
    },
});
const currentCustomer = ref({});
const prices = ref({
    tarif: {
        ht_per_meter: 0,
        total_ht: 0,
        ht_per_sqft: 0,
        total_ttc: 0,
    },
    grand_public: {
        total_ht: 0,
        total_ttc: 0,
        ht_per_meter: 0,
        ht_per_sqft: 0,
    },
    remise: {
        ht_per_meter: 0,
        ht_per_sqft: 0,
        total_ht: 0,
        total_ttc: 0,
    },
    tarif_avant_remise_complementaire: {
        ht_per_meter: 0,
        ht_per_sqft: 0,
        total_ht: 0,
        total_ttc: 0,
    },
    tarif_propose: {
        ht_per_meter: 0,
        ht_per_sqft: 0,
        total_ht: 0,
        total_ttc: 0,
    },
});

let disableAutoSave = true;

const errorHandling = ref({});
const validationSubmitErrors = ref({
    collectionId: '',
    modelId: '',
    locationId: '',
    tarifId: '',
    qualityId: '',
});
// Validation function
const validationSubmitData = () => {
    validationSubmitErrors.value.collectionId = parseInt(data.value.carpetSpecification.collectionId) !== 0 ? '' : 'la collection est requis';
    validationSubmitErrors.value.modelId = parseInt(data.value.carpetSpecification.modelId) !== 0 ? '' : 'Le modele est requis';
    validationSubmitErrors.value.qualityId = parseInt(data.value.carpetSpecification.qualityId) !== 0 ? '' : 'la qualité est requis';
    validationSubmitErrors.value.locationId = parseInt(data.value.quoteDetail.locationId) !== 0 ? '' : 'Le genre est requis';
    validationSubmitErrors.value.tarifId = parseInt(data.value.quoteDetail.TarifId) !== 0 ? '' : 'Le tarif est requis';
    console.log("test : ", validationSubmitErrors.value.tarifId, data.value.quoteDetail.TarifId)
};
const saveDevisDetails = async () => {
    try {
        error.value = {};
        if (quote_id) {
            const measurements = store.getters.measurements;
            const dataToSent = Object.assign({}, data.value);
            dataToSent.carpetSpecification.dimensions = measurements.reduce((acc, dimension) => {
                acc[dimension.id] = dimension.unit.map((u) => {
                    return {
                        dimension_id: u.id,
                        value: u.value ? parseFloat(u.value) : 0,
                    };
                });
                return acc;
            }, {});

            dataToSent.carpetSpecification.materials = store.getters.materials;
            dataToSent.quoteDetail.wantedQuantity = parseInt(dataToSent.quoteDetail.wantedQuantity);
            dataToSent.quoteDetail.proposedDiscountRate = parseFloat(dataToSent.quoteDetail.proposedDiscountRate);

            if (quoteDetailId) {
                validationSubmitData();
                if (Object.values(validationSubmitErrors.value).some((error) => error !== '')) {
                    console.log('There are validation errors.', validationSubmitErrors.value);
                    return; // Stop further execution if there are errors
                }
                const res = await axiosInstance.put(`/api/Quote/${quote_id}/updateQuoteDetail/${quoteDetailId}`, dataToSent);
                window.showMessage('Mise a jour avec succées.');
                if (leave) {
                    setTimeout(() => {
                        goToDevis();
                    }, 2000);
                }
            } else {
                validationSubmitData();
                if (Object.values(validationSubmitErrors.value).some((error) => error !== '')) {
                    console.log('There are validation errors.', validationSubmitErrors.value);
                    return; // Stop further execution if there are errors
                }
                console.log('dataTosent: ', dataToSent);
                const respn = await axiosInstance.post(`/api/Quote/${quote_id}/createQuoteDetail`, dataToSent);
                window.showMessage('Ajout avec succées.');
                setTimeout(() => {
                    router.push({ name: 'devisDetails', params: { qouteId: quote_id, id: respn.data.response.quoteDetail.id } });
                }, 2000);
            }
        } else {
            window.showMessage('Veuillez sélectionner une contremarque valide.', 'error');
        }
    } catch (e) {
        if (e.response.data.violations) {
            error.value = formatErrorViolationsComposed(e.response.data.violations);
            console.log(error.value);
        }
        window.showMessage(e.message, 'error');
    }
};

const getQuote = async (quote_id) => {
    try {
        if (quote_id) {
            quote.value = await quoteService.getQuoteById(quote_id);
            // Set defaultCustomTarifId if exists
            if (quote.value?.defaultCustomTarifId) {
                data.value.quoteDetail.TarifId = quote.value.defaultCustomTarifId;
                console.log('data.value.quoteDetail.TarifId', data.value.quoteDetail.TarifId);
            }
            contremarqueId.value = quote.value.contremarqueId;
            quoteNumber.value = quote.value.reference;
            data.value.quoteDetail.currencyId = quote.value?.currency.id;
            createdDate.value = moment(quote.value.createdAt).format('YYYY-MM-DD');
        }
    } catch (e) {
        console.log(e);
        const msg = "le devis d'id " + quote_id + " n'existe pas";
        window.showMessage(msg, 'error');
    }
};
const changePrices = async (price) => {
    applyStopAutoSave();
    if (price.tarif && price.grand_public) {
        prices.value = price;
    }
};
const getQuoteDetails = async (quoteDetailId) => {
    try {
        applyStopAutoSave();
        if (quoteDetailId) {
            quoteDetail.value = await quoteService.getQuoteDetailsById(quoteDetailId);
            carpetNumber.value = quoteDetail.value.reference;
            if (quoteDetail.value.prices) {
                formatPrices(quoteDetail.value.prices);
            }
            data.value = {
                quoteDetail: {
                    locationId: quoteDetail.value.location?.location_id,
                    reference: quoteDetail.value.reference,
                    TarifId: quoteDetail.value?.tarif.id,
                    currencyId: quoteDetail.value.currency ? quoteDetail.value.currency.id : quote.value?.currency.id,
                    totalPriceRate: quoteDetail.value.totalPriceRate,
                    isValidated: quoteDetail.value.isValidated,
                    validatedAt: null,
                    wantedQuantity: quoteDetail.value.wantedQuantity,
                    estimatedDeliveryTime: parseInt(quoteDetail.value.estimatedDeliveryTime),
                    applyLargeProjectRate: quoteDetail.value.applyLargeProjectRate,
                    applyProposedDiscount: quoteDetail.value.applyProposedDiscount,
                    proposedDiscountRate: Helper.FormatNumber(quoteDetail.value.proposedDiscountRate),
                    calculateFromTotalExcludingTax: quoteDetail.value.calculateFromTotalExcludingTax,
                    inStockCarpet: quoteDetail.value.inStockCarpet,
                    comment: quoteDetail.value.comment,
                    rn: quoteDetail.value.rn,
                    specificTreatmentIds: quoteDetail.value.specificTreatmentIds,
                    impactOnTheQuotePrice: Helper.FormatNumber(quoteDetail.value.impactOnTheQuotePrice),
                },
                carpetSpecification: {
                    reference: '',
                    description: quoteDetail.value.carpetSpecification.description,
                    collectionId: quoteDetail.value.carpetSpecification?.collection?.id,
                    modelId: quoteDetail.value.carpetSpecification?.model?.id,
                    qualityId: quoteDetail.value.carpetSpecification?.quality?.id,
                    hasSpecialShape: quoteDetail.value.carpetSpecification.has_special_shape,
                    isOversized: quoteDetail.value.carpetSpecification.is_oversized,
                    specialShapeId: quoteDetail.value.carpetSpecification?.specialShape?.id,
                    dimensions: [],
                    materials: [],
                    randomWeight: quoteDetail.value.carpetSpecification?.randomWeight,
                },
            };
        }
    } catch (e) {
        console.log(e);
        const msg = e;
        window.showMessage(msg, 'error');
    }
};
const formatPrice = (priceCategory, price) => {
    return {
        total_ht: Helper.FormatNumber(price[priceCategory] ? price[priceCategory].totalPriceHt : 0),
        total_ttc: Helper.FormatNumber(price[priceCategory] ? price[priceCategory].totalPriceTtc : 0),
        ht_per_meter: Helper.FormatNumber(price[priceCategory] && price[priceCategory]['m²'] ? price[priceCategory]['m²']?.price : 0),
        ht_per_sqft: Helper.FormatNumber(price[priceCategory] && price[priceCategory].sqft ? price[priceCategory].sqft?.price : 0),
    };
};
const formatPrices = (price) => {
    prices.value = {
        tarif: formatPrice('tarif', price),
        grand_public: formatPrice('tarif-grand-projet', price),
        remise: formatPrice('remise-proposee', price),
        tarif_propose: {
            total_ht: Helper.FormatNumber(price['prix-propose']?.totalPriceHt),
            total_ttc: Helper.FormatNumber(price['prix-propose']?.totalPriceTtc),
            ht_per_meter: Helper.FormatNumber(price['prix-propose'] ? price['prix-propose']['m²']?.price : 0),
            ht_per_sqft: Helper.FormatNumber(price['prix-propose']?.sqft?.price),
        },
        tarif_avant_remise_complementaire: formatPrice('prix-propose-avant-remise-complementaire', price),
    };
};
const applyStopAutoSave = () => {
    disableAutoSave = true;
    setTimeout(() => {
        disableAutoSave = false;
    }, 5000);
};
onMounted(() => {
    if (quote_id) {
        getQuote(quote_id);
        modalInstance = new bootstrap.Modal(document.getElementById('confirmModal'));
    }
    if (quoteDetailId) {
        getQuoteDetails(quoteDetailId);
    }
});

const SaveTraitement = async () => {
    try {
        error.value = {};
        if (quoteDetailId) {
            const res = await axiosInstance.post(`/api/quote-detail/${quoteDetailId}/carpet-specific-treatment/create`, specialTreatment.value);
            window.showMessage('Ajout avec succées.');
        }
    } catch (e) {
        if (e.response.data.violations) {
            error.value = formatErrorViolations(e.response.data.violations);
        }
        window.showMessage(e.message, 'error');
    }
};
const goToDevis = () => {
    router.push({ name: 'devisManage', params: { id: quote_id } });
};
const changeWeight = async (weight) => {
    data.value.carpetSpecification.randomWeight = parseFloat(weight);
    await saveAndCalculate();
};
const UpdateTreatments = async () => {
    await saveAndCalculate();
};
const saveAndCalculate = async () => {
    if (quoteDetailId && !disableAutoSave) {
        await saveDevisDetails();
        document.getElementById('clickConvertCalculation').click();
    }
};

watch(
    () => [
        data.value.quoteDetail.applyLargeProjectRate,
        data.value.quoteDetail.applyProposedDiscount,
        data.value.quoteDetail.TarifId,
        data.value.quoteDetail.calculateFromTotalExcludingTax,
        data.value.carpetSpecification.collectionId,
        data.value.carpetSpecification.modelId,
        data.value.carpetSpecification.specialShapeId,
        data.value.carpetSpecification.qualityId,
        data.value.carpetSpecification.hasSpecialShape,
        data.value.quoteDetail.proposedDiscountRate,
        data.value.quoteDetail.wantedQuantity,
        prices.value?.tarif_avant_remise_complementaire?.total_ht,
    ],

    async (newCarpert, oldCarpet) => {
        await saveAndCalculate();
    },
    { deep: true }
);
watch(
    () => data.value.carpetSpecification.hasSpecialShape,
    (specialShape) => {
        if (!specialShape) {
            data.value.carpetSpecification.specialShapeId = 0;
        } else {
            data.value.carpetSpecification.specialShapeId = 1;
        }
    },
    { deep: true }
);

watch(
    () => data.value.carpetSpecification.specialShapeId,
    (specialShapeId) => {
        if (specialShapeId === 0) {
            data.value.carpetSpecification.hasSpecialShape = false;
        } else {
            data.value.carpetSpecification.hasSpecialShape = true;
        }
    },
    { deep: true }
);

const isModalOpen = ref(false);
const newTarifId = ref(null);
let modalInstance = null;

const openModal = (tarifId) => {
    newTarifId.value = tarifId;
    isModalOpen.value = true;
    modalInstance.show();
};

const closeModal = () => {
    isModalOpen.value = false;
    modalInstance.hide();
};

const confirmTarifChange = () => {
    // Apply the change
    isModalOpen.value = false;
    modalInstance.hide();
};
</script>
<style scoped>
.row {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
}

.row>[class*='col-'] {
    display: flex;
    flex-direction: column;
}

.modal-content {
    text-align: center;
}
</style>
