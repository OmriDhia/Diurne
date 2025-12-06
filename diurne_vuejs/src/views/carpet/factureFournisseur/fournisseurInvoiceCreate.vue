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
                                <d-input label="Numéro facture" v-model="form.invoiceNumber"
                                         :error="errors.invoiceNumber" :required="route.params.id" />
                                <d-input label="Packing list" v-model="form.packingList" class="pt-2"
                                         :error="errors.packingList" :required="true" />
                                <d-currency v-model="form.currencyId" class="pt-2" :error="errors.currencyId"
                                            :required="true" />
                            </div>
                            <div class="col-md-4">
                                <div class="row align-items-center">
                                    <label for="invoice-date" class="col-4">Date facture <span class="required">*</span></label>
                                    <div class="col-8">
                                        <input id="invoice-date" class="form-control custom-date" type="date"
                                               v-model="form.invoiceDate" />
                                        <div v-if="errors.invoiceDate" class="invalid-feedback d-block">
                                            {{ errors.invoiceDate }}
                                        </div>
                                    </div>
                                </div>
                                <d-input label="Air way bill" v-model="form.airWay" class="pt-2" :error="errors.airWay"
                                         :required="true" />
                            </div>

                            <div class="col-md-4">
                                <d-fabricant-dropdown v-model="form.supplier" :error="errors.supplier"
                                                      :required="true" />
                                <d-input label="Fret total" v-model="form.freightTotal" class="pt-2"
                                         :error="errors.freightTotal" :required="true" :type="'text'"
                                         @changeValue="onFreightChange" />

                                <div class="form-check text-end pt-2">
                                    <input class="form-check-input" type="checkbox" id="freight-included"
                                           v-model="form.freightIncluded" />
                                    <!-- 0 ou 1 response + cond Fret total inclue sur le clacule ou non if 1 inclue sinon 0 non -->
                                    <label class="form-check-label" for="freight-included">compris dans la
                                        facture</label>
                                </div>
                            </div>
                        </div>
                    </template>
                </d-panel>

                <div class="mt-3">
                    <d-panel>
                        <template #panel-body>
                            <div class="table-responsive" style="overflow: visible">
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
                                            <d-rn-number-dropdown v-model="line.rn"
                                                                  @dataOfRn="(data) => ResultasRnData(data, index)"
                                                                  :showActionRn="true"></d-rn-number-dropdown>
                                        </td>
                                        <td><input type="text" class="form-control form-control-sm"
                                                   v-model="line.carpetNumber" /></td>
                                        <td><input type="number" class="form-control form-control-sm"
                                                   v-model="line.pricePerSquareMeter" /></td>
                                        <td><input type="number" class="form-control form-control-sm"
                                                   v-model="line.invoiceSurface" /></td>
                                        <td><input type="number" class="form-control form-control-sm"
                                                   v-model="line.invoiceAmount" /></td>
                                        <td><input type="number" disabled class="form-control form-control-sm"
                                                   v-model="line.theoreticalPrice" /></td>
                                        <td><input type="number" disabled class="form-control form-control-sm"
                                                   v-model="line.penalty" /></td>
                                        <td><input type="number" disabled class="form-control form-control-sm"
                                                   v-model="line.producedSurface" /></td>
                                        <td><input type="number" class="form-control form-control-sm"
                                                   v-model="line.actualCreditAmount" /></td>
                                        <td><input type="number" disabled class="form-control form-control-sm"
                                                   v-model="line.theoreticalCredit" /></td>
                                        <td><input type="number" class="form-control form-control-sm"
                                                   v-model="line.finalCarpetAmount" /></td>
                                        <td><input type="number" disabled class="form-control form-control-sm"
                                                   v-model="line.weight" /></td>
                                        <td><input type="number" disabled class="form-control form-control-sm"
                                                   v-model="line.weightPercentage" /></td>
                                        <td><input type="number" disabled class="form-control form-control-sm"
                                                   v-model="line.fret" /></td>
                                        <td class="text-center td-actions">
                                            <button class="btn btn-add btn-sm me-1" @click="saveLine(index)"
                                                    v-if="route.params.id">
                                                <vue-feather type="save" size="16" />
                                            </button>
                                            <button class="btn btn-add btn-sm me-1" @click="addLine">
                                                <vue-feather type="plus" size="16" />
                                            </button>
                                            <button v-if="lines.length > 1" class="btn btn-add btn-sm"
                                                    @click="removeLine(index)">
                                                <vue-feather type="x" size="16" />
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <d-input label="Autre montant" v-model="form.amountOther"
                                             :error="errors.amountOther" :required="true" :type="'text'"
                                             @changeValue="onAmountOtherChange" />
                                    <d-input label="Poids" v-model="form.weight" :error="errors.weight"
                                             :required="true" :type="'text'" @changeValue="onWeightChange" />
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <label for="" class="col-4">Description:</label>
                                        <div class="col-8">
                                            <label class="d-none">&nbsp;</label>
                                            <textarea v-model="form.description" class="form-control"></textarea>
                                            <div v-if="errors.description" class="invalid-feedback d-block">
                                                {{ errors.description }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <d-panel-title title="Avoir sur cette facture" class-name="ps-2 mt-0" />
                                    <div class="row">
                                        <div class="col d-block__item">
                                            <d-input label="Montant théorique" disabled
                                                     v-model="form.amountTheoretical" />
                                        </div>
                                        <div class="col d-block__item">
                                            <d-input label="Montant réel" disabled v-model="form.amountReal"
                                                     :type="'text'" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col d-block__item">
                                            <d-input label="Numéro de l'avoir" v-model="form.creditNumber"
                                                     @input="verif" />
                                        </div>

                                        <div class="col pt-2">
                                            <label for="date-avoir">Date de l'avoir</label>
                                            <input id="date-avoir" class="form-control custom-date" type="date"
                                                   v-model="form.creditDate" @input="verif" />
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
                                            <d-input label="Montant théorique" disabled
                                                     v-model="form.paymentTheoretical" />
                                        </div>
                                        <div class="col d-block__item">
                                            <d-input label="Montant réel" v-model="form.paymentReal" :type="'text'"
                                                     @changeValue="onPaymentRealChange" />
                                        </div>

                                        <div class="col pt-2">
                                            <label for="date-avoir">Date de paiement</label>
                                            <input id="date-avoir" class="form-control custom-date" type="date"
                                                   v-model="form.paymentDate" />
                                        </div>
                                    </div>
                                    <!-- <div class="valeur-commande">
                                        <d-input label="Valeur de la commande" v-model="form.valeurCommande" />
                                    </div> -->
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
    import dFabricantDropdown from '../../../components/common/d-fabricant-dropdown.vue';
    import userService from '../../../Services/user-service';
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
        // show formatted defaults
        freightTotal: Helper.FormatNumber(0),
        currencyId: null,
        freightIncluded: false, //0:1
        amountOther: Helper.FormatNumber(0),
        weight: Helper.FormatNumber(0), //response.workshopOrder.workshopInformation.quality.weight (surface real)
        description: '',
        amountTheoretical: Helper.FormatNumber(0),
        amountReal: Helper.FormatNumber(0),
        creditNumber: '',
        creditDate: '',
        invoiceTotal: '',
        theoreticalTotal: '',
        surfaceTotal: '',
        weightTotal: '',
        paymentTheoretical: '',
        paymentReal: Helper.FormatNumber(0),
        paymentDate: '',
        valeurCommande: '', //?
        suiviAnterieur: '', //?
        suiviRestant: '' //?
    });
    const errors = ref({});

    const formatLine = (ln) => {
        // Ensure numeric fields display with 2 decimals as strings
        ln.pricePerSquareMeter = Helper.FormatNumber(ln.pricePerSquareMeter ?? 0);
        ln.invoiceSurface = Helper.FormatNumber(ln.invoiceSurface ?? 0);
        ln.invoiceAmount = Helper.FormatNumber(ln.invoiceAmount ?? 0);
        ln.theoreticalPrice = Helper.FormatNumber(ln.theoreticalPrice ?? 0);
        ln.penalty = Helper.FormatNumber(ln.penalty ?? 0);
        ln.producedSurface = Helper.FormatNumber(ln.producedSurface ?? 0);
        ln.actualCreditAmount = Helper.FormatNumber(ln.actualCreditAmount ?? 0);
        ln.theoreticalCredit = Helper.FormatNumber(ln.theoreticalCredit ?? 0);
        ln.finalCarpetAmount = Helper.FormatNumber(ln.finalCarpetAmount ?? 0);
        ln.weight = ln.weight !== null && ln.weight !== undefined ? Helper.FormatNumber(ln.weight) : null;
        ln.weightPercentage = Helper.FormatNumber(ln.weightPercentage ?? 0);
        ln.fret = Helper.FormatNumber(ln.fret ?? 0);
        return ln;
    };

    const addLine = () => {
        const ln = {
            rn: null,
            carpetNumber: '',
            pricePerSquareMeter: Helper.FormatNumber(0),
            invoiceSurface: Helper.FormatNumber(0),
            invoiceAmount: Helper.FormatNumber(0),
            theoreticalPrice: Helper.FormatNumber(0),
            penalty: Helper.FormatNumber(0),
            producedSurface: Helper.FormatNumber(0),
            actualCreditAmount: Helper.FormatNumber(0),
            theoreticalCredit: Helper.FormatNumber(0),
            finalCarpetAmount: Helper.FormatNumber(0),
            weight: null,
            weightPercentage: Helper.FormatNumber(0), //,% poids : % de poids parmi tous les tapis de la facture
            fret: Helper.FormatNumber(0) //fret total x % de poids
        };
        lines.value.push(ln);
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
            theoreticalCredit: 0,
            montantReelAvoir2: null,
            montantFinalTapis: null,
            weight: null,
            pourcentweight: null,
            fret: null
        }
    ]);
    const ResultasRnData = (raw, index) => {
        // raw may be an axios response (res) or a parsed response object (res.data.response)
        try {
            console.log('ResultasRnData', raw, index);
            const res = raw?.data?.response ? raw.data.response : raw?.response ? raw.response : raw;
            if (!res) return;

            // Prefer id at top level
            rnId.value = res.id ?? res.rnId ?? rnId.value;

            // Some responses nest imageCommand or image_command
            const imageCommand = res.imageCommand ?? res.image_command ?? res.imageCommande ?? null;
            if (imageCommand) {
                carpetOrderDetailsId.value = imageCommand.carpetSpecification?.id ?? imageCommand.carpet_specification?.id ?? carpetOrderDetailsId.value;
            }

            // Workshop order info may be deeply nested
            const workshopInfo = res.workshopOrder?.workshopInformation ?? res.workshopOrder?.workshop_information ?? res.workshopInformation ?? res.workshop_info ?? null;

            const mapped = {
                // Parenthesize the nullish coalescing chain to avoid mixing ?? with ||
                rn: (res.rnNumber ?? res.rn ?? res.carpetRnNumber ?? res.reference ?? lines.value[index].rn) ?? null,
                id: lines.value[index].id || null,
                // try to extract a carpetNumber from several possible fields in the RN payload
                carpetNumber: (res.carpetNumber ?? res.carpet?.number ?? res.imageCommand?.commandNumber ?? res.commandNumber ?? res.carpet_order_number ?? res.carpet_order?.number ?? lines.value[index].carpetNumber) ?? null,
                pricePerSquareMeter: Helper.FormatNumber(workshopInfo?.carpetPurchasePricePerM2 ?? workshopInfo?.carpet_purchase_price_per_m2 ?? lines.value[index].pricePerSquareMeter ?? 0),
                invoiceAmount: Helper.FormatNumber(lines.value[index].invoiceAmount ?? 0),
                theoreticalPrice: Helper.FormatNumber(lines.value[index].theoreticalPrice ?? 0),
                producedSurface: Helper.FormatNumber(workshopInfo?.realSurface ?? workshopInfo?.real_surface ?? lines.value[index].producedSurface ?? 0),
                theoreticalCredit: Helper.FormatNumber(lines.value[index].theoreticalCredit ?? 0),
                finalCarpetAmount: Helper.FormatNumber(lines.value[index].finalCarpetAmount ?? 0),
                invoiceSurface: Helper.FormatNumber(workshopInfo?.orderedSurface ?? workshopInfo?.ordered_surface ?? lines.value[index].invoiceSurface ?? 0),
                penalty: Helper.FormatNumber(workshopInfo?.penalty ?? lines.value[index].penalty ?? 0),
                actualCreditAmount: Helper.FormatNumber(lines.value[index].actualCreditAmount ?? 0),
                weight: workshopInfo?.quality?.weight !== undefined && workshopInfo?.quality?.weight !== null ? Helper.FormatNumber(workshopInfo.quality.weight) : lines.value[index].weight
            };

            // Assign mapped fields into the specific line object to preserve reactivity
            Object.keys(mapped).forEach((k) => {
                lines.value[index][k] = mapped[k];
            });
            // Format the updated line numeric fields
            formatLine(lines.value[index]);
        } catch (e) {
            console.error('Error handling RN data:', e);
        }
    };
    const loadInvoice = async (id) => {
        try {
            loading.value = true;
            const data = await supplierInvoiceService.getById(id);
            form.value.invoiceDate = moment(form.value.invoiceDate).format('YYYY-MM-DD');
            const dataRn = await axiosInstance.get('api/carpets');

            const resDataRn = dataRn.data.response;

            form.value = {
                invoiceNumber: data.invoice_number || '',
                invoiceDate: data.invoice_date ? moment(data.invoice_date).format('YYYY-MM-DD') : '',
                // if API returns nested manufacturer object, map its id to supplier (dropdown expects id)
                supplier: data.manufacturer?.id || data.supplier || '',
                packingList: data.packing_list || '',
                airWay: data.air_way || '',

                freightTotal: Helper.FormatNumber(data.fret_total ?? 0),
                currencyId: data.currency_id || null,
                freightIncluded: data.freightIncluded == false ? 0 : 1, // Convert to boolean
                amountOther: Helper.FormatNumber(data.amount_other ?? 0),
                weight: data.weight !== null && data.weight !== undefined ? Helper.FormatNumber(data.weight) : Helper.FormatNumber(0),
                description: data.description || '',
                amountTheoretical: Helper.FormatNumber(data.prices?.amount_theoretical ?? 0),
                amountReal: Helper.FormatNumber(data.prices.amount_real ?? 0),
                creditNumber: data.prices.credit_number || '',
                creditDate: data.prices.credit_date ? moment(data.prices.credit_date).format('YYYY-MM-DD') : '',
                invoiceTotal: data.invoice_total || '',
                theoreticalTotal: data.theoretical_total || '',
                surfaceTotal: data.surface_total || '',
                weightTotal: data.weight_total || '',
                paymentTheoretical: data.prices.payment_theoretical || '',
                paymentReal: Helper.FormatNumber(data.prices.payment_real ?? 0),
                paymentDate: data.prices.payment_date ? moment(data.prices.payment_date).format('YYYY-MM-DD') : '',
                valeurCommande: data.valeurCommande || '',
                suiviAnterieur: data.suiviAnterieur || '',
                suiviRestant: data.suiviRestant || ''
            };

            if (data.supplierInvoiceDetails.length > 0) {
                lines.value = data.supplierInvoiceDetails.map((detail) => {
                    const l = {
                        id: detail.id, // Add ID for updates/deletes
                        rn: detail.rn,
                        carpetNumber: detail.carpet_number,
                        pricePerSquareMeter: Helper.FormatNumber(detail.price_per_square_meter ?? 0),
                        invoiceSurface: Helper.FormatNumber(detail.invoice_surface ?? 0),
                        invoiceAmount: Helper.FormatNumber(detail.invoice_amount ?? 0),
                        theoreticalPrice: Helper.FormatNumber(detail.theoretical_price ?? 0),
                        penalty: Helper.FormatNumber(detail.penalty ?? 0),
                        producedSurface: Helper.FormatNumber(detail.produced_surface ?? 0),
                        actualCreditAmount: Helper.FormatNumber(detail.actual_credit_amount ?? 0),
                        theoreticalCredit: Helper.FormatNumber(detail.theoretical_credit ?? 0),
                        finalCarpetAmount: Helper.FormatNumber(detail.final_carpet_amount ?? 0),
                        weight: detail.weight ? Helper.FormatNumber(detail.weight) : null,
                        weightPercentage: Helper.FormatNumber(detail.weight_percentage ?? 0),
                        fret: Helper.FormatNumber(detail.freight ?? 0)
                    };
                    return l;
                });
            } else {
                lines.value = [
                    {
                        rn: null,
                        carpetNumber: '',
                        pricePerSquareMeter: Helper.FormatNumber(0),
                        invoiceSurface: Helper.FormatNumber(0),
                        invoiceAmount: Helper.FormatNumber(0),
                        theoreticalPrice: Helper.FormatNumber(0),
                        penalty: Helper.FormatNumber(0),
                        producedSurface: Helper.FormatNumber(0),
                        actualCreditAmount: Helper.FormatNumber(0),
                        theoreticalCredit: Helper.FormatNumber(0),
                        finalCarpetAmount: Helper.FormatNumber(0),
                        weight: null,
                        weightPercentage: Helper.FormatNumber(0),
                        fret: Helper.FormatNumber(0)
                    }
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

    const validateForm = () => {
        errors.value = {};
        // invoiceDate required
        if (!form.value.invoiceDate) {
            errors.value.invoiceDate = 'Date facture requise';
        } else if (!moment(form.value.invoiceDate).isValid()) {
            errors.value.invoiceDate = 'Date facture invalide';
        }
        // supplier (manufacturerId)
        if (!form.value.supplier) {
            errors.value.supplier = 'Fournisseur requis';
        }
        // packingList
        if (!form.value.packingList) {
            errors.value.packingList = 'Packing list requis';
        }
        // airWay
        if (!form.value.airWay) {
            errors.value.airWay = 'Air way requis';
        }
        // freightTotal
        if (!form.value.freightTotal && form.value.freightTotal !== 0) {
            errors.value.freightTotal = 'Fret total requis';
        } else if (isNaN(parseFloat(form.value.freightTotal))) {
            errors.value.freightTotal = 'Fret total invalide';
        }
        // currencyId
        if (!form.value.currencyId) {
            errors.value.currencyId = 'Devise requise';
        }
        // amountOther
        if (!form.value.amountOther && form.value.amountOther !== 0) {
            errors.value.amountOther = 'Autre montant requis';
        } else if (isNaN(parseFloat(form.value.amountOther))) {
            errors.value.amountOther = 'Autre montant invalide';
        }
        // weight
        if (!form.value.weight && form.value.weight !== 0) {
            errors.value.weight = 'Poids requis';
        } else if (isNaN(parseFloat(form.value.weight))) {
            errors.value.weight = 'Poids invalide';
        }
        // On update invoiceNumber required
        if (route.params.id && !form.value.invoiceNumber) {
            errors.value.invoiceNumber = 'Numéro facture requis';
        }

        return Object.keys(errors.value).length === 0;
    };

    const save = async () => {
        try {
            const userData = userService.getUserInfo();
            // Client-side validation
            if (!validateForm()) {
                window.showMessage('Veuillez corriger les champs obligatoires', 'error');
                return;
            }

            loading.value = true;
            // Normalize payload per DTO expectations
            const payload = {
                id: parseInt(route.params.id) || null,
                invoiceNumber: form.value.invoiceNumber ? String(form.value.invoiceNumber) : null,
                packingList: String(form.value.packingList || ''),
                airWay: String(form.value.airWay || ''),
                invoiceDate: moment(form.value.invoiceDate).format('YYYY-MM-DD HH:mm:ss'),
                fretTotal: Helper.FormatNumber(form.value.freightTotal || '0'),
                supplier: form.value.supplier ? String(form.value.supplier) : null,
                manufacturerId: form.value.supplier ? parseInt(form.value.supplier) : null,
                currencyId: parseInt(form.value.currencyId) || null,
                freightIncluded: form.value.freightIncluded ? 1 : 0,
                amountOther: Helper.FormatNumber(form.value.amountOther || '0'),
                weight: Helper.FormatNumber(form.value.weight || '0'),
                description: String(form.value.description || ''),
                amountTheoretical: Helper.FormatNumber(parseFloat(String(form.value.amountTheoretical).replace(/,/g, '')) || 0),
                amountReal: form.value.amountReal || null,
                creditNumber: form.value.creditNumber ? parseInt(form.value.creditNumber) : null,
                invoiceTotal: form.value.invoiceTotal ? Helper.FormatNumber(form.value.invoiceTotal) : null,
                theoreticalTotal: form.value.theoreticalTotal ? Helper.FormatNumber(form.value.theoreticalTotal) : null,
                surfaceTotal: form.value.surfaceTotal ? Helper.FormatNumber(form.value.surfaceTotal) : null,
                weightTotal: form.value.weightTotal ? Helper.FormatNumber(form.value.weightTotal) : null,
                paymentTheoretical: form.value.paymentTheoretical || null,
                paymentReal: Helper.FormatNumber(form.value.paymentReal || '0'),
                suiviAnterieur: form.value.suiviAnterieur || null,
                suiviRestant: form.value.suiviRestant || null,
                valeurCommande: form.value.valeurCommande || null,
                montantReelAvoir: form.value.montantReelAvoir || null,
                theoreticalCredit: form.value.theoreticalCredit || null,
                invoice_date: moment(form.value.invoiceDate).format('YYYY-MM-DD HH:mm:ss'),
                creditDate: form.value.creditDate ? moment(form.value.creditDate).format('YYYY-MM-DD HH:mm:ss') : null,
                paymentDate: form.value.paymentDate ? moment(form.value.paymentDate).format('YYYY-MM-DD HH:mm:ss') : null,
                authorId: parseInt(userData.id)
            };
            if (route.params.id) {
                await supplierInvoiceService.update(route.params.id, payload);
                window.showMessage('Mise à jour avec succès.');
            } else {
                const response = await supplierInvoiceService.create(payload);
                // Lines may have an optional carpetNumber. Send null when empty to avoid empty-string issues.
                for (const line of lines.value) {
                    if (!line.id) {
                        const lineAdd = {
                            supplierInvoiceId: parseInt(response.id),
                            rnId: rnId.value || null,
                            rn: line.rn || null,
                            carpetNumber: line.carpetNumber && String(line.carpetNumber).trim() !== '' ? String(line.carpetNumber) : null,
                            pricePerSquareMeter: Helper.FormatNumber(line.pricePerSquareMeter || '0'),
                            invoiceSurface: Helper.FormatNumber(line.invoiceSurface || '0'),
                            invoiceAmount: Helper.FormatNumber(line.invoiceAmount || '0'),
                            theoreticalPrice: Helper.FormatNumber(line.theoreticalPrice || '0'),
                            penalty: Helper.FormatNumber(line.penalty || '0'),
                            producedSurface: Helper.FormatNumber(line.producedSurface || '0'),
                            actualCreditAmount: Helper.FormatNumber(line.actualCreditAmount || '0'),
                            theoreticalCredit: Helper.FormatNumber(line.theoreticalCredit || '0'),
                            finalCarpetAmount: Helper.FormatNumber(line.finalCarpetAmount || '0'),
                            weight: line.weight ? Helper.FormatNumber(line.weight) : null,
                            weightPercentage: Helper.FormatNumber(line.weightPercentage || '0'),
                            freight: Helper.FormatNumber(line.fret || '0')
                        };
                        await supplierInvoiceDetailsService.create(lineAdd);
                    }
                }
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
            // carpetNumber is optional; send null when empty
            const payload = {
                supplierInvoiceId: parseInt(route.params.id) || null,
                rnId: rnId.value || null,
                rn: line.rn,
                carpetNumber: line.carpetNumber && String(line.carpetNumber).trim() !== '' ? String(line.carpetNumber) : null,
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
                freight: String(line.fret) || '0'
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
        lines.value.splice(index, 1);
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
    };
    const verif = () => {
        // When credit date and number are present, set amountTheoretical to sum of actualCreditAmount across lines
        if (form.value.creditDate && form.value.creditNumber) {
            const sum = lines.value.reduce((acc, l) => {
                const v = parseFloat(String(l.actualCreditAmount).replace(/,/g, '')) || 0;
                return acc + v;
            }, 0);
            form.value.amountTheoretical = Helper.FormatNumber(sum);
            form.value.amountReal = Helper.FormatNumber(sum);
        }
    };
    const calculate = () => {
        let totalFacture = 0;
        let totalTheorique = 0;
        let totalSurface = 0;
        let totalWeight = 0;

        lines.value.forEach((l) => {
            // Parse formatted strings back to floats for calculation
            const srfCmd = parseFloat(String(l.invoiceSurface).replace(/,/g, '')) || 0;
            const srfReal = parseFloat(String(l.producedSurface).replace(/,/g, '')) || 0;
            const priceM2Cmd = parseFloat(String(l.pricePerSquareMeter).replace(/,/g, '')) || 0;
            const priceCmd = parseFloat(String(l.invoiceAmount).replace(/,/g, '')) || 0; // Use prixFacture

            if (srfReal < srfCmd) {
                l.theoreticalPrice = Helper.FormatNumber(srfReal * priceM2Cmd);
            } else {
                l.theoreticalPrice = Helper.FormatNumber(priceCmd);
            }

            // theoreticalCredit = theoreticalPrice - penalty
            const theoPriceVal = parseFloat(String(l.theoreticalPrice).replace(/,/g, '')) || 0;
            const penaltyVal = parseFloat(String(l.penalty).replace(/,/g, '')) || 0;
            l.theoreticalCredit = Helper.FormatNumber(theoPriceVal - penaltyVal);

            totalFacture += priceCmd;
            totalTheorique += parseFloat(String(l.theoreticalCredit).replace(/,/g, '')) || 0;
            totalSurface += srfCmd;
            totalWeight += parseFloat(String(l.weight).replace(/,/g, '')) || 0;
        });

        lines.value.forEach((l) => {
            const poids = parseFloat(String(l.weight).replace(/,/g, '')) || 0;
            const wp = totalWeight ? (poids / totalWeight) * 100 : 0;
            l.weightPercentage = Helper.FormatNumber(wp);
            const fretVal = form.value.freightIncluded == true ? (parseFloat(String(form.value.freightTotal).replace(/,/g, '')) || 0) * (wp / 100) : wp / 100;
            l.fret = Helper.FormatNumber(fretVal);
        });

        form.value.invoiceTotal = Helper.FormatNumber(totalFacture);
        form.value.theoreticalTotal = Helper.FormatNumber(totalTheorique + (parseFloat(String(form.value.amountOther).replace(/,/g, '')) || 0));
        form.value.surfaceTotal = Helper.FormatNumber(totalSurface);
        form.value.weightTotal = Helper.FormatNumber(totalWeight);

        // Use updated property names from form
        // parse formatted numbers (remove thousands separators) before arithmetic
        const invoiceTotalNum = parseFloat(String(form.value.invoiceTotal).replace(/,/g, '')) || 0;
        const amountRealNum = parseFloat(String(form.value.amountReal).replace(/,/g, '')) || 0;
        const suiviAnterieurNum = parseFloat(String(form.value.suiviAnterieur).replace(/,/g, '')) || 0;
        const paymentRealNum = parseFloat(String(form.value.paymentReal).replace(/,/g, '')) || 0;

        form.value.paymentTheoretical = Helper.FormatNumber(invoiceTotalNum - amountRealNum - suiviAnterieurNum);
        form.value.suiviRestant = Helper.FormatNumber((parseFloat(String(form.value.paymentTheoretical).replace(/,/g, '')) || 0) - paymentRealNum);
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
            form.value.theoreticalPrice
        ],
        () => {
            calculate();
        }
    );

    // Keep key form numeric fields formatted for display using Helper.FormatNumber
    const normalizeFormattedField = (raw) => {
        const n = parseFloat(String(raw).replace(/,/g, ''));
        return isNaN(n) ? Helper.FormatNumber(0) : Helper.FormatNumber(n);
    };

    watch(
        () => form.value.freightTotal,
        (v) => {
            const formatted = normalizeFormattedField(v);
            if (formatted !== v) form.value.freightTotal = formatted;
        }
    );

    watch(
        () => form.value.amountOther,
        (v) => {
            const formatted = normalizeFormattedField(v);
            if (formatted !== v) form.value.amountOther = formatted;
        }
    );

    watch(
        () => form.value.weight,
        (v) => {
            const formatted = normalizeFormattedField(v);
            if (formatted !== v) form.value.weight = formatted;
        }
    );

    watch(
        () => form.value.amountReal,
        (v) => {
            const formatted = normalizeFormattedField(v);
            if (formatted !== v) form.value.amountReal = formatted;
        }
    );

    watch(
        () => form.value.paymentReal,
        (v) => {
            const formatted = normalizeFormattedField(v);
            if (formatted !== v) form.value.paymentReal = formatted;
        }
    );

    // Keep Montant théorique formatted as well
    watch(
        () => form.value.amountTheoretical,
        (v) => {
            const formatted = normalizeFormattedField(v);
            if (formatted !== v) form.value.amountTheoretical = formatted;
        }
    );

    const onFreightChange = () => {
        form.value.freightTotal = normalizeFormattedField(form.value.freightTotal);
        calculate();
    };

    const onAmountOtherChange = () => {
        form.value.amountOther = normalizeFormattedField(form.value.amountOther);
        calculate();
    };

    const onWeightChange = () => {
        form.value.weight = normalizeFormattedField(form.value.weight);
        calculate();
    };

    const onPaymentRealChange = () => {
        form.value.paymentReal = normalizeFormattedField(form.value.paymentReal);
        calculate();
    };
</script>

<style scoped>
    .custom-date {
        width: 100%;
    }
</style>
