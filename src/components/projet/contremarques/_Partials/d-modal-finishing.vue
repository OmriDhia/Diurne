<template>
    <div>
        <d-base-modal id="modalManageFinishing" title="Finition" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center justify-content-center">
                        <div class="col-sm-9">
                            <d-input label="Couleur de tissu" v-model="data.fabricColor"></d-input>
                        </div>
                    </div>
                    <div class="row p-1 justify-content-center">
                        <div class="col-sm-8">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="checkbox-default custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="fringe" v-model="data.fringe"/>
                                        <label class="custom-control-label" for="fringe">Frange</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkbox-default custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="withoutBanking" v-model="data.withoutBanking"/>
                                        <label class="custom-control-label" for="withoutBanking">Sans backing(tufté)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkbox-default custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="noBinding" v-model="data.noBinding"/>
                                        <label class="custom-control-label" for="noBinding">No Binding</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="checkbox-default custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="mzCarved" v-model="data.mzCarved"/>
                                        <label class="custom-control-label" for="mzCarved">MZ Carved</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center justify-content-center">
                        <div class="col-sm-9">
                            <d-input label="Autre Signature Carved" v-model="data.otherCarvedSignature"></d-input>
                        </div>
                    </div>
                    <div class="row mt-2 mb-2 align-items-center justify-content-center">
                        <span class="border-bottom border-dark border-2"></span>
                    </div>
                    <div class="row p-1 align-items-center justify-content-center">
                        <div class="col-md-4">
                            Hauteur standard de velours
                        </div>
                        <div class="col-md-2">
                            <input class="form-control" v-model="data.standardVelvetHeight">
                        </div>
                        <div class="col-md-1">
                            mm
                        </div>
                    </div>
                    <div class="row p-1 align-items-center justify-content-center">
                        <div class="col-md-4">
                            Hauteur spéciale de velours
                        </div>
                        <div class="col-md-2">
                            <input class="form-control" v-model="data.specialVelvetHeight">
                        </div>
                        <div class="col-md-1">
                            mm
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="saveFinishing">Enregistrer</button>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { ref } from "vue";
    import VueFeather from 'vue-feather';
    import axiosInstance from "../../../../config/http";
    import dInput from "../../../../components/base/d-input.vue";
    import dBaseModal from "../../../../components/base/d-base-modal.vue";
    import dPanelTitle from "../../../../components/common/d-panel-title.vue";
    import {formatErrorViolations, Helper} from "../../../../composables/global-methods";
  
    const props = defineProps({
        carpetDesignOrderId:{
            type: Number
        },
        id_di:{
            type: Number
        },
    });
    
    const data = ref({
        fabricColor: "",
        fringe: true,
        withoutBanking: true,
        noBinding: true,
        mzCarved: true,
        otherCarvedSignature: "",
        standardVelvetHeight: "",
        specialVelvetHeight: ""
    });
    const error = ref({});
    
    const saveFinishing = async () =>{
        try{
            /*if(props.carpetDesignOrderId){
                const url = `/api/carpetDesignOrder/${props.carpetDesignOrderId}/createVariation`;
                const res = await axiosInstance.post(url,data.value);
                window.showMessage("Ajout avec succées.");
            }*/
            document.querySelector("#modalCreateVariation .btn-close").click();
            location.href = `/projet/contremarques/projectdis/${props.id_di}`;
            initData();
        }catch (e){
            if(e.response.data.violations){
                error.value = formatErrorViolations(e.response.data.violations);
            }
            window.showMessage(e.message,'error')
        }
    };
    
    const initData = () => {
        data.value = {
            variation_image_reference: "",
            variation: ""
        };
    };
    
    const emit = defineEmits(['onClose']);

    const handleClose = () => {
        initData();
        error.value = {};
        emit('onClose')
    }
</script>
