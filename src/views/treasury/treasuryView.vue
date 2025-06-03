<template>
    <d-base-page :loading="loading">
        <template #title>
            <d-page-title title="Affectation de règlement" />
        </template>

        <template #body>
            <d-panel>
                <template #panel-body>
                    <d-panel-title title="Règlement" class-name="ps-2" />

                    <div class="row p-3 align-items-center">
                        <div class="col-md-3 col-sm-6">
                            <d-input-secondary label="Date" type="date" v-model="paymentData.date" :error="errors.paymentDate" />
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <d-payment-methods v-model="paymentData.paymentMethodId" :error="errors.paymentMethodId" />
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <d-input-secondary label="Montant" v-model="paymentData.paymentAmountHt" class="text-end"
                                :error="errors.paymentAmountHt" />
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <d-currency v-model="paymentData.currencyId" />
                        </div>
                    </div>

                    <div class="row p-3 align-items-center">
                        <div class="col-md-6">
                            <d-textarea label="Libellé sur compte" v-model="paymentData.accountLabel"
                                :error="errors.accountLabel" />
                            <d-input label="Référence transaction" v-model="paymentData.transactionNumber"
                                :error="errors.transactionNumber" />
                        </div>
                        <div class="col-md-6">
                            <div class="row p-3 align-items-center">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Affectation</label>
                                        <select class="form-select" v-model="allocationType">
                                            <option value="quote">Devis</option>
                                            <option value="order">Facture</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6" v-if="allocationType === 'quote'">
                                    <d-quote-dropdown v-model="selectedQuoteObject" label="Devis"
                                        @selected="handleQuoteSelection" />
                                </div>

                                <div class="col-md-6" v-if="allocationType === 'order'">
                                    <d-order-dropdown v-model="selectedOrderObject" label="Facture"
                                        @selected="handleOrderSelection" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <d-panel-title title="Produit" class-name="ps-2 mt-4" />
                    <div class="col-md-3 col-sm-6">
                        <d-input-secondary type="date" />
                    </div>

                    <div class="row px-3 mt-3">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-sm">
                                    <thead class="">
                                        <tr class="border-top text-black bg-black">
                                            <th class="border-start border-end text-white">Projet</th>
                                            <th class="border-start border-end text-white">Emplacement</th>
                                            <th class="border-start border-end text-white">Devis</th>
                                            <th class="border-start border-end text-white">Commande</th>
                                            <th class="border-start border-end text-white">RN</th>
                                            <th class="border-start border-end text-white">Facture</th>
                                            <th class="border-start border-end text-white">Repartit.(%)</th>
                                            <th class="border-start border-end text-white">Affect. TTC</th>
                                            <th class="border-start border-end text-white">Total Doc. TTC</th>
                                            <th class="border-start border-end text-white">Restant TTC</th>
                                            <th class="border-start border-end text-white">Affect. HT</th>
                                            <th class="border-start border-end text-white">TVA</th>
                                            <th class="border-start border-end text-white">Soldé</th>
                                            <th class="border-start border-end text-white"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(allocation, index) in allocations" :key="`allocation-${index}`">
                                            <!-- Projet -->
                                            <td>
                                                <d-collections-dropdown
                                                    v-if="allocation.carpetSpecification?.collection" :disabled="true"
                                                    :show-only-dropdown="true"
                                                    v-model="allocation.carpetSpecification.collection.id" />
                                            </td>

                                            <!-- Emplacement -->
                                            <td>
                                                <d-location-dropdown v-if="allocation.location"
                                                    :show-only-dropdown="true" :disabled="true"
                                                    :contremarque-id="contremarqueId"
                                                    v-model="allocation.location.location_id" />
                                            </td>

                                            <!-- Devis -->
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    v-model="allocation.devis" @change="updateDevis(index)" />
                                            </td>

                                            <!-- Commande -->
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    v-model="allocation.commande_ref"
                                                    @change="updateCommandeRef(index)" />
                                            </td>

                                            <!-- RN -->
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    v-model="allocation.rn" @change="updateRN(index)"
                                                    placeholder="RN-XXXXX" />
                                            </td>

                                            <!-- Facture -->
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    v-model="allocation.facture" @change="updateFacture(index)" />
                                            </td>

                                            <!-- Repartition (%) -->
                                            <td class="text-end">
                                                <input type="number" class="form-control form-control-sm text-end"
                                                    v-model="allocation.distribution"
                                                    @change="updateAllocationAmount(index)" step="0.01" min="0"
                                                    max="100" />
                                            </td>

                                            <td class="text-end">
                                                <input type="number" class="form-control form-control-sm text-end"
                                                    v-model="allocation.allocatedAmountTtc"
                                                    @change="updateAllocationFromAmount(index, 'allocatedAmountTtc')"
                                                    step="0.01" min="0" />
                                            </td>
                                            <td class="text-end">
                                                <input type="number" class="form-control form-control-sm text-end"
                                                    v-model="allocation.totalAmountTtc"
                                                    @change="updateAllocationFromAmount(index, 'totalAmountTtc')"
                                                    step="0.01" min="0" :readonly="true" />
                                            </td>
                                            <td class="text-end">
                                                <input type="number" class="form-control form-control-sm text-end"
                                                    v-model="allocation.remainingAmountTtc"
                                                    @change="updateAllocationFromAmount(index, 'remainingAmountTtc')"
                                                    step="0.01" min="0" :readonly="true" />
                                            </td>
                                            <td class="text-end">
                                                <input type="number" class="form-control form-control-sm text-end"
                                                    v-model="allocation.allocatedAmountHt"
                                                    @change="updateAllocationFromAmount(index, 'allocatedAmountHt')"
                                                    step="0.01" min="0" />
                                            </td>
                                            <td class="text-end">
                                                <input type="number" class="form-control form-control-sm text-end"
                                                    v-model="allocation.tva"
                                                    @change="updateAllocationFromAmount(index, 'tva')" step="0.01"
                                                    min="0" />
                                            </td>

                                            <!-- Soldé -->
                                            <td class="text-center">
                                                <input type="checkbox" class="form-check-input"
                                                    v-model="allocation.cleared"
                                                    @change="handlePaidStatusChange(index)" />
                                            </td>

                                            <!-- Actions -->
                                            <td class="text-center">
                                                <div class="col-auto p-1" @click="removeAllocation(index)">
                                                    <d-delete :api="`/api/order-payment-details/${allocation.id}`"
                                                        class="btn-small" @deleted="removeAllocation(index)"></d-delete>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row p-3 bg-light mt-3">
                        <div class="col-md-12 text-end">
                            <div class="d-inline-block me-4">
                                <span class="fw-bold me-2">Total affecté HT:</span>
                                <span>{{ formatNumber(totalAllocatedHt) }}</span>
                            </div>
                            <div class="d-inline-block me-4">
                                <span class="fw-bold me-2">Total affecté TTC:</span>
                                <span>{{ formatNumber(totalAllocatedTtc) }}</span>
                            </div>
                        </div>
                    </div>
                </template>
            </d-panel>
        </template>

        <template #footer>
            <div class="row p-2 justify-content-between">
                <div class="col-auto">
                    <button class="btn btn-secondary pe-5 ps-5" @click="goBack">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary pe-5 ps-5" @click="savePayment" :disabled="loading || !isFormValid">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </div>
        </template>
    </d-base-page>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axiosInstance from '../../config/http';
