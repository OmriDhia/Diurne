<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title title="TAPIS (M0025)"></d-page-title>

        <div class="row">
            <div class="col-md-8">
                <TabNavigation
                    :tabs="tabs"
                    :activeTab="activeTab"
                    @change-tab="changeTab"
                />

                <div class="tab-content">
                    <InformationAtelier ref="infoTab" :imageCommandId="imageCommandId" v-if="activeTab === 'information'" />
                    <ImageTab v-if="activeTab === 'image'" />
                    <HistoriqueTab v-if="activeTab === 'historique'" />
                </div>
            </div>
            <div class="col-md-4">
                <HistoryPanel />
                <div class="status-options">
                    <RadioButton class="w-100" v-model="formData.disponibleVente" :value="true"
                                 label="Disponible à la vente" />
                    <RadioButton class="w-100" v-model="formData.envoye" :value="true" label="Envoyé" />
                    <RadioButton class="w-100" v-model="formData.receptionParis" :value="true"
                                 label="Réception Paris" />
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

</template>
<script setup>
    import { ref } from 'vue';
    import { useRoute } from 'vue-router';
    import TabNavigation from './TabNavigation.vue';
    import InformationAtelier from '../../components/workshop/tabs/InformationAtelier.vue';
    import ImageTab from '../../components/workshop/tabs/ImageTab.vue';
    import HistoriqueTab from '../../components/workshop/tabs/HistoriqueTab.vue';
    import HistoryPanel from './HistoryPanel.vue';
    import dPageTitle from '../../components/common/d-page-title.vue';
    import RadioButton from '@/components/workshop/ui/RadioButton.vue';

    const activeTab = ref('information');
    const route = useRoute();
    const imageCommandId = parseInt(route.params.imagesCommadeId, 10);
    const infoTab = ref(null);

    const tabs = [
        { id: 'information', label: 'Information atelier' },
        { id: 'image', label: 'Image' },
        { id: 'historique', label: 'Historique stockage RN' }
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
