<template>
    <div>
        <d-animated-skeleton :loading="loading" :marginTop="5"></d-animated-skeleton>
        <div class="layout-px-spacing mt-4"  v-if="!loading">
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
                                <InformationAtelier :workshop-info-id="workshopInfoId" 
                                                    :workshop-info="workshopInfo"
                                                    :image-commande="imageCommande"
                                                    :order-id="workshopOrderId" 
                                                    ref="infoTab"
                                                    :imageCommandId="imageCommandId"
                                                    v-if="activeTab === 'information'"/>
                                <ImageTab :imageCommandId="imageCommandId" :imageCommande="imageCommande" :workshopOrderId="workshopOrderId" v-if="activeTab === 'image' && workshopOrderId"/>
                                <HistoriqueTab :workshopOrderId="workshopOrderId" v-if="activeTab === 'historique' && workshopOrderId"/>
                            </div>
                        </div>
                        <div class="col-md-4">
<!--                            <HistoryPanel v-if="workshopOrderId"></HistoryPanel>-->
                            <d-progress-report-histories  v-if="workshopOrderId" :workshopOrderId="workshopOrderId"></d-progress-report-histories>
                            <div class="status-options">
                                <RadioButton class="w-100" v-model="formData.disponibleVente" :value="true"
                                             label="Disponible à la vente"/>
                                <RadioButton class="w-100" v-model="formData.envoye" :value="true" label="Envoyé"/>
                                <RadioButton class="w-100" v-model="formData.receptionParis" :value="true"
                                             label="Réception Paris"/>
                            </div>
    
                            <div class="action-buttons">
                                <button class="save-btn btn btn-outline-dark   text-uppercase w-100 my-2"
                                        @click="enregistrer">
                                    ENREGISTRER
                                </button>
                                <button class="command-btn btn btn-custom  text-uppercase w-100 my-2"
                                        @click="commandeAtelier">COMMANDE ATELIER
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {onMounted, ref} from 'vue';
import {useRoute} from 'vue-router';
import TabNavigation from './TabNavigation.vue';
import InformationAtelier from '../../components/workshop/tabs/InformationAtelier.vue';
import ImageTab from '../../components/workshop/tabs/ImageTab.vue';
import HistoriqueTab from '../../components/workshop/tabs/HistoriqueTab.vue';
import dPageTitle from '../../components/common/d-page-title.vue';
import RadioButton from '@/components/workshop/ui/RadioButton.vue';
import DProgressReportHistories from "@/components/workshop/_partial/d-progress-report-histories.vue";
import workshopService from "@/Services/workshop-service.js";
import DPageTitle from "@/components/common/d-page-title.vue";
import DAnimatedSkeleton from "@/components/base/d-animated-skeleton.vue";
import axiosInstance from "@/config/http.js";

const activeTab = ref('information');
const route = useRoute();
const workshopOrderId = route.params.workshopOrderId ? parseInt(route.params.workshopOrderId) : null;
const imageCommandId = ref(route.params.imagesCommadeId ? parseInt(route.params.imagesCommadeId) : null);
const infoTab = ref(null);
const workshopInfoId = ref(null);
const workshopOrder = ref({})
const workshopInfo = ref({})
const imageCommande = ref({})
const loading = ref(false);

const getWorkshopOrder = async () => {
    loading.value = true;
    if (workshopOrderId) {
        workshopOrder.value = await workshopService.getWorkshopOrder(workshopOrderId);
        workshopInfo.value = workshopOrder.value.workshopInformation;
        workshopInfoId.value = workshopInfo.value.id;
        imageCommande.value = workshopOrder.value.imageCommand;
        imageCommandId.value = imageCommande.value.id;
    }else if (imageCommandId) {
        const res = await axiosInstance.get(`/api/image-command/${imageCommandId}`);
        imageCommande.value = res.data.response;
    }
    loading.value = false;
}

onMounted(getWorkshopOrder)

const tabs = [
    {id: 'information', label: 'Information atelier'},
    {id: 'image', label: 'Image'},
    {id: 'historique', label: 'Historique stockage RN'}
];
const formData = ref({
    disponibleVente: false,
    envoye: false,
    receptionParis: true
});
const changeTab = (tabId) => {
    activeTab.value = tabId;
};

const enregistrer = () => {
    infoTab.value?.saveWorkshopInformation();
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
