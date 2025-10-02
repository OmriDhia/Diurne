<template>
    <d-base-page :loading="loading">
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
                            <d-contremarque-dropdown v-model="contremarqueId" customer-id=""></d-contremarque-dropdown>
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
                                <d-customer-dropdown :disabled="disbledContremarque" :showCustomer="true" :required="true" v-model="selectedCustomer" :error="error.customer_id"></d-customer-dropdown>
                            </div>
                            <div class="row pe-2 ps-0" v-if="currentCustomer.contactsData">
                                <d-base-dropdown
                                    :disabled="disbledContremarque"
                                    name="Contact"
                                    label="lastname"
                                    trackBy="contact_id"
                                    :datas="currentCustomer.contactsData"
                                    v-model="contact"
                                ></d-base-dropdown>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Prescripteur" className="ps-2"></d-panel-title>
                            <div class="row pe-2 ps-0">
                                <d-customer-dropdown :disabled="disbledContremarque" :isPrescripteur="true" v-model="prescriber" :error="error.prescriber_id"></d-customer-dropdown>
                            </div>
                            <div class="row pe-2 ps-0 align-items-center">
                                <d-input :disabled="disbledContremarque" label="Commission (%)" v-model="commission" :error="null"></d-input>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3 pe-0">
                        <div class="col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Commercial" className="ps-2"></d-panel-title>
                            <div class="row pe-2 ps-0" v-if="currentCustomer.contactCommercialHistoriesData">
                                <d-base-dropdown
                                    :disabled="disbledContremarque"
                                    name="Commercial"
                                    label="lastname"
                                    trackBy="commercial_id"
                                    :datas="currentCustomer.contactCommercialHistoriesData"
                                    v-model="commercial"
                                ></d-base-dropdown>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Cractéristique tarif" className="ps-2"></d-panel-title>
                            <div class="row pe-4 align-items-center">
                                <div class="col-md-6 col-sm-12 pe-sm-0">
                                    <d-taxRules :required="true" v-model="data.taxRuleId" :error="error.taxRuleId"></d-taxRules>
                                </div>
                                <div class="col-md-6 col-sm-12 pe-sm-0">
                                    <d-conversions :required="data.currencyId === 1" v-model="data.conversionId" :error="error.conversionId"></d-conversions>
                                </div>
                            </div>
                            <div class="row pe-4 align-items-center">
                                <div class="col-md-6 col-sm-12 pe-sm-0">
                                    <d-currency :required="true" v-model="data.currencyId" :error="error.currencyId"></d-currency>
                                </div>
                                <div class="col-md-6 col-sm-12 pe-sm-0">
                                    <d-langages :required="true" v-model="data.languageId" :error="error.languageId"></d-langages>
                                </div>
                            </div>
                            <div class="row pt-2">
                                <d-unit-measurements :required="true" :selectList="true" v-model="data.unitOfMeasurement" :error="error.unitOfMeasurement"></d-unit-measurements>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3 pe-0">
                        <div class="col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Autres informations" className="ps-2"></d-panel-title>
                            <div class="row pe-2 ps-0">
                                <d-transport-condition
                                    :required="true"
                                    v-model="data.transportConditionId"
                                    :error="error.transportConditionId"
                                    :language-id="data.languageId"
                                ></d-transport-condition>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 pe-sm-0">
                            <d-panel-title title="Adresses" className="ps-2"></d-panel-title>
                            <div class="row pe-2 ps-0" v-if="currentCustomer.addressesData">
                                <d-base-dropdown
                                    :required="true"
                                    name="Adresse de livraison"
                                    label="address1"
                                    trackBy="address_id"
                                    :datas="deliveryAddresses"
                                    v-model="data.deliveryAddressId"
                                    :error="error.deliveryAddressId"
                                />
                            </div>
                            <div class="row pe-2 ps-0 align-items-center" v-if="currentCustomer.addressesData">
                                <d-base-dropdown
                                    :required="true"
                                    name="Adresse de facturation"
                                    label="address1"
                                    trackBy="address_id"
                                    :datas="invoiceAddresses"
                                    v-model="data.invoiceAddressId"
                                    :error="error.invoiceAddressId"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3 pe-0" v-if="quote_id">
                        <d-quote-details @changeStatus="changeStatusDetails" :contremarque="contremarque" :quoteId="quote_id" :quoteDetails="quoteDetails"></d-quote-details>
                    </div>
                    <div class="row mt-3 mb-3 pe-0">
                        <div class="col-md-4 col-sm-12">
                            <d-input
                                label="frais de port"
                                v-model="data.shippingPrice"
                                :error="error.shippingPrice"
                                :required="data.transportConditionId === 9 || data.transportConditionId === 8"
                                @changeValue="handlePricingFieldBlur"
                            ></d-input>
                            <d-input label="Poids global (kg)" v-model="data.weight" :error="error.weight"></d-input>
                            <d-input type="Date" label="Date commande"></d-input>
                            <div class="row justify-content-center align-items-center mt-5">
                                <div class="col-md-6">
                                    <button class="btn btn-custom font-size-0-7 text-uppercase" data-bs-toggle="modal" data-bs-target="#downloadFactureProforma">Facture proforma</button>
                                    <d-modal-facture-proforma-devis :quoteId="quote_id"></d-modal-facture-proforma-devis>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-custom font-size-0-7 text-uppercase">Copie de devis</button>
                                </div>
                            </div>
                            <div class="row justify-content-center align-items-center mt-2">
                                <div class="col-md-6">
                                    <button class="btn btn-custom font-size-0-7 text-uppercase" data-bs-toggle="modal" data-bs-target="#downloadFacture">Facture d'acompte</button>
                                    <d-modal-facture-devis :quoteId="quote_id"></d-modal-facture-devis>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-custom font-size-0-7 text-uppercase" @click="goToAttachReglement">rattacher un règlement</button>
                                </div>
                            </div>
                            <div class="row justify-content-center align-items-center mt-2">
                                <div class="col-md-6">
                                    <button class="btn btn-custom font-size-0-7 text-uppercase" @click="createCarpetOrder" :disabled="!quote_id || loading">créer un commande tapis</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="card p-0">
                                <div class="card-body p-0 mt-2">
                                    <div class="row align-items-center p-2">
                                        <div class="col-md-6 col-sm-12">
                                            <d-input :disabled="disbledPrices" label="Prix avant remise" v-model="data.withoutDiscountPrice"></d-input>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <d-input :disabled="disbledPrices" label="Total HT" v-model="data.totalTaxExcluded"></d-input>
                                        </div>
                                    </div>
                                    <div class="row align-items-center p-2">
                                        <div class="col-md-6 col-sm-12">
                                            <d-input :disabled="disbledPrices" label="Remise tapis cumulé" v-model="data.cumulatedDiscountAmount"></d-input>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <d-input :disabled="disbledPrices" label="Total versement" v-model="data.totalTaxIncluded"></d-input>
                                        </div>
                                    </div>
                                    <div class="row align-items-center p-2">
                                        <div class="col-md-6 col-sm-12">
                                            <d-input label="Remise complémentaire (HT)" v-model="data.additionalDiscount" @changeValue="handlePricingFieldBlur"></d-input>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <d-input :disabled="disbledPrices" label="TVA" v-model="data.tax"></d-input>
                                        </div>
                                    </div>
                                    <div class="row align-items-center p-2">
                                        <div class="col-md-6 col-sm-12">
                                            <d-input :disabled="disbledPrices" label="Total remise de devis" v-model="data.totalDiscountAmount"></d-input>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <d-input :disabled="disbledPrices" label="Autre Tva" v-model="data.otherTva"></d-input>
                                        </div>
                                    </div>
                                    <div class="row align-items-center justify-content-end p-2">
                                        <div class="col-md-6 col-sm-12">
                                            <d-input :disabled="disbledPrices" label="Total TTC + port" v-model="data.totalTaxIncluded"></d-input>
                                        </div>
                                    </div>
                                    <!--div class="row align-items-center justify-content-end p-2">
                                        <div class="col-md-6 col-sm-12">
                                            <button class="btn btn-custom pe-5 ps-5">Calculer</button>
                                        </div>
                                    </div-->
                                    <div class="row align-items-center justify-content-end p-2">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="custom-control custom-radio">
                                                <input type="checkbox" class="custom-control-input" id="quoteSentToCustomer" v-model="data.quoteSentToCustomer" name="quoteSentToCustomer" />
                                                <label class="custom-control-label text-black" for="quoteSentToCustomer"> Devis expédié au client </label>
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
                    <button class="btn btn-custom pe-5 ps-5" @click="goToDevisList">Retour à la liste</button>
                </div>
                <div class="col-auto">
                    <div class="row">
                        <div class="col-auto" v-if="quote_id">
                            <button class="btn btn-custom pe-5 ps-5" @click="saveDevis(false)">Enregistrer & Rester</button>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-custom pe-5 ps-5" @click="saveDevis(false)">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </d-base-page>
