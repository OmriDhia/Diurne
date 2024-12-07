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
                          <d-input type="date" label="Date de création" v-model="createdDate" :disabled="true"></d-input>
                      </div>
                      <div class="col-md-4 col-sm-12">
                          <d-contremarque-dropdown v-model="contremarqueId"></d-contremarque-dropdown>
                      </div>
                  </div>
              </template>
          </d-panel>
        </template>
        <template v-slot:body>
            <d-panel v-if="contremarqueId">
                <template v-slot:panel-body>
                    <div class="row mt-3 mb-3 pe-0">
                        <div class="col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Client de contremarque" className="ps-2"></d-panel-title>
                            <div class="row pe-2 ps-0">
                                <d-customer-dropdown :showCustomer="true" :required="true" v-model="selectedCustomer" :error="error.customer_id"></d-customer-dropdown>
                            </div>
                            <div class="row pe-2 ps-0" v-if="currentCustomer.contactsData">
                                <d-base-dropdown name="Contact" label="firstname" trackBy="contact_id" :datas="currentCustomer.contactsData" v-model="contact"></d-base-dropdown>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Prescripteur" className="ps-2"></d-panel-title>
                            <div class="row pe-2 ps-0">
                                <d-customer-dropdown :isPrescripteur="true" v-model="prescriber" :error="error.prescriber_id"></d-customer-dropdown>
                            </div>
                            <div class="row pe-2 ps-0 align-items-center">
                                <d-input label="Commission (%)" type="Number" v-model="data.commission" :error="null"></d-input>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3 pe-0">
                        <div class="col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Commercial" className="ps-2"></d-panel-title>
                            <div class="row pe-2 ps-0" v-if="currentCustomer.contactCommercialHistoriesData">
                                <d-base-dropdown name="Commercial" label="firstname" trackBy="commercial_id" :datas="currentCustomer.contactCommercialHistoriesData" v-model="commercial"></d-base-dropdown>
                            </div>
                            <div class="row pe-2 ps-0">
                                <d-input label="Auteur" v-model="data.auteur" :error="error.auteur"></d-input>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Cractéristique tarif" className="ps-2"></d-panel-title>
                            <div class="row pe-4 align-items-center">
                                <div class="col-md-6 col-sm-12 pe-sm-0">
                                    <d-tarifs v-model="data.taxRuleId" :error="error.taxRuleId"></d-tarifs>
                                </div>
                                <div class="col-md-6 col-sm-12 pe-sm-0">
                                    <d-conversions v-model="data.conversionId" :error="error.conversionId"></d-conversions>
                                </div>
                            </div>
                            <div class="row pe-4 align-items-center">
                                <div class="col-md-6 col-sm-12 pe-sm-0">
                                    <d-currency v-model="data.currencyId" :error="error.currencyId"></d-currency>
                                </div>
                                <div class="col-md-6 col-sm-12 pe-sm-0">
                                    <d-langages v-model="data.languageId" :error="error.languageId"></d-langages>
                                </div>
                            </div>
                            <div class="row pt-2">
                                <d-unit-measurements :selectList="true" v-model="data.unitOfMeasurementId" :error="error.unitOfMeasurementId"></d-unit-measurements>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3 pe-0">
                        <div class="col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Autres informations" className="ps-2"></d-panel-title>
                            <div class="row pe-2 ps-0">
                                <d-transport-condition v-model="data.transportCond" :error="error.customer_id"></d-transport-condition>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Adresses" className="ps-2"></d-panel-title>
                            <div class="row pe-2 ps-0" v-if="currentCustomer.addressesData">
                                <d-base-dropdown name="Adresse de livraison" label="address1" trackBy="address_id" :datas="currentCustomer.addressesData" v-model="data.deliveryAddressId"></d-base-dropdown>
                            </div>
                            <div class="row pe-2 ps-0 align-items-center" v-if="currentCustomer.addressesData">
                                <d-base-dropdown name="Adresse de facturation" label="address1" trackBy="address_id" :datas="currentCustomer.addressesData" v-model="data.invoiceAddressId"></d-base-dropdown>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3 pe-0">
                        <d-quote-details :quoteDetails="quoteDetails"></d-quote-details>
                    </div>
                    <div class="row mt-3 mb-3 pe-0">
                        <div class="col-md-4 col-sm-12">
                            
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="card p-0">
                                <div class="card-body p-0 mt-2">
                                    <div class="row align-items-center p-2">
                                        <div class="col-md-6 col-sm-12">
                                            <d-input label="Prix avant remise" v-model="data.withoutDiscountPrice"></d-input>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <d-input label="Total HT" v-model="data.totalTaxExcluded"></d-input>
                                        </div>
                                    </div>
                                    <div class="row align-items-center p-2">
                                        <div class="col-md-6 col-sm-12">
                                            <d-input label="Remise tapis cumulé" v-model="data.cumulatedDiscountAmount"></d-input>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <d-input label="Total versement" v-model="data.totalTaxIncluded"></d-input>
                                        </div>
                                    </div>
                                    <div class="row align-items-center p-2">
                                        <div class="col-md-6 col-sm-12">
                                            <d-input label="Remise complémentaire (HT)" v-model="data.additionalDiscount"></d-input>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <d-input label="TVA" v-model="data.tax"></d-input>
                                        </div>
                                    </div>
                                    <div class="row align-items-center p-2">
                                        <div class="col-md-6 col-sm-12">
                                            <d-input label="Total remise de devis" v-model="data.totalDiscountAmount"></d-input>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <d-input label="Autre Tva" v-model="data.otherTva"></d-input>
                                        </div>
                                    </div>
                                    <div class="row align-items-center justify-content-end p-2">
                                        <div class="col-md-6 col-sm-12">
                                            <d-input label="Total TTC + port" v-model="data.totalTaxIncluded"></d-input>
                                        </div>
                                    </div>
                                    <div class="row align-items-center justify-content-end p-2">
                                        <div class="col-md-6 col-sm-12">
                                            <button class="btn btn-custom pe-5 ps-5">Calculer</button>
                                        </div>
                                    </div>
                                    <div class="row align-items-center justify-content-end p-2">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="quoteSentToCustomer" v-model="data.quoteSentToCustomer"
                                                       name="quoteSentToCustomer"/>
                                                <label class="custom-control-label text-black" for="quoteSentToCustomer">
                                                    Devis expédié au client </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center justify-content-end p-2">
                                        <div class="col-md-8 col-sm-12">
                                            <d-input label="Expédition devis" v-model="data.qualificationMessage"></d-input>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </d-panel>
        </template>
        <template v-slot:footer>
            <div class="row p-2 justify-content-between">
                <div class="col-auto">
                    <button class="btn btn-custom pe-5 ps-5" @click="goToDevis">Retour à la page devis</button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-custom pe-5 ps-5" @click="saveDevisDetails">Enregistrer</button>
                </div>
            </div>
        </template>
    </d-base-page>
