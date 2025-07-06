<template>
    <div class="col-12">
        <div class="row justify-content-between align-items-start">
            <!--div class="col-auto">
                <div class="d-flex w-100">
                    <div class="custom-control custom-radio">
                        <input type="checkbox" class="custom-control-input" id="jpegOnly" name="jpegOnly"/>
                        <label class="custom-control-label text-black" for="jpegOnly">
                            Jpeg uniquement</label>
                    </div>
                </div>
                <div class="d-flex w-100">
                    <div class="custom-control custom-radio">
                        <input type="checkbox" class="custom-control-input" id="impression" name="impression"/>
                        <label class="custom-control-label text-black" for="impression">
                            Impression</label>
                    </div>
                </div>
                <div class="d-flex w-100">
                    <div class="custom-control custom-radio">
                        <input type="checkbox" class="custom-control-input" id="impressionBarLane" name="impressionBarLane"/>
                        <label class="custom-control-label text-black" for="impressionBarLane">
                            Impression-barre de laine</label>
                    </div>
                </div>
            </div-->
            <div class="col-auto">
                <button class="btn btn-custom ps-4 pe-4 text-uppercase" @click="saveDI">Enregistrer</button>
            </div>
            <div class="col-auto" v-if="canShowTransmisStudio">
                <button class="btn btn-custom ps-4 pe-4 text-uppercase font-size-0-7" @click="transmisStudio">Transmettre la demande au studio</button>
            </div>
        </div>
        <div class="row justify-content-end align-items-center mt-3">
            <!--div class="col-lg-4 col-md-12">
                <d-input label="Variation"></d-input>
            </div>
            <div-- class="col-lg-4 col-md-12">
                <d-input label="Nom de l'image"></d-input>
            </div-->
            <div class="col-lg-4 col-md-12">
                <button class="btn btn-custom ps-4 pe-3 text-uppercase font-size-0-7">Modification DI (STUDIO)</button>
            </div>
        </div>
        <div class="row justify-content-end align-items-start mt-1">
            <!--div class="col-auto" v-if="canCreateVariation">
                <button class="btn btn-custom ps-4 pe-4 text-uppercase font-size-0-7" >Créer une variation</button>
            </div-->
            <div class="col-lg-4 col-md-12">
                <button class="btn btn-custom ps-4 pe-4 text-uppercase font-size-0-7">Incoherence DI STUDIO</button>
            </div>
        </div>
        
    </div>
</template>

<script setup>
    import { ref, watch, onMounted, computed } from 'vue';
    import { useStore } from "vuex";
    import VueFeather from 'vue-feather';
    import dInput from "../../base/d-input.vue";
    import {carpetStatus} from "../../../composables/constants";

    const props = defineProps({
        carpetDesignOrderId: {
            type: Number,
        },
    });

    const store = useStore();
    const emit = defineEmits(['transmisStudio','saveCarpetOrderSpecifications']);
    const canShowTransmisStudio = computed(() => (store.getters.isCommertial || store.getters.isSuperAdmin) && !store.getters.isFinStatus);
    const canCreateVariation = computed(() => (store.getters.isDesigner || store.getters.isSuperAdmin) && !store.getters.isFinStatus);
    
    const transmisStudio = () => {
        emit('transmisStudio',carpetStatus.transmisId);
    }
    const saveDI = () => {
        emit('saveCarpetOrderSpecifications');
    }
</script>
