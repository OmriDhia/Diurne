<template>
    <div class="row align-items-center justify-content-between pt-5">
        <div class="col-auto"><button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#modalAddSample">Nouveau</button></div>
        <div class="col-auto">
            <div class="row align-items-center">
                <div class="col-auto"><d-input v-model="existedRn" label="RN existant"></d-input></div>
                <div class="col-auto"><button class="btn btn-custom" >Ajouter</button></div>
            </div>
        </div>
        <d-modal-add-sample :contremarqueId="props.contremarqueId"></d-modal-add-sample>
        <div class="col-md-12">
            <div class="table-responsive pt-5">
                <table role="table" class="table table-bordered">
                    <thead role="rowgroup">
                    <tr role="row">
                        <th class="text-center"><div>Emplacement</div></th>
                        <th class="text-center"><div>qualité</div></th>
                        <th class="text-center"><div>lrg.</div></th>
                        <th class="text-center"><div>lng.</div></th>
                        <th class="text-center"><div>Etat</div></th>
                        <th class="text-center"><div>RN</div></th>
                        <th class="text-center"><div>N° DI CMD</div></th>
                        <th class="text-center"><div>Coll.</div></th>
                        <th class="text-center"><div>Mod.</div></th>
                        <th class="fixed-width"></th>
                    </tr>
                    </thead>
                    <tbody role="rowgroup">
                    <tr v-for="item in samples" :key="item.name" role="row">
                        <td role="cell">{{ item.location }}</td>
                        <td role="cell">{{ item.quality }}</td>
                        <td role="cell">{{ $Helper.FormatNumber(item?.dimension.width) }}</td>
                        <td role="cell">{{ $Helper.FormatNumber(item?.dimension.height) }}</td>
                        <td role="cell">{{ statusNames[item.status] }}</td>
                        <td role="cell">{{ item.rn }}</td>
                        <td role="cell">{{ item.diCommandNumber }}</td>
                        <td role="cell">{{item.collection}}</td>
                        <td role="cell">{{item.model}}</td>
                        <td role="cell" class="fixed-width">
                            <div class="row align-items-center gap-1">
                                <div class="col-auto"><vue-feather type="search" stroke-width="1" class="cursor-pointer"></vue-feather></div>
                                <div class="col-auto"><button class="btn btn-custom p-0 font-size-0-6" >Transmettre ADV</button></div>
                                <div class="col-auto"><d-delete></d-delete></div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import VueFeather from 'vue-feather';
import dInput from '../../../base/d-input.vue';
import dDelete from "../../../common/d-delete.vue";
import dBtnOutlined from "../../../base/d-btn-outlined.vue";
import contremarqueService from "../../../../Services/contremarque-service.js";
import {statusNames} from "../../../../composables/constants.js";
import dModalManageLocations from "../_Partials/d-modal-manage-locations.vue";
import DModalAddSample from "./d-modal-add-sample.vue";

const props = defineProps({
    carpetDesignOrderId: {
        type: [Number, null],
        required: true
    },
    contremarqueId: {
        type: [Number, null],
        required: true
    }
});

const samples = ref([]);
const existedRn = ref("");

const getSamples = async () => {
    try {
        const res = await contremarqueService.getSamplesByCarpetDesignOrderId(props.carpetDesignOrderId);
        samples.value = res.samples;
    } catch (error) {
        console.error('Failed to fetch samples:', error);
    }
};

/*const updateLocation = (location) => {
    selectedLocation.value = location;
};
const addNewLocation = (location) => {
    locations.value.push(location)
    emit("updateLocation",locations.value.length);
};*/

const handleClose = () => {
    selectedLocation.value = null;
    getSamples();
};

onMounted(() => {
    if (props.carpetDesignOrderId) {
        getSamples();
    }
});

const initForm = () => {
    /*selectedLocation.value = null;
    manageLocations.value.initData();*/
};

watch(() => props.carpetDesignOrderId, getSamples);
</script>
<style scoped>
.fixed-width {
    width: 250px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
