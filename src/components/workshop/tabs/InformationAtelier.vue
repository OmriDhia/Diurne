<script setup lang="ts">
import {ref, onMounted, watch} from 'vue';
import {useRouter} from 'vue-router';
import SelectInput from '../ui/SelectInput.vue';
import RadioButton from '../ui/RadioButton.vue';
import dInput from '../../../components/base/d-input.vue';
import DCurrency from '@/components/common/d-currency.vue';
import { Helper, formatErrorViolations, formatErrorViolationsComposed } from '@/composables/global-methods';
import DPanelTitle from '@/components/common/d-panel-title.vue';
import checkingListService from '../../../Services/checkingList-service';
import workshopService from '@/Services/workshop-service.js';
import DTarifTextureDropdown from "@/components/workshop/dropdown/d-tarif-texture-dropdown.vue";
import DMaterialsDropdown from "@/components/projet/contremarques/dropdown/d-materials-dropdown.vue";
import DCoherenceCheck from "@/components/workshop/_partial/d-coherence-check.vue";

const props = defineProps({
    formData: {
        type: Object,
        required: true
    },
    orderId: {
        type: Number,
        required: false
    },
    workshopInfoId: {
        type: Number,
        required: false
    },
    workshopInfo: {
        type: Object,
        required: false
    },
    lastprogressReporting: {
        type: Object,
        required: false
    },
    imageCommande: {
        type: Object,
        required: false
    },
    imageCommandId: {
        type: Number,
        required: true
    }
});

const manufacturers = ref<Array<{ value: number | string, label: string }>>([]);

const fetchManufacturers = async () => {
    try {
        const data = await workshopService.getManufacturers({page: 1, itemsPerPage: 50});
        const list = data.response?.data || data.data || [];
        manufacturers.value = list.map((m: any) => ({value: m.id, label: m.name}));
    } catch (e) {
        console.error('Failed to load manufacturers', e);
    }
};

onMounted(() => {
    fetchManufacturers();
});

const checkingLists = ref([]);
const router = useRouter();
const error = ref({});
const loadCheckingLists = async () => {
    try {
        if(props.orderId){
            const response = await checkingListService.getCheckingListsByOrder(props.orderId);
            checkingLists.value = response || [];
            const lastcheckingList = checkingLists.value[checkingLists.value.length - 1];
            if(lastcheckingList){
                props.formData.infoCommande.largeurReelle = Helper.FormatNumber(lastcheckingList.shapeValidation.realWidth);
                props.formData.infoCommande.longueurReelle = Helper.FormatNumber(lastcheckingList.shapeValidation.realLength);
                props.formData.infoCommande.srfReelle = Helper.FormatNumber(lastcheckingList.shapeValidation.surface);
            } 
        }
    } catch (e) {
        console.error('Error loading checking lists:', e);
    }
};

// Methods
const generateRN = async () => {
    try {
        error.value = {}
        const manufacturerId = parseInt(props.formData.tapisDuProjet.fabricant);
        const data = await workshopService.generateRN(manufacturerId, props.imageCommandId);
        props.formData.tapisDuProjet.rn = data.response?.rnNumber || data.response?.rnNumber || '';
    } catch (e) {
        if (e.response.data.violations) {
            error.value = formatErrorViolations(e.response.data.violations);
        }
        window.showMessage('Erreur de génération RN','error');
    }
};

const controlCoherence = () => {
    console.log('Controlling coherence...');
};

const updatePrixTapis = async() => {
    try{
        await saveWorkshopInformation();
        const res = await workshopService.calculatePrices(props.workshopInfoId);
        const prices = res.response.prices;
        props.formData.prixAchatTapis.auM2 =  `${Helper.FormatNumber(prices.carpet_purchase_price_per_m2)}`;
        props.formData.prixAchatTapis.cmd = `${Helper.FormatNumber(prices.carpet_purchase_price_cmd)}`;
        props.formData.prixAchatTapis.theorique = `${Helper.FormatNumber(prices.carpet_purchase_price_theoretical)}`;
    }catch(e){
        window.showMessage('Erreur au niveau de calcule des prix','error');
    }
};

const voir = () => {
    console.log('Viewing DI commande...');
};

const attribuer = () => {
    console.log('Attributing...');
};

