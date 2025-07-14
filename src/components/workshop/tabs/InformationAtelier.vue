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
    imageCommande: {
        type: Object,
        required: false
    },
    imageCommandId: {
        type: Number,
        required: true
    }
});
// Form data
const formData = ref({
    infoCommande: {
        dateCmdAtelier: '24-10-2021',
        dateFinTheo: '24-10-2021',
        dateFinAtelierPrev: '',
        delaisProd: '2',
        pourcentCommande: '0.00',
        deviseAchat: '',
        largeurCmd: '30',
        largeurReelle: '0',
        longueurCmd: '30',
        longueurReelle: '0',
        srfCmd: '0.9',
        srfReelle: '0',
        anneeGrilleTarif: ''
    },
    tarifSpecial: true,
    prixAchat: [],
    reductionTapis: '0',
    complexiteAtelier: false,
    multiLevelAtelier: true,
    formeSpeciale: true,
    tapisDuProjet: {
        fabricant: '',
        typeCommande: '',
        rn: 'M0025',
        exemplaire: '3'
    },
    prixAchatTapis: {
        auM2: '32',
        cmd: '2.88',
        theorique: '2.88',
        facture: '0'
    },
    others: {
        penalite: '0',
        transport: '0',
        taxe: '0',
        margeBrute: '0',
        referenceSurFacture: '0',
        numeroDuFacture: null
    },
    dateValidationClient: '24-10-2021',
    disponibleVente: false,
    envoye: false,
    receptionParis: true
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
                formData.value.infoCommande.largeurReelle = Helper.FormatNumber(lastcheckingList.shapeValidation.realWidth);
                formData.value.infoCommande.longueurReelle = Helper.FormatNumber(lastcheckingList.shapeValidation.realLength);
                formData.value.infoCommande.srfReelle = Helper.FormatNumber(lastcheckingList.shapeValidation.surface);
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
        const manufacturerId = parseInt(formData.value.tapisDuProjet.fabricant);
        const data = await workshopService.generateRN(manufacturerId, props.imageCommandId);
        formData.value.tapisDuProjet.rn = data.response?.rnNumber || data.response?.rnNumber || '';
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
        formData.value.prixAchatTapis.auM2 =  `${Helper.FormatNumber(prices.carpet_purchase_price_per_m2)}`;
        formData.value.prixAchatTapis.cmd = `${Helper.FormatNumber(prices.carpet_purchase_price_cmd)}`;
        formData.value.prixAchatTapis.theorique = `${Helper.FormatNumber(prices.carpet_purchase_price_theoretical)}`;
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
    const payload = {
        launchDate: formData.value.infoCommande.dateCmdAtelier || "",
        expectedEndDate: formData.value.infoCommande.dateFinTheo || "",
        productionTime: Number(formData.value.infoCommande.delaisProd) || 0,
        orderSilkPercentage: formData.value.infoCommande.pourcentCommande,
        orderedWidth: formData.value.infoCommande.largeurCmd,
        orderedHeigh: formData.value.infoCommande.longueurCmd,
        orderedSurface: formData.value.infoCommande.srfCmd,
        realWidth: formData.value.infoCommande.largeurReelle,
        realHeight: formData.value.infoCommande.longueurReelle,
        realSurface: formData.value.infoCommande.srfReelle,
        idTarifGroup: Number(formData.value.infoCommande.anneeGrilleTarif) || 0,
        idTarifTexture: Number(formData.value.infoCommande.anneeGrilleTarif) || 0,
        reductionRate: formData.value.reductionTapis,
        hasComplixityWorkshop: formData.value.complexiteAtelier,
        hasMultilevelWorkshop: formData.value.multiLevelAtelier,
        hasSpecialShape: formData.value.formeSpeciale,
        carpetPurchasePricePerM2: formData.value.prixAchatTapis.auM2,
        carpetPurchasePriceCmd: formData.value.prixAchatTapis.cmd,
        carpetPurchasePriceTheoretical: formData.value.prixAchatTapis.theorique,
        carpetPurchasePriceInvoice: formData.value.prixAchatTapis.facture,
        penalty: formData.value.others.penalite,
        shipping: formData.value.others.transport,
        tva: formData.value.others.taxe,
        grossMargin: formData.value.others.margeBrute,
        referenceOnInvoice: formData.value.others.referenceSurFacture,
        invoiceNumber: formData.value.others.numeroDuFacture,
        manufacturerId: parseInt(formData.value.tapisDuProjet.fabricant),
        Rn: formData.value.tapisDuProjet.rn,
        idQuality: props.imageCommande?.carpetSpecification?.quality?.id,
    };
    try {
        if(props.workshopInfoId){
            const res = await workshopService.updateWorkshopInformation(props.workshopInfoId,payload);
        } else {
            const res = await workshopService.createWorkshopInformation(payload);
            
            if(res?.response?.id){
                const resWorkshopOrder = await workshopService.createWorkshopOrder({
                    reference: formData.value.tapisDuProjet.rn,
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
        formData.value.infoCommande.dateCmdAtelier = props.workshopInfo.launchDate;
        formData.value.infoCommande.dateFinTheo = props.workshopInfo.expectedEndDate;
        formData.value.infoCommande.delaisProd = props.workshopInfo.productionTime?.toString() || "";
        formData.value.infoCommande.pourcentCommande = Helper.FormatNumber(props.workshopInfo.orderSilkPercentage);
        formData.value.infoCommande.largeurCmd =  Helper.FormatNumber(props.workshopInfo.orderedWidth);
        formData.value.infoCommande.longueurCmd = Helper.FormatNumber(props.workshopInfo.orderedHeigh);
        formData.value.infoCommande.srfCmd = Helper.FormatNumber(props.workshopInfo.orderedSurface);
        formData.value.infoCommande.largeurReelle = Helper.FormatNumber(props.workshopInfo.realWidth);
        formData.value.infoCommande.longueurReelle = Helper.FormatNumber(props.workshopInfo.realHeight);
        formData.value.infoCommande.srfReelle = Helper.FormatNumber(props.workshopInfo.realSurface);
        formData.value.infoCommande.anneeGrilleTarif = props.workshopInfo.idTarifTexture || "";
        formData.value.prixAchat = [];

        formData.value.reductionTapis = Helper.FormatNumber(props.workshopInfo.reductionRate);
        formData.value.complexiteAtelier = props.workshopInfo.hasComplixityWorkshop;
        formData.value.multiLevelAtelier = props.workshopInfo.hasMultilevelWorkshop;
        formData.value.formeSpeciale = props.workshopInfo.hasSpecialShape;

        formData.value.prixAchatTapis.auM2 = Helper.FormatNumber(props.workshopInfo.carpetPurchasePricePerM2);
        formData.value.prixAchatTapis.cmd = Helper.FormatNumber(props.workshopInfo.carpetPurchasePriceCmd);
        formData.value.prixAchatTapis.theorique = Helper.FormatNumber(props.workshopInfo.carpetPurchasePriceTheoretical);
        formData.value.prixAchatTapis.facture = Helper.FormatNumber(props.workshopInfo.carpetPurchasePriceInvoice);

        formData.value.others.penalite = Helper.FormatNumber(props.workshopInfo.penalty);
        formData.value.others.transport = Helper.FormatNumber(props.workshopInfo.shipping);
        formData.value.others.taxe = Helper.FormatNumber(props.workshopInfo.tva);
        formData.value.others.margeBrute = Helper.FormatNumber(props.workshopInfo.grossMargin);
        formData.value.others.referenceSurFacture = props.workshopInfo.referenceOnInvoice;
        formData.value.others.numeroDuFacture = props.workshopInfo.invoiceNumber;

        formData.value.tapisDuProjet.fabricant = props.workshopInfo.manufacturerId?.toString() || "";
        formData.value.tapisDuProjet.rn = props.workshopInfo.rn;

        if(props.workshopInfo?.materialPurchasePrices.length > 0){
            formData.value.prixAchat = props.workshopInfo?.materialPurchasePrices.map(item => {
                item.price = Helper.FormatNumber(item.price);
                return item;
            });  
        }
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
        formData.value.infoCommande.largeurCmd =  Helper.FormatNumber(larg);
        formData.value.infoCommande.longueurCmd = Helper.FormatNumber(long);
        formData.value.infoCommande.srfCmd = Helper.FormatNumber(long * larg);
    }
}
const updatePurchasePrice = async (index, price) => {
    const pa = formData.value.prixAchat[index]
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
onMounted(() =>{
    loadCheckingLists();
    setDataForUpdate();
    setDataFromImageCommande();
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
                                     v-model="formData.infoCommande.dateCmdAtelier"/>
                        </div>

                        <div class="form-row">
                            <d-input label="Date fin atelier Prev" type="datetime-local"
                                     v-model="formData.infoCommande.dateFinAtelierPrev"
                                     rootClass="pink-bg"/>
                        </div>

                        <div class="form-row">
                            <d-input label="% commande soie" v-model="formData.infoCommande.pourcentCommande"
                                     rootClass="pink-bg"/>
                        </div>

                        <div class="form-row">
                            <d-input label="Largeur cmd. atelier" v-model="formData.infoCommande.largeurCmd"
                                     rootClass="pink-bg"/>
                        </div>

                        <div class="form-row">
                            <d-input label="Longueur cmd. atelier" v-model="formData.infoCommande.longueurCmd"
                                     rootClass="pink-bg"/>
                        </div>

                        <div class="form-row">
                            <d-input label="Srf cmd. atelier" v-model="formData.infoCommande.srfCmd"/>
                        </div>
                        
                        <d-tarif-texture-dropdown v-model="formData.infoCommande.anneeGrilleTarif" rootClass="pink-bg" :error="error.idTarifGroup"/>

                        <div class="form-row special-tarif row py-3">
                            <div class="col-12 p-0">
                                <RadioButton v-model="formData.tarifSpecial" :value="true" label="Tarif spécial"/>
                            </div>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="theoretical-section">
                            <div class="form-row">
                                <d-input label="Date fin Théo" type="datetime-local"
                                         v-model="formData.infoCommande.dateFinTheo"/>
                            </div>

                            <div class="form-row">
                                <div class="col-12">
                                    <d-input label="Délais de prod" v-model="formData.infoCommande.delaisProd"/>
                                </div>
                            </div>

                            <div class="form-row row py-2">
                                <d-currency :required="true"
                                            :error=0 model-value="">

                                </d-currency>
                            </div>

                            <div class="form-row">
                                <d-input label="Lrg. réelle" v-model="formData.infoCommande.largeurReelle"/>
                            </div>

                            <div class="form-row">
                                <d-input label="Lng. réelle" v-model="formData.infoCommande.longueurReelle"/>
                            </div>

                            <div class="form-row">
                                <d-input label="Srf réelle" v-model="formData.infoCommande.srfReelle"/>
                            </div>

                            <div class="form-row py-2">
                                <button class="btn btn-custom  text-uppercase w-100">
                                    GESTION GRILLE TARIFAIRE
                                </button>
                            </div>

                            <div class="form-row">
                                <d-input label="% Réduc. tapis " v-model="formData.reductionTapis"/>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4 my-4 align-items-center">
                    <div class="col-md-12">
                        <div class="row align-items-center" v-for="(material, index) in formData.prixAchat"
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
                                       @change="updatePurchasePrice(index, $event)"/>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="radio-options my-4">
                    <RadioButton v-model="formData.complexiteAtelier" :value="true" label="Complexité atelier"/>
                    <RadioButton v-model="formData.multiLevelAtelier" :value="true" label="Multi-level atelier"/>
                    <RadioButton v-model="formData.formeSpeciale" :value="true" label="Forme spéciale"/>
                </div>


                <div class="row" v-if="props.workshopInfoId">
                    <div class="col-6 ps-0">
                        <div class="price-row">
                            <d-input label="Prix d'achat tapis au m² " v-model="formData.prixAchatTapis.auM2"/>
                        </div>
                        <div class="price-row">
                            <d-input label="Prix d'achat tapis théorique"
                                     v-model="formData.prixAchatTapis.theorique"/>
                        </div>
                        <div class="price-row">
                            <d-input label="Pénalité" v-model="formData.others.penalite"/>
                        </div>
                        <div class="price-row">
                            <d-input label="Taxe" v-model="formData.others.taxe"/>
                        </div>
                        <div class="price-row">
                            <d-input label="Référence sur facture" v-model="formData.others.referenceSurFacture"/>
                        </div>
                    </div>
                    <div class="col-6">


                        <div class="price-row">
                            <d-input label="Prix d'achat tapis Cmd" v-model="formData.prixAchatTapis.cmd"/>
                        </div>


                        <div class="price-row">
                            <d-input label="Prix d'achat tapis facture" v-model="formData.prixAchatTapis.facture"/>
                        </div>


                        <div class="price-row">
                            <d-input label="Transport" v-model="formData.others.transport"/>
                        </div>


                        <div class="price-row">
                            <d-input label="Marge brute" v-model="formData.others.margeBrute"/>
                        </div>

                        <div class="price-row">
                            <d-input label="Numéro du facture" v-model="formData.others.numeroDuFacture"/>
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
                    <img src="https://placehold.co/100x200?text=Carpet+Image" alt="Carpet placeholder image">
                </div>

                <button class="btn btn-outline-dark btn-reset text-uppercase w-100" @click="voir">VOIR LA DI
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
                    <d-input label="Date validation client" type="datetime-local" v-model="formData.dateValidationClient"/>
                </div>

                <!--button class="coherence-btn btn btn-custom  text-uppercase w-100 py-2"
                        @click="controlCoherence">CONTRÔLE DE COHÉRENCE
                </button-->
                
                <d-coherence-check v-if="props.orderId" :imageCommandId="props.imageCommandId" :workshopOrderId="props.orderId"></d-coherence-check>

                <div class="form-row row py-2 align-items-center">
                    <div class="col-4"><label>Fabricant :</label></div>
                    <div class="col-8">
                        <SelectInput v-model="formData.tapisDuProjet.fabricant" :options="manufacturers"  :error="error.manufacturerId"
                                     rootClass="pink-bg"/>
                        <div v-if="error.manufacturerId" class="invalid-feedback">{{ $t("Le champ fabricant est abligatoire.") }}</div>
                    </div>
                </div>
                <div class="form-row row py-2">
                    <div class="col-4"><label>Type commandé :</label></div>
                    <div class="col-8">
                        <SelectInput v-model="formData.tapisDuProjet.fabricant" rootClass="pink-bg"/>
                    </div>
                </div>

                <div class="form-row">
                    <d-input label="RN" v-model="formData.tapisDuProjet.rn" rootClass="pink-bg" disabled/>
                </div>

                <div class="form-row">
                    <d-input label="N° d'exemplaire" v-model="formData.tapisDuProjet.exemplaire"/>
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
