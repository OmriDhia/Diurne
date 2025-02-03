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
    import {formatErrorViolations} from "../../../../composables/global-methods";
    import { customerInstructionObject } from "../../../../composables/constants";
    import contremarqueService from "../../../../Services/contremarque-service";

    const props = defineProps({
        carpetDesignOrderId:{
            type: Number
        },
        customerInstructionId:{
            type: Number
        },
        finishingData:{
            type: Object
        },
    });
    
    const emit = defineEmits(['onClose','updateCustomerInstructionId']);
    const finishingId = ref(null);
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
            let customerInstructionId = props.customerInstructionId;
            let customerInstruction = Object.assign({}, customerInstructionObject);
            if(!customerInstructionId && props.carpetDesignOrderId){
                const res = await contremarqueService.addUpdatecustomerInstruction(props.carpetDesignOrderId, customerInstruction);
                customerInstructionId = parseInt(res.id);
                emit('updateCustomerInstructionId',customerInstructionId)
            }

            if(customerInstructionId){
                if(finishingId.value){
                    const res = await axiosInstance.put(`/api/customerInstruction/${customerInstructionId}/finishing/${finishingId.value}/update`,data.value);
                    window.showMessage("Mise a jour avec succées.");
                }else{
                    const res = await axiosInstance.post(`/api/customerInstruction/${customerInstructionId}/finishing/create`,data.value);
                    window.showMessage("Ajout avec succées.");
                    customerInstruction.finitionInstructionId = res.data.response.id;
                    if(props.carpetDesignOrderId){
                        const res = await contremarqueService.addUpdatecustomerInstruction(props.carpetDesignOrderId, customerInstruction, customerInstructionId);
                    }
                }
            }
            document.querySelector("#modalManageValidatedSimple .btn-close").click();
        }catch (e){
            console.log(e);
            if(e.response?.data?.violations){
                error.value = formatErrorViolations(e.response.data.violations);
            }
            window.showMessage(e.message,'error')
        }
    };
    
    const handleClose = () => {
        error.value = {};
        emit('onClose')
    }
</script>
