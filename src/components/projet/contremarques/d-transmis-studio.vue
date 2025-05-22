<template>
    <div class="col-12">
        <div class="row justify-content-end align-items-start">
            <div class="col-auto" v-if="canShowTransmisStudio">
                <button class="btn btn-custom ps-4 pe-4 text-uppercase font-size-0-7" @click="transmisStudio">Transmettre la demande au studio</button>
            </div>
        </div>
        <div class="row justify-content-end align-items-center mt-3" v-if="canShow">
            <div class="col-lg-4 col-md-12">
                <button class="btn btn-custom ps-4 pe-3 text-uppercase font-size-0-7">Modification DI (STUDIO)</button>
            </div>
        </div>
        <div class="row justify-content-end align-items-start mt-1" v-if="canShow">
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
        canShow: {
            type: Boolean,
            default: false
        },
    });

    const store = useStore();
    const emit = defineEmits(['transmisStudio']);
    const canShowTransmisStudio = computed(() => (store.getters.isCommertial || store.getters.isSuperAdmin) && !store.getters.isFinStatus);
    const canCreateVariation = computed(() => (store.getters.isDesigner || store.getters.isSuperAdmin) && !store.getters.isFinStatus);
    
    const transmisStudio = () => {
        emit('transmisStudio',carpetStatus.transmisId);
    }
</script>
