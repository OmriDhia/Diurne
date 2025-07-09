<template>
    <div class="create-facture-client">
        <d-base-page :loading="loading">
            <template #title>
                <d-page-title title="Nouvelle Facture" />
            </template>

            <template #body>
                <d-panel>
                    <template #panel-body>
                        <div class="row p-2">
                            <div class="col-3">
                                <!-- <d-input label="Référence client" v-model="form.customerRef" /> -->
                                <d-customer-dropdown :disabled="disbledContremarque" :showCustomer="true" :required="true" v-model="selectedCustomer"></d-customer-dropdown>
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
                                                        <input id="date" class="form-control custom-date custom-date" type="date" v-model="form.invoiceDate" />
                                                    </div>
                                                </div>

                                                <d-input label="Projet" v-model="form.project" />
                                                <hr class="mt-3" />

                                                <div class="row">
                                                    <div class="col-md-12 mt-2">
                                                        <d-contremarque-dropdown v-model="form.contremarque" :customerId="selectedCustomer" class="contremarque" />
                                                    </div>

                                                    <div class="col-md-12 mt-2">
                                                        <d-customer-dropdown :isPrescripteur="true" v-model="form.prescripteur" />
                                                    </div>
                                                    <div class="col-md-12 mt-2">
                                                        <d-input label="Description" v-model="form.description" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row align-items-center">
                                                    <label for="" class="col-4">Type de facture:</label>
                                                    <div class="col-8 custom-droupdown-exist">
                                                        <d-invoice-types v-model="form.invoiceType" :disabled="false" :showOnlyDropdown="true"></d-invoice-types>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-2 mb-1">
                                                    <label for="" class="col-4">TVA:</label>
                                                    <div class="col-8 custom-droupdown-exist">
                                                        <d-taxRules v-model="form.tva"></d-taxRules>
                                                        <!-- <multiselect v-model="form.tva" :options="[]" :multiple="false" :placeholder="'TVA'" :searchable="true"></multiselect> -->
                                                    </div>
                                                </div>

                                                <d-currency v-model="form.currency" />
                                                <div class="row align-items-center mt-2">
                                                    <label for="" class="col-4">Tx de conversion:</label>
                                                    <div class="col-8 custom-droupdown-exist">
                                                        <!-- <multiselect v-model="form.rate" :options="[]" :multiple="false" :placeholder="'Tx de conversion'" :searchable="true"></multiselect> -->
                                                        <d-conversions v-model="form.rate"></d-conversions>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-2">
                                                    <label for="" class="col-4">Langue:</label>
                                                    <div class="col-8 custom-droupdown-exist">
                                                        <d-langages v-model="form.languageId"></d-langages>
                                                        <!-- <multiselect v-model="form.language" :options="[]" :multiple="false" :placeholder="'Langue'" :searchable="true"></multiselect> -->
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-2">
                                                    <label for="" class="col-4">Unité de mesure:</label>
                                                    <div class="col-8 custom-droupdown-exist">
                                                        <d-unit-measurements v-model="form.unitOfMeasurement"></d-unit-measurements>
                                                        <!-- <multiselect v-model="form.weight" :options="[]" :multiple="false" :placeholder="'Unité de mesure'" :searchable="true"></multiselect> -->
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
                                                    <div class="col-8 custom-droupdown-exist">
                                                        <d-regulations-dropdown v-model="form.reglement" />
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-2">
                                                    <label for="" class="col-4">Tarif d’expédition</label>
                                                    <div class="col-8 custom-droupdown-exist">
                                                        <d-tarif-expedition v-model="form.tarifExpedition" />
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-2">
                                                    <label for="" class="col-4">Transporteur</label>
                                                    <div class="col-8 custom-droupdown-exist">
                                                        <d-carrier-dropdown v-model="form.carrierId"></d-carrier-dropdown>
                                                    </div>
                                                </div>

                                                <d-input label="Numéro" v-model="form.numero" />
                                            </div>
                                        </div>
                                        <d-panel-title title="Autre tapis" class-name="ps-2 mt-2" />
                                        <div class="row p-3">
                                            <div class="bloc-add">
                                                <div class="col-12">
                                                    <!-- <d-RN-dropdown v-model="form.rn" :carpetOrderDetailsId="form.rn" :showOnlyDropdown="true" /> -->
                                                    <d-rn-number-dropdown v-model="form.rn" @dataOfRn="ResultasRnData" :showActionRn="true"></d-rn-number-dropdown>
                                                    <!-- <d-RN-dropdown
                                                        :required="true"
                                                        :hideBtn="true"
                                                        v-model="carpetOrderDetailsId"
                                                        :carpetOrderDetailsId="carpetOrderDetailsId"
                                                        :error="validationSubmitErrors.collectionId"
                                                    ></d-RN-dropdown> -->
                                                    <!-- <div class="row w-100 d-block" v-for="(rn, index) in form.otherRns" :key="index">
                                                        <div class="d-flex align-items-center">
                                                            <d-input label="Numéro RN" v-model="form.otherRns[index]" />
                                                            <button v-if="form.otherRns.length > 1" class="btn btn-add me-2 ms-2" @click="form.otherRns.splice(index, 1)" type="button">
                                                                <vue-feather type="trash-2" stroke-width="1" class="cursor-pointer"></vue-feather>
                                                            </button>
                                                            <button class="btn btn-add m-2" @click="addAutreRn">
                                                                <vue-feather type="plus" stroke-width="1" class="cursor-pointer"></vue-feather>
                                                            </button>
                                                        </div>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </d-panel>
                            </div>
                        </div>
                    </template></d-panel
                >

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
                                            <td><d-collections-dropdown v-if="line.collection" :disabled="false" :showOnlyDropdown="true" v-model="line.collection"></d-collections-dropdown></td>
                                            <td><d-model-dropdown v-if="line.model" :disabled="false" :showOnlyDropdown="true" v-model="line.model"></d-model-dropdown></td>
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
                                    <d-input label="Qte total" v-model="form.quantityTotal" />
                                    <d-input label="Frais port HT" v-model="form.shippingCostsHt" />
                                </div>
                                <div class="col-md-3">
                                    <d-input label="Versement" v-model="form.versement" />
                                    <d-input label="% facturé" v-model="form.billed" />
                                </div>
                                <div class="col-md-3">
                                    <d-input label="Total HT" v-model="form.totalHt" />
                                    <d-input label="Montant HT" v-model="form.amountHt" />
                                    <d-input label="Montant TVA" v-model="form.amountTva" />
                                    <d-input label="Montant TTC" v-model="form.amountTtc" />
                                </div>
                                <div class="col-md-3 bloc-btns-actions">
                                    <button class="btn btn-custom">RÉPARTITION</button>
                                    <button class="btn btn-custom" @click="calculate">CALCULER</button>
                                    <button class="btn btn-custom" @click="save">ÉDITER</button>
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
    import { ref, onMounted, watch } from 'vue';

    import { useRoute, useRouter } from 'vue-router';
    import dBasePage from '../../../components/base/d-base-page.vue';
    import dPanel from '../../../components/common/d-panel.vue';
    import dPanelTitle from '../../../components/common/d-panel-title.vue';
    import dPageTitle from '../../../components/common/d-page-title.vue';
    import dInput from '../../../components/base/d-input.vue';

    import dCurrency from '../../../components/common/d-currency.vue';
    import dContremarqueDropdown from '../../../components/common/d-contremarque-dropdown.vue';
    import dCustomerDropdown from '../../../components/common/d-customer-dropdown.vue';
    import dConversions from '../../../components/common/d-conversions.vue';

    import dUnitMeasurements from '../../../components/common/d-unit-measurements.vue';
    import dLangages from '../../../components/common/d-langages.vue';
    import VueFeather from 'vue-feather';
    import { useMeta } from '/src/composables/use-meta';
    import Multiselect from 'vue-multiselect';
    import customerInvoiceService from '../../../Services/customer-invoice-service';
    import customerInvoiceDetailsService from '../../../Services/customer-invoice-details-service';
    import quoteService from '../../../Services/quote-service';
    import invoiceTypeService from '../../../Services/invoice-type-service';

    import dCarrierDropdown from '../../../components/common/d-carrier-dropdown.vue';

    import dTarifExpedition from '../../../components/common/d-tarif-expedition.vue';
    import dModelDropdown from '../../../components/projet/contremarques/dropdown/d-model-dropdown.vue';
    import dCollectionsDropdown from '../../../components/projet/contremarques/dropdown/d-collections-dropdown.vue';
    import { Helper } from '../../../composables/global-methods';
    import dRegulationsDropdown from '../../../components/common/d-regulations-dropdown.vue';
    import moment from 'moment';
    import contremarqueService from '../../../Services/contremarque-service';
    import dInvoiceTypes from '../../../components/common/d-invoice-types.vue';
    import axiosInstance from '../../../config/http';
    import dTaxRules from '../../../components/common/d-taxRules.vue';
    import dRnNumberDropdown from '../../../components/common/d-rn-number-dropdown.vue';
    useMeta({ title: 'Nouvelle Facture' });

    const route = useRoute();
    const router = useRouter();
    const loading = ref(false);
    const quote = ref({});
    let carpetOrderDetailsId = ref(null);
    const contremarque = ref({});
    const selectedCustomer = ref(null);
    const prescriber = ref(null);
    const currentCustomer = ref({});
    const regulations = ref([]);
    const invoiceTypes = ref([]);
    const rnId = ref(null); // To store the RN ID from the fetched data
    const form = ref({
        customerRef: null, //??
        invoiceNumber: '', //invoiceNumber == customerId
        invoiceDate: '',
        project: '', //??
        invoiceType: '',
        tva: '', //?
        currency: null, //?
        rate: '', //?
        languageId: 0, //?
        unitOfMeasurement: null, //?
        contremarque: null, //?
        prescripteur: '', //?
        description: '', //?
        reglement: '', //?
        tarifExpedition: '', //?
        carrierId: null, //?
        numero: '', //?
        rn: null, //?
        quantityTotal: null,
        shippingCostsHt: '',
        versement: '', //Versement==payment
        billed: '',
        totalHt: null,
        amountHt: null,
        amountTva: null,
        amountTtc: null,
        carpetOrderId: null, // Quel champ???
    });

    const lines = ref([
        // Initial empty line
        // {
        //     percent: null,
        //     rn: '',
        //     collection: null,
        //     model: null,
        //     refDevis: '',
        //     refCommande: '',
        //     versement: null,
        //     priceM2: null,
        //     priceSqft: null,
        //     priceHt: null,
        //     priceTtc: null,
        // },
    ]);

    watch(selectedCustomer, (customerId) => {
        getCustomer(customerId);
    });

    watch(
        () => form.value.contremarque,
        (contremarqueId) => {
            getContremarque(contremarqueId);
        }
    );
    const mapDetailsToLines = (details, originalQuoteReference = null) => {
        if (!details || !details.length) {
            return [];
        }
        return details.map((d) => ({
            id: d.id, // Add ID for updates/deletes
            percent: d.impactOnTheQuotePrice,
            rn: d.rn,
            collection: d.carpetSpecification?.collection?.id || null,
            model: d.carpetSpecification?.model?.id || null,

            // If loading from a quote, refDevis comes from the original quote header, refCommande from the quote detail.
            refDevis: originalQuoteReference || d.refQuote || '', // Use originalQuoteReference if provided (from quote), otherwise try d.refQuote (from invoice detail)
            refCommande: d.reference || d.refCommand || '', // Use d.reference (from quote detail) or d.refCommand (from invoice detail)
            versement: d.payment || 0, // Assuming payment might be on the detail line, or default to 0
            priceM2: Helper.getPrice(d.prices, 'tarif.m².price'),
            priceSqft: Helper.getPrice(d.prices, 'tarif.sqft.price'),
            priceHt: Helper.getPrice(d.prices, 'prix-propose-avant-remise-complementaire.m².price'),
            priceTtc: Helper.getPrice(d.prices, 'prix-propose-avant-remise-complementaire.totalPriceTtc'),
            carpetOrderDetailId: d.carpetOrderDetailId || null, // Keep track of the source detail ID
            cleared: d.cleared || false, // Keep track of cleared status
        }));
    };
    const fetchRegulations = async () => {
        try {
            const res = await axiosInstance.get('/api/regulations');
            regulations.value = res.data.response || [];
        } catch (error) {
            console.error('Failed to fetch regulations:', error);
        }
    };
    const ResultasRnData = (data) => {
        if (data && data.data && data.data.response) {
            const rnData = data.data.response;
            console.log('data', data);
            rnId.value = rnData.id; // Assuming rnData has an ID
            // form.value.rn = rnData.rnNumber; // Don't set form.rn here, it's for the header
            // carpetOrderDetailsId.value = rnData.imageCommand.carpetSpecification.id; // This seems incorrect, should be the detail ID

            // Push a new line to lines array with RN data
            lines.value.push({
                id: null, // New line, no ID yet
                percent: null, // Percentage needs to be entered
                rn: rnData.rnNumber,
                collection: rnData.imageCommand.carpetSpecification.collection?.id || null,
                model: rnData.imageCommand.carpetSpecification.model?.id || null,
                refDevis: rnData.imageCommand.reference || '', // Assuming RN data provides a reference
                refCommande: '', // Command reference needs to be entered
                versement: null, // Versement needs to be entered
                priceM2: Helper.getPrice(rnData.imageCommand.prices, 'tarif.m².price'),
                priceSqft: Helper.getPrice(rnData.imageCommand.prices, 'tarif.sqft.price'),
                priceHt: Helper.getPrice(rnData.imageCommand.prices, 'prix-propose-avant-remise-complementaire.m².price'),
                priceTtc: Helper.getPrice(rnData.imageCommand.prices, 'prix-propose-avant-remise-complementaire.totalPriceTtc'),
                carpetOrderDetailId: rnData.imageCommand.id || null, // Link to the source detail from RN data
                cleared: false,
            });
        }
    };
    const fetchInvoiceTypes = async () => {
        try {
            invoiceTypes.value = await invoiceTypeService.getInvoiceTypes();
        } catch (error) {
            console.error('Failed to fetch invoice types:', error);
        }
    };

    const getQuote = async (id) => {
        try {
            if (id) {
                loading.value = true;
                const data = await quoteService.getAllQuoteById(id);
                const quoteData = data.quoteData;
                const originalQuoteData = data.originalQuoteData;

                if (quoteData) {
                    carpetOrderDetailsId.value = quote.value.id;
                    // Populate form fields relevant when creating an invoice from a quote
                    selectedCustomer.value = quoteData.customer?.id || null;
                    form.value.contremarque = quoteData.contremarqueId || null;
                    form.value.prescripteur = quoteData.prescriber?.id || '';
                    form.value.languageId = quoteData.language?.id || 0;
                    form.value.currency = quoteData.currency?.id || null;
                    form.value.rate = quoteData.conversion?.id || '';
                    form.value.carrierId = quoteData.transportCondition?.id || '';
                    form.value.shippingCostsHt = quoteData.shippingPrice || '';
                    form.value.carpetOrderId = quoteData.carpetOrderId || null; // Assuming quoteData has carpetOrderId

                    // Invoice-specific fields should be empty or default for a new invoice
                    form.value.invoiceNumber = '';
                    form.value.invoiceDate = moment(form.value.invoiceDate).format('YYYY-MM-DD'); // Default to today
                    form.value.invoiceType = '';
                    form.value.tva = '';
                    form.value.unitOfMeasurement = null;
                    form.value.reglement = '';
                    form.value.tarifExpedition = '';
                    form.value.numero = '';
                    form.value.rn = null; // RN field in header? Or only in lines? Assuming only in lines based on template
                    form.value.quantityTotal = null;
                    form.value.totalHt = parseFloat(quote.value.totalTaxExcluded) || '';
                    form.value.amountHt = parseFloat(quote.value.otherTva) || '';
                    form.value.amountTtc = parseFloat(quote.value.totalTaxIncluded) || '';
                    form.value.versement = parseFloat(quote.value.totalTaxIncluded) || '';
                    form.value.billed = parseFloat(quote.value.totalDiscountPercentage) || '';
                    form.value.amountTva = parseFloat(quote.value.tax) || '';

                    // Populate lines from quote details using the helper
                    if (quoteData.quoteDetails) {
                        lines.value = mapDetailsToLines(quoteData.quoteDetails, originalQuoteData?.reference);
                    }
                }
            }
        } catch (e) {
            window.showMessage(e.message, 'error');
        } finally {
            loading.value = false;
        }
    };

    const getCustomer = async (customer_id) => {
        try {
            if (customer_id) {
                currentCustomer.value = await contremarqueService.getCustomerById(customer_id);
            }
        } catch (e) {
            const msg = "Un client d'id " + customer_id + " n'existe pas";
            window.showMessage(msg, 'error');
        }
    };
    const fetchInvoiceById = async (id) => {
        // Added id parameter
        try {
            if (id) {
                loading.value = true;
                const invoiceData = await customerInvoiceService.getById(id);
                // quote.value = invoiceData; // Renaming 'quote' ref to 'invoice' might be clearer if it holds invoice data

                if (invoiceData) {
                    selectedCustomer.value = invoiceData.customer_id;
                    form.value = {
                        languageId: invoiceData.language_id || 0,
                        prescripteur: invoiceData.prescriber_id || '',
                        //customerRef: invoiceData.reference || '',
                        invoiceNumber: invoiceData.invoice_number || '',
                        invoiceDate: moment(invoiceData.invoice_date).format('YYYY-MM-DD') || '',
                        invoiceType: invoiceData.invoice_type || '', // Assuming this is the ID or value
                        unitOfMeasurement: invoiceData.lmesurement_id || '', // Assuming this is the ID or value
                        tva: invoiceData.otherTva || '', // Assuming this is the ID or value
                        currency: invoiceData.currency_id || null,
                        rate: invoiceData.conversion_id || '',
                        carrierId: invoiceData.carrier_id || null,
                        tarifExpedition: invoiceData.tarif_expedition_id || '',
                        reglement: invoiceData.regulation_id || '',
                        rn: invoiceData.rn_id || '', // Assuming this is the ID or value
                        contremarque: invoiceData.contremarque_id || null,
                        shippingCostsHt: invoiceData.shipping_costs_ht || '',
                        totalHt: invoiceData.total_ht || '',
                        amountHt: invoiceData.amount_ht || '',
                        amountTva: invoiceData.amount_tva || '',
                        amountTtc: invoiceData.amount_ttc || '',
                        versement: invoiceData.payment || '',
                        billed: invoiceData.billed || '',
                        carpetOrderId: invoiceData.carpet_order_id || null,
                        description: invoiceData.description || '', // Add description
                        project: invoiceData.project || '', // Add project
                    };

                    // Fetch related data if IDs are present
                    if (selectedCustomer.value) {
                        getCustomer(selectedCustomer.value);
                    }
                    if (form.value.contremarque) {
                        getContremarque(form.value.contremarque);
                    }

                    // Populate lines from invoice details using the helper
                    if (invoiceData.customerInvoiceDetails) {
                        lines.value = mapDetailsToLines(invoiceData.customerInvoiceDetails);
                    } else {
                        lines.value = [];
                    }
                }
            }
        } catch (error) {
            console.error('Failed to fetch invoice:', error);
            window.showMessage('Erreur lors du chargement de la facture.', 'error');
        } finally {
            loading.value = false;
        }
    };
    const getContremarque = async (contremarque_id) => {
        try {
            if (contremarque_id) {
                contremarque.value = await contremarqueService.getContremarqueById(contremarque_id);
                // Update customer and prescriber based on contremarque data
                selectedCustomer.value = contremarque.value.customer?.customer_id || null;
                form.value.prescripteur = contremarque.value.prescriber?.customer_id || ''; // Assuming prescriber is an object
            }
        } catch (e) {
            const msg = "Une contremarque d'id " + contremarque_id + " n'existe pas";
            window.showMessage(msg, 'error');
        }
    };
    const calculate = () => {
        let totalHtFromLines = 0;
        lines.value.forEach((line) => {
            totalHtFromLines += parseFloat(line.priceHt || 0);
        });
        form.value.quantityTotal = lines.value.length; // Assuming quantityTotal is the number of lines

        form.value.totalHt = totalHtFromLines;

        const shippingCosts = parseFloat(form.value.shippingCostsHt || 0);
        form.value.amountHt = form.value.totalHt + shippingCosts;

        const tvaRate = form.value.tva?.rate || 0; // Assuming form.tva has a 'rate' property
        form.value.amountTva = form.value.amountHt * (tvaRate / 100);

        form.value.amountTtc = form.value.amountHt + form.value.amountTva;
    };
    const save = async () => {
        const payload = {
            invoiceNumber: form.value.invoiceNumber,
            invoiceDate: form.value.invoiceDate,
            invoiceType: form.value.invoiceType, // Assuming this is the ID or value
            carrierId: form.value.carrierId || null, // Assuming carrierId is an object
            customerId: selectedCustomer.value,
            carpetOrderId: carpetOrderDetailsId.value || null, // Use carpetOrderId from form
            quantityTotal: form.value.quantityTotal || null,
            shippingCostsHt: form.value.shippingCostsHt || null,
            billed: String(form.value.billed) || null,
            payment: String(form.value.versement) || null,
            totalHt: String(form.value.totalHt) || null,
            amountHt: String(form.value.amountHt) || null,
            amountTva: String(form.value.amountTva) || null,
            amountTtc: String(form.value.amountTtc) || null,
            prescriberId: form.value.prescripteur || null, // Assuming prescripteur is an object
            invoiceTypeEntityId: form.value.invoiceType || null, // Assuming invoiceType is an object
            currencyId: form.value.currency || null, // Assuming currency is an object
            conversionId: form.value.rate || null, // Assuming rate is an object
            languageId: form.value.languageId || 0,
            mesurementId: form.value.unitOfMeasurement || null, // Assuming unitOfMeasurement is an object
            regulationId: form.value.reglement || null, // Assuming reglement is an object
            tarifExpeditionId: form.value.tarifExpedition || null, // Assuming tarifExpedition is an object

            description: form.value.description || '', // Add description
            project: form.value.project || '', // Add project
            otherTva: form.value.tva || form.value.tva || '', // Assuming tva can be object or value
            description: form.value.description || '', // Add description
            rnId: rnId.value || null, // Use the ref value for rnId
            contremarqueId: form.value.contremarque || null, // Assuming contremarque
        };

        try {
            loading.value = true;
            let invoiceId = route.params.id;
            let resultat;
            if (invoiceId) {
                resultat = await customerInvoiceService.update(invoiceId, payload);

                if (form.value.rn) {
                    for (const line of lines.value) {
                        const linePayload = {
                            customerInvoiceId: invoiceId, // Link to the new invoice
                            // Use the carpetOrderDetailId stored on the line object (from RN data or quote mapping)
                            carpetOrderDetailId: line.carpetOrderDetailId || null,
                            cleared: line.cleared || false,
                            rn: line.rn,
                            collectionId: line.collection,
                            modelId: line.model,
                            m2: line.priceM2 || null,
                            sqft: line.priceSqft || null,
                            ht: line.priceHt || null,
                            ttc: line.priceTtc || null,
                            refCommand: line.refCommande,
                            refQuote: line.refDevis,
                            payment: line.versement || null,
                            percent: line.percent || null,
                        };
                        const lineResult = await customerInvoiceDetailsService.create(linePayload);
                        line.id = lineResult.id;
                    }
                } else {
                    for (const line of lines.value) {
                        const linePayload = {
                            customerInvoiceId: invoiceId, // Link to the new invoice
                            // Use the carpetOrderDetailId stored on the line object (from RN data or quote mapping)
                            carpetOrderDetailId: line.carpetOrderDetailId || null,
                            cleared: line.cleared || false,
                            rn: line.rn,
                            collectionId: line.collection,
                            modelId: line.model,
                            m2: line.priceM2 || null,
                            sqft: line.priceSqft || null,
                            ht: line.priceHt || null,
                            ttc: line.priceTtc || null,
                            refCommand: line.refCommande,
                            refQuote: line.refDevis,
                            payment: line.versement || null,
                            percent: line.percent || null,
                        };
                        line.id = lineResult.id;
                        const lineResult = await customerInvoiceDetailsService.update(line.id, linePayload);
                    }
                }
                window.showMessage('Mise à jour avec succés.');
                router.push({ name: 'client-invoice-list' });
            } else {
                const resultat = await customerInvoiceService.create(payload);
                if (form.value.rn) {
                    for (const line of lines.value) {
                        const linePayload = {
                            customerInvoiceId: resultat.id, // Link to the new invoice
                            // Use the carpetOrderDetailId stored on the line object (from RN data or quote mapping)
                            carpetOrderDetailId: line.carpetOrderDetailId || null,
                            cleared: line.cleared || false,
                            rn: line.rn,
                            collectionId: line.collection,
                            modelId: line.model,
                            m2: line.priceM2 || null,
                            sqft: line.priceSqft || null,
                            ht: line.priceHt || null,
                            ttc: line.priceTtc || null,
                            refCommand: line.refCommande,
                            refQuote: line.refDevis,
                            payment: line.versement || null,
                            percent: line.percent || null,
                        };
                        const lineResult = await customerInvoiceDetailsService.create(linePayload);
                        line.id = lineResult.id;
                    }
                }
                window.showMessage('Ajout avec succés.');
                router.push({ name: 'client-invoice-list' });
            }
        } catch (e) {
            window.showMessage(e.message, 'error');
        } finally {
            loading.value = false;
        }
    };

    onMounted(async () => {
        // Check if creating from a quote
        console.log(route.query.quote_id);

        if (route.query.quote_id) {
            await getQuote(route.query.quote_id);
        } else if (route.params.id) {
            await fetchInvoiceById(route.params.id);
        }
        fetchRegulations();
        fetchInvoiceTypes();
    });

    const saveLine = async (index) => {
        const line = lines.value[index];
        const invoiceId = route.params.id; // Get invoice ID from route params

        if (!invoiceId) {
            window.showMessage("Veuillez d'abord enregistrer la facture principale.", 'warning');
            return; // Cannot save lines if the main invoice hasn't been created
        }

        // Ensure line data is correctly mapped for the API call
        const linePayload = {
            customerInvoiceId: invoiceId, // Link to the main invoice
            carpetOrderDetailId: line.carpetOrderDetailId || null, // Use the ID stored on the line
            cleared: line.cleared || false,
            rn: line.rn,
            collectionId: line.collection,
            modelId: line.model,
            m2: line.priceM2 || null,
            sqft: line.priceSqft || null,
            ht: line.priceHt || null,
            ttc: line.priceTtc || null,
            refCommand: line.refCommande,
            refQuote: line.refDevis,
            payment: line.versement || null,
            percent: line.percent || null,
        };

        try {
            loading.value = true;
            if (line.id) {
                // Update existing line
                await customerInvoiceDetailsService.update(line.id, linePayload);
                window.showMessage('Ligne mise à jour avec succès.');
            } else {
                // Create new line (should ideally only happen when adding via RN in edit mode and saving it)
                const result = await customerInvoiceDetailsService.create(linePayload);
                line.id = result.id; // Update the line object with the new ID
                window.showMessage('Ligne ajoutée avec succès.');
            }
        } catch (e) {
            window.showMessage(e.message, 'error');
        } finally {
            loading.value = false;
        }
    };

    const removeLine = async (index) => {
        const line = lines.value[index];
        if (line.id) {
            try {
                loading.value = true; // Add loading state for deletion
                await customerInvoiceDetailsService.delete(line.id);
                window.showMessage('Ligne supprimée avec succès.');
            } catch (e) {
                window.showMessage(e.message, 'error');
                loading.value = false; // Ensure loading is turned off on error
                return; // Stop if API call fails
            } finally {
                loading.value = false; // Ensure loading is turned off on success
            }
        }
        // Remove from array only after successful API delete if line had an ID
        lines.value.splice(index, 1);
    };
</script>

<style scoped></style>
