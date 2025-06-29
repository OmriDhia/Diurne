<template>
    <div class="create-fournisseur-invoice">
        <d-base-page :loading="loading">
            <template #title>
                <d-page-title title="Nouvelle Facture" />
            </template>

            <template #body>
                <d-panel>
                    <template #panel-body>
                        <!-- <d-panel-title title="Caractéristiques facture" class-name="ps-2" /> -->
                        <div class="row">
                            <div class="col-md-4">
                                <d-input label="Numéro facture" v-model="form.invoiceNumber" />
                                <d-input label="Packing list" v-model="form.packingList" class="pt-2" />
                                <d-currency v-model="form.currencyId" class="pt-2" />
                            </div>
                            <div class="col-md-4">
                                <div class="row align-items-center">
                                    <label for="invoice-date" class="col-4">Date facture</label>
                                    <div class="col-8">
                                        <input id="invoice-date" class="form-control custom-date" type="date" v-model="form.invoiceDate" />
                                    </div>
                                </div>
                                <d-input label="Air way bill" v-model="form.airWay" class="pt-2" />
                            </div>

                            <div class="col-md-4">
                                <d-input label="Fournisseur" v-model="form.supplier" />
                                <d-input label="Fret total" v-model="form.fretTotal" class="pt-2" />
                                <div class="form-check text-end pt-2">
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
                                            <th class="text-white">RN</th>
                                            <th class="text-white">N° tapis</th>
                                            <th class="text-white">Prix m²</th>
                                            <th class="text-white">Surface facture</th>
                                            <th class="text-white">Prix de la facture</th>
                                            <th class="text-white">Prix théorique</th>
                                            <th class="text-white">Pénalité</th>
                                            <th class="text-white">Surface produite</th>
                                            <th class="text-white">Montant réel avoir</th>
                                            <th class="text-white">Avoir théorique</th>
                                            <th class="text-white">Montant réel avoir</th>
                                            <th class="text-white">Montant final tapis</th>
                                            <th class="text-white">Poids</th>
                                            <th class="text-white">% poids</th>
                                            <th class="text-white">Fret</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(line, index) in lines" :key="index">
                                            <td><input type="text" class="form-control form-control-sm" v-model="line.rn" /></td>
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
                                    <d-input label="Autre montant" v-model="form.amountOther" />
                                    <d-input label="Poids" v-model="form.weight" />
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <label for="" class="col-4">Description:</label>
                                        <div class="col-8">
                                            <textarea v-model="form.description" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <d-panel-title title="Avoir sur cette facture" class-name="ps-2 mt-0" />
                                    <div class="row">
                                        <div class="col d-block__item">
                                            <d-input label="Montant théorique" v-model="form.amountTheoretical" />
                                        </div>
                                        <div class="col d-block__item">
                                            <d-input label="Montant réel" v-model="form.amountReal" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col d-block__item">
                                            <d-input label="Numéro de l'avoir" v-model="form.creditNumber" />
                                        </div>

                                        <div class="col pt-2">
                                            <label for="date-avoir">Date de l'avoir</label>
                                            <input id="date-avoir" class="form-control custom-date" type="date" v-model="form.creditDate" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col d-block__item">
                                            <d-input label="Total facture" v-model="form.invoiceTotal" />
                                        </div>
                                        <div class="col d-block__item">
                                            <d-input label="Total théorique" v-model="form.theoreticalTotal" />
                                        </div>
                                        <div class="col d-block__item">
                                            <d-input label="Total surface" v-model="form.surfaceTotal" />
                                        </div>
                                        <div class="col d-block__item">
                                            <d-input label="Total Poids" v-model="form.weightTotal" />
                                        </div>
                                    </div>

                                    <d-panel-title title="Paiement" class-name="ps-2" />
                                    <div class="row">
                                        <div class="col d-block__item">
                                            <d-input label="Montant théorique" v-model="form.paymentTheoretical" />
                                        </div>
                                        <div class="col d-block__item">
                                            <d-input label="Montant réel" v-model="form.paymentReal" />
                                        </div>

                                        <div class="col pt-2">
                                            <label for="date-avoir">Date de paiement</label>
                                            <input id="date-avoir" class="form-control custom-date" type="date" v-model="form.paymentDate" />
                                        </div>
                                    </div>
                                    <div class="valeur-commande">
                                        <d-input label="Valeur de la commande" v-model="form.valeurCommande" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <d-panel-title title="Suivi avoir fournisseur" class-name="ps-2" />
                                    <div class="row">
                                        <div class="col d-block__item">
                                            <d-input label="Antérieur" v-model="form.suiviAnterieur" />
                                        </div>
                                        <div class="col d-block__item">
                                            <d-input label="Restant" v-model="form.suiviRestant" />
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
                </div>
            </template>
        </d-base-page>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import dBasePage from '../../../components/base/d-base-page.vue';
    import dPanel from '../../../components/common/d-panel.vue';
    import dPanelTitle from '../../../components/common/d-panel-title.vue';
    import dPageTitle from '../../../components/common/d-page-title.vue';
    import dInput from '../../../components/base/d-input.vue';
    import supplierInvoiceService from '../../../Services/supplier-invoice-service';
    import { useMeta } from '/src/composables/use-meta';

    import dCurrency from '../../../components/common/d-currency.vue';
    useMeta({ title: 'Nouvelle Facture Fournisseur' });
    const route = useRoute();
    const router = useRouter();
    const loading = ref(false);
    const form = ref({
        invoiceNumber: '', //?
        invoiceDate: '',
        supplier: '',
        packingList: '',
        airWay: '',
        fretTotal: '',
        currencyId: null,
        freightIncluded: false, //0:1
        amountOther: '',
        weight: '',
        description: '',
        amountTheoretical: '',
        amountReal: '',
        creditNumber: '',
        creditDate: '',
        invoiceTotal: '',
        theoreticalTotal: '',
        surfaceTotal: '',
        weightTotal: '',
        paymentTheoretical: '',
        paymentReal: '',
        paymentDate: '',
        valeurCommande: '', //?
        suiviAnterieur: '', //?
        suiviRestant: '', //?
    });

    const lines = ref([
        // Initial empty line
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
            weight: null,
            pourcentweight: null,
            fret: null,
        },
    ]);

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
