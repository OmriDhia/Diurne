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
                                                        <d-customer-dropdown :disabled="true" :isPrescripteur="true" v-model="prescriber" />
                                                    </div>
                                                    <div class="col-md-12 mt-2">
                                                        <d-input label="Description" v-model="form.description" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- <d-input label="Type de facture" v-model="form.invoiceType" /> -->
                                                <div class="row align-items-center">
                                                    <label for="" class="col-4">Type de facture:</label>
                                                    <div class="col-8">
                                                        <!-- <d-customer-type v-model="form.invoiceType"> </d-customer-type> -->
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
                                                    <div class="col-8 custom-droupdown-exist">
                                                        <d-transport-condition v-model="form.transporteur"></d-transport-condition>
                                                        <!-- <multiselect v-model="form.transporteur" :options="[]" :multiple="false" placeholder="" :searchable="true"></multiselect> -->
                                                    </div>
                                                </div>

                                                <d-input label="Numéro" v-model="form.numero" />
                                            </div>
                                        </div>
                                        <d-panel-title title="Autre tapis" class-name="ps-2 mt-2" />
                                        <div class="row p-3">
                                            <div class="bloc-add">
                                                <div class="col-12">
                                                    <d-RN-dropdown v-model="form.rn" :carpetOrderDetailsId="carpetOrderDetailsId" :showOnlyDropdown="true" />
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

                <div class="mt-3" v-if="quote?.quoteDetails && quote?.quoteDetails.length > 0">
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
                                    <button class="btn btn-custom">CALCULER</button>
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
    import quoteService from '../../../Services/quote-service';
    import dTransportCondition from '../../../components/common/d-transportCondition.vue';
    import dModelDropdown from '../../../components/projet/contremarques/dropdown/d-model-dropdown.vue';
    import dCollectionsDropdown from '../../../components/projet/contremarques/dropdown/d-collections-dropdown.vue';
    import { Helper } from '../../../composables/global-methods';

    import DRNDropdown from '../../../components/projet/contremarques/dropdown/d-RN-dropdown.vue';
    import moment from 'moment';
    import contremarqueService from '../../../Services/contremarque-service';
    useMeta({ title: 'Nouvelle Facture' });

    const route = useRoute();
    const router = useRouter();
    const loading = ref(false);
    const quote_id = route.query.quote_id || null;
    const quote = ref({});
    let carpetOrderDetailsId = ref(null);
    const contremarque = ref({});
    const selectedCustomer = ref(null);
    const prescriber = ref(null);
    const currentCustomer = ref({});

    const form = ref({
        customerRef: '', //??
        invoiceNumber: '', //invoiceNumber == customerId
        invoiceDate: '',
        project: '', //??
        invoiceType: '',
        tva: '', //?
        currency: null, //?
        rate: '', //?
        languageId: 0, //?
        unit: '', //?
        contremarque: null, //?
        prescripteur: '', //?
        description: '', //?
        reglement: '', //?
        tarifExpedition: '', //?
        transporteur: '', //?
        numero: '', //?
        otherRns: [''],
        quantityTotal: '',
        shippingCostsHt: '',
        versement: '', //Versement==payment
        billed: '',
        totalHt: '',
        amountHt: '',
        amountTva: '',
        amountTtc: '',
        carpetOrderId: null, // Quel champ???
    });

    const lines = ref([
        // Initial empty line
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

    watch(selectedCustomer, (customerId) => {
        getCustomer(customerId);
    });

    watch(
        () => form.value.contremarque,
        (contremarqueId) => {
            getContremarque(contremarqueId);
        }
    );

    const getQuote = async (id) => {
        try {
            if (id) {
                loading.value = true;
                const data = await quoteService.getQuoteById(id);
                quote.value = data;
                if (quote.value) {
                    carpetOrderDetailsId = quote.value.id;
                    form.value = {
                        ...form.value,
                        languageId: quote.value?.language.id,
                        // prescripteur: quote.value.prescripteur.id || '',
                        customerRef: quote.value.reference || '',
                        invoiceNumber: quote.value.invoiceNumber || '',
                        invoiceDate: moment(quote.value.createdAt).format('YYYY-MM-DD') || '',
                        invoiceType: quote.value.invoiceType || '',
                        tva: quote.value.otherTva || '',
                        currency: quote.value.currency.id || null,
                        rate: quote.value.conversion.id || '',
                        transporteur: quote.value.transportCondition.id || '',
                        contremarque: quote.value.contremarqueId || null,
                        shippingCostsHt: parseFloat(quote.value.shippingPrice) || '',
                        totalHt: parseFloat(quote.value.totalTaxExcluded) || '',
                        amountHt: parseFloat(quote.value.otherTva) || '',
                        amountTtc: parseFloat(quote.value.totalTaxIncluded) || '',
                        versement: parseFloat(quote.value.totalTaxIncluded) || '',
                        billed: parseFloat(quote.value.totalDiscountPercentage) || '',
                        amountTva: parseFloat(quote.value.tax) || '',
                    };
                    if (form.value.contremarque) {
                        getContremarque(form.value.contremarque);
                    }
                }
                if (data?.quoteDetails) {
                    lines.value = data.quoteDetails.map((d) => ({
                        percent: d.impactOnTheQuotePrice,
                        rn: d.rn,
                        collection: d.carpetSpecification?.collection?.id || null,
                        model: d.carpetSpecification?.model?.id || null,
                        refDevis: d.reference,
                        refCommande: '',
                        versement: null,
                        priceM2: Helper.getPrice(d.prices, 'tarif.m².price'),
                        priceSqft: Helper.getPrice(d.prices, 'tarif.sqft.price'),
                        priceHt: Helper.getPrice(d.prices, 'prix-propose-avant-remise-complementaire.m².price'),
                        priceTtc: Helper.getPrice(d.prices, 'prix-propose-avant-remise-complementaire.totalPriceTtc'),
                    }));
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

    const getContremarque = async (contremarque_id) => {
        try {
            if (contremarque_id) {
                contremarque.value = await contremarqueService.getContremarqueById(contremarque_id);
                selectedCustomer.value = contremarque.value.customer.customer_id;
                prescriber.value = contremarque.value.prescriber.customer_id;
            }
        } catch (e) {
            const msg = "Une contremarque d'id " + contremarque_id + " n'existe pas";
            window.showMessage(msg, 'error');
        }
    };

    const save = async () => {
        try {
            loading.value = true;
            if (route.params.id) {
                await customerInvoiceService.update(route.params.id, { ...form.value, lines: lines.value });
                window.showMessage('Mise à jour avec succés.');
            } else {
                await customerInvoiceService.create({ ...form.value, lines: lines.value });
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
        if (route.params.id) {
            await getQuote(route.params.id);
        }
    });

    const saveLine = (index) => {
        console.log('save line', lines.value[index]);
    };

    const removeLine = (index) => {
        lines.value.splice(index, 1);
    };

    const addAutreRn = () => {
        form.value.otherRns.push('');
    };
</script>

<style scoped></style>