import { Helper } from '../../composables/global-methods';
import dBasePage from '../../components/base/d-base-page.vue';
import dPageTitle from '../../components/common/d-page-title.vue';
import dPanel from '../../components/common/d-panel.vue';
import dPanelTitle from '../../components/common/d-panel-title.vue';
import dInput from '../../components/base/d-input.vue';
import dInputSecondary from '../../components/base/d-input-secondary.vue';
import dTextarea from '../../components/base/d-textarea.vue';
import dPaymentMethods from '../../components/common/d-payment-methods.vue';
import dCurrency from '../../components/common/d-currency.vue';
import dQuoteDropdown from '../../components/common/d-quote-dropdown.vue';
import dOrderDropdown from '../../components/common/d-order-dropdown.vue';
import dCollectionsDropdown from '../../components/projet/contremarques/dropdown/d-collections-dropdown.vue';
import dLocationDropdown from '../../components/projet/contremarques/dropdown/d-location-dropdown.vue';
import dDelete from '../../components/common/d-delete.vue';

const router = useRouter();
const route = useRoute();
const loading = ref(false);
const errors = ref({});
const allocationType = ref('quote');
const contremarqueId = ref(0);
const paymentId = ref(route.params.id || null);
const isEditMode = computed(() => !!paymentId.value);

