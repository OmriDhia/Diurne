<template>
    <div class="col-12">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="row justify-content-between align-items-center mt-3">
                    <div class="col-lg-7 col-md-12">
                        <d-input :disabled="disabled" label="N° de la commande" v-model="data.orderNumber"></d-input>
                    </div>
                    <div class="col-lg-5 col-md-12 d-flex">
                        <div class="checkbox-default custom-control custom-checkbox">
                            <input :disabled="disabled" type="checkbox" class="custom-control-input" id="hasConstraints" v-model="data.hasConstraints"/>
                            <label class="custom-control-label" for="hasConstraints"></label>
                        </div>
                        <d-btn-outlined :disabled="disabled" data-bs-toggle="modal" data-bs-target="#modalManageConstraint" label="Contraintes et remarque" icon="arrow-right" buttonClass="ps-1 font-size-0-6"></d-btn-outlined>
                    </div>
                    <d-modal-constraint v-if="!disabled"
                        :customerInstructionId="customerInstructionId"
                        :carpetDesignOrderId="props.carpetDesignOrderId"
                        :constraintData="props.customerInstruction?.customerConstraint"
                        @updateCustomerInstructionId="updateCustomerInstructionId"
                        @updateCustomerInstruction="updateCustomerInstruction"
                    ></d-modal-constraint>
                </div>
                <div class="row justify-content-between align-items-center mt-3">
                    <div class="col-lg-7 col-md-12">
                        <d-input :disabled="disabled" label="Transmi. ADV" v-model="data.transmi_adv"></d-input>
                    </div>
                    <div class="col-lg-5 col-md-12 d-flex">
                        <div class="checkbox-default custom-control custom-checkbox">
                            <input :disabled="disabled" type="checkbox" class="custom-control-input" id="hasValidateSample" v-model="data.hasValidateSample"/>
                            <label class="custom-control-label" for="hasValidateSample"></label>
                        </div>
                        <d-btn-outlined :disabled="disabled" data-bs-toggle="modal" data-bs-target="#modalManageValidatedSimple" label="Ech. Validée de ref" icon="arrow-right" buttonClass="ps-1 font-size-0-6"></d-btn-outlined>
                        <d-modal-validated-sample v-if="!disabled"
                            :customerInstructionId="customerInstructionId"
                            :carpetDesignOrderId="props.carpetDesignOrderId"
                            :validateSimpleData="props.customerInstruction?.validatedSample"
                            @updateCustomerInstructionId="updateCustomerInstructionId"
                            @updateCustomerInstruction="updateCustomerInstruction"
                        ></d-modal-validated-sample>
                    </div>
                </div>
                <div class="row justify-content-between align-items-center mt-3">
                    <div class="col-lg-7 col-md-12">
                        <d-input type="datetime-local" label="Validation client" v-model="data.customerValidationDate" :error="errorADV.dateCustomer"></d-input>
                    </div>
                    <div class="col-lg-5 col-md-12 d-flex">
                        <div class="checkbox-default custom-control custom-checkbox">
                            <input :disabled="disabled" type="checkbox" class="custom-control-input" id="hasFinitionInstruction" v-model="data.hasFinitionInstruction"/>
                            <label :disabled="disabled" class="custom-control-label" for="hasFinitionInstruction"></label>
                        </div>
                        <d-btn-outlined :disabled="disabled" data-bs-toggle="modal" data-bs-target="#modalManageFinishing" label="Finition" icon="arrow-right" buttonClass="ps-1 font-size-0-8"></d-btn-outlined>
                    </div>
                    <d-modal-finishing  v-if="!disabled"
                        :customerInstructionId="customerInstructionId" 
                        :carpetDesignOrderId="props.carpetDesignOrderId"
                        :finishingData="props.customerInstruction?.finitionInstruction"
                        @updateCustomerInstructionId="updateCustomerInstructionId"
                        @updateCustomerInstruction="updateCustomerInstruction"
                    ></d-modal-finishing>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="row justify-content-between">
                    <div class="col-12">
                        <div class="text-black p-0 pb-2">Commentaire client</div>
                    </div>
                    <div class="col-12">
                        <textarea
                            :disabled="disabled"
                            :class="{ 'is-invalid': errorADV.customerComment }"
                            class="w-100 h-130-forced block-custom-border" 
                            v-model="data.customerComment">
                        </textarea>
                    </div>
                    <div v-if="errorADV.customerComment" class="invalid-feedback">{{ errorADV.customerComment }}</div>
                </div>
            </div>
        </div>
        <div class="row justify-content-between align-items-start mt-3">
            <div class="col-auto">
                <d-btn-outlined label="Editer la demande de l'image projet" icon="arrow-right" buttonClass="ps-1 font-size-0-8"></d-btn-outlined>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="row justify-content-between align-items-start mt-1 pe-2" v-if="canShowTransmisAdv">
                    <button class="btn btn-custom w-100 text-uppercase font-size-0-7" @click="transmisAdv"  :disabled="disabled">Transmettre l'image à l'ADV</button>
                </div>
                <!-- <div class="row justify-content-between align-items-start mt-1  pe-2" v-if="canCreateVariation">
                    <button class="btn btn-custom w-100 text-uppercase font-size-0-7"  data-bs-toggle="modal" data-bs-target="#modalCreateVariation">Créer une variation</button>
                </div> -->
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
        disabled: {
            type: Boolean,
            default: false
        }
    });
    const data = ref(Object.assign({}, customerInstructionObject));
    const errorADV = ref({});
    
    const store = useStore();
    const customerInstructionId = ref(null);
    const emit = defineEmits(['transmisAdv']);
    const canShowTransmisAdv = computed(() => (store.getters.isCommertial || store.getters.isSuperAdmin || store.getters.isCommercialManager) && store.getters.isFinStatus);
    const canCreateVariation = computed(() => (store.getters.isDesigner || store.getters.isSuperAdmin) && !store.getters.isFinStatus);
    
    const transmisAdv = () => {
        errorADV.value = {};
        if(!data.value.customerValidationDate){
            errorADV.value.dateCustomer = "Date de validation client est obligatoire.";  
        }
        if(!data.value.customerComment){
            errorADV.value.customerComment = "Commentaire client est obligatoire.";  
        }
        
        if(Object.keys(errorADV.value).length === 0){
            emit('transmisAdv',data.value); 
        }
        
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
    
    const updateCustomerInstruction = (event) => {
        switch (event.instruction) {
            case "constraint":
                data.value.constraintInstructionId = event.id;
                break;
            case "finishing":
                data.value.finitionInstructionId = event.id;
                break;
            case "validateSimple":
                data.value.validatedSampleId = event.id;
                break;
        }
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
    };
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
            if(props.carpetDesignOrderId){
                try{
                    const res = await contremarqueService.addUpdatecustomerInstruction(props.carpetDesignOrderId, data.value, customerInstructionId.value);
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
    watch(
        () => props.disabled,
        (newvalue) => {
            console.log("new disabled value : " ,newvalue);
        },
        { deep: true }
    );
</script>
