<template>
    <div class="col-12">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="row justify-content-between align-items-center mt-3">
                    <div class="col-lg-8 col-md-12">
                        <d-input label="N° de la commande"></d-input>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <d-btn-outlined label="Contraintes et remarque" icon="arrow-right" buttonClass="ps-1 font-size-0-6"></d-btn-outlined>
                    </div>
                </div>
                <div class="row justify-content-between align-items-center mt-3">
                    <div class="col-lg-8 col-md-12">
                        <d-input label="Transmi. ADV"></d-input>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <d-btn-outlined label="Ech. Validée de ref" icon="arrow-right" buttonClass="ps-1 font-size-0-6"></d-btn-outlined>
                    </div>
                </div>
                <div class="row justify-content-between align-items-center mt-3">
                    <div class="col-lg-8 col-md-12">
                        <d-input type="date" label="Validation client"></d-input>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <d-btn-outlined label="Finition" icon="arrow-right" buttonClass="ps-1 font-size-0-8"></d-btn-outlined>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="row justify-content-between">
                    <div class="col-12">
                        <div class="text-black p-0 pb-2">Commentaire client</div>
                    </div>
                    <div class="col-12">
                        <textarea class="w-100 h-130-forced block-custom-border"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-between align-items-start mt-3">
            <div class="col-auto">
                <d-btn-outlined label="Editer la demande de l'image projet" icon="arrow-right" buttonClass="ps-1 font-size-0-8"></d-btn-outlined>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="row justify-content-between align-items-start mt-1 pe-2" v-if="canShowTransmisAdv">
                    <button class="btn btn-custom w-100 text-uppercase font-size-0-7">Transmettre l'image à l'ADV</button>
                </div>
                <div class="row justify-content-between align-items-start mt-1  pe-2" v-if="canCreateVariation">
                    <button class="btn btn-custom w-100 text-uppercase font-size-0-7"  data-bs-toggle="modal" data-bs-target="#modalCreateVariation">Créer une variation</button>
                </div>
            </div>
        </div>
        <d-modal-create-variation :carpetDesignOrderId="props.carpetDesignOrderId"></d-modal-create-variation>
    </div>
</template>

<script setup>
    import { ref, watch, onMounted, computed} from 'vue';
    import { useStore } from "vuex";
    import VueFeather from 'vue-feather';
    import dInput from "../../base/d-input.vue";
    import dBtnOutlined from "../../base/d-btn-outlined.vue";
    import {carpetStatus} from "../../../composables/constants";
    import dModalCreateVariation from "./_Partials/d-modal-create-variation.vue"

    const props = defineProps({
        carpetDesignOrderId: {
            type: Number,
        },
    });

    const store = useStore();
    const emit = defineEmits(['transmisAdv']);
    const canShowTransmisAdv = computed(() => (store.getters.isCommertial || store.getters.isSuperAdmin) && !store.getters.isFinStatus);
    const canCreateVariation = computed(() => (store.getters.isDesigner || store.getters.isSuperAdmin) && !store.getters.isFinStatus);
    const transmisStudio = () => {
        emit('transmisAdv',carpetStatus.transmisAdvId);
    }
</script>