const paymentData = ref({
    date: new Date().toISOString().split('T')[0],
    paymentMethodId: null,
    customerId: null,
    commercialId: null,
    currencyId: null,
    taxRuleId: 1,
    accountLabel: '',
    transactionNumber: '',
    paymentAmountHt: 0,
    taxAmount: 0,
    paymentAmountTtc: 0,
    affectationNote: ''
});

const allocations = ref([]);
const selectedQuoteObject = ref(null);
const selectedOrderObject = ref(null);

const DEFAULT_RN_PREFIX = 'RN-';
const DEFAULT_DISTRIBUTION = '100.00';

onMounted(async () => {
    if (isEditMode.value) {
        await loadPaymentData();
    }
});

const loadPaymentData = async () => {
    try {
        loading.value = true;
        const response = await axiosInstance.get(`/api/order-payment/${paymentId.value}`);
        const data = response.data.response;

        paymentData.value = {
            date: data.date || new Date().toISOString().split('T')[0],
            paymentMethodId: data.paymentMethodId || await getPaymentMethodIdByName(data.paymentMethod),
            customerId: data.customer,
            commercialId: data.commercial,
            currencyId: data.currencyId || await getCurrencyIdByName(data.currency),
            taxRuleId: data.taxRuleId || 1,
            accountLabel: data.accountLabel,
            transactionNumber: data.transactionNumber,
            paymentAmountHt: data.paymentAmountHt,
            taxAmount: data.taxAmount,
            paymentAmountTtc: data.paymentAmountTtc,
            affectationNote: data.affectationNote
        };

        if (data.orderPaymentDetails && data.orderPaymentDetails.length > 0) {
            allocations.value = await Promise.all(data.orderPaymentDetails.map(async (item) => {
                if (item.quote) {
                    try {
                        const quoteResponse = await axiosInstance.get(`/api/quote/${item.quote}`);
                        const quoteData = quoteResponse.data.response;

                        contremarqueId.value = quoteData?.quoteData?.contremarqueId || 0;

                        return {
                            id: item.id,
                            quoteId: item.quote,
                            quoteDetailId: item.quoteDetail,
                            orderId: item.orderId,
                            orderInvoiceId: item.orderInvoiceId,
                            projetId: item.projetId,
                            emplacementId: item.emplacementId,
                            carpetSpecification: quoteData?.quoteData?.quoteDetails?.find(d => d.id === item.quoteDetail)?.carpetSpecification || null,
                            location: quoteData?.quoteData?.quoteDetails?.find(d => d.id === item.quoteDetail)?.location || null,
                            devis: quoteData?.quoteData?.reference || '',
                            commande_ref: item.commandNumber || '',
                            rn: item.rn || DEFAULT_RN_PREFIX + Math.random().toString(36).substring(2, 7).toUpperCase(),
                            facture: item.facture || '',
                            distribution: item.distribution || DEFAULT_DISTRIBUTION,
                            allocatedAmountTtc: item.allocatedAmountTtc || 0,
                            totalAmountTtc: item.totalAmountTtc || 0,
                            remainingAmountTtc: item.remainingAmountTtc || 0,
                            allocatedAmountHt: item.allocatedAmountHt || 0,
                            tva: item.tva || 0,
                            cleared: item.cleared || false,
                            type: 'quote',
                            areaSquareMeter: item.areaSquareMeter || 0,
                            areaSquareFeet: item.areaSquareFeet || 0
                        };
                    } catch (error) {
                        console.error(`Erreur lors du chargement du devis ${item.quote}:`, error);
                        return {
                            ...item,
                            type: 'quote',
                            devis: 'Devis non chargé',
                            carpetSpecification: null,
                            location: null
                        };
                    }
                } else {
                    return {
                        ...item,
                        type: 'order',
                        devis: '',
                        commande_ref: item.commandNumber || '',
                        carpetSpecification: null,
                        location: null
                    };
                }
            }));
        }

    } catch (error) {
        console.error("Erreur lors du chargement du paiement:", error);
        window.showMessage('Erreur lors du chargement du paiement', 'error');
    } finally {
        loading.value = false;
    }
};