const saveWorkshopInformation = async () => {
    error.value = {}
    console.log("formData",props.formData)
    const payload = {
        launchDate: props.formData.infoCommande.dateCmdAtelier || "",
        expectedEndDate: props.formData.infoCommande.dateFinTheo || "",
        productionTime: Number(props.formData.infoCommande.delaisProd) || 0,
        orderSilkPercentage: props.formData.infoCommande.pourcentCommande,
        orderedWidth: props.formData.infoCommande.largeurCmd,
        orderedHeigh: props.formData.infoCommande.longueurCmd,
        orderedSurface: props.formData.infoCommande.srfCmd,
        realWidth: props.formData.infoCommande.largeurReelle,
        realHeight: props.formData.infoCommande.longueurReelle,
        realSurface: props.formData.infoCommande.srfReelle,
        idTarifGroup: Number(props.formData.infoCommande.anneeGrilleTarif) || 0,
        idTarifTexture: Number(props.formData.infoCommande.anneeGrilleTarif) || 0,
        reductionRate: props.formData.reductionTapis,
        hasComplixityWorkshop: props.formData.complexiteAtelier,
        hasMultilevelWorkshop: props.formData.multiLevelAtelier,
        hasSpecialShape: props.formData.formeSpeciale,
        carpetPurchasePricePerM2: props.formData.prixAchatTapis.auM2,
        carpetPurchasePriceCmd: props.formData.prixAchatTapis.cmd,
        carpetPurchasePriceTheoretical: props.formData.prixAchatTapis.theorique,
        carpetPurchasePriceInvoice: props.formData.prixAchatTapis.facture,
        penalty: props.formData.others.penalite,
        shipping: props.formData.others.transport,
        tva: props.formData.others.taxe,
        grossMargin: props.formData.others.margeBrute,
        referenceOnInvoice: props.formData.others.referenceSurFacture,
        invoiceNumber: props.formData.others.numeroDuFacture,
        manufacturerId: parseInt(props.formData.tapisDuProjet.fabricant),
        Rn: props.formData.tapisDuProjet.rn,
        idQuality: props.imageCommande?.carpetSpecification?.quality?.id,
        currencyId: props.formData.currencyId,
        availableForSale: props.formData.disponibleVente,
        sent: props.formData.envoye,
        receivedInParis: props.formData.receptionParis,
        specialRateInParis: props.formData.tarifSpecial,
        specialRate: props.formData.tarifSpecial
    };
    try {
        if(props.workshopInfoId){
            const res = await workshopService.updateWorkshopInformation(props.workshopInfoId,payload);
            // Removed updateWorkshopOrder call for these fields
        } else {
            const res = await workshopService.createWorkshopInformation(payload);
            
            if(res?.response?.id){
                const resWorkshopOrder = await workshopService.createWorkshopOrder({
                    reference: props.formData.tapisDuProjet.rn,
                    image_command_id: props.imageCommandId,
                    workshop_information_id: res?.response?.id
                })
                router.push({name: "updateCarpetWorkshop",params:{workshopOrderId:resWorkshopOrder?.response?.id}})
            }
        }
        window.showMessage("Commande atelier enregistrer avec succées");
    } catch (e) {
        if (e.response.data.violations) {
            error.value = formatErrorViolations(e.response.data.violations);
        }else if(e.status === 500 && e.response.data?.detail?.includes('Duplicate entry')){
            window.showMessage("Une commande atelier existe déja pour cette commande image", 'error');
            return
        }
        console.log(e);
        window.showMessage(e.message, 'error');
    }
};

const commandeAtelier = () => {
    console.log('Workshop command...');
};

defineExpose({
    saveWorkshopInformation,
    commandeAtelier,
    generateRN
});

