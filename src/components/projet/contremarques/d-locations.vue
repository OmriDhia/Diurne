<template>
    <div class="row align-items-center">
        <d-modal-manage-locations ref="manageLocations" :locationData="selectedLocation"
            :contremarqueId="contremarqueId" :options="props.locationOptions"
            @onClose="handleClose" :saveAndStay="false" @addLocation="addNewLocation"></d-modal-manage-locations>
        <div class="row pe-2 ps-2">
            <div class="col-auto">
                <button class="btn btn-custom pe-5 ps-5" data-bs-toggle="modal" data-bs-target="#modalLocationManage"
                    @click="initForm">
                    Nouveau
                </button>
            </div>
        </div>

        <perfect-scrollbar tag="div" class="h-200 row pe-2 ps-2 mt-2"
            :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 200, suppressScrollX: true }">
            <template v-for="location in locations">
                <div class="col-md-12 col-xl-6">
                    <div class="row mt-2 mb-2 align-items-center block-custom-border ms-1">
                        <div class="col-md-4 pe-1">
                            <img :src="$Helper.getImagePathNew(location.vignette)" class="w-100">
                        </div>
                        <div class="col-md-8 ps-1 ">
                            <ul class="list-group">
                                <li class="text-black text-capitalize">{{ location.description }}</li>
                                <li class="text-black">Date de création: {{
                                    $Helper.FormatDate(location.created_at.date, "DD/MM/YYYY") }}
                                </li>
                                <li class="text-black">Prix Min: {{ $Helper.FormatPrice(location.price_min,
                                    "DD/MM/YYYY")
                                }}
                                </li>
                                <li class="text-black">Prix Max: {{ $Helper.FormatPrice(location.price_max,
                                    "DD/MM/YYYY")
                                }}
                                </li>
                                <li class="pt-1"><d-btn-outlined label="Commande" icon="arrow-right"
                                        buttonClass="ps-2"></d-btn-outlined></li>
                                <li class="pt-1 d-flex">
                                    <d-btn-outlined label="B1234" icon="arrow-right"
                                        buttonClass="ps-2"></d-btn-outlined>
                                    <div class="col-auto p-1 pe-0">
                                        <d-delete :api="'/api/locations/' + location.location_id"
                                            @isDone="handleClose"></d-delete>
                                    </div>
                                    <div class="col-auto p-1 ps-0">
                                        <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle"
                                            data-bs-toggle="modal" data-bs-target="#modalLocationManage"
                                            @click="updateLocation(location)">
                                            <vue-feather type="save" size="14"></vue-feather>
                                        </button>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row pe-0 ps-3 justify-content-between">
                        <div class="col-auto p-0">
                            <button class="btn btn-custom pe-2 ps-2 h6">Voir Images</button>
                        </div>
                        <div class="col-auto p-0">
                            <button class="btn btn-custom pe-2 ps-2 h6">Voir Devis</button>
                        </div>
                    </div>
                </div>
            </template>
        </perfect-scrollbar>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import VueFeather from 'vue-feather';
import dDelete from "../../common/d-delete.vue";
import dBtnOutlined from "../../base/d-btn-outlined.vue";
import contremarqueService from "../../../Services/contremarque-service";
import dModalManageLocations from "./_Partials/d-modal-manage-locations.vue";

const props = defineProps({
    contremarqueId: {
        type: [Number, null],
        required: true
    },
    locationOptions: {
        type: Object,
    }
});

const locations = ref([]);
const selectedLocation = ref(null);
const manageLocations = ref(null);
const emit = defineEmits(['updateLocation']);

const getLocations = async () => {
    try {
        locations.value = await contremarqueService.getLocationsByContremarque(props.contremarqueId);
        emit("updateLocation",locations.value.length);
    } catch (error) {
        console.error('Failed to fetch profiles:', error);
    }
};

const updateLocation = (location) => {
    selectedLocation.value = location;
};
const addNewLocation = (location) => {
    locations.value.push(location)
    emit("updateLocation",locations.value.length);
};

const handleClose = () => {
    selectedLocation.value = null;
    getLocations();
};

onMounted(() => {
    if (props.contremarqueId) {
        getLocations();
    }
});

const initForm = () => {
    selectedLocation.value = null;
    manageLocations.value.initData();
};

watch(() => props.contremarqueId, getLocations);
</script>
<style scoped>
ul {
    list-style-type: none;
}
</style>
