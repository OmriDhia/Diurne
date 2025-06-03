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
                            <d-input
                                label="Date"
                                type="date"
                                v-model="paymentData.date"
                                :error="errors.paymentDate"
                            />
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <d-payment-methods
                                v-model="paymentData.paymentMethodId"
                                :error="errors.paymentMethodId"
                            />
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <d-input
                                label="Montant"
                                v-model="paymentData.paymentAmountHt"
                                class="text-end"
                                :error="errors.paymentAmountHt"
                            />
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <d-currency v-model="paymentData.currencyId" />
                        </div>
                    </div>

                    <div class="row p-3 align-items-center">
                        <div class="col-md-6">
                            <d-input
                                label="Référence transaction"
                                v-model="paymentData.transactionNumber"
                                :error="errors.transactionNumber"
                            />
                        </div>
                        <div class="col-md-6">
                            <d-input
                                label="Libellé sur compte"
                                v-model="paymentData.accountLabel"
                                :error="errors.accountLabel"
                            />
                        </div>
                    </div>

                    <d-panel-title title="Affectation du règlement" class-name="ps-2 mt-4" />

                    <div class="row p-3 align-items-center">
                        <div class="col-md-auto">
                            <div class="form-group">
                                <label class="form-label">Type d'affectation</label>
                                <select
                                    class="form-select"
                                    v-model="allocationType"
                                    style="width: 150px;"
                                >
                                    <option value="quote">Devis</option>
                                    <option value="order">Facture</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3" v-if="allocationType === 'quote'">
                            <d-quote-dropdown
                                v-model="selectedQuoteObject"
                                label="Devis"
                                @selected="handleQuoteSelection"
                            />
                        </div>

                        <div class="col-md-3" v-if="allocationType === 'order'">
                            <d-order-dropdown
                                v-model="selectedOrderObject"
                                label="Facture" 
                                @selected="handleOrderSelection"
                            />
                        </div>
                    </div>

                    <div class="row px-3 mt-3">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Projet</th>
                                            <th>Emplacement</th>
                                            <th>Devis</th>
                                            <th>Commande</th>
                                            <th>RN</th>
                                            <th>Facture</th>
                                            <th class="text-end">Repartit.(%)</th>
                                            <th class="text-end">Affect. TTC</th>
                                            <th class="text-end">Total Doc. TTC</th>
                                            <th class="text-end">Restant TTC</th>
                                            <th class="text-end">Affect. HT</th>
                                            <th class="text-end">TVA</th>
                                            <th class="text-center">Soldé</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(allocation, index) in allocations" :key="`allocation-${index}`">
                                            <!-- Projet -->
                                            <td>
                                                <d-collections-dropdown
                                                    v-if="allocation.carpetSpecification?.collection"
                                                    :disabled="true"
                                                    :show-only-dropdown="true"
                                                    v-model="allocation.carpetSpecification.collection.id"
                                                />
                                            </td>
                                            
                                            <!-- Emplacement -->
                                            <td>
                                                <d-location-dropdown
                                                    v-if="allocation.location"
                                                    :show-only-dropdown="true"
                                                    :disabled="true"
                                                    :contremarque-id="contremarqueId"
                                                    v-model="allocation.location.location_id"
                                                /> 
                                            </td>
                                            
                                            <!-- Devis -->
                                            <td>
                                                <input
                                                    type="text"
                                                    class="form-control form-control-sm" 
                                                    v-model="allocation.devis"
                                                    @change="updateDevis(index)"
                                                />
                                            </td>
                                            
                                            <!-- Commande -->
                                            <td>
                                                <input
                                                    type="text"
                                                    class="form-control form-control-sm" 
                                                    v-model="allocation.commande_ref"
                                                    @change="updateCommandeRef(index)"
                                                />
                                            </td>
                                            
                                            <!-- RN -->
                                            <td>
                                                <input
                                                    type="text"
                                                    class="form-control form-control-sm" 
                                                    v-model="allocation.rn"
                                                    @change="updateRN(index)"
                                                    placeholder="RN-XXXXX"
                                                />
                                            </td>
                                            
                                            <!-- Facture -->
                                            <td>
                                                <input
                                                    type="text"
                                                    class="form-control form-control-sm" 
                                                    v-model="allocation.facture"
                                                    @change="updateFacture(index)"
                                                />
                                            </td>
                                            
                                            <!-- Repartition (%) -->
                                            <td class="text-end">
                                                <input
                                                    type="number"
                                                    class="form-control form-control-sm text-end" 
                                                    v-model="allocation.distribution"
                                                    @change="updateAllocationAmount(index)"
                                                    step="0.01"
                                                    min="0"
                                                    max="100"
                                                />
                                            </td>
                                            
                                           <td class="text-end">
                                            <input
                                                type="number"
                                                class="form-control form-control-sm text-end"
                                                v-model="allocation.allocatedAmountTtc"
                                                @change="updateAllocationFromAmount(index, 'allocatedAmountTtc')"
                                                step="0.01"
                                                min="0"
                                            />
                                        </td>
                                        <td class="text-end">
                                            <input
                                                type="number"
                                                class="form-control form-control-sm text-end"
                                                v-model="allocation.totalAmountTtc"
                                                @change="updateAllocationFromAmount(index, 'totalAmountTtc')"
                                                step="0.01"
                                                min="0"
                                                :readonly="true"
                                            />
                                        </td>
                                        <td class="text-end">
                                            <input
                                                type="number"
                                                class="form-control form-control-sm text-end"
                                                v-model="allocation.remainingAmountTtc"
                                                @change="updateAllocationFromAmount(index, 'remainingAmountTtc')"
                                                step="0.01"
                                                min="0"
                                                :readonly="true"
                                            />
                                        </td>
                                        <td class="text-end">
                                            <input
                                                type="number"
                                                class="form-control form-control-sm text-end"
                                                v-model="allocation.allocatedAmountHt"
                                                @change="updateAllocationFromAmount(index, 'allocatedAmountHt')"
                                                step="0.01"
                                                min="0"
                                            />
                                        </td>
                                        <td class="text-end">
                                            <input
                                                type="number"
                                                class="form-control form-control-sm text-end"
                                                v-model="allocation.tva"
                                                @change="updateAllocationFromAmount(index, 'tva')"
                                                step="0.01"
                                                min="0"
                                            />
                                        </td>
                                            
                                            <!-- Soldé -->
                                            <td class="text-center">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input" 
                                                    v-model="allocation.cleared"
                                                    @change="handlePaidStatusChange(index)"
                                                />
                                            </td>
                                            
                                            <!-- Actions -->
                                            <td class="text-center">
                                                <div class="col-auto p-1" @click="removeAllocation(index)">
                                                    <d-delete :api="''" class="btn-small"></d-delete>
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
                            <div class="d-inline-block">
                                <span class="fw-bold me-2">Reste à affecter:</span>
                                <span :class="{ 'text-danger': remainingAmount < 0, 'text-success': remainingAmount === 0 }">
                                    {{ formatNumber(remainingAmount) }}
                                </span>
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
                    <button
                        class="btn btn-primary pe-5 ps-5"
                        @click="savePayment"
                        :disabled="loading || !isFormValid"
                    >
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </div>
        </template>
    </d-base-page>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axiosInstance from '../../config/http';
