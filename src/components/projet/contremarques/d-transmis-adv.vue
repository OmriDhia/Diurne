<template>
    <div class="col-12">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="row justify-content-between align-items-center mt-3">
                    <div class="col-lg-7 col-md-12">
                        <d-input label="N° de la commande" v-model="data.orderNumber"></d-input>
                    </div>
                    <div class="col-lg-5 col-md-12 d-flex">
                        <div class="checkbox-default custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="hasConstraints" v-model="data.hasConstraints"/>
                            <label class="custom-control-label" for="hasConstraints"></label>
                        </div>
                        <d-btn-outlined data-bs-toggle="modal" data-bs-target="#modalManageConstraint" label="Contraintes et remarque" icon="arrow-right" buttonClass="ps-1 font-size-0-6"></d-btn-outlined>
                    </div>
                    <d-modal-constraint
                        :customerInstructionId="customerInstructionId"
                        :carpetDesignOrderId="props.carpetDesignOrderId"
                        @updateCustomerInstructionId="updateCustomerInstructionId"
                    ></d-modal-constraint>
                </div>
                <div class="row justify-content-between align-items-center mt-3">
                    <div class="col-lg-7 col-md-12">
                        <d-input label="Transmi. ADV" v-model="data.transmi_adv"></d-input>
                    </div>
                    <div class="col-lg-5 col-md-12 d-flex">
                        <div class="checkbox-default custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="hasValidateSample" v-model="data.hasValidateSample"/>
                            <label class="custom-control-label" for="hasValidateSample"></label>
                        </div>
                        <d-btn-outlined data-bs-toggle="modal" data-bs-target="#modalManageValidatedSimple" label="Ech. Validée de ref" icon="arrow-right" buttonClass="ps-1 font-size-0-6"></d-btn-outlined>
                        <d-modal-validated-sample
                            :customerInstructionId="customerInstructionId"
                            :carpetDesignOrderId="props.carpetDesignOrderId"
                            @updateCustomerInstructionId="updateCustomerInstructionId"
                        ></d-modal-validated-sample>
                    </div>
                </div>
                <div class="row justify-content-between align-items-center mt-3">
                    <div class="col-lg-7 col-md-12">
                        <d-input type="date" label="Validation client" v-model="data.customerValidationDate"></d-input>
                    </div>
                    <div class="col-lg-5 col-md-12 d-flex">
                        <div class="checkbox-default custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="hasFinitionInstruction" v-model="data.hasFinitionInstruction"/>
                            <label class="custom-control-label" for="hasFinitionInstruction"></label>
                        </div>
                        <d-btn-outlined data-bs-toggle="modal" data-bs-target="#modalManageFinishing" label="Finition" icon="arrow-right" buttonClass="ps-1 font-size-0-8"></d-btn-outlined>
                    </div>
                    <d-modal-finishing 
                        :customerInstructionId="customerInstructionId" 
                        :carpetDesignOrderId="props.carpetDesignOrderId"
                        @updateCustomerInstructionId="updateCustomerInstructionId"
                    ></d-modal-finishing>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="row justify-content-between">
                    <div class="col-12">
                        <div class="text-black p-0 pb-2">Commentaire client</div>
                    </div>
                    <div class="col-12">
                        <textarea class="w-100 h-130-forced block-custom-border" v-model="data.customerComment"></textarea>
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
        <d-modal-create-variation :id_di="props.id_di" :carpetDesignOrderId="props.carpetDesignOrderId"></d-modal-create-variation>
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
    import dModalConstraint from "./_Partials/d-modal-constraint.vue"
    import dModalFinishing from "./_Partials/d-modal-finishing.vue"
    import dModalValidatedSample from "./_Partials/d-modal-validated-sample.vue"
    import contremarqueService from "../../../Services/contremarque-service";
    import { customerInstructionObject } from "../../../composables/constants";

    const props = defineProps({
        carpetDesignOrderId: {
            type: Number,
        },
        customerInstruction:{
            type: Object
        },
    });
    const data = ref(Object.assign({}, customerInstructionObject));
    
    const store = useStore();
    const customerInstructionId = ref(null);
    const emit = defineEmits(['transmisAdv']);
    const canShowTransmisAdv = computed(() => (store.getters.isCommertial || store.getters.isSuperAdmin) && !store.getters.isFinStatus);
    const canCreateVariation = computed(() => (store.getters.isDesigner || store.getters.isSuperAdmin) && !store.getters.isFinStatus);
    const transmisStudio = () => {
        emit('transmisAdv',carpetStatus.transmisAdvId);
    };
    onMounted(() => {
        if(props.customerInstruction){
            updateCustomerInstructionId(props.customerInstruction.id);
        }
    });
    const updateCustomerInstructionId = (id) => {
        customerInstructionId.value = id;
        setData();
    };
    const setData = () => {
        data.value.orderNumber = props.customerInstruction?.orderNumber;
        data.value.transmi_adv = props.customerInstruction?.transmi_adv;
        data.value.customerComment = props.customerInstruction?.customerComment;
        data.value.customerValidationDate = props.customerInstruction?.customerValidationDate;
        data.value.hasConstraints = props.customerInstruction?.hasCustomerConstraints;
        data.value.hasValidateSample = props.customerInstruction?.hasValidateSample;
        data.value.hasFinitionInstruction = props.customerInstruction?.hasFinitionInstruction;
        data.value.validatedSampleId = props.customerInstruction?.validatedSampleId;
        data.value.finitionInstructionId = props.customerInstruction?.finitionInstructionId;
        data.value.constraintInstructionId = props.customerInstruction?.constraintInstructionId;
    }
    watch(
        () => [
            data.value.orderNumber,
            data.value.transmi_adv,
            data.value.customerComment,
            data.value.customerValidationDate,
            data.value.hasConstraints,
            data.value.hasValidateSample,
            data.value.hasFinitionInstruction,
            data.value.validatedSampleId,
            data.value.finitionInstructionId,
            data.value.constraintInstructionId,
        ],
        async (newCarpert, oldCarpet) => {
            console.log(customerInstructionId.value);
            if(props.carpetDesignOrderId){
                try{
                    console.log(customerInstructionId.value);
                    const res = await contremarqueService.addUpdatecustomerInstruction(props.carpetDesignOrderId, data.value, customerInstructionId.value);
                    console.log(res.data);
                    if(!customerInstructionId.value){
                        updateCustomerInstructionId(res.id);
                    }
                }catch(e){
                    
                }
            }
        },
        { deep: true }
    );
    watch(
        () => props.customerInstruction,
        (customerInstruction) => {
            if(customerInstruction){
                updateCustomerInstructionId(props.customerInstruction.id);
                setData();
            }
        },
        { deep: true }
    );
</script>
