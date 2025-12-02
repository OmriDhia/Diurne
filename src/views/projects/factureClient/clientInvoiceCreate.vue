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
                                <d-customer-dropdown compact :disabled="disbledContremarque" :showCustomer="true"
                                                     :required="true"
                                                     v-model="selectedCustomer"></d-customer-dropdown>
                                <div v-if="validationErrors.customer" class="invalid-feedback d-block">
                                    {{ validationErrors.customer }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <d-panel>
                                    <template #panel-body>
                                        <d-panel-title title="Caractéristiques facture" class-name="ps-2" />
                                        <div class="row">
                                            <div class="col-md-6">
                                                <d-input label="Numéro facture" v-model="invoiceNumberFormatted" />
                                                <small class="text-muted">Format: 300.00</small>
                                                <div class="row align-items-center pt-2">
                                                    <label for="date" class="col-4">Date</label>
                                                    <div class="col-8">
                                                        <input id="date" class="form-control custom-date custom-date"
                                                               type="date" v-model="form.invoiceDate" />
                                                        <div v-if="validationErrors.invoiceDate"
                                                             class="invalid-feedback d-block">
                                                            {{ validationErrors.invoiceDate }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <d-input label="Projet" v-model="form.project" />
                                                <hr class="mt-3" />

                                                <div class="row">
                                                    <div class="col-md-12 mt-2">
                                                        <d-contremarque-dropdown v-model="form.contremarque"
                                                                                 :customerId="selectedCustomer"
                                                                                 class="contremarque" />
                                                    </div>

                                                    <div class="col-md-12 mt-2">
                                                        <d-customer-dropdown :isPrescripteur="true"
                                                                             v-model="form.prescripteur" />
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
                                                        <d-invoice-types v-model="form.invoiceType" :disabled="false"
                                                                         :showOnlyDropdown="true"
                                                                         :required="true"></d-invoice-types>
                                                        <div v-if="validationErrors.invoiceType"
                                                             class="invalid-feedback d-block">
                                                            {{ validationErrors.invoiceType }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mt-2 mb-1">
                                                    <label for="" class="col-4">TVA:</label>
                                                    <div class="col-8 custom-droupdown-exist">
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
                                                        <d-unit-measurements
                                                            v-model="form.unitOfMeasurement"></d-unit-measurements>
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
                                                        <d-carrier-dropdown
                                                            v-model="form.carrierId"
                                                            :required="true"></d-carrier-dropdown>
                                                        <div v-if="validationErrors.carrierId"
                                                             class="invalid-feedback d-block">
                                                            {{ validationErrors.carrierId }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <d-input label="Numéro" v-model="form.numero" />
                                            </div>
                                        </div>
                                        <d-panel-title title="Autre tapis" class-name="ps-2 mt-2" />
                                        <div class="row p-3">
                                            <div class="bloc-add">
                                                <div class="col-12">
                                                    <!--                                                    <d-RN-dropdown v-model="form.rn" :carpetOrderDetailsId="form.rn"
                                                                                                                       :showOnlyDropdown="true" />-->
                                                    <d-rn-number-dropdown v-model="form.rn" @dataOfRn="ResultasRnData"
                                                                          :showActionRn="true"></d-rn-number-dropdown>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </d-panel>
                            </div>
                        </div>
                    </template
                    >
                </d-panel>

                <div class="mt-3">
                    <d-panel>
                        <template #panel-body>
                            <!-- <d-panel-title title="Détails" class-name="ps-2" /> -->
                            <div class="table-responsive" style="overflow:visible">
                                <table class="table table-striped table-hover table-sm">
                                    <thead>
                                    <tr class="border-top text-black bg-black">
                                        <th rowspan="2" class="text-white">% Prix total</th>
                                        <th rowspan="2" class="text-white">RN<span class="required">*</span></th>
                                        <th rowspan="2" class="text-white">Collection</th>
                                        <th rowspan="2" class="text-white">Modèle</th>
                                        <!--                                            <th rowspan="2" class="text-white">Ref tapis devis</th>-->
                                        <th rowspan="2" class="text-white">Ref tapis commande<span
                                            class="required">*</span></th>
                                        <th rowspan="2" class="border-end text-white">Versement</th>
                                        <th colspan="4" class="border-start border-end text-white text-center">Prix
                                            vendu
                                        </th>
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
                                        <td><input type="number" class="form-control form-control-sm"
                                                   v-model="line.percent" /></td>
                                        <td class="invoiceclient-rn d-flex align-items-center">
                                            <d-rn-number-dropdown v-model="line.rn" @dataOfRn="ResultasRnData"
                                                                  :showActionRn="false"></d-rn-number-dropdown>
                                            <!-- search icon to go to workshop order (if available) -->
                                            <vue-feather type="search"
                                                         size="14"
                                                         class="ms-2 clickable-icon" role="button"
                                                         @click="goToWorkshop(line)"
                                                         aria-label="Voir commande atelier" />
                                        </td>
                                        <td>
                                            <div class="compact-dropdown-collections">
                                                <d-collections-dropdown class="form-control form-control-sm" compact
                                                                        v-if="line.collection" :disabled="false"
                                                                        :showOnlyDropdown="true"
                                                                        v-model="line.collection"></d-collections-dropdown>
                                            </div>
                                        </td>
                                        <td>
                                            <d-model-dropdown v-if="line.model" :disabled="false"
                                                              :showOnlyDropdown="true"
                                                              v-model="line.model"></d-model-dropdown>
                                        </td>
                                        <!--                                            <td><input type="text" class="form-control form-control-sm" v-model="line.refDevis" /></td>-->
                                        <td><input type="text" class="form-control form-control-sm"
                                                   v-model="line.refCommande" /></td>
                                        <!-- versement formatted -->
                                        <td>
                                            <input type="text" class="form-control form-control-sm"
                                                   :value="formatAmount(line.versement)"
                                                   @input="onLineAmountInput($event, line, 'versement')" />
                                        </td>
                                        <!-- price fields formatted with 2 decimals -->
                                        <td>
                                            <input type="text" class="form-control form-control-sm"
                                                   :value="formatAmount(line.priceM2)"
                                                   @input="onLineAmountInput($event, line, 'priceM2')" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm"
                                                   :value="formatAmount(line.priceSqft)"
                                                   @input="onLineAmountInput($event, line, 'priceSqft')" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm"
                                                   :value="formatAmount(line.priceHt)"
                                                   @input="onLineAmountInput($event, line, 'priceHt')" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm"
                                                   :value="formatAmount(line.priceTtc)"
                                                   @input="onLineAmountInput($event, line, 'priceTtc')" />
                                        </td>
                                        <td class="text-center td-actions">
                                            <button class="btn btn-add btn-sm me-1" @click="saveLine(index)"
                                                    v-if="route.params.id && route.query.quote_id == null">
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
                                    <d-input label="Frais port HT" v-model="shippingCostsHtDisplay" />
                                    <div v-if="validationErrors.shippingCostsHt" class="invalid-feedback d-block">
                                        {{ validationErrors.shippingCostsHt }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <d-input label="Versement" v-model="form.versement" />
                                    <d-input label="% facturé" v-model="form.billed" />
                                </div>
                                <div class="col-md-3">
                                    <d-input label="Total HT" v-model="totalHtDisplay" />
                                    <d-input label="Montant HT" v-model="amountHtDisplay" />
                                    <d-input label="Montant TVA" v-model="amountTvaDisplay" />
                                    <d-input label="Montant TTC" v-model="amountTtcDisplay" />
                                </div>
                                <div class="col-md-3 bloc-btns-actions">
                                    <button class="btn btn-custom">RÉPARTITION</button>
                                    <button class="btn btn-custom" @click="calculate">CALCULER</button>
                                    <button class="btn btn-custom" @click="save"><span
                                        v-if="route.params.id && route.query.quote_id == null">ÉDITER</span><span
                                        v-else>AJOUTER</span></button>
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
    import dRnNumberDropdown from '../../../components/common/d-rn-number-dropdown.vue';

    useMeta({ title: 'Nouvelle Facture' });

    const route = useRoute();
    const router = useRouter();
    const loading = ref(false);
    const quote = ref({});
    let carpetOrderDetailsId = ref(null);
    const contremarque = ref({});
    const selectedCustomer = ref(null);
    const rnLists = ref([]);
    const currentCustomer = ref({});
    const regulations = ref([]);
    const invoiceTypes = ref([]);
    const arrayRnDetaill = ref([]);

    const rnId = ref(null);
    // validation errors for header fields
    const validationErrors = ref({
        customer: '',
        invoiceDate: '',
        invoiceType: '',
        carrierId: '',
        shippingCostsHt: ''
    });

    // formatted invoice number for display (two decimals)
    const invoiceNumberFormatted = ref('');

    const form = ref({
        customerRef: null,
        invoiceNumber: '',
        invoiceDate: '',
        project: '',
        invoiceType: '',
        tva: '',
        currency: null,
        rate: '',
        languageId: 0,
        unitOfMeasurement: null,
        contremarque: null,
        prescripteur: '',
        description: '',
        reglement: '',
        tarifExpedition: '',
        carrierId: null,
        numero: '',
        rn: null,
        quantityTotal: null,
        shippingCostsHt: '',
        versement: '',
        billed: '',
        totalHt: null,
        amountHt: null,
        amountTva: null,
        amountTtc: null,
        carpetOrderId: null
    });

    // Ensure `lines` exists so template and methods can use it safely
    // Initialize from global fallback in case child emitted RN before setup ran
    const lines = (typeof window !== 'undefined' && window.__invoice_lines) ? ref(window.__invoice_lines) : ref([]);
    // keep a global reference so any early emissions are accessible
    if (typeof window !== 'undefined') window.__invoice_lines = lines.value;

    // Minimal helper to map API details to the lines expected by this component
    const mapDetailsToLines = (details, originalQuoteReference = null) => {
        if (!details) return [];
        if (!Array.isArray(details)) details = Object.values(details);
        return details.map((d) => ({
            id: d.id || null,
            percent: d.impactOnTheQuotePrice || d.percent || 100,
            rn: d.rn || null,
            // collection/model may come from different shapes depending on the API response
            collection: (
                d.carpetOrderData?.QuoteDetail?.carpetSpecification?.collection?.id ||
                d.carpetOrderData?.carpetSpecification?.collection?.id ||
                d.carpetSpecification?.collection?.id ||
                d.collection || null
            ),
            model: (
                d.carpetOrderData?.QuoteDetail?.carpetSpecification?.model?.id ||
                d.carpetOrderData?.carpetSpecification?.model?.id ||
                d.carpetSpecification?.model?.id ||
                d.model || null
            ),
            refDevis: originalQuoteReference || d.refQuote || '',
            refCommande: d.reference || d.refCommand || '',
            versement: d.payment || 0,
            priceM2: formatNumber(d.m2) || null,
            priceSqft: formatNumber(d.sqft) || null,
            priceHt: formatNumber(d.ht) || null,
            priceTtc: formatNumber(d.ttc) || null,
            // d may include carpetOrderDetail or carpetOrderData.id
            carpetOrderDetailId: d.carpetOrderDetail || d.carpetOrderData?.id || null,
            cleared: d.cleared || false
        }));
    };

    // Display formatted fields (two decimals + thousands separator)
    const shippingCostsHtDisplay = ref('');
    const totalHtDisplay = ref('');
    const amountHtDisplay = ref('');
    const amountTvaDisplay = ref('');
    const amountTtcDisplay = ref('');

    // formatters
    function toNumber(val) {
        if (val === null || val === undefined || val === '') return 0;
        const s = String(val).replace(/,/g, '');
        const n = Number(s);
        return isNaN(n) ? 0 : n;
    }

    // Use shared Helper.FormatNumber for consistent formatting (normalize input first)
    function formatAmount(val) {
        const n = toNumber(val);
        return Helper.FormatNumber(n);
    }

    // Use shared Helper.FormatNumber for mapping values into lines
    function formatNumber(val) {
        if (val === null || val === undefined || val === '') return null;
        return Helper.FormatNumber(toNumber(val));
    }

    // Sync form -> display
    const syncDisplayFromForm = () => {
        shippingCostsHtDisplay.value = form.value.shippingCostsHt !== '' && form.value.shippingCostsHt !== null ? formatAmount(form.value.shippingCostsHt) : '0.00';
        totalHtDisplay.value = form.value.totalHt !== '' && form.value.totalHt !== null ? formatAmount(form.value.totalHt) : '';
        amountHtDisplay.value = form.value.amountHt !== '' && form.value.amountHt !== null ? formatAmount(form.value.amountHt) : '';
        amountTvaDisplay.value = form.value.amountTva !== '' && form.value.amountTva !== null ? formatAmount(form.value.amountTva) : '';
        amountTtcDisplay.value = form.value.amountTtc !== '' && form.value.amountTtc !== null ? formatAmount(form.value.amountTtc) : '';
    };

    // initialize displays
    syncDisplayFromForm();

    // keep display -> form in sync on edit
    watch(shippingCostsHtDisplay, (nv) => {
        const num = toNumber(nv);
        form.value.shippingCostsHt = num.toFixed(2);
        validationErrors.value.shippingCostsHt = '';
    });
    watch(totalHtDisplay, (nv) => {
        const num = toNumber(nv);
        form.value.totalHt = num.toFixed(2);
    });
    watch(amountHtDisplay, (nv) => {
        const num = toNumber(nv);
        form.value.amountHt = num.toFixed(2);
    });
    watch(amountTvaDisplay, (nv) => {
        const num = toNumber(nv);
        form.value.amountTva = num.toFixed(2);
    });
    watch(amountTtcDisplay, (nv) => {
        const num = toNumber(nv);
        form.value.amountTtc = num.toFixed(2);
    });

    // invoice number formatting
    const formatInvoiceNumberForDisplay = (val) => {
        if (val === null || val === undefined || String(val) === '') return '';
        const n = Number(String(val).replace(',', '.'));
        if (isNaN(n)) return String(val);
        return n.toFixed(2);
    };

    // initialize formatted value from form
    invoiceNumberFormatted.value = formatInvoiceNumberForDisplay(form.value.invoiceNumber);

    // keep invoiceNumberFormatted -> form.invoiceNumber in sync
    watch(invoiceNumberFormatted, (nv) => {
        if (nv === '' || nv === null) {
            form.value.invoiceNumber = '';
            return;
        }
        const parsed = parseFloat(String(nv).replace(',', '.'));
        if (!isNaN(parsed)) {
            form.value.invoiceNumber = parsed.toFixed(2);
        } else {
            form.value.invoiceNumber = nv;
        }
    });

    // keep form.invoiceNumber -> formatted display in sync
    watch(() => form.value.invoiceNumber, (nv) => {
        invoiceNumberFormatted.value = formatInvoiceNumberForDisplay(nv);
    });

    // validate required header fields according to DTO
    const validateInvoiceHeader = () => {
        validationErrors.value = { customer: '', invoiceDate: '', invoiceType: '', carrierId: '', shippingCostsHt: '' };
        const missing = [];
        if (!selectedCustomer.value) {
            validationErrors.value.customer = 'Le client est requis.';
            missing.push('Client');
        }
        if (!form.value.invoiceDate) {
            validationErrors.value.invoiceDate = 'La date de la facture est requise.';
            missing.push('Date');
        }
        // invoiceType may be an id or object; accept truthy
        if (!form.value.invoiceType) {
            validationErrors.value.invoiceType = 'Le type de facture est requis.';
            missing.push('Type de facture');
        }
        if (!form.value.carrierId) {
            validationErrors.value.carrierId = 'Le transporteur est requis.';
            missing.push('Transporteur');
        }
        // Frais port HT required, default to 0 if unset
        if (form.value.shippingCostsHt === '' || form.value.shippingCostsHt === null || String(form.value.shippingCostsHt).trim() === '') {
            form.value.shippingCostsHt = '0.00';
            shippingCostsHtDisplay.value = '0.00';
        }

        if (missing.length) {
            const msg = `Champs requis manquants: ${missing.join(', ')}`;
            window.showMessage(msg, 'error');
            return false;
        }
        return true;
    };

    const ResultasRnData = (data) => {
        if (data && data.data && data.data.response) {
            const rnData = data.data.response;

            rnId.value = rnData.id || null;
            carpetOrderDetailsId.value = rnData.carpetOrderDetail?.id || rnData.imageCommand?.id || null;

            const item = {
                id: null,
                percent: rnData.carpetOrderDetail?.QuoteDetail?.impactOnTheQuotePrice || rnData.carpetOrderDetail?.impactOnTheQuotePrice || null,
                rn: rnData.rnNumber || rnData.carpetRnNumber || rnData.rn || null,
                collection: rnData.imageCommand?.carpetSpecification?.collection?.id || rnData.carpetOrderDetail?.carpetSpecification?.collection?.id || null,
                model: rnData.imageCommand?.carpetSpecification?.model?.id || rnData.carpetOrderDetail?.carpetSpecification?.model?.id || null,
                refDevis: rnData.imageCommand?.reference || rnData.carpetOrderDetail?.carpetOrder?.refQuote || '',
                refCommande: rnData.carpetOrderDetail?.QuoteDetail?.reference || rnData.carpetOrderDetail?.reference || '',
                versement: 0,
                priceM2: Helper.getPrice(rnData.carpetOrderDetail?.QuoteDetail?.prices || rnData.carpetOrderDetail?.prices || rnData.imageCommand?.prices, 'prix-propose.m².price') || null,
                priceSqft: Helper.getPrice(rnData.carpetOrderDetail?.QuoteDetail?.prices || rnData.carpetOrderDetail?.prices || rnData.imageCommand?.prices, 'prix-propose.sqft.price') || null,
                priceHt: Helper.getPrice(rnData.carpetOrderDetail?.QuoteDetail?.prices || rnData.carpetOrderDetail?.prices || rnData.imageCommand?.prices, 'prix-propose.totalPriceHt') || null,
                priceTtc: Helper.getPrice(rnData.carpetOrderDetail?.QuoteDetail?.prices || rnData.carpetOrderDetail?.prices || rnData.imageCommand?.prices, 'prix-propose.totalPriceTtc') || null,
                carpetOrderDetailId: carpetOrderDetailsId.value || null,
                workshopOrderId: rnData.workshopOrderId || null,
                cleared: false
            };

            // Try to push to the reactive `lines` if available; if accessing `lines` throws (TDZ), fallback to global array
            try {
                // Accessing `lines` might throw if it's still in TDZ; this is intentional to catch that case
                if (lines && typeof lines.value !== 'undefined') {
                    lines.value.push(item);
                } else {
                    window.__invoice_lines = window.__invoice_lines || [];
                    window.__invoice_lines.push(item);
                }
            } catch (e) {
                // If any ReferenceError (TDZ) or other error, store in global fallback
                window.__invoice_lines = window.__invoice_lines || [];
                window.__invoice_lines.push(item);
            }

            if (rnData.workshopOrderId) {
                form.value.carpetOrderId = rnData.workshopOrderId;
            }
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
                    // Populate form fields relevant when creating an invoice from a quote
                    selectedCustomer.value = quoteData.customer?.id || null;
                    form.value.contremarque = quoteData.contremarqueId || null;
                    form.value.prescripteur = quoteData.prescriber?.id || '';
                    form.value.languageId = quoteData.language?.id || 0;
                    form.value.currency = quoteData.currency?.id || null;
                    form.value.rate = quoteData.conversion?.id || '';
                    form.value.carrierId = quoteData.transportCondition?.id || '';
                    form.value.shippingCostsHt = quoteData.shippingPrice || '';
                    form.value.carpetOrderId = quoteData.carpetOrderDetail || null; // Assuming quoteData has carpetOrderId

                    // Invoice-specific fields should be empty or default for a new invoice
                    form.value.invoiceNumber = '';
                    form.value.invoiceDate = moment(form.value.invoiceDate).format('YYYY-MM-DD'); // Default to today
                    form.value.invoiceType = '';
                    form.value.tva = quoteData.taxRule?.id || ''; // Assuming taxRule is an object with an ID
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
                    if (route.query.quote_id) {
                        await fetchRnList();
                        const responseRn = await axiosInstance.get(`/api/carpetOrder/${route.query.quote_id}`); //carpet idcarpetOrderDetail[0].rnAttributions[0].carpet
                        arrayRnDetaill.value = responseRn.data.carpetOrderDetail;

                        carpetOrderDetailsId.value = responseRn.data.id || null; // Assuming this is the ID or value
                    }
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
            const msg = 'Un client d\'id ' + customer_id + ' n\'existe pas';
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

                    // carpetOrderDetailsId.value = invoiceData.customerInvoiceDetails?.[0].carpetOrderDetail || invoiceData.customerInvoiceDetails.carpetOrderDetail;
                    // console.log('carpetOrderDetailsId', carpetOrderDetailsId.value);
                    form.value = {
                        languageId: invoiceData.language_id || 0,
                        prescripteur: invoiceData.prescriber_id || '',
                        //customerRef: invoiceData.reference || '',
                        invoiceNumber: invoiceData.invoice_number || '',
                        invoiceDate: moment(invoiceData.invoice_date).format('YYYY-MM-DD') || '',
                        invoiceType: invoiceData.invoice_type || '', // Assuming this is the ID or value
                        unitOfMeasurement: invoiceData.lmesurement_id || '', // Assuming this is the ID or value
                        tva: invoiceData.tax_rule_id || '', // Assuming this is the ID or value
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
                        number: invoiceData.number || '',
                        rnId: invoiceData.rn_id || null, // Use the rn_id from the invoice data
                        quantityTotal: invoiceData.quantity_total || null // Assuming quantityTotal is the number of lines
                    };

                    // set display fields so CALCULER results and existing data show correctly
                    try {
                        shippingCostsHtDisplay.value = invoiceData.shipping_costs_ht ? formatAmount(invoiceData.shipping_costs_ht) : '0.00';
                    } catch (e) {
                        shippingCostsHtDisplay.value = '0.00';
                    }
                    try {
                        totalHtDisplay.value = invoiceData.total_ht ? formatAmount(invoiceData.total_ht) : '';
                    } catch (e) {
                        totalHtDisplay.value = '';
                    }
                    try {
                        amountHtDisplay.value = invoiceData.amount_ht ? formatAmount(invoiceData.amount_ht) : '';
                    } catch (e) {
                        amountHtDisplay.value = '';
                    }
                    try {
                        amountTvaDisplay.value = invoiceData.amount_tva ? formatAmount(invoiceData.amount_tva) : '';
                    } catch (e) {
                        amountTvaDisplay.value = '';
                    }
                    try {
                        amountTtcDisplay.value = invoiceData.amount_ttc ? formatAmount(invoiceData.amount_ttc) : '';
                    } catch (e) {
                        amountTtcDisplay.value = '';
                    }
                    // format invoice number and billed for display
                    invoiceNumberFormatted.value = invoiceData.invoice_number ? formatInvoiceNumberForDisplay(invoiceData.invoice_number) : '';
                    form.value.billed = invoiceData.billed ? Helper.FormatNumber(toNumber(invoiceData.billed)) : '';

                    // Fetch related data if IDs are present
                    if (selectedCustomer.value) {
                        getCustomer(selectedCustomer.value);
                    }
                    if (form.value.contremarque) {
                        getContremarque(form.value.contremarque);
                    }

                    // Populate lines from invoice details using the helper
                    if (invoiceData.customerInvoiceDetails.length) {
                        carpetOrderDetailsId.value = invoiceData.customerInvoiceDetails[0].carpetOrderDetail || null; // Assuming this is the ID or value
                    }

                    lines.value = mapDetailsToLines(invoiceData.customerInvoiceDetails);
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
            const msg = 'Une contremarque d\'id ' + contremarque_id + ' n\'existe pas';
            window.showMessage(msg, 'error');
        }
    };
    const calculate = () => {
        let totalHtFromLines = 0;
        lines.value.forEach((line) => {
            totalHtFromLines += parseFloat(line.priceHt || 0);
        });
        form.value.quantityTotal = lines.value.length; // Assuming quantityTotal is the number of lines

        form.value.totalHt = totalHtFromLines.toFixed(2);
        totalHtDisplay.value = formatAmount(form.value.totalHt);

        // Ensure shippingCostsHt defaults to 0 if empty
        if (form.value.shippingCostsHt === '' || form.value.shippingCostsHt === null) {
            form.value.shippingCostsHt = '0.00';
        }
        const shippingCosts = toNumber(form.value.shippingCostsHt);
        form.value.amountHt = (toNumber(form.value.totalHt) + shippingCosts).toFixed(2);
        amountHtDisplay.value = formatAmount(form.value.amountHt);

        const tvaRate = form.value.tva?.rate || 0; // Assuming form.tva has a 'rate' property
        form.value.amountTva = (toNumber(form.value.amountHt) * (tvaRate / 100)).toFixed(2);
        amountTvaDisplay.value = formatAmount(form.value.amountTva);

        form.value.amountTtc = (toNumber(form.value.amountHt) + toNumber(form.value.amountTva)).toFixed(2);
        amountTtcDisplay.value = formatAmount(form.value.amountTtc);

        // sync shipping display too
        shippingCostsHtDisplay.value = formatAmount(form.value.shippingCostsHt);
    };
    const save = async () => {
        // validate header before sending
        if (!validateInvoiceHeader()) return;

        // Validate that each line has the required carpetOrderDetailId (backend DTO requires it)
        const missingCarpetDetailLines = lines.value
            .map((l, idx) => ({ id: l.carpetOrderDetailId, idx }))
            .filter(x => !x.id)
            .map(x => x.idx + 1);
        if (missingCarpetDetailLines.length) {
            window.showMessage('Impossible d\'enregistrer: la/les ligne(s) ' + missingCarpetDetailLines.join(', ') + ' n\'ont pas de référence tapis (carpetOrderDetailId).', 'error');
            return;
        }

        // normalize amounts to strings with 2 decimals
        const normalized = (v) => (v === null || v === undefined || v === '' ? null : toNumber(v).toFixed(2));

        // Helper to prefer form value but fall back to display value (two-decimal string)
        const finalString = (formVal, displayVal) => {
            const nForm = normalized(formVal);
            if (nForm !== null) return nForm;
            if (displayVal !== null && displayVal !== undefined && String(displayVal) !== '') {
                return toNumber(displayVal).toFixed(2);
            }
            return null;
        };

        // Ensure billed is a string (two decimals) as expected by backend DTO
        const billedFinal = normalized(form.value.billed) ?? (form.value.billed === 0 || form.value.billed === '0' ? '0.00' : (totalHtDisplay.value ? toNumber(totalHtDisplay.value).toFixed(2) : '0.00'));

        // Compute final totals using either form values or their display equivalents
        const totalHtFinal = finalString(form.value.totalHt, totalHtDisplay.value);
        const amountHtFinal = finalString(form.value.amountHt, amountHtDisplay.value);
        const amountTvaFinal = finalString(form.value.amountTva, amountTvaDisplay.value);
        const amountTtcFinal = finalString(form.value.amountTtc, amountTtcDisplay.value);
        const shippingCostsFinal = finalString(form.value.shippingCostsHt, shippingCostsHtDisplay.value);

        const payload = {
            invoiceNumber: form.value.invoiceNumber,
            invoiceDate: form.value.invoiceDate,
            invoiceType: form.value.invoiceType, // Assuming this is the ID or value
            carrierId: form.value.carrierId || null, // Assuming carrierId is an object
            customerId: selectedCustomer.value,
            carpetOrderId: carpetOrderDetailsId.value || null, // Use carpetOrderId from form
            quantityTotal: form.value.quantityTotal || null,
            shippingCostsHt: shippingCostsFinal,
            // billed must be a string according to backend DTO. Send a two-decimal string, default to '0.00'
            billed: billedFinal,
            payment: normalized(form.value.versement),
            totalHt: totalHtFinal,
            amountHt: amountHtFinal,
            amountTva: amountTvaFinal,
            amountTtc: amountTtcFinal,
            prescriberId: form.value.prescripteur || null, // Assuming prescripteur is an object
            invoiceTypeEntityId: form.value.invoiceType || null, // Assuming invoiceType is an object
            currencyId: form.value.currency || null, // Assuming currency is an object
            conversionId: form.value.rate || null, // Assuming rate is an object
            languageId: form.value.languageId || 0,
            mesurementId: form.value.unitOfMeasurement || null, // Assuming unitOfMeasurement is an object
            regulationId: form.value.reglement || null, // Assuming reglement is an object
            tarifExpeditionId: form.value.tarifExpedition || null, // Assuming tarifExpedition is an object
            number: form.value.numero || '', // Assuming numero is a string
            description: form.value.description || '', // Add description
            project: form.value.project || '', // Add project
            rnId: rnId.value || null, // Use the ref value for rnId
            contremarqueId: form.value.contremarque || null, // Assuming contremarque is an object
            taxRuleId: form.value.tva || null // Assuming tva is an object
        };

        // Build snake_case payload for the backend (matches API DTO / response keys)
        // Ensure invoice_date is a full datetime string 'YYYY-MM-DD HH:mm:ss'
        let invoice_date_full = null;
        if (form.value.invoiceDate) {
            try {
                // If it's already a full datetime, normalize it; if it's a date only, append 00:00:00
                const d = moment(form.value.invoiceDate);
                if (d.isValid()) {
                    invoice_date_full = d.format('YYYY-MM-DD HH:mm:ss');
                } else if (String(form.value.invoiceDate).length === 10) {
                    invoice_date_full = moment(String(form.value.invoiceDate) + ' 00:00:00').format('YYYY-MM-DD HH:mm:ss');
                }
            } catch (err) {
                invoice_date_full = String(form.value.invoiceDate);
            }
        }

        const toIntOrNull = (v) => {
            if (v === null || v === undefined || v === '') return null;
            if (typeof v === 'object') {
                return v?.id ? parseInt(v.id) : null;
            }
            const n = parseInt(v);
            return isNaN(n) ? null : n;
        };

        const snakePayload = {
            invoice_number: form.value.invoiceNumber !== null && form.value.invoiceNumber !== undefined ? String(form.value.invoiceNumber) : null,
            invoice_date: invoice_date_full || null,
            invoice_type: toIntOrNull(form.value.invoiceType),
            carrier_id: toIntOrNull(form.value.carrierId),
            customer_id: toIntOrNull(selectedCustomer.value),
            carpet_order_id: toIntOrNull(carpetOrderDetailsId.value),
            quantity_total: toIntOrNull(form.value.quantityTotal),
            shipping_costs_ht: shippingCostsFinal,
            billed: billedFinal,
            payment: normalized(form.value.versement),
            total_ht: totalHtFinal,
            amount_ht: amountHtFinal,
            amount_tva: amountTvaFinal,
            amount_ttc: amountTtcFinal,
            prescriber_id: toIntOrNull(form.value.prescripteur),
            invoice_type_entity_id: toIntOrNull(form.value.invoiceType),
            currency_id: toIntOrNull(form.value.currency),
            conversion_id: toIntOrNull(form.value.rate),
            language_id: toIntOrNull(form.value.languageId) ?? 0,
            lmesurement_id: toIntOrNull(form.value.unitOfMeasurement),
            regulation_id: toIntOrNull(form.value.reglement),
            tarif_expedition_id: toIntOrNull(form.value.tarifExpedition),
            number: form.value.numero || '',
            description: form.value.description || '',
            project: form.value.project || '',
            rn_id: toIntOrNull(rnId.value),
            contremarque_id: toIntOrNull(form.value.contremarque),
            tax_rule_id: toIntOrNull(form.value.tva)
        };

        console.log('Using snakePayload for API', snakePayload);

        // Build a strictly-typed camelCase payload for the backend DTO (compute typed values inline)
        const finalPayloadCamel = {
            ...payload,
            invoiceNumber: (snakePayload.invoice_number !== null && snakePayload.invoice_number !== undefined) ? String(snakePayload.invoice_number) : (form.value.invoiceNumber !== null && form.value.invoiceNumber !== undefined ? String(form.value.invoiceNumber) : ''),
            invoiceDate: snakePayload.invoice_date || invoice_date_full || (form.value.invoiceDate ? moment(form.value.invoiceDate).format('YYYY-MM-DD HH:mm:ss') : moment().format('YYYY-MM-DD HH:mm:ss')),
            invoiceType: toIntOrNull(form.value.invoiceType) || toIntOrNull(snakePayload.invoice_type) || 0,
            carrierId: toIntOrNull(form.value.carrierId) || toIntOrNull(snakePayload.carrier_id) || 0,
            customerId: toIntOrNull(selectedCustomer.value) || toIntOrNull(snakePayload.customer_id) || 0,
            // ensure billed and totals are strings with two decimals where present
            billed: billedFinal || '0.00',
            totalHt: totalHtFinal || '0.00',
            amountHt: amountHtFinal || '0.00',
            amountTva: amountTvaFinal || '0.00',
            amountTtc: amountTtcFinal || '0.00',
            shippingCostsHt: shippingCostsFinal || '0.00'
        };

        console.log('Using finalPayloadCamel for API', finalPayloadCamel);

        try {
            loading.value = true;
            const invoiceId = route.params.id;
            console.log('Invoice payload', payload);

            if (invoiceId && route.query.quote_id == null) {
                // Update invoice (send strictly-typed camelCase payload to satisfy validators)
                const updateResult = await customerInvoiceService.update(invoiceId, finalPayloadCamel);
                console.log('Update result', updateResult);

                // Save or update lines
                const lineErrors = [];
                for (const line of lines.value) {
                    const linePayload = {
                        customerInvoiceId: parseInt(invoiceId),
                        carpetOrderDetailId: line.carpetOrderDetailId || carpetOrderDetailsId.value || null,
                        cleared: line.cleared || false,
                        rn: line.rn,
                        collectionId: line.collection,
                        modelId: line.model,
                        m2: normalized(line.priceM2),
                        sqft: normalized(line.priceSqft),
                        ht: normalized(line.priceHt),
                        ttc: normalized(line.priceTtc),
                        refCommand: line.refCommande,
                        refQuote: line.refDevis,
                        payment: normalized(line.versement),
                        percent: line.percent || null
                    };
                    try {
                        if (line.id) {
                            await customerInvoiceDetailsService.update(line.id, linePayload);
                        } else {
                            const created = await customerInvoiceDetailsService.create(linePayload);
                            line.id = created.id;
                        }
                    } catch (err) {
                        console.error('Line save error', err, linePayload);
                        lineErrors.push(err?.response?.data?.detail || err?.message || 'Erreur ligne');
                    }
                }

                if (lineErrors.length) {
                    window.showMessage('Erreurs lors de l\'enregistrement des lignes: ' + lineErrors.join('; '), 'error');
                    return;
                }

                window.showMessage('Mise à jour effectuée (id: ' + (updateResult?.id || invoiceId) + ').', 'success');
                router.push({ name: 'client-invoice-list' });
            } else {
                // Create invoice (send strictly-typed camelCase payload to satisfy validators)
                const createdInvoice = await customerInvoiceService.create(finalPayloadCamel);
                console.log('Create result', createdInvoice);

                const lineErrors = [];
                for (const line of lines.value) {
                    const linePayload = {
                        customerInvoiceId: parseInt(createdInvoice.id),
                        carpetOrderDetailId: line.carpetOrderDetailId || carpetOrderDetailsId.value || null,
                        cleared: line.cleared || false,
                        rn: line.rn,
                        collectionId: line.collection,
                        modelId: line.model,
                        m2: normalized(line.priceM2),
                        sqft: normalized(line.priceSqft),
                        ht: normalized(line.priceHt),
                        ttc: normalized(line.priceTtc),
                        refCommand: line.refCommande,
                        refQuote: line.refDevis,
                        payment: normalized(line.versement),
                        percent: line.percent || null
                    };
                    try {
                        const lineResult = await customerInvoiceDetailsService.create(linePayload);
                        line.id = lineResult.id;
                    } catch (err) {
                        console.error('Line create error', err, linePayload);
                        lineErrors.push(err?.response?.data?.detail || err?.message || 'Erreur ligne');
                    }
                }

                if (lineErrors.length) {
                    window.showMessage('Erreurs lors de l\'ajout des lignes: ' + lineErrors.join('; '), 'error');
                    return;
                }

                window.showMessage('Ajout effectué (id: ' + (createdInvoice?.id || '') + ').', 'success');
                router.push({ name: 'client-invoice-list' });
            }
        } catch (e) {
            const serverData = e?.response?.data;
            const serverDetail = serverData?.detail || serverData?.message || e?.message;
            console.error('Save error', e, serverData || e);
            try {
                window.alert('Erreur sauvegarde: ' + (serverDetail || 'Voir console pour détails'));
            } catch (err) {
            }
            window.showMessage(serverDetail || 'Erreur lors de la sauvegarde.', 'error');
        } finally {
            loading.value = false;
        }
    };

    onMounted(async () => {
        // Check if creating from a quote

        if (route.query.quote_id) {
            await getQuote(route.query.quote_id);
            // await fetchRnList();
            // const responseRn = await axiosInstance.get(`/api/carpetOrder/${route.query.quote_id}`); //carpet idcarpetOrderDetail[0].rnAttributions[0].carpet
            // arrayRnDetaill.value = responseRn.data.carpetOrderDetail;
            // console.log('responseRn', arrayRnDetaill.value);
        } else if (route.params.id) {
            await fetchInvoiceById(route.params.id);
        }
        fetchRegulations();
        fetchInvoiceTypes();
        // make sure displays reflect loaded values
        syncDisplayFromForm();
    });

    const saveLine = async (index) => {
        const line = lines.value[index];
        const invoiceId = route.params.id || null; // Get invoice ID from route params

        if (!invoiceId) {
            window.showMessage('Veuillez d\'abord enregistrer la facture principale.', 'warning');
            return; // Cannot save lines if the main invoice hasn't been created
        }

        // ensure we have a carpetOrderDetailId for this line (required by API DTO)
        const effectiveCarpetOrderDetailId = line.carpetOrderDetailId || carpetOrderDetailsId.value || null;
        if (!effectiveCarpetOrderDetailId) {
            window.showMessage('Impossible d\'enregistrer la ligne: identifiant detail tapis (carpetOrderDetailId) manquant.', 'error');
            return;
        }

        // Ensure line data is correctly mapped for the API call
        const linePayload = {
            customerInvoiceId: parseInt(invoiceId), // Link to the main invoice
            carpetOrderDetailId: effectiveCarpetOrderDetailId, // Use the line/carpetOrderDetails id
            cleared: line.cleared || false,
            rn: line.rn,
            collectionId: line.collection,
            modelId: line.model,
            m2: String(line.priceM2) || null,
            sqft: String(line.priceSqft) || null,
            ht: String(line.priceHt) || null,
            ttc: String(line.priceTtc) || null,
            refCommand: line.refCommande,
            refQuote: line.refDevis,
            payment: line.versement || null,
            percent: line.percent || null
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
        lines.value.splice(index, 1);
        const line = lines.value[index];
        if (line.id) {
            try {
                loading.value = true; // Add loading state for deletion
                lines.value.splice(index, 1);
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
    };

    // parse input string into a numeric string with 2 decimals stored on the line
    const onLineAmountInput = (event, line, field) => {
        const raw = event.target.value;
        const num = toNumber(raw);
        // store as string with 2 decimals to be consistent with other code that expects strings
        line[field] = num.toFixed(2);
    };

    // navigate to workshop order details
    const goToWorkshop = (line) => {
        const workshopOrderId = line?.workshopOrderId || form.value.carpetOrderId || null;
        if (!workshopOrderId) {
            window.showMessage('Aucune commande atelier associée.', 'warning');
            return;
        }
        try {
            router.push({ name: 'updateCarpetWorkshop', params: { workshopOrderId: workshopOrderId } });
        } catch (e) {
            router.push({ path: `/workshop/details/${workshopOrderId}` }).catch(() => {
                window.showMessage('Route atelier introuvable.', 'error');
            });
        }
    };
</script>

<style>
    .compact-dropdown-collections .multiselect__tags {
        max-height: 25px;
        overflow: hidden;
    }

</style>