</template>

<script setup>
    import VueFeather from 'vue-feather';
    import { useRoute } from 'vue-router';
    import moment from "moment";
    import {ref, onMounted, watch} from 'vue';
    import {useMeta} from '/src/composables/use-meta';
    import { Helper, formatErrorViolations } from "../../../composables/global-methods";
    import axiosInstance from "../../../config/http";
    import contremarqueService from "../../../Services/contremarque-service"
    import quoteService from "../../../Services/quote-service"
    import dContremarqueDropdown from "../../../components/common/d-contremarque-dropdown.vue";
    import dPrescripteurDropdown from "../../../components/common/d-prescripteur-dropdown.vue"
    import dBaseDropdown from "../../../components/base/d-base-dropdown.vue";
    import dDiscount from "../../../components/common/d-discount.vue";
    import dBasePage from "../../../components/base/d-base-page.vue"
    import dInput from "../../../components/base/d-input.vue"
    import dPageTitle from "../../../components/common/d-page-title.vue"
    import dPanelTitle from "../../../components/common/d-panel-title.vue";
    import dPanel from "../../../components/common/d-panel.vue";
    import dCommertialHistories from "../../../components/contacts/_partial/d-commertial-histories.vue"
    import dCustomerDropdown from "../../../components/common/d-customer-dropdown.vue";
    import dEventHistories from "../../../components/contacts/_partial/d-event-histories.vue";
    import dLocations from "../../../components/projet/contremarques/d-locations.vue";
    import dConversions from "../../../components/common/d-conversions.vue";
    import dCurrency from "../../../components/common/d-currency.vue";
    import dLangages from "../../../components/common/d-langages.vue";
    import dUnitMeasurements from "../../../components/common/d-unit-measurements.vue";
    import dTarifs from "../../../components/common/d-tarifs.vue";
    import dTransportCondition from "../../../components/common/d-transportCondition.vue";
    import dQuoteDetails from "../../../components/projet/devis/d-quote-details.vue";
    
    useMeta({ title: 'Gestion Devis' });

    const route = useRoute();
    const contremarqueId = ref(0);
    const quote_id = route.params.qouteId;
    const detailsQuoteId = route.params.id;
    const quoteNumber = ref("");
    const createdDate = ref(moment().format('YYYY-MM-DD'));
    const quote = ref({});
    const quoteDetails = ref([]);
    const error = ref({});
    
    const data = ref({
        discountRuleId: 0,
        taxRuleId: 0,
        currencyId: 0,
        languageId: 0,
        unitOfMeasurementId: 0,
        deliveryAddressId: 0,
        invoiceAddressId: 0,
        withoutDiscountPrice: 0,
        additionalDiscount: 0,
        totalDiscountAmount: 0,
        totalDiscountPercentage: 0,
        totalTaxExcluded: 0,
        shippingPrice: 0,
        tax: 0,
        totalTaxIncluded: 0,
        quoteSentToCustomer: true,
        qualificationMessage: "",
        conversionId: 0,
        cumulatedDiscountAmount: 0,
        otherTva: 0,
    });
    const currentCustomer = ref({});

    const saveDevisDetails = async () => {
        try{
            error.value = {};
            if(contremarque){
                const dataTosend = {
                    discountRuleId: data.value.discountRuleId,
                    taxRuleId: data.value.taxRuleId,
                    currencyId: data.value.currencyId,
                    languageId: data.value.languageId,
                    unitOfMeasurementId: data.value.unitOfMeasurementId,
                    deliveryAddressId: data.value.deliveryAddressId.address_id,
                    invoiceAddressId: data.value.invoiceAddressId.address_id,
                    withoutDiscountPrice: parseFloat(data.value.withoutDiscountPrice),
                    additionalDiscount: parseFloat(data.value.additionalDiscount),
                    totalDiscountAmount: parseFloat(data.value.totalDiscountAmount),
                    totalDiscountPercentage: parseFloat(data.value.totalDiscountPercentage),
                    totalTaxExcluded: parseFloat(data.value.totalTaxExcluded),
                    shippingPrice: parseFloat(data.value.shippingPrice),
                    tax: parseFloat(data.value.tax),
                    totalTaxIncluded: parseFloat(data.value.totalTaxIncluded),
                    quoteSentToCustomer: data.value.quoteSentToCustomer,
                    qualificationMessage: data.value.qualificationMessage,
                    conversionId: data.value.conversionId,
                    cumulatedDiscountAmount: parseFloat(data.value.cumulatedDiscountAmount),
                    otherTva: parseFloat(data.value.otherTva),
                }
                if(quote_id){
                    const res = await axiosInstance.put(`/api/contremarque/${contremarqueId.value}/quote/${quote_id}`,dataTosend);
                    window.showMessage("Mise a jour avec succées.")
                }else{
                    const respn = await axiosInstance.post(`/api/contremarque/${contremarqueId.value}/createQuote`,dataTosend);
                    window.showMessage("Ajout avec succées.")
                } 
                setTimeout(()=>{
                    goToDevisList();
                }, 2000);
            }else{
                window.showMessage("Veuillez sélectionner une contremarque valide.","error")
            }
            
        }catch(e){
            if(e.response.data.violations){
                error.value = formatErrorViolations(e.response.data.violations)
            }
            window.showMessage(e.message,'error')
        }
    };

    const getQuote = async (quote_id) => {
        try{
            if(quote_id){
                quote.value = await quoteService.getQuoteById(quote_id);
                contremarqueId.value = quote.value.contremarques[0]?.contremarque_id;
                quoteNumber.value = quote.value.reference;
                quoteDetails.value = quote.value.quoteDetails;
                createdDate.value = moment(quote.value.createdAt).format('YYYY-MM-DD');
            }
        }catch(e){
            console.log(e);
            const msg = "le devis d'id " + quote_id + " n'existe pas";
            window.showMessage(msg,'error');
        }
    };
    onMounted(() => {
       if(quote_id){
           getQuote(quote_id);
       }
    });

    const goToDevis = () => {
        location.href = `/projet/devis/manage/${quote_id}`;
    };
</script>
<style scoped>
    .row {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display:         flex;
    }
    .row > [class*='col-'] {
        display: flex;
        flex-direction: column;
    }
</style>
