<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title icon="carpet" :title="`Tapis (${workshopOrder?.workshopInformation?.rn})`"></d-page-title>
        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-4">
                <div class="row">
                    <!-- Left column: Image -->
                    <div class="col-md-5">
                        <img :src="$Helper.getImagePath(workshopOrder?.imageCommand?.images?.[0]?.attachment)" class="card-img-top cursor-pointer"
                             alt="Image Preview" />
                    </div>

                    <!-- Right column: Form -->
                    <div class="col-md-7">
                        <form>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <d-input label="RN" v-model="data.rn" :disabled="true"></d-input>
                                </div>
                                <div class="col-md-6">
                                    <d-model-dropdown v-model="data.model" :disabled="true" :hideBtn="true"></d-model-dropdown>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                   <d-collections-dropdown v-model="data.collection" :disabled="true" :hideBtn="true"></d-collections-dropdown>
                                </div>
                                <div class="col-md-6">
                                    <d-qualities-dropdown v-model="data.quality" :disabled="true"></d-qualities-dropdown>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <d-input v-model="data.longueur" label="Longueur" :disabled="true"></d-input>
                                </div>
                                <div class="col-md-6">
                                    <d-input label="Materiaux" :disabled="true"></d-input>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <d-input v-model="data.largeur" label="Largeur" :disabled="true"></d-input>
                                </div>
                                <div class="col-md-6">
                                    <d-input v-model="data.status" label="Etat" :disabled="true"></d-input>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <d-input v-model="data.srf" label="SRF" :disabled="true"></d-input>
                                </div>
                                <div class="col-md-6">
                                    <d-location-dropdown :disabled="true" :contremarque-id="data.contremarqueId" v-model="data.location"></d-location-dropdown>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-custom w-100">EDITION ETIQUETTE AVEC CODE BARRE</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-custom w-100" @click="goToWorkshopOrder">INFO ATELIER</button>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-custom w-100">EDITION ETIQUETTE</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-custom w-100">HISTO STOCKAGE</button>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-custom w-100">NOUV. DEPLACEMENT</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-custom w-100">IMAGE</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import dInput from '@/components/base/d-input.vue';
import {onMounted, ref} from 'vue';
import {useRoute, useRouter} from "vue-router";
import workshopService from "@/Services/workshop-service.js";
import DPageTitle from "@/components/common/d-page-title.vue";
import DModelDropdown from "@/components/projet/contremarques/dropdown/d-model-dropdown.vue";
import DCollectionsDropdown from "@/components/projet/contremarques/dropdown/d-collections-dropdown.vue";
import DQualitiesDropdown from "@/components/projet/contremarques/dropdown/d-qualities-dropdown.vue";
import DLocationDropdown from "@/components/projet/contremarques/dropdown/d-location-dropdown.vue";

const route = useRoute();
const router = useRouter();
const workshopOrderId = parseInt(route.params.workshopOrderId);
const workshopOrder = ref({})
const data = ref({
    rn: "",
    longueur: "",
    largeur: "",
    model: "",
    collection: "",
    quality: "",
    status: "",
    location: "",
    contremarqueId:"",
    srf: "",
    materials: ""
})
const loading = ref(false);

const getWorkshopOrder = async () => {
    loading.value = true;
    workshopOrder.value = await workshopService.getWorkshopOrder(workshopOrderId);
    loading.value = false;
    console.log(workshopOrder.value);
    data.value = {
        rn: workshopOrder.value?.workshopInformation?.rn,
        longueur: workshopOrder.value?.workshopInformation?.orderedWidth,
        largeur: workshopOrder.value?.workshopInformation?.orderedHeigh,
        model: workshopOrder.value?.imageCommand?.carpetSpecification?.model?.id,
        collection: workshopOrder.value?.imageCommand?.carpetSpecification?.collection?.id,
        quality: workshopOrder.value?.imageCommand?.carpetSpecification?.quality?.id,
        status: workshopOrder.value?.imageCommand?.rn,
        location: workshopOrder.value?.imageCommand?.carpetDesignOrder?.location?.location_id,
        contremarqueId: workshopOrder.value?.imageCommand?.carpetDesignOrder?.location?.contremarque_id,
        srf: workshopOrder.value?.workshopInformation?.orderedSurface,
        materials: ""
    };
}

onMounted(getWorkshopOrder)

const goToWorkshopOrder = () => {
    router.push({name: "updateCarpetWorkshop",params:{workshopOrderId:workshopOrderId}})
}

</script>

<style scoped>
.image-placeholder {
    width: 250px;
    height: 300px;
    border: 1px solid #ccc;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 14px;
    color: #999;
}
.button-group .btn {
    margin: 5px 5px 0 0;
}
</style>