const getCurrencyIdByName = async (currencyName) => {
    if (!currencyName) return null;

    try {
        const response = await axiosInstance.get('/api/currency');
        const currencies = response.data.response;
        const currency = currencies.find(c => c.name === currencyName);
        return currency ? currency.id : null;
    } catch (error) {
        console.error("Erreur lors de la récupération des devises:", error);
        return null;
    }
};

const updateAllocationFromAmount = (index, field) => {
    if (!isEditMode.value) return;
    
    const allocation = allocations.value[index];
    const taxRate = getTaxRateForAllocation(allocation);
    
    allocation.allocatedAmountTtc = parseFloat(allocation.allocatedAmountTtc) || 0;
    allocation.allocatedAmountHt = parseFloat(allocation.allocatedAmountHt) || 0;
    allocation.tva = parseFloat(allocation.tva) || 0;
    
    if (field === 'allocatedAmountTtc') {
        allocation.allocatedAmountHt = allocation.allocatedAmountTtc / (1 + taxRate);
        allocation.tva = allocation.allocatedAmountTtc - allocation.allocatedAmountHt;
    } else if (field === 'allocatedAmountHt') {
        allocation.allocatedAmountTtc = allocation.allocatedAmountHt * (1 + taxRate);
        allocation.tva = allocation.allocatedAmountTtc - allocation.allocatedAmountHt;
    } else if (field === 'tva') {
        allocation.allocatedAmountTtc = allocation.allocatedAmountHt + allocation.tva;
    }
    
    if (allocation.totalAmountTtc > 0) {
        allocation.distribution = ((allocation.allocatedAmountTtc / allocation.totalAmountTtc) * 100).toFixed(2);
    }
    
    allocation.remainingAmountTtc = allocation.totalAmountTtc - allocation.allocatedAmountTtc;
    
    allocations.value[index] = {...allocation};
};

const getPaymentMethodIdByName = async (paymentMethodName) => {
    if (!paymentMethodName) return null;

    try {
        const response = await axiosInstance.get('/api/payment-type');
        const methods = response.data.response;
        const method = methods.find(m => m.label === paymentMethodName);
        return method ? method.id : null;
    } catch (error) {
        console.error("Erreur lors de la récupération des modes de paiement:", error);
        return null;
    }
};

const handleQuoteSelection = async (quoteFullObject) => {
    if (!quoteFullObject?.quote_id) {
        window.showMessage('Veuillez sélectionner un devis valide.', 'warning');
        return;
    }

    if (allocations.value.some(a => a.quoteId === quoteFullObject.quote_id)) {
        window.showMessage('Ce devis a déjà été ajouté.', 'warning');
        return;
    }

    try {
        loading.value = true;
        const response = await axiosInstance.get(`/api/quote/${quoteFullObject.quote_id}`);
        const quoteWithDetails = response.data.response;

        if (!quoteWithDetails.quoteData?.quoteDetails?.length) {
            window.showMessage('Ce devis ne contient aucun détail.', 'warning');
            return;
        }

        contremarqueId.value = quoteFullObject.contremarque_id;

        quoteWithDetails.quoteData.quoteDetails.forEach(quoteDetail => {
            const allocation = createAllocationObject(quoteFullObject, quoteDetail);
            updateAllocationForNewItem(allocation);
            allocations.value.push(allocation);
        });

        paymentData.value.customerId = quoteFullObject.customer_id || null;
        paymentData.value.commercialId = quoteFullObject.commercial_id || null;
        paymentData.value.taxRuleId = quoteFullObject.taxRule?.id || 1;

    } catch (error) {
        console.error("Erreur lors de la récupération des détails du devis:", error);
        window.showMessage('Erreur lors de la récupération des détails du devis', 'error');
    } finally {
        loading.value = false;
    }
};