const setDataForUpdate = () => {
    if(Object.keys(props.workshopInfo).length > 0){
        props.formData.infoCommande.dateCmdAtelier = props.workshopInfo.launchDate;
        props.formData.infoCommande.dateFinTheo = props.workshopInfo.expectedEndDate;
        props.formData.infoCommande.delaisProd = props.workshopInfo.productionTime?.toString() || "";
        props.formData.infoCommande.pourcentCommande = Helper.FormatNumber(props.workshopInfo.orderSilkPercentage);
        props.formData.infoCommande.largeurCmd =  Helper.FormatNumber(props.workshopInfo.orderedWidth);
        props.formData.infoCommande.longueurCmd = Helper.FormatNumber(props.workshopInfo.orderedHeigh);
        props.formData.infoCommande.srfCmd = Helper.FormatNumber(props.workshopInfo.orderedSurface);
        props.formData.infoCommande.largeurReelle = Helper.FormatNumber(props.workshopInfo.realWidth);
        props.formData.infoCommande.longueurReelle = Helper.FormatNumber(props.workshopInfo.realHeight);
        props.formData.infoCommande.srfReelle = Helper.FormatNumber(props.workshopInfo.realSurface);
        props.formData.infoCommande.anneeGrilleTarif = props.workshopInfo.idTarifTexture || "";
        props.formData.currencyId = props.workshopInfo.idCurrency || 1;
        props.formData.prixAchat = [];

        props.formData.reductionTapis = Helper.FormatNumber(props.workshopInfo.reductionRate);
        props.formData.complexiteAtelier = props.workshopInfo.hasComplixityWorkshop;
        props.formData.multiLevelAtelier = props.workshopInfo.hasMultilevelWorkshop;
        props.formData.formeSpeciale = props.workshopInfo.hasSpecialShape;

        props.formData.prixAchatTapis.auM2 = Helper.FormatNumber(props.workshopInfo.carpetPurchasePricePerM2);
        props.formData.prixAchatTapis.cmd = Helper.FormatNumber(props.workshopInfo.carpetPurchasePriceCmd);
        props.formData.prixAchatTapis.theorique = Helper.FormatNumber(props.workshopInfo.carpetPurchasePriceTheoretical);
        props.formData.prixAchatTapis.facture = Helper.FormatNumber(props.workshopInfo.carpetPurchasePriceInvoice);

        props.formData.others.penalite = Helper.FormatNumber(props.workshopInfo.penalty);
        props.formData.others.transport = Helper.FormatNumber(props.workshopInfo.shipping);
        props.formData.others.taxe = Helper.FormatNumber(props.workshopInfo.tva);
        props.formData.others.margeBrute = Helper.FormatNumber(props.workshopInfo.grossMargin);
        props.formData.others.referenceSurFacture = props.workshopInfo.referenceOnInvoice;
        props.formData.others.numeroDuFacture = props.workshopInfo.invoiceNumber;

        props.formData.tapisDuProjet.fabricant = props.workshopInfo.manufacturerId?.toString() || "";
        props.formData.tapisDuProjet.rn = props.workshopInfo.rn;

        if(props.workshopInfo?.materialPurchasePrices.length > 0){
            props.formData.prixAchat = props.workshopInfo?.materialPurchasePrices.map(item => {
                item.price = Helper.FormatNumber(item.price);
                return item;
            });  
        }
        // Map API boolean fields to formData
        props.formData.disponibleVente = props.workshopInfo.availableForSale ?? false;
        props.formData.envoye = props.workshopInfo.sent ?? false;
        props.formData.receptionParis = props.workshopInfo.receivedInParis ?? false;
        props.formData.tarifSpecial = (props.workshopInfo.specialRateInParis ?? props.workshopInfo.specialRate) ?? false;
    }
}
const createNewCheckingList = async () => {
    try {
        const newList = await checkingListService.createCheckingList(
            props.orderId
        );
        if (newList) {
            checkingLists.value.push(newList);
            router.push(`/checking-progress/list/${newList.id}`);
        }
    } catch (e) {
        console.error('Failed to create checking list:', e);
    }
};
const setDataFromImageCommande = () => {
    if(!props.orderId){
        const long = props.imageCommande?.carpetSpecification?.carpetDimensions?.[2]?.[0]?.value ?? 0
        const larg = props.imageCommande?.carpetSpecification?.carpetDimensions?.[1]?.[0]?.value ?? 0
        props.formData.infoCommande.largeurCmd =  Helper.FormatNumber(larg);
        props.formData.infoCommande.longueurCmd = Helper.FormatNumber(long);
        props.formData.infoCommande.srfCmd = Helper.FormatNumber(long * larg);
    }
}
const updatePurchasePrice = async (index, price) => {
    const pa = props.formData.prixAchat[index]
    if(pa){
        const p = {
            materialId: pa.material_id,
            price: price.target.value,
            productionOrderId: pa.production_order_id,
            workshopInformationId: pa.workshop_information
        }
        try {
            const res = await workshopService.updatePruchasePrices(pa.id, p);
            window.showMessage("Mise a jour de prix d'achat materials avec succées");
        } catch (e) {
            console.error('Failed to create checking list:', e);
        }
    }
}
const goToImageDetails = () => {
    router.push({name: "imagesCommadeDetails", params:{id: props.imageCommandId}})
}
onMounted(() =>{
    loadCheckingLists();
    setDataForUpdate();
    setDataFromImageCommande();
    console.log("lastOne: ", props.lastprogressReporting)
});
watch(
    () => props.workshopInfo,
    () => {
        setDataForUpdate()
    },
    { deep: true }
);
watch(
    () => props.imageCommande,
    () => {
        setDataFromImageCommande()
    },
    { deep: true }
);
watch(
    () => props.lastprogressReporting,
    () => {
        console.log("lastOne: ", props.lastprogressReporting)
    },
    { deep: true }
);
</script>

