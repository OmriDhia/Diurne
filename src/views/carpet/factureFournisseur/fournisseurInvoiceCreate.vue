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
                                <d-input label="Fret total" v-model="form.freightTotal" class="pt-2" />

                                <div class="form-check text-end pt-2">
                                    <input class="form-check-input" type="checkbox" id="freight-included" v-model="form.freightIncluded" />
                                    <!-- 0 ou 1 response + cond Fret total inclue sur le clacule ou non if 1 inclue sinon 0 non -->
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
                                            <!--response.workshopOrder.workshopInformation.orderedSurface-->
                                            <th class="text-white">Prix de la facture</th>
                                            <th class="text-white">Prix théorique</th>
                                            <th class="text-white">Pénalité</th>
                                            <!--response.workshopOrder.workshopInformation.penalty-->
                                            <th class="text-white">Surface produite</th>
                                            <!--response.workshopOrder.workshopInformation.realSurface-->
                                            <th class="text-white">Montant réel avoir</th>
                                            <th class="text-white">Avoir théorique</th>
                                            <th class="text-white">Montant final tapis</th>
                                            <th class="text-white">Poids</th>
                                            <th class="text-white">% poids</th>
                                            <th class="text-white">Fret</th>
                                            <th class="text-white actions-invoices"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(line, index) in lines" :key="index">
                                            <td class="rn-custom-td">
                                                <d-rn-number-dropdown v-model="line.rn" @dataOfRn="(data) => ResultasRnData(data, index)" :showActionRn="true"></d-rn-number-dropdown>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" v-model="line.carpetNumber" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.pricePerSquareMeter" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.invoiceSurface" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.invoiceAmount" /></td>
                                            <td><input type="number" disabled class="form-control form-control-sm" v-model="line.theoreticalPrice" /></td>
                                            <td><input type="number" disabled class="form-control form-control-sm" v-model="line.penalty" /></td>
                                            <td><input type="number" disabled class="form-control form-control-sm" v-model="line.producedSurface" /></td>
                                            <td><input type="number" class="form-control form-control-sm" v-model="line.actualCreditAmount" /></td>
                                            <td><input type="number" disabled class="form-control form-control-sm" v-model="line.theoreticalCredit" /></td>
                                            <td><input type="number" disabled class="form-control form-control-sm" v-model="line.finalCarpetAmount" /></td>
                                            <td><input type="number" disabled class="form-control form-control-sm" v-model="line.weight" /></td>
                                            <td><input type="number" disabled class="form-control form-control-sm" v-model="line.weightPercentage" /></td>
                                            <td><input type="number" disabled class="form-control form-control-sm" v-model="line.fret" /></td>
                                            <td class="text-center td-actions">
                                                <button class="btn btn-add btn-sm me-1" @click="saveLine(index)">
                                                    <vue-feather type="save" size="16" />
                                                </button>
                                                <button class="btn btn-add btn-sm me-1" @click="addLine">
                                                    <vue-feather type="plus" size="16" />
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
                                            <d-input label="Montant théorique" disabled v-model="form.amountTheoretical" />
                                        </div>
                                        <div class="col d-block__item">
                                            <d-input label="Montant réel" disabled v-model="form.amountReal" />
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
                                            <d-input label="Total facture" disabled v-model="form.invoiceTotal" />
                                        </div>
                                        <div class="col d-block__item">
                                            <d-input label="Total théorique" disabled v-model="form.theoreticalTotal" />
                                        </div>
                                        <div class="col d-block__item">
                                            <d-input label="Total surface" disabled v-model="form.surfaceTotal" />
                                        </div>
                                        <div class="col d-block__item">
                                            <d-input label="Total Poids" disabled v-model="form.weightTotal" />
                                        </div>
                                    </div>

                                    <d-panel-title title="Paiement" class-name="ps-2" />
                                    <div class="row">
                                        <div class="col d-block__item">
                                            <d-input label="Montant théorique" disabled v-model="form.paymentTheoretical" />
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
    import { ref, onMounted, watch } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import { Helper } from '../../../composables/global-methods';
    import quoteService from '../../../Services/quote-service';
    import dBasePage from '../../../components/base/d-base-page.vue';
    import dPanel from '../../../components/common/d-panel.vue';
    import dPanelTitle from '../../../components/common/d-panel-title.vue';
    import dPageTitle from '../../../components/common/d-page-title.vue';
    import dInput from '../../../components/base/d-input.vue';
    import { useMeta } from '/src/composables/use-meta';
    import VueFeather from 'vue-feather';
    import dCurrency from '../../../components/common/d-currency.vue';
    import moment from 'moment';
    import dRnNumberDropdown from '../../../components/common/d-rn-number-dropdown.vue';
    import supplierInvoiceService from '../../../Services/supplier-invoice-service';
    import supplierInvoiceDetailsService from '../../../Services/supplier-invoice-details-service';
    import axiosInstance from '../../../config/http';
    useMeta({ title: 'Nouvelle Facture Fournisseur' });
    const route = useRoute();
    const router = useRouter();
    const quote_id = route.query.quote_id || null;
    const carpetOrderDetailsId = ref(null);
    const rnId = ref(null); // This will hold the ID of the RN (if
    const quote = ref({});
    const loading = ref(false);
    const form = ref({
        invoiceNumber: '', //?
        invoiceDate: '',
        supplier: '',
        packingList: '',
        airWay: '',
        freightTotal: '',
        currencyId: null,
        freightIncluded: false, //0:1
        amountOther: '',
        weight: '', //response.workshopOrder.workshopInformation.quality.weight (surface real)
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

    const addLine = () => {
        lines.value.push({
            rn: null,
            carpetNumber: '',
            pricePerSquareMeter: null,
            invoiceSurface: null,
            invoiceAmount: null,
            theoreticalPrice: null,
            penalty: null,
            producedSurface: null,
            actualCreditAmount: null,
            theoreticalCredit: null,
            finalCarpetAmount: null,
            weight: null,
            weightPercentage: null, //,% poids : % de poids parmi tous les tapis de la facture
            fret: null, //fret total x % de poids
        });
    };

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
            theoreticalCredit: null,
            montantReelAvoir2: null,
            montantFinalTapis: null,
            weight: null,
            pourcentweight: null,
            fret: null,
        },
    ]);
    const ResultasRnData = (data, index) => {
        console.log('ResultasRnData', data, index);
        if (data && data.data && data.data.response) {
            const rnData = data.data.response;

            rnId.value = rnData.id; // Assuming rnData has an ID
            carpetOrderDetailsId.value = rnData.imageCommand.carpetSpecification.id;
            lines.value[index] = {
                rn: rnData.rnNumber,
                id: lines.value[index].id || null, // Add ID for updates/deletes
                carpetNumber: lines.value[index].carpet_number || null,
                pricePerSquareMeter: lines.value[index].price_per_square_meter || null,
                invoiceAmount: lines.value[index].invoice_amount || null,
                theoreticalPrice: lines.value[index].theoretical_price || null,
                producedSurface: rnData.workshopOrder.workshopInformation.realSurface,
                theoreticalCredit: lines.value[index].theoretical_credit || null,
                finalCarpetAmount: lines.value[index].final_carpet_amount || null,
                invoiceSurface: rnData.workshopOrder.workshopInformation.orderedSurface,
                penalty: rnData.workshopOrder.workshopInformation.penalty,
                actualCreditAmount: lines.value[index].actualCreditAmount || null,
                weight: rnData.workshopOrder.workshopInformation.quality.weight,
            };
            calculate();
        }
    };
    const loadInvoice = async (id) => {
        try {
            loading.value = true;
            const data = await supplierInvoiceService.getById(id);
            form.value.invoiceDate = moment(form.value.invoiceDate).format('YYYY-MM-DD');
            const dataRn = await axiosInstance.get('api/carpets');

            const resDataRn = dataRn.data.response;
            console.log();
            form.value = {
                invoiceNumber: data.invoice_number || '',
                invoiceDate: data.invoice_date ? moment(data.invoice_date).format('YYYY-MM-DD') : '',
                supplier: data.supplier || '',
                packingList: data.packing_list || '',
                airWay: data.air_way || '',

                freightTotal: data.freightTotal || '',
                currencyId: data.currency_id || null,
                freightIncluded: data.freightIncluded == false ? 0 : 1, // Convert to boolean
                amountOther: data.amount_other || '',
                weight: data.weight || '',
                description: data.description || '',
                amountTheoretical: data.amount_theoretical || '',
                amountReal: data.amount_real || '',
                creditNumber: data.credit_number || '',
                creditDate: data.credit_date ? moment(data.credit_date).format('YYYY-MM-DD') : '',
                invoiceTotal: data.invoice_total || '',
                theoreticalTotal: data.theoretical_total || '',
                surfaceTotal: data.surface_total || '',
                weightTotal: data.weight_total || '',
                paymentTheoretical: data.payment_theoretical || '',
                paymentReal: data.payment_real || '',
                paymentDate: data.payment_date ? moment(data.payment_date).format('YYYY-MM-DD') : '',
                valeurCommande: data.valeurCommande || '',
                suiviAnterieur: data.suiviAnterieur || '',
                suiviRestant: data.suiviRestant || '',
            };

            if (data.supplierInvoiceDetails.length > 0) {
                lines.value = data.supplierInvoiceDetails.map((detail) => ({
                    id: detail.id, // Add ID for updates/deletes
                    rn: detail.rn,
                    carpetNumber: detail.carpet_number,
                    pricePerSquareMeter: detail.price_per_square_meter,
                    invoiceSurface: detail.invoice_surface,
                    invoiceAmount: detail.invoice_amount,
                    theoreticalPrice: detail.theoretical_price,
                    penalty: detail.penalty,
                    producedSurface: detail.produced_surface,
                    actualCreditAmount: detail.actual_credit_amount,
                    theoreticalCredit: detail.theoretical_credit,
                    finalCarpetAmount: detail.final_carpet_amount,
                    weight: detail.weight,
                    weightPercentage: detail.weight_percentage,
                    fret: detail.freight,
                }));
            } else {
                lines.value = [
                    {
                        rn: null,
                        carpetNumber: '',
                        pricePerSquareMeter: null,
                        invoiceSurface: null,
                        invoiceAmount: null,
                        theoreticalPrice: null,
                        penalty: null,
                        producedSurface: null,
                        actualCreditAmount: null,
                        theoreticalCredit: null,
                        finalCarpetAmount: null,
                        weight: null,
                        weightPercentage: null,
                        fret: null,
                    },
                ];
            }
        } catch (e) {
            window.showMessage(e.message, 'error');
        } finally {
            loading.value = false;
        }
    };

    const getQuote = async (id) => {
        try {
            if (id) {
                loading.value = true;
                quote.value = await quoteService.getQuoteById(id);
            }
        } catch (e) {
            console.log(e);
            const msg = 'Echec de r\u00e9cup\u00e9ration des donn\u00e9es devis';
            window.showMessage(msg, 'error');
        } finally {
            loading.value = false;
        }
    };

    const save = async () => {
        try {
            loading.value = true;
            const payload = {
                id: parseInt(route.params.id) || null,
                invoiceNumber: String(form.value.invoiceNumber) || null,
                packingList: String(form.value.packingList) || null,
                airWay: String(form.value.airWay) || null,
                invoiceDate: moment(form.value.invoiceDate).toISOString(),
                fretTotal: String(form.value.freightTotal) || null,
                supplier: String(form.value.supplier) || null,
                authorId: 0,
                currencyId: parseInt(form.value.currencyId) || null,
                freightIncluded: form.value.freightIncluded ? 1 : 0,
                amountOther: String(form.value.amountOther) || null,
                weight: form.value.weight,
                description: form.value.description,
                amountTheoretical: form.value.amountTheoretical,
                amountReal: form.value.amountReal,
                creditNumber: parseInt(form.value.creditNumber) || null,
                invoiceTotal: form.value.invoiceTotal,
                theoreticalTotal: form.value.theoreticalTotal,
                surfaceTotal: form.value.surfaceTotal,
                weightTotal: form.value.weightTotal,
                paymentTheoretical: String(form.value.paymentTheoretical) || null,
                paymentReal: form.value.paymentReal,
                suiviAnterieur: form.value.suiviAnterieur,
                suiviRestant: form.value.suiviRestant,
                valeurCommande: form.value.valeurCommande,
                montantReelAvoir: form.value.montantReelAvoir,
                theoreticalCredit: form.value.theoreticalCredit,
                invoice_date: moment(form.value.invoiceDate).toISOString(),
                creditDate: form.value.creditDate ? moment(form.value.creditDate).format('YYYY-MM-DD') : null,
                paymentDate: form.value.paymentDate ? moment(form.value.paymentDate).format('YYYY-MM-DD') : null,
            };
            if (route.params.id) {
                await supplierInvoiceService.update(route.params.id, payload);
                window.showMessage('Mise à jour avec succès.');
            } else {
                await supplierInvoiceService.create(payload);
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
        if (quote_id) {
            getQuote(quote_id);
        }
    });
    const saveLine = async (index) => {
        const line = lines.value[index];
        try {
            const payload = {
                supplierInvoiceId: parseInt(route.params.id) || null,
                rnId: rnId.value || null,
                rn: line.rn,
                carpetNumber: line.carpetNumber,
                pricePerSquareMeter: String(line.pricePerSquareMeter) || '0',
                invoiceSurface: String(line.invoiceSurface) || '0',
                invoiceAmount: String(line.invoiceAmount) || '0',
                theoreticalPrice: String(line.theoreticalPrice) || '0',
                penalty: String(line.penalty) || '0',
                producedSurface: String(line.producedSurface) || '0',
                actualCreditAmount: String(line.actualCreditAmount) || '0',
                theoreticalCredit: String(line.theoreticalCredit) || '0',
                finalCarpetAmount: String(line.finalCarpetAmount) || '0',
                weight: String(line.weight) || null,
                weightPercentage: String(line.weightPercentage) || '0',
                freight: String(line.fret) || '0',
            };
            if (line.id) {
                await supplierInvoiceDetailsService.update(line.id, payload);
            } else {
                await supplierInvoiceDetailsService.create(payload);
            }

            window.showMessage('Ligne mise à jour avec succès');
        } catch (e) {
            window.showMessage(e.message, 'error');
        }
    };

    const removeLine = async (index) => {
        const line = lines.value[index];
        if (line.id) {
            try {
                await supplierInvoiceDetailsService.delete(line.id);
                window.showMessage('Ligne supprimée avec succès');
            } catch (e) {
                window.showMessage(e.message, 'error');
                return;
            }
        }
        lines.value.splice(index, 1);
    };

    const calculate = () => {
        let totalFacture = 0;
        let totalTheorique = 0;
        let totalSurface = 0;
        let totalWeight = 0;

        lines.value.forEach((l) => {
            // Use updated property names
            const srfCmd = parseFloat(l.invoiceSurface) || 0;
            const srfReal = parseFloat(l.producedSurface) || 0;
            const priceM2Cmd = parseFloat(l.pricePerSquareMeter) || 0;
            const priceCmd = parseFloat(l.invoiceAmount) || 0; // Use prixFacture
            let realAvoirAmount = 0;
            realAvoirAmount += parseFloat(l.actualCreditAmount) || 0;
            // Correct the syntax error in the if condition

            if (srfReal < srfCmd) {
                l.theoreticalPrice = srfReal * priceM2Cmd; // Use prixTheorique
            } else {
                l.theoreticalPrice = priceCmd; // Use prixTheorique
            }

            // Use updated property names
            l.theoreticalCredit = (parseFloat(l.theoreticalPrice) || 0) - (parseFloat(l.penalty) || 0); // Use theoreticalCredit and penalite

            totalFacture += priceCmd;
            // Use updated property names - assuming amountTheoretical in form is different from theoreticalCredit in line
            // If totalTheorique should sum theoreticalCredit from lines, change l.amountTheoretical to l.theoreticalCredit
            totalTheorique += parseFloat(l.theoreticalCredit) || 0; // Assuming totalTheorique sums theoreticalCredit from lines
            totalSurface += srfCmd;
            totalWeight += parseFloat(l.weight) || 0; // Use poids
        });
        if (form.value.creditDate && form.value.creditNumber) {
            form.value.amountTheoretical += realAvoirAmount;
            form.value.amountReal = form.value.amountTheoretical;
        }

        lines.value.forEach((l) => {
            const poids = parseFloat(l.weight) || 0; // Use poids
            l.weightPercentage = totalWeight ? (poids / totalWeight) * 100 : 0; // Use pourcentPoids
            l.fret = form.value.freightIncluded == true ? (parseFloat(form.value.freightTotal) || 0) * (l.weightPercentage / 100) : l.weightPercentage / 100; // Use pourcentPoids
        });

        form.value.invoiceTotal = totalFacture;
        // Assuming theoretical_total in form sums theoreticalCredit from lines + amount_other from form
        form.value.theoreticalTotal = totalTheorique + (parseFloat(form.value.amountOther) || 0);
        form.value.surfaceTotal = totalSurface;
        form.value.weightTotal = totalWeight;

        // Use updated property names from form
        form.value.paymentTheoretical = (parseFloat(form.value.invoiceTotal) || 0) - (parseFloat(form.value.amountReal) || 0) - (parseFloat(form.value.suiviAnterieur) || 0);
        form.value.suiviRestant = (parseFloat(form.value.paymentTheoretical) || 0) - (parseFloat(form.value.paymentReal) || 0);
    };

    watch(
        lines,
        () => {
            calculate();
        },
        { deep: true }
    );

    watch(
        () => [
            form.value.producedSurface,
            form.value.invoiceSurface,
            form.value.pricePerSquareMeter,
            form.value.amountOther,
            form.value.freightTotal,
            form.value.amountReal,
            form.value.suiviAnterieur,
            form.value.paymentReal,
            form.value.theoreticalPrice,
        ],
        () => {
            calculate();
        }
    );
</script>

<style scoped>
    .custom-date {
        width: 100%;
    }
</style>