const createAllocationObject = (quoteFullObject, quoteDetail) => {
    return {
        quoteId: quoteFullObject.quote_id,
        quoteDetailId: quoteDetail.id,
        orderId: null,
        orderInvoiceId: null,
        projetId: quoteDetail.location?.location_id || null,
        emplacementId: quoteDetail.location?.location_id || quoteFullObject.deliveryAddress?.id || null,
        carpetSpecification: quoteDetail?.carpetSpecification || null,
        location: quoteDetail?.location || null,
        devis: quoteFullObject.reference || '',
        commande_ref: quoteDetail.reference || '',
        rn: DEFAULT_RN_PREFIX + Math.random().toString(36).substring(2, 7).toUpperCase(),
        facture: '',
        distribution: DEFAULT_DISTRIBUTION,
        allocatedAmountTtc: 0,
        totalAmountTtc: parseFloat(quoteDetail.prices?.['prix-propose-avant-remise-complementaire']?.totalPriceTtc) || 0,
        remainingAmountTtc: parseFloat(quoteDetail.prices?.['prix-propose-avant-remise-complementaire']?.totalPriceTtc) || 0,
        allocatedAmountHt: 0,
        tva: 0,
        cleared: false,
        type: 'quote',
        areaSquareMeter: quoteDetail.areaSquareMeter || 0,
        areaSquareFeet: quoteDetail.areaSquareFeet || 0
    };
};

const handleOrderSelection = (orderFullObject) => {
    // @to DO
};

const updateAllocationForNewItem = (allocation) => {
    const taxRate = allocation.type === 'quote' ?
        (selectedQuoteObject.value?.taxRule?.taxRate || 0.20) :
        (paymentData.value.taxRule?.taxRate || 0.20);

    const allocatedTtc = (allocation.totalAmountTtc * parseFloat(allocation.distribution) / 100).toFixed(2);
    allocation.allocatedAmountTtc = Helper.FormatNumber(allocatedTtc);
    const allocatedHt = allocatedTtc / (1 + taxRate);
    allocation.allocatedAmountHt = Helper.FormatNumber(allocatedHt);
    allocation.tva = Helper.FormatNumber(allocatedTtc - allocatedHt);
    allocation.remainingAmountTtc = Helper.FormatNumber(allocation.totalAmountTtc - allocatedTtc);
};

const updateDevis = (index) => {
    console.log('Devis mis à jour:', allocations.value[index].devis);
};

const updateCommandeRef = (index) => {
    console.log('Commande ref mise à jour:', allocations.value[index].commande_ref);
};

const updateRN = (index) => {
    console.log('RN mis à jour:', allocations.value[index].rn);
};

const updateFacture = (index) => {
    console.log('Facture mise à jour:', allocations.value[index].facture);
};

const updateAllocationAmount = (index) => {
    const allocation = allocations.value[index];
    const distribution = Math.min(100, Math.max(0, parseFloat(allocation.distribution) || 0));
    allocation.distribution = distribution.toFixed(2);

    const totalTtcOfDocument = parseFloat(allocation.totalAmountTtc) || 0;
    const allocatedTtc = (totalTtcOfDocument * distribution / 100);

    const taxRate = getTaxRateForAllocation(allocation);

    allocation.allocatedAmountTtc = Helper.FormatNumber(allocatedTtc);
    const allocatedHt = allocatedTtc / (1 + taxRate);
    allocation.allocatedAmountHt = Helper.FormatNumber(allocatedHt);
    allocation.tva = Helper.FormatNumber(allocatedTtc - allocatedHt);
    allocation.remainingAmountTtc = Helper.FormatNumber(totalTtcOfDocument - allocatedTtc);

    if (allocation.cleared) {
        handlePaidStatusChange(index);
    }
};

const getTaxRateForAllocation = (allocation) => {
    if (allocation.type === 'quote' && selectedQuoteObject.value?.taxRule?.taxRate) {
        return parseFloat(selectedQuoteObject.value.taxRule.taxRate);
    }
    if (allocation.type === 'order' && paymentData.value.taxRule?.taxRate) {
        return parseFloat(paymentData.value.taxRule.taxRate);
    }
    return 0.20;
};

const handlePaidStatusChange = (index) => {
    const allocation = allocations.value[index];
    if (allocation.cleared) {
        allocation.paidAmount = allocation.allocatedAmountTtc;
        allocation.paidDate = new Date().toISOString().split('T')[0];
    } else {
        allocation.paidAmount = 0;
        allocation.paidDate = null;
    }
};

