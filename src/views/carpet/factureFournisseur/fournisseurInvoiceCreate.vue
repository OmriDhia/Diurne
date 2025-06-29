<template>
    <div class="create-fournisseur-invoice">
        <d-base-page :loading="loading">
            <template #title>
                <d-page-title title="Nouvelle Facture Fournisseur" />
            </template>

            <template #body>
                <d-panel>
                    <template #panel-body>
                        <div class="row">
                            <div class="col-md-4">
                                <d-input label="Numéro facture" v-model="form.invoiceNumber" />
                                <div class="row align-items-center pt-2">
                                    <label for="invoice-date" class="col-4">Date facture</label>
                                    <div class="col-8">
                                        <input id="invoice-date" class="form-control custom-date" type="date" v-model="form.invoiceDate" />
                                    </div>
                                </div>
                                <d-input label="Fournisseur" v-model="form.supplier" class="pt-2" />
                                <d-input label="Packing list" v-model="form.packingList" class="pt-2" />
                                <d-input label="Air Way" v-model="form.airWay" class="pt-2" />
                                <d-input label="Fret total" v-model="form.fretTotal" class="pt-2" />
                                <d-input label="Devise ID" type="number" v-model="form.currencyId" class="pt-2" />
                                <d-input label="Auteur ID" type="number" v-model="form.authorId" class="pt-2" />
                            </div>
                            <div class="col-md-4">
                                <d-input label="Autre montant" v-model="form.amountOther" />
                                <d-input label="Poids" v-model="form.weight" class="pt-2" />
                                <div class="row pt-2">
                                    <label for="description" class="col-4">Description</label>
                                    <div class="col-8">
                                        <textarea id="description" class="form-control" v-model="form.description"></textarea>
                                    </div>
                                </div>
                                <d-input label="Poids total" v-model="form.weightTotal" class="pt-2" />
                                <d-input label="Surface totale" v-model="form.surfaceTotal" class="pt-2" />
                                <d-input label="Total facture" v-model="form.invoiceTotal" class="pt-2" />
                                <d-input label="Total théorique" v-model="form.theoreticalTotal" class="pt-2" />
                            </div>
                            <div class="col-md-4">
                                <d-input label="Montant théorique" v-model="form.amountTheoretical" />
                                <d-input label="Montant réel" v-model="form.amountReal" class="pt-2" />
                                <d-input label="Numéro avoir" v-model="form.creditNumber" class="pt-2" />
                                <div class="row align-items-center pt-2">
                                    <label for="credit-date" class="col-4">Date avoir</label>
                                    <div class="col-8">
                                        <input id="credit-date" class="form-control custom-date" type="date" v-model="form.creditDate" />
                                    </div>
                                </div>
                                <d-input label="Paiement réel" v-model="form.paymentReal" class="pt-2" />
                                <d-input label="Paiement théorique" v-model="form.paymentTheoretical" class="pt-2" />
                                <div class="row align-items-center pt-2">
                                    <label for="payment-date" class="col-4">Date paiement</label>
                                    <div class="col-8">
                                        <input id="payment-date" class="form-control custom-date" type="date" v-model="form.paymentDate" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 justify-content-end">
                            <div class="col-auto">
                                <button class="btn btn-custom me-2" @click="save">Valider</button>
                                <button class="btn btn-outline-custom" @click="cancel">Annuler</button>
                            </div>
                        </div>
                    </template>
                </d-panel>
            </template>
        </d-base-page>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import dBasePage from '../../../components/base/d-base-page.vue';
import dPanel from '../../../components/common/d-panel.vue';
import dPageTitle from '../../../components/common/d-page-title.vue';
import dInput from '../../../components/base/d-input.vue';
import supplierInvoiceService from '../../../Services/supplier-invoice-service';
import { useMeta } from '/src/composables/use-meta';

useMeta({ title: 'Nouvelle Facture Fournisseur' });

const route = useRoute();
const router = useRouter();
const loading = ref(false);

const form = ref({
    invoiceNumber: '',
    invoiceDate: '',
    supplier: '',
    packingList: '',
    airWay: '',
    fretTotal: '',
    currencyId: null,
    authorId: null,
    amountOther: '',
    weight: '',
    description: '',
    weightTotal: '',
    surfaceTotal: '',
    invoiceTotal: '',
    theoreticalTotal: '',
    amountTheoretical: '',
    amountReal: '',
    creditNumber: 0,
    creditDate: '',
    paymentReal: '',
    paymentTheoretical: '',
    paymentDate: ''
});

const loadInvoice = async (id) => {
    try {
        loading.value = true;
        const data = await supplierInvoiceService.getById(id);
        form.value = { ...form.value, ...data };
    } catch (e) {
        window.showMessage(e.message, 'error');
    } finally {
        loading.value = false;
    }
};

const save = async () => {
    try {
        loading.value = true;
        if (route.params.id) {
            await supplierInvoiceService.update(route.params.id, form.value);
            window.showMessage('Mise à jour avec succès.');
        } else {
            await supplierInvoiceService.create(form.value);
            window.showMessage('Ajout avec succès.');
            router.push({ name: 'fournisseur-invoice-list' });
        }
    } catch (e) {
        window.showMessage(e.message, 'error');
    } finally {
        loading.value = false;
    }
};

const cancel = () => {
    router.back();
};

onMounted(() => {
    if (route.params.id) {
        loadInvoice(route.params.id);
    }
});
</script>

<style scoped>
.custom-date {
    width: 100%;
}
</style>

