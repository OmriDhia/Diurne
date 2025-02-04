<template>
    <div>
        <d-base-modal id="modalManageConstraint" title="Contrainte et Remarque" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-end">
                                    Plan transmis
                                </div>
                                <div class="col-md-auto">
                                    <div class="checkbox-default custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="libTransmittedPlan" v-model="data.transmittedPlan"/>
                                        <label class="custom-control-label" for="libTransmittedPlan"></label>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control" v-model="data.libTransmittedPlan">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-end">
                                    Fosse
                                </div>
                                <div class="col-md-auto">
                                    <div class="checkbox-default custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="pit" v-model="data.pit"/>
                                        <label class="custom-control-label" for="pit"></label>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control" v-model="data.libPit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-end">
                                    Hauteur de porte
                                </div>
                                <div class="col-md-auto">
                                    <div class="checkbox-default custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="lineHeight" v-model="data.lineHeight"/>
                                        <label class="custom-control-label" for="lineHeight"></label>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control" v-model="data.libLineHeight">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-end">
                                    Autre tapis dans la pièce
                                </div>
                                <div class="col-md-auto">
                                    <div class="checkbox-default custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="otherCarpetInTheRoom" v-model="data.otherCarpetInTheRoom"/>
                                        <label class="custom-control-label" for="otherCarpetInTheRoom"></label>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control" v-model="data.libOtherCarpetInTheRoom">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center justify-content-center mt-3">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="row  align-items-center">
                                        <div class="col-md-auto">
                                            longueur
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="checkbox-default custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="miniLength" v-model="data.miniLength"/>
                                                <label class="custom-control-label" for="miniLength">Min</label>
                                            </div>
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="checkbox-default custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="maxiLength" v-model="data.maxiLength"/>
                                                <label class="custom-control-label" for="maxiLength">Max</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row align-items-center">
                                        <div class="col-md-auto">
                                           largeur
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="checkbox-default custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="miniWidth" v-model="data.miniWidth"/>
                                                <label class="custom-control-label" for="miniWidth">Min</label>
                                            </div>
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="checkbox-default custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="maxiWidth" v-model="data.maxiWidth"/>
                                                <label class="custom-control-label" for="maxiWidth">Max</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row p-1 align-items-center justify-content-center mt-3">
                        <div class="col-md-8">
                            <div class="col-md-12">
                                Distances aux murs:
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="row  align-items-center">
                                        <d-input label="Haut" v-model="data.dstWallHeight"></d-input>
                                        <d-input label="Bas" v-model="data.dstWallDown"></d-input>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row align-items-center">
                                        <d-input label="Droite" v-model="data.dstWallRight"></d-input>
                                        <d-input label="Gauche" v-model="data.dstWallLeft"></d-input>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="saveConstraints">Enregistrer</button>
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
    import { formatErrorViolations } from "../../../../composables/global-methods";
    import { customerInstructionObject } from "../../../../composables/constants";
    import contremarqueService from "../../../../Services/contremarque-service";

    const props = defineProps({
        carpetDesignOrderId:{
            type: Number
        },
        customerInstructionId:{
            type: Number
        },
        constraintData:{
            type: Object
        },
    });

    const emit = defineEmits(['onClose','updateCustomerInstructionId']);
    const constraintId = ref(null);
    const data = ref({
        transmittedPlan: true,
        libTransmittedPlan: "",
        pit: false,
        libPit: "",
        lineHeight: false,
        libLineHeight: "",
        specialThickness: false,
        libSpecialThickness: "",
        otherCarpetInTheRoom: false,
        libOtherCarpetInTheRoom: "",
        miniLength: false,
        maxiLength: false,
        miniWidth: false,
        maxiWidth: false,
        dstWallHeight: '0.0',
        dstWallDown: '0.0',
        dstWallLeft: '0.0',
        dstWallRight: '0.0'
    });
    const error = ref({});
    
    const saveConstraints = async () =>{
        try{
            let customerInstructionId = props.customerInstructionId;
            let customerInstruction = Object.assign({}, customerInstructionObject);
            if(!customerInstructionId && props.carpetDesignOrderId){
                const res = await contremarqueService.addUpdatecustomerInstruction(props.carpetDesignOrderId, customerInstruction);
                customerInstructionId = parseInt(res.id);
                emit('updateCustomerInstructionId',customerInstructionId)
            }
            
            if(customerInstructionId){
                if(constraintId.value){
                    const res = await axiosInstance.put(`/api/customerInstruction/${customerInstructionId}/constraints/${constraintId.value}/update`,data.value);
                    window.showMessage("Mise a jour avec succées.");
                }else{
                    const res = await axiosInstance.post(`/api/customerInstruction/${customerInstructionId}/constraints/create`,data.value);
                    window.showMessage("Ajout avec succées.");
                    customerInstruction.constraintInstructionId = res.data.response.id;
                    if(props.carpetDesignOrderId){
                        const res = await contremarqueService.addUpdatecustomerInstruction(props.carpetDesignOrderId, customerInstruction, customerInstructionId);
                    }
                }
            }
            document.querySelector("#modalManageConstraint .btn-close").click();
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