const removeAllocation = async (index) => {
    const allocation = allocations.value[index];

    if (allocation.id) {
        try {
            await axiosInstance.delete(`/api/order-payment-details/${allocation.id}`);
            window.showMessage('Affectation supprimée avec succès', 'success');
        } catch (error) {
            console.error("Erreur lors de la suppression de l'affectation:", error);
            window.showMessage('Erreur lors de la suppression', 'error');
            return;
        }
    }

    allocations.value.splice(index, 1);
};

const formatNumber = (value) => {
    if (value === null || value === undefined || isNaN(parseFloat(value))) return '0.00';
    return Helper.FormatNumber(parseFloat(value));
};

const totalAllocatedHt = computed(() => {
    return allocations.value.reduce((sum, alloc) => sum + (parseFloat(alloc.allocatedAmountHt) || 0), 0);
});

const totalAllocatedTtc = computed(() => {
    return allocations.value.reduce((sum, alloc) => sum + (parseFloat(alloc.allocatedAmountTtc) || 0), 0);
});

const remainingAmount = computed(() => {
    const paymentTtcNum = parseFloat(paymentData.value.paymentAmountTtc) || 0;
    return paymentTtcNum - totalAllocatedTtc.value;
});

const isFormValid = computed(() => {
    const paymentAmountNum = parseFloat(paymentData.value.paymentAmountTtc) || 0;
    return paymentData.value.paymentMethodId &&
        paymentData.value.customerId &&
        paymentData.value.currencyId &&
        paymentData.value.taxRuleId &&
        paymentAmountNum > 0 &&
        allocations.value.length > 0;
});

const savePayment = async () => {
    console.log(paymentData)
    if (!isFormValid.value) {
        let errorMsg = "Formulaire invalide. Vérifiez les champs: ";
        if (!paymentData.value.paymentMethodId) errorMsg += "Mode de paiement, ";
        if (!paymentData.value.customerId) errorMsg += "Client, ";
        if (allocations.value.length === 0) errorMsg += "Aucune affectation, ";
        window.showMessage(errorMsg.slice(0, -2) + ".", 'error');
        return;
    }

    loading.value = true;
    errors.value = {};

    try {
        if (isEditMode.value) {
            await axiosInstance.put(`/api/order-payments/${paymentId.value}/update`, createPaymentPayload());

            for (const allocation of allocations.value) {
                if (allocation.id) {
                    await axiosInstance.put(`/api/order-payment-details/${allocation.id}/update`,
                        createAllocationDetailPayload(paymentId.value, allocation));
                } else {
                    await axiosInstance.post('/api/order-payment-details/create',
                        createAllocationDetailPayload(paymentId.value, allocation));
                }
            }
        } else {
            const paymentResponse = await axiosInstance.post('/api/order-payment', createPaymentPayload());
            paymentId.value = paymentResponse.data.response.id;

            for (const allocation of allocations.value) {
                await axiosInstance.post('/api/order-payment-details/create',
                    createAllocationDetailPayload(paymentId.value, allocation));
            }
        }

        window.showMessage('Règlement enregistré avec succès', 'success');
        router.push({ name: 'treasury_list' });
    } catch (error) {
        handleSavePaymentError(error);
    } finally {
        loading.value = false;
    }
};

const createPaymentPayload = () => {
    return {
        paymentMethodId: parseInt(paymentData.value.paymentMethodId),
        customerId: parseInt(paymentData.value.customerId),
        commercialId: paymentData.value.commercialId ? parseInt(paymentData.value.commercialId) : null,
        currencyId: parseInt(paymentData.value.currencyId),
        taxRuleId: parseInt(paymentData.value.taxRuleId),
        accountLabel: paymentData.value.accountLabel,
        transactionNumber: paymentData.value.transactionNumber,
        paymentAmountHt: parseFloat(paymentData.value.paymentAmountHt).toFixed(2),
        paymentAmountTtc: parseFloat(paymentData.value.paymentAmountTtc).toFixed(2),
        date: paymentData.value.date,
        affectationNote: paymentData.value.affectationNote
    };
};

