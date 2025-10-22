<template>
    <div>
        <d-animated-skeleton :loading="loading" :marginTop="5"></d-animated-skeleton>
        <div class="layout-px-spacing mt-4" v-if="!loading">
            <d-page-title icon="carpet" :title="`Tapis (${workshopOrder?.workshopInformation?.rn})`"></d-page-title>
            <div class="row layout-top-spacing mt-3 p-2" v-if="!loading">
                <div class="panel br-6 p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <TabNavigation
                                :tabs="tabs"
                                :activeTab="activeTab"
                                @change-tab="changeTab"
                            />

                            <div class="tab-content">
                                <InformationAtelier :form-data="formData" :workshop-info-id="workshopInfoId"
                                                    :workshop-info="workshopInfo"
                                                    :image-commande="imageCommande"
                                                    :order-id="workshopOrderId"
                                                    :lastprogressReporting="lastprogressReporting"
                                                    ref="infoTab"
                                                    :imageCommandId="imageCommandId"
                                                    v-if="activeTab === 'information'" />
                                <ImageTab :imageCommandId="imageCommandId" :imageCommande="imageCommande"
                                          :workshopOrderId="workshopOrderId"
                                          v-if="activeTab === 'image' && workshopOrderId" />
                                <HistoriqueTab :rn="workshopInfo.rn" :workshopOrderId="workshopOrderId"
                                               v-if="activeTab === 'historique' && workshopOrderId" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!--                            <HistoryPanel v-if="workshopOrderId"></HistoryPanel>-->
                            <d-progress-report-histories v-if="workshopOrderId" :workshopOrderId="workshopOrderId"
                                                         @lastOne="changeLastProgressReporting"></d-progress-report-histories>
                            <div class="status-options">
                                <RadioButton class="w-100" v-model="formData.disponibleVente"
                                             label="Disponible à la vente" />
                                <RadioButton class="w-100" v-model="formData.envoye" label="Envoyé" />
                                <RadioButton class="w-100" v-model="formData.receptionParis" label="Réception Paris" />
                            </div>

                            <div class="action-buttons">
                                <button class="save-btn btn btn-outline-dark   text-uppercase w-100 my-2"
                                        @click="enregistrer">
                                    ENREGISTRER
                                </button>
                                <button class="command-btn btn btn-custom  text-uppercase w-100 my-2"
                                        data-bs-toggle="modal" data-bs-target="#downloadWorkshopFacture">
                                    COMMANDE ATELIER
                                </button>
                                <d-modal-bon-commande-atelier
                                    :workshopOrderId="workshopOrderId"></d-modal-bon-commande-atelier>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import { onMounted, ref } from 'vue';
    import { useRoute } from 'vue-router';
    import TabNavigation from './TabNavigation.vue';
    import InformationAtelier from '../../components/workshop/tabs/InformationAtelier.vue';
    import ImageTab from '../../components/workshop/tabs/ImageTab.vue';
    import HistoriqueTab from '../../components/workshop/tabs/HistoriqueTab.vue';
    import dPageTitle from '../../components/common/d-page-title.vue';
    import RadioButton from '@/components/workshop/ui/RadioButton.vue';
    import DProgressReportHistories from '@/components/workshop/_partial/d-progress-report-histories.vue';
    import workshopService from '@/Services/workshop-service.js';
    import DPageTitle from '@/components/common/d-page-title.vue';
    import DAnimatedSkeleton from '@/components/base/d-animated-skeleton.vue';
    import axiosInstance from '@/config/http.js';
    import DModalBonCommandeAtelier from '@/components/workshop/_partial/d-modal-bon-commande-atelier.vue';
    import DCoherenceCheck from '@/components/workshop/_partial/d-coherence-check.vue';
    import { Helper } from '@/composables/global-methods';

    const activeTab = ref('information');
    const route = useRoute();
    const workshopOrderId = route.params.workshopOrderId ? parseInt(route.params.workshopOrderId) : null;
    const imageCommandId = ref(route.params.imagesCommadeId ? parseInt(route.params.imagesCommadeId) : null);
    const infoTab = ref(null);
    const workshopInfoId = ref(null);
    const workshopOrder = ref({});
    const workshopInfo = ref({});
    const imageCommande = ref({});
    const lastprogressReporting = ref({});
    const loading = ref(false);

    const getWorkshopOrder = async () => {
        loading.value = true;
        if (workshopOrderId) {
            workshopOrder.value = await workshopService.getWorkshopOrder(workshopOrderId);
            workshopInfo.value = workshopOrder.value.workshopInformation;
            workshopInfoId.value = workshopInfo.value.id;
            imageCommande.value = workshopOrder.value.imageCommand;
            imageCommandId.value = imageCommande.value.id;

            if (workshopInfo.value.dateEndAtelierPrev) {
                const formattedPrev = Helper.FormatDate(workshopInfo.value.dateEndAtelierPrev, 'YYYY-MM-DD');
                formData.value.infoCommande.dateFinAtelierPrev = formattedPrev;
                workshopInfo.value.dateEndAtelierPrev = formattedPrev;
            }

            if (workshopOrder.value.dateEndFinition) {
                const formatted = Helper.FormatDateTime(workshopOrder.value.dateEndFinition, 'YYYY-MM-DDTHH:mm');
                formData.value.infoCommande.dateFinTheo = formatted;
                workshopInfo.value.expectedEndDate = formatted;
            }
        } else if (imageCommandId.value) {
            const res = await axiosInstance.get(`/api/image-command/${imageCommandId.value}`);
            imageCommande.value = res.data.response;
        }
        loading.value = false;
    };

    onMounted(getWorkshopOrder);

    const tabs = [
        { id: 'information', label: 'Information atelier' },
        { id: 'image', label: 'Image' },
        { id: 'historique', label: 'Historique stockage RN' }
    ];
    const formData = ref({
        infoCommande: {
            dateCmdAtelier: '', // visualisé
            dateFinTheo: '', // visualisé
            dateFinAtelierPrev: '', // visualisé
            delaisProd: '', // visualisé
            pourcentCommande: '', // visualisé
            deviseAchat: '',
            largeurCmd: '', // visualisé
            largeurReelle: '', // visualisé
            longueurCmd: '', // visualisé
            longueurReelle: '', // visualisé
            srfCmd: '', // visualisé
            srfReelle: '', // visualisé
            anneeGrilleTarif: '' // visualisé
        },
        currencyId: 1, // visualisé
        tarifSpecial: false, // visualisé
        prixAchat: [],
        reductionTapis: '', // visualisé
        complexiteAtelier: false, // visualisé
        multiLevelAtelier: false, // visualisé
        formeSpeciale: false, // visualisé
        tapisDuProjet: {
            fabricant: '', // visualisé
            typeCommande: '',
            rn: '', // visualisé
            exemplaire: '' // visualisé
        },
        prixAchatTapis: {
            auM2: '32', // non visualisé à la création
            cmd: '2.88', // non visualisé à la création
            theorique: '2.88', // non visualisé à la création
            facture: '0' // non visualisé à la création
        },
        others: {
            penalite: '0', // non visualisé à la création
            transport: '0', // non visualisé à la création
            taxe: '0', // non visualisé à la création
            margeBrute: '0', // non visualisé à la création
            referenceSurFacture: '0', // non visualisé à la création
            numeroDuFacture: null // non visualisé à la création
        },
        dateValidationClient: '', // visualisé
        disponibleVente: false, // visualisé
        envoye: false, // visualisé
        receptionParis: false // visualisé
    });
    const changeTab = (tabId) => {
        activeTab.value = tabId;
    };

    const enregistrer = () => {
        infoTab.value?.saveWorkshopInformation();
    };

    const changeLastProgressReporting = (event) => {
        lastprogressReporting.value = event;
        console.log('lastOne: ', props.lastprogressReporting);
    };

    const commandeAtelier = () => {
        infoTab.value?.commandeAtelier();
    };
</script>
<style scoped lang="scss">
    .tapis-container {

        margin: 0 auto;
        font-family: Arial, sans-serif;
    }

    .header {
        padding: 15px 0;
        border-bottom: 1px solid #eaeaea;

        h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            text-align: center;
        }
    }

    .content {
        display: flex;
        padding: 20px 0;
        gap: 20px;
    }

    .main-content {
        flex: 1;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .tab-content {
        padding: 20px;
        background-color: white;
        border: 1px solid #E0E6ED;
        border-top: none;
        border-radius: 5px;
    }

    @media (max-width: 768px) {
        .content {
            flex-direction: column;
        }
    }
</style>