</template>

<script setup>
    import VueFeather from 'vue-feather';
    import { useRoute, useRouter } from 'vue-router';
    import moment from 'moment';
    import { ref, onMounted, watch, computed } from 'vue';
    import { useMeta } from '/src/composables/use-meta';
    import { Helper, formatErrorViolations } from '../../../composables/global-methods';
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
    import dTaxRules from '../../../components/common/d-taxRules.vue';
    import dTransportCondition from '../../../components/common/d-transportCondition.vue';
    import dQuoteDetails from '../../../components/projet/devis/d-quote-details.vue';
    import dModalFactureDevis from '../../../components/projet/devis/d-modal-facture-devis.vue';
    import DModalFactureProformaDevis from "@/components/projet/devis/d-modal-facture-proforma-devis.vue";

    useMeta({ title: 'Gestion Contremarque' });

    const route = useRoute();
    const router = useRouter();
    const quote_id = route.params.id;
    const selectedCustomer = ref(0);
    const selectedContact = ref({});
    const contremarque = ref({});
    const tarifId = ref(0);
    const contact = ref({});
    const quoteNumber = ref('');
    const createdDate = ref(moment().format('YYYY-MM-DD'));
    const commercial = ref({});
    const quote = ref({});
    const prescriber = ref(0);
    const commission = ref('');
    const quoteDetails = ref([]);
    const loading = ref(false);
    const error = ref({});
    const disbledContremarque = true;
    const disbledPrices = true;
    let statusUpdate = true;

    const data = ref({
        discountRuleId: 0,
        taxRuleId: 0,
        currencyId: 0,
        languageId: 0,
        unitOfMeasurement: '',
        deliveryAddressId: 0,
        invoiceAddressId: 0,
        withoutDiscountPrice: '',
        additionalDiscount: '',
        totalDiscountAmount: '',
        totalDiscountPercentage: '',
        totalTaxExcluded: '',
        shippingPrice: '',
        tax: '',
        totalTaxIncluded: '',
        quoteSentToCustomer: false,
        qualificationMessage: '',
        conversionId: 0,
        cumulatedDiscountAmount: '',
        otherTva: '',
        transportConditionId: 0,
        weight: '',
    });
    const currentCustomer = ref({});
    const contremarqueId = ref(route.query.contremarqueId ? parseInt(route.query.contremarqueId) : null);

    watch(selectedCustomer, (customerId) => {
        getCustomer(customerId);
    });

    watch(contremarqueId, (contremarqueId) => {
        getContremarque(contremarqueId);
    });

    const getCustomer = async (customer_id) => {
        try {
            if (customer_id) {
                currentCustomer.value = await contremarqueService.getCustomerById(customer_id);
                tarifId.value = currentCustomer.value.discountRule;
                contact.value = currentCustomer.value.contactsData[0];
                commercial.value = currentCustomer.value.contactCommercialHistoriesData[0];
            }
        } catch (e) {
            const msg = "Un client d'id " + customer_id + " n'existe pas";
            window.showMessage(msg, 'error');
        }
    };
    const deliveryAddresses = computed(() => {
        return currentCustomer.value?.addressesData?.filter((addr) => [1, 3].includes(addr.addressType.addressTypeId)) || [];
    });

    const invoiceAddresses = computed(() => {
        return currentCustomer.value?.addressesData?.filter((addr) => [2, 3].includes(addr.addressType.addressTypeId)) || [];
    });
    const saveDevis = async (leave) => {
        try {
            error.value = {};
            if (contremarque) {
                const dataTosend = {
                    discountRuleId: data.value.discountRuleId,
                    taxRuleId: data.value.taxRuleId,
                    currencyId: data.value.currencyId,
                    languageId: data.value.languageId,
                    unitOfMeasurement: data.value.unitOfMeasurement,
                    deliveryAddressId: data.value.deliveryAddressId ? data.value.deliveryAddressId.address_id : 0,
                    invoiceAddressId: data.value.invoiceAddressId ? data.value.invoiceAddressId.address_id : 0,
                    withoutDiscountPrice: parseFloat(data.value.withoutDiscountPrice),
                    additionalDiscount: parseFloat(data.value.additionalDiscount),
                    totalDiscountAmount: parseFloat(data.value.totalDiscountAmount),
                    totalDiscountPercentage: parseFloat(data.value.totalDiscountPercentage),
                    totalTaxExcluded: parseFloat(data.value.totalTaxExcluded),
                    shippingPrice: data.value.shippingPrice !== null ? parseFloat(data.value.shippingPrice) : null,
                    tax: parseFloat(data.value.tax),
                    totalTaxIncluded: parseFloat(data.value.totalTaxIncluded),
                    quoteSentToCustomer: data.value.quoteSentToCustomer,
                    qualificationMessage: data.value.qualificationMessage,
                    conversionId: data.value.conversionId,
                    cumulatedDiscountAmount: data.value.cumulatedDiscountAmount,
                    otherTva: data.value.otherTva,
                    transportConditionId: data.value.transportConditionId,
                    weight: parseFloat(data.value.weight),
                };
                if (quote_id) {
                    const res = await axiosInstance.put(`/api/contremarque/${contremarqueId.value}/quote/${quote_id}`, dataTosend);
                    window.showMessage('Mise a jour avec succées.');
                } else {
                    const respn = await axiosInstance.post(`/api/contremarque/${contremarqueId.value}/createQuote`, dataTosend);
                    window.showMessage('Ajout avec succées.');
                    router.push({ name: 'devisManage', params: { id: respn.data.response.id } });
                }
                if (leave) {
                    setTimeout(() => {
                        goToDevisList();
                    }, 2000);
                }
            } else {
                window.showMessage('Veuillez sélectionner une contremarque valide.', 'error');
            }
        } catch (e) {
            if (e.response.data.violations) {
                error.value = formatErrorViolations(e.response.data.violations);
            }
            window.showMessage(e.message, 'error');
        }
    };

    const getContremarque = async (contremarque_id) => {
        try {
            if (contremarque_id) {
                contremarque.value = await contremarqueService.getContremarqueById(contremarque_id);
                selectedCustomer.value = contremarque.value.customer.customer_id;
                prescriber.value = contremarque.value.prescriber.customer_id;
                commission.value = contremarque.value.commission;
                data.value.discountRuleId = contremarque.value?.customerDiscount?.discount_rule_id;
            }
        } catch (e) {
            console.log(e);
            const msg = "Une contremarque d'id " + contremarque_id + " n'existe pas";
            window.showMessage(msg, 'error');
        } finally {
            loading.value = false;
        }
    };
    let disableAutoSave = true;
    const applyStopAutoSave = () => {
        disableAutoSave = true;
        setTimeout(() => {
            disableAutoSave = false;
        }, 3000);
    };
    const getQuote = async (quote_id) => {
        try {
            if (quote_id) {
                loading.value = true;
                quote.value = await quoteService.getQuoteById(quote_id);
                contremarqueId.value = quote.value?.contremarqueId;
                quoteNumber.value = quote.value.reference;
                quoteDetails.value = quote.value?.quoteDetails;
                createdDate.value = moment(quote.value.createdAt).format('YYYY-MM-DD');
                applyStopAutoSave();
                data.value = {
                    discountRuleId: 0,
                    taxRuleId: quote.value?.taxRule.id,
                    currencyId: quote.value?.currency.id,
                    languageId: quote.value?.language.id,
                    unitOfMeasurement: quote.value?.unitOfMeasurement,
                    deliveryAddressId: quote.value?.deliveryAddress,
                    invoiceAddressId: quote.value?.invoiceAddress,
                    withoutDiscountPrice: Helper.FormatNumber(quote.value?.withoutDiscountPrice),
                    additionalDiscount: Helper.FormatNumber(quote.value?.additionalDiscount),
                    totalDiscountAmount: Helper.FormatNumber(quote.value?.totalDiscountAmount),
                    totalDiscountPercentage: Helper.FormatNumber(quote.value?.totalDiscountPercentage),
                    totalTaxExcluded: Helper.FormatNumber(quote.value?.totalTaxExcluded),
                    shippingPrice: Helper.FormatNumber(quote.value?.shippingPrice),
                    tax: Helper.FormatNumber(quote.value?.tax),
                    totalTaxIncluded: Helper.FormatNumber(quote.value?.totalTaxIncluded),
                    quoteSentToCustomer: quote.value?.quoteSentToCustomer,
                    qualificationMessage: quote.value?.qualificationMessage,
                    cumulatedDiscountAmount: Helper.FormatNumber(quote.value?.cumulatedDiscountAmount),
                    otherTva: quote.value?.otherTva,
                    conversionId: quote.value?.conversion?.id,
                    weight: Helper.FormatNumber(quote.value?.weight),
                    transportConditionId: quote.value?.transportCondition?.id,
                };
            }
        } catch (e) {
            console.log(e);
            const msg = 'Echec de récupération des données devis';
            window.showMessage(msg, 'error');
        } finally {
            if (statusUpdate) {
                statusUpdate = false;
            }
            loading.value = false;
        }
    };

    onMounted(async () => {
        if (quote_id) {
            getQuote(quote_id);
        }
        if (contremarqueId.value) {
            console.log('Detected contremarqueId from URL:', contremarqueId.value);
            getContremarque(contremarqueId.value);
        }
    });
    const changeStatusDetails = async () => {
        if (quote_id) {
            statusUpdate = true;
            loading.value = true;
            await calculateTotal(quote_id);
            await getQuote(quote_id);
        }
    };
    onMounted(async () => {
        if (quote_id) {
            await getQuote(quote_id);
        }
    });

    const goToDevisList = () => {
        router.push({ name: 'devisList' });
    };

    const goToAttachReglement = () => {
        if (!quote_id) {
            window.showMessage('Veuillez enregistrer le devis avant de rattacher un règlement', 'error');
            return;
        }
        router.push({ name: 'reglement_attach_list', params: { quoteId: quote_id } });
    };
    const calculateTotal = async (quote_id) => {
        try {
            const res = await quoteService.calculateQuote(quote_id, {
                additionalDiscount: parseFloat(data.value.additionalDiscount),
                shippingPrice: parseFloat(data.value.shippingPrice),
            });
        } catch (e) {
            console.log(e);
            const msg = 'Echec dans le calcule des données devis';
            window.showMessage(msg, 'error');
        }
    };
    const persistQuoteTotals = async () => {
        if (quote_id && !disableAutoSave) {
            await saveDevis(false);
            await calculateTotal(quote_id);
            await getQuote(quote_id);
        }
    };
    const handlePricingFieldBlur = async () => {
        await persistQuoteTotals();
    };
    const createCarpetOrder = async () => {
        try {
            if (!quote_id) {
                window.showMessage('Veuillez enregistrer le devis avant de créer une commande tapis', 'error');
                return;
            }

            //loading.value = true;
            const response = await axiosInstance.post('/api/carpetOrder', {
                originalQuoteId: parseInt(quote_id),
                clonedQuoteId: 1, // Assuming we're using the same quote for now
                contremarqueId: contremarqueId.value,
            });

            window.showMessage('Commande tapis créée avec succès');
            // You might want to redirect or update the UI here
        } catch (error) {
            console.error('Error creating carpet order:', error);

            let message = 'Erreur lors de la création de la commande tapis';

            if (error.response && error.response.data) {
                const { violations, message: apiMessage, detail } = error.response.data;

                if (apiMessage) {
                    message = apiMessage;
                } else if (detail) {
                    message = detail;
                } else if (violations) {
                    if (Array.isArray(violations)) {
                        // Violations may be an array of strings or objects
                        const msgs = violations.map((v) => (typeof v === 'string' ? v : v.title)).filter(Boolean);
                        if (msgs.length) {
                            message = msgs.join('\n');
                        }
                    } else if (typeof violations === 'string') {
                        message = violations;
                    }
                }
            } else if (error.message) {
                message = error.message;
            }

            window.showMessage(message, 'error');
        } finally {
            loading.value = false;
        }
    };
    watch(
        () => data.value.weight,
        async () => {
            await persistQuoteTotals();
        }
    );
    watch(
        () => [data.value.quoteSentToCustomer, data.value.qualificationMessage],
        async () => {
            if (quote_id && !disableAutoSave) {
                saveDevis(false);
            }
        }
    );
</script>
<style scoped>
    .row {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
    }

    .row > [class*='col-'] {
        display: flex;
        flex-direction: column;
    }
</style>