const createAllocationDetailPayload = (paymentId, allocation) => {
    return {
        orderPaymentId: parseInt(paymentId),
        quoteId: allocation.type === 'quote' ? parseInt(allocation.quoteId) : null,
        quoteDetailId: allocation.type === 'quote' ? parseInt(allocation.quoteDetailId) : null,
        orderId: allocation.type === 'order' ? parseInt(allocation.orderId) : null,
        orderInvoiceId: allocation.orderInvoiceId || null,
        projetId: allocation.projetId ? parseInt(allocation.projetId) : null,
        emplacementId: allocation.emplacementId ? parseInt(allocation.emplacementId) : null,
        devis: allocation.devis || '',
        commandNumber: allocation.commande_ref || '',
        rn: allocation.rn || '',
        facture: allocation.facture || '',
        distribution: parseFloat(allocation.distribution).toFixed(2),
        allocatedAmountTtc: parseFloat(allocation.allocatedAmountTtc).toFixed(2),
        remainingAmountTtc: parseFloat(allocation.remainingAmountTtc).toFixed(2),
        totalAmountTtc: parseFloat(allocation.totalAmountTtc).toFixed(2),
        tva: parseFloat(allocation.tva).toFixed(2),
        allocatedAmountHt: parseFloat(allocation.allocatedAmountHt).toFixed(2),
        cleared: allocation.cleared,
        paidAmount: allocation.paidAmount ? parseFloat(allocation.paidAmount).toFixed(2) : 0,
        paidDate: allocation.paidDate || null,
        areaSquareMeter: allocation.areaSquareMeter || 0,
        areaSquareFeet: allocation.areaSquareFeet || 0
    };
};

const handleSavePaymentError = (error) => {
    if (error.response?.data?.violations) {
        errors.value = formatErrorViolations(error.response.data.violations);
    } else {
        const errorDetail = error.response?.data?.detail || error.response?.data?.message || error.message || "Erreur inconnue";
        window.showMessage(`Erreur lors de l'enregistrement: ${errorDetail}`, 'error');
        console.error("Save Payment Error:", error.response || error);
    }
};

const formatErrorViolations = (violations) => {
    return violations.reduce((acc, violation) => {
        acc[violation.propertyPath] = violation.message;
        return acc;
    }, {});
};

const goBack = () => {
    router.go(-1);
};

watch(() => allocationType.value, (newType) => {
    selectedQuoteObject.value = null;
    selectedOrderObject.value = null;
});

watch(
    [() => paymentData.value.paymentAmountHt, () => paymentData.value.taxRuleId],
    () => {
        const ht = parseFloat(paymentData.value.paymentAmountHt) || 0;
        const taxRate = getCurrentTaxRate();
        paymentData.value.taxAmount = ht * taxRate;
        paymentData.value.paymentAmountTtc = ht + paymentData.value.taxAmount;
    },
    { deep: true }
);

const getCurrentTaxRate = () => {
    if (paymentData.value.taxRuleId) {
        if (paymentData.value.taxRule && typeof paymentData.value.taxRule.taxRate !== 'undefined') {
            return parseFloat(paymentData.value.taxRule.taxRate);
        }
        return paymentData.value.taxRuleId === 1 ? 0.20 : (paymentData.value.taxRuleId === 2 ? 0.10 : 0);
    }
    return 0;
};
</script>


<style scoped>
.table th {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.table td {
    padding: 0.5rem;
    vertical-align: middle;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.text-danger {
    color: #dc3545;
}

.row {
    display: flex;
}

.row>[class*='col-'] {
    display: flex;
    flex-direction: column;
}

.form-select {
    padding: 0.375rem 2.25rem 0.375rem 0.75rem;
}

.form-control-sm {
    min-height: calc(1.5em + 0.5rem + 2px);
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.2rem;
}

.text-end .form-control-sm {
    text-align: right;
}

.btn-small .btn.rounded-circle {
    height: 20px !important;
    width: 20px !important;
}

.form-group{
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-bottom: 0px;
}

.bg-black {
    --bs-bg-opacity: 1;
    background-color: rgba(var(--bs-black-rgb), var(--bs-bg-opacity)) !important;
}
</style>