import { Helper } from '../../composables/global-methods';
import dBasePage from '../../components/base/d-base-page.vue';
import dPageTitle from '../../components/common/d-page-title.vue';
import dPanel from '../../components/common/d-panel.vue';
import dPanelTitle from '../../components/common/d-panel-title.vue';
import dInput from '../../components/base/d-input.vue';
import dPaymentMethods from '../../components/common/d-payment-methods.vue';
import dCurrency from '../../components/common/d-currency.vue';
import dQuoteDropdown from '../../components/common/d-quote-dropdown.vue';
import dOrderDropdown from '../../components/common/d-order-dropdown.vue';
import dCollectionsDropdown from '../../components/projet/contremarques/dropdown/d-collections-dropdown.vue';
import dLocationDropdown from '../../components/projet/contremarques/dropdown/d-location-dropdown.vue';
import dDelete from '../../components/common/d-delete.vue';

const router = useRouter();
const loading = ref(false);
const errors = ref({});
const allocationType = ref('quote');
const contremarqueId = ref(0);
const projectsList = ref([]);
const locationsList = ref([]);

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
    // @To do
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

const removeAllocation = (index) => {
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
        allocations.value.length > 0 &&
        Math.abs(remainingAmount.value) < 0.01;
});

const updateAllocationFromAmount = (index, field) => {
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
        allocation.allocatedAmountHt = allocation.allocatedAmountTtc - allocation.tva;
    }
    
    if (allocation.totalAmountTtc > 0) {
        allocation.distribution = ((allocation.allocatedAmountTtc / allocation.totalAmountTtc) * 100).toFixed(2);
    }
    
    allocation.remainingAmountTtc = allocation.totalAmountTtc - allocation.allocatedAmountTtc;
    
    allocations.value[index] = {...allocation};
};

const savePayment = async () => {
    if (!isFormValid.value) {
        let errorMsg = "Formulaire invalide. Vérifiez les champs: ";
        if (!paymentData.value.paymentMethodId) errorMsg += "Mode de paiement, ";
        if (!paymentData.value.customerId) errorMsg += "Client, ";
        if (allocations.value.length === 0) errorMsg += "Aucune affectation, ";
        if (Math.abs(remainingAmount.value) >= 0.01) errorMsg += `Reste à affecter non nul (${formatNumber(remainingAmount.value)}), `;
        window.showMessage(errorMsg.slice(0, -2) + ".", 'error');
        return;
    }

    loading.value = true;
    errors.value = {};

    try {
        const paymentPayload = createPaymentPayload();
        const paymentResponse = await axiosInstance.post('/api/order-payment', paymentPayload);
        const paymentId = paymentResponse.data.response.id; 
        await saveAllocationDetails(paymentId);

        window.showMessage('Règlement enregistré avec succès');
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

const saveAllocationDetails = async (paymentId) => {
    for (const allocation of allocations.value) {
        const detailPayload = createAllocationDetailPayload(paymentId, allocation);
        await axiosInstance.post('/api/order-payment-details/create', detailPayload);
    }
};

const createAllocationDetailPayload = (paymentId, allocation) => {
    return {
        orderPaymentId: paymentId,
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

.row > [class*='col-'] {
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

.btn-small .btn.rounded-circle{
    height: 20px!important;
    width: 20px!important;
}
</style>