<template>
    <div class="information-atelier">
        <div class="main-sections row">
            <div class="left-section col-8">
                <d-panel-title title="Info commande atelier" className="ps-2"></d-panel-title>
                <div class="row">
                    <div class="col-6">

                        <div class="form-row">
                            <d-input label="Date de cmd. atelier" type="datetime-local"
                                     v-model="props.formData.infoCommande.dateCmdAtelier"/>
                        </div>

                        <div class="form-row">
                            <d-input label="Date fin atelier Prev" type="datetime-local"
                                     v-model="props.formData.infoCommande.dateFinAtelierPrev"
                                     rootClass="pink-bg"/>
                        </div>

                        <div class="form-row">
                            <d-input label="% commande soie" v-model="props.formData.infoCommande.pourcentCommande"
                                     rootClass="pink-bg"/>
                        </div>

                        <div class="form-row">
                            <d-input label="Largeur cmd. atelier" v-model="props.formData.infoCommande.largeurCmd"
                                     rootClass="pink-bg"/>
                        </div>

                        <div class="form-row">
                            <d-input label="Longueur cmd. atelier" v-model="props.formData.infoCommande.longueurCmd"
                                     rootClass="pink-bg"/>
                        </div>

                        <div class="form-row">
                            <d-input label="Srf cmd. atelier" v-model="props.formData.infoCommande.srfCmd"/>
                        </div>
                        
                        <d-tarif-texture-dropdown v-model="props.formData.infoCommande.anneeGrilleTarif" rootClass="pink-bg" :error="error.idTarifGroup"/>

                        <div class="form-row special-tarif row py-3">
                            <div class="col-12 p-0">
                                <RadioButton class="w-100" v-model="props.formData.tarifSpecial"
                                             label="Tarif spécial"/>
                            </div>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="theoretical-section">
                            <div class="form-row">
                                <d-input label="Date fin Théo" type="datetime-local"
                                         v-model="props.formData.infoCommande.dateFinTheo"/>
                            </div>

                            <div class="form-row">
                                <div class="col-12">
                                    <d-input label="Délais de prod" v-model="props.formData.infoCommande.delaisProd"/>
                                </div>
                            </div>

                            <div class="form-row row py-2">
                                <d-currency :required="true"
                                            :error="error.currencyId" v-model="props.formData.currencyId">

                                </d-currency>
                            </div>

                            <div class="form-row">
                                <d-input label="Lrg. réelle" v-model="props.formData.infoCommande.largeurReelle"/>
                            </div>

                            <div class="form-row">
                                <d-input label="Lng. réelle" v-model="props.formData.infoCommande.longueurReelle"/>
                            </div>

                            <div class="form-row">
                                <d-input label="Srf réelle" v-model="props.formData.infoCommande.srfReelle"/>
                            </div>

                            <div class="form-row py-2">
                                <router-link :to="{ name: 'tarification-taxes' }" class="btn btn-custom text-uppercase w-100">
                                    GESTION GRILLE TARIFAIRE
                                </router-link>
                            </div>

                            <div class="form-row">
                                <d-input label="% Réduc. tapis " v-model="props.formData.reductionTapis"/>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4 my-4 align-items-center">
                    <div class="col-md-12">
                        <div class="row align-items-center" v-for="(material, index) in props.formData.prixAchat"
                             :key="index">
                            <div class="col-md-3">
                                <label class="pt-2">Prix d'achat :</label>
                            </div>
                            <div class="col-md-5">
                                <d-materials-dropdown :hide-label="true" class="pt-2" v-model="material.material_id" :disabled="true" />
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" 
                                       type="text" 
                                       :name="`price_${index}`" 
                                       :id="`price_${index}`" 
                                       :value="material.price"
                                       :disabled="!props.formData.tarifSpecial"
                                       @change="updatePurchasePrice(index, $event)"/>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="radio-options my-4">
                    <RadioButton v-model="props.formData.complexiteAtelier" :value="true" label="Complexité atelier"/>
                    <RadioButton v-model="props.formData.multiLevelAtelier" :value="true" label="Multi-level atelier"/>
                    <RadioButton v-model="props.formData.formeSpeciale" :value="true" label="Forme spéciale"/>
                </div>


                <div class="row">
                    <div class="col-6 ps-0">
                        <div class="price-row">
                            <d-input label="Prix d'achat tapis au m² " v-model="props.formData.prixAchatTapis.auM2"/>
                        </div>
                        <div class="price-row">
                            <d-input label="Prix d'achat tapis théorique"
                                     v-model="props.formData.prixAchatTapis.theorique"/>
                        </div>
                        <div class="price-row">
                            <d-input label="Pénalité" v-model="props.formData.others.penalite"/>
                        </div>
                        <div class="price-row">
                            <d-input label="Taxe" v-model="props.formData.others.taxe"/>
                        </div>
                        <div class="price-row">
                            <d-input label="Référence sur facture" v-model="props.formData.others.referenceSurFacture"/>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="price-row">
                            <d-input label="Prix d'achat tapis Cmd" v-model="props.formData.prixAchatTapis.cmd"/>
                        </div>
                        <div class="price-row">
                            <d-input label="Prix d'achat tapis facture" v-model="props.formData.prixAchatTapis.facture"/>
                        </div>
                        <div class="price-row">
                            <d-input label="Transport" v-model="props.formData.others.transport"/>
                        </div>
                        <div class="price-row">
                            <d-input label="Marge brute" v-model="props.formData.others.margeBrute"/>
                        </div>
                        <div class="price-row">
                            <d-input label="Numéro du facture" v-model="props.formData.others.numeroDuFacture"/>
                        </div>
                    </div>
                </div>


                <div class="details-row d-flex justify-content-center align-items-center py-2"  v-if="props.workshopInfoId">
                    <div class="col-4"><label>Détails prix tapis</label></div>
                    <div class="col-4">
                        <button class="btn btn-outline-dark  text-uppercase" @click="updatePrixTapis">
                            MISE À JOURS
                        </button>
                    </div>


                </div>

                <div class="checking-lists">
                    <div class="list-links">
                        <router-link
                            v-for="list in checkingLists"
                            :key="list.id"
                            :to="`/checking-progress/list/${list.id}`"
                            class="checking-link"
                        >
                            <span class="text-decoration-underline me-2">
                                Checking List n°{{ list.id }}
                            </span>
                        </router-link>
                    </div>
                    <button class="new-list-btn btn btn-custom  text-uppercase my-2"
                            @click="createNewCheckingList">NOUVELLE CHECKING LIST
                    </button>
                </div>
            </div>

            <!-- Right section -->
            <div class="right-section col-4">


                <d-panel-title title="Tapis du projet" className="ps-2"></d-panel-title>
                <div class="text-center py-2">
                    <img :src="$Helper.getImagePath(props.imageCommande?.technicalImages?.[0]?.attachment)" alt="Carpet Image" class="img-thumbnail" style="width: 100px; height: auto;">
                </div>

                <button class="btn btn-outline-dark btn-reset text-uppercase w-100" @click="goToImageDetails">VOIR LA DI
                    COMMANDE
                </button>

                <div class="selector-row row py-3">
                    <div class="col-7">
                        <SelectInput/>
                    </div>
                    <div class="col-5">
                        <SelectInput/>
                    </div>
                </div>

                <button class="btn btn-custom  text-uppercase w-100" @click="attribuer">ATTRIBUER</button>

                <div class="form-row">
                    <d-input label="Date validation client" type="datetime-local" v-model="props.formData.dateValidationClient"/>
                </div>

                <!--button class="coherence-btn btn btn-custom  text-uppercase w-100 py-2"
                        @click="controlCoherence">CONTRÔLE DE COHÉRENCE
                </button-->
                
                <d-coherence-check v-if="props.orderId" :imageCommandId="props.imageCommandId" :workshopOrderId="props.orderId"></d-coherence-check>

                <div class="form-row row py-2 align-items-center">
                    <div class="col-4"><label>Fabricant :</label></div>
                    <div class="col-8">
                        <SelectInput v-model="props.formData.tapisDuProjet.fabricant" :options="manufacturers"  :error="error.manufacturerId"
                                     rootClass="pink-bg"/>
                        <div v-if="error.manufacturerId" class="invalid-feedback">{{ $t("Le champ fabricant est abligatoire.") }}</div>
                    </div>
                </div>
                <div class="form-row row py-2">
                    <div class="col-4"><label>Type commandé :</label></div>
                    <div class="col-8">
                        <SelectInput v-model="props.formData.tapisDuProjet.fabricant" rootClass="pink-bg"/>
                    </div>
                </div>

                <div class="form-row">
                    <d-input label="RN" v-model="props.formData.tapisDuProjet.rn" rootClass="pink-bg" disabled/>
                </div>

                <div class="form-row">
                    <d-input label="N° d'exemplaire" v-model="props.formData.tapisDuProjet.exemplaire"/>
                </div>

                <button class="generate-btn btn btn-outline-dark   text-uppercase w-100" @click="generateRN">
                    GÉNÉRER RN
                </button>

            </div>
        </div>

        <!-- Theoretical values section -->

    </div>
</template>

<style scoped lang="scss">

</style>
