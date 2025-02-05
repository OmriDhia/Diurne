<template>
    <div class="col-sm-12 col-md-6">
        <div class="row p-2">
            <d-customer-type :required="true" :error="error.customerGroupId" v-model="data.customerGroupId"></d-customer-type>
        </div>
        <div class="row p-2" v-if="!isParticular">
            <d-input :required="true" label="Raison social" :error="error.social_reason" v-model="data.social_reason"></d-input>
        </div>
        <div class="row p-2" v-if="isParticular">
            <d-input required="true" label="Nom" :error="error.lastname" v-model="data.lastname"></d-input>
        </div>
        <div class="row p-2" v-if="isParticular">
            <d-input label="Prénom" :error="error.firstname" v-model="data.firstname"></d-input>
        </div>
        <!-- <div class="row p-2">
            <d-input  :button="data.customer_id === 0" required="true" label="Code contact" v-model="data.code" :error="error.code">
                <template v-slot:input-button>
                    <button class="btn btn-success" @click.prevent="incrimentSuffix" v-if="data.customer_id === 0">Générer</button>
                </template>
            </d-input>
        </div> -->
        <div class="row p-2" v-if="!isParticular">
            <d-input label="CE TVA" v-model="data.tva_ce" :error="error.tva_ce"></d-input>
        </div>
        <div class="row p-2">
            <d-discount required="true" :error="error.customerGroupId" v-model="data.discountTypeId"></d-discount>
        </div>
        <div class="row p-2" v-if="!isParticular">
            <d-input label="Site web" :error="error.website" v-model="data.website"></d-input>
        </div>
        <div class="row p-2" v-if="!isParticular">
            <d-languages :error="error.website" v-model="data.mailingLanguageId"></d-languages>
        </div>
        <div class="row p-2">
            <div class="col-md-auto">
                <div class="checkbox-primary custom-control custom-checkbox text-color rounded">
                    <input type="checkbox" v-model="data.is_agent" class="custom-control-input" id="isAgent"/>
                    <label class="custom-control-label" for="isAgent"> Agent </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 d-flex flex-column" v-if="data.customerGroupId !== 1">
        <div class="row p-2">
            <d-customer-origin :required="true" :error="errorContactOrigin" v-model="data"  />
        </div>
        <div class="row p-2" v-if="isAutreSelectedOriginType">
            <d-textarea
                label="Commentaire"
                v-model="data.commentaire"
                :error="errorCommentaire"
                :required="true"
                :rows="5"
                type="textarea"
                class="custom-textarea"
            />
        </div>
        <div class="row align-content-end justify-content-end p-2 pe-3 mt-auto">
            <div class="col-auto p-1">
                <button class="btn btn-custom pe-5 ps-5" @click="createCustomer">Ajouter Client</button> 
            </div>
            <div class="col-auto p-1 pe-4" v-if="data.customer_id">
                <d-delete :api="`/api/customer/${data.customer_id}/delete`"></d-delete>
            </div>
        </div>
    </div>


    <d-contact-client-particulier v-if="data.customerGroupId === 1"
    :contactData="customerData.contactsData"
    :customerId="customerData.customer_id"
    :customerData="localCustomerData"
    :isParticular="isParticular"
    ></d-contact-client-particulier>


    <div class="col-sm-12 col-md-12 d-flex flex-column" v-if="data.customerGroupId === 1">
               <div class="row align-content-end justify-content-end p-2 pe-3 mt-auto">
            <div class="col-auto p-1">
                <button class="btn btn-custom pe-5 ps-5" @click="createCustomer">Ajouter Client</button> 
            </div>
            <div class="col-auto p-1 pe-4" v-if="data.customer_id">
                <d-delete :api="`/api/customer/${data.customer_id}/delete`"></d-delete>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {ref, defineProps, onMounted, watch, watchEffect, computed} from 'vue';
    import axiosInstance from "../../config/http";
    import VueFeather from 'vue-feather';
    import dCustomerType from "../../components/common/d-customer-type.vue";
    import dCustomerOrigin from "../../components/common/d-customer-origin.vue";
    import dDiscount from "../../components/common/d-discount.vue";
    import dLanguages from "../../components/common/d-langages.vue";
    import dInput from "../../components/base/d-input.vue";
    import dDelete from "../common/d-delete.vue";
    import {formatErrorViolations} from "../../composables/global-methods";
    import {particularCustomerGroupId, publicDiscountTypeId} from "../../composables/constants";
    import dTextarea from '../../components/base/d-textarea.vue';
    import dContactClientParticulier from "./d-contact-client-particulier.vue";

    import { useRouter } from 'vue-router';
    
    const props = defineProps({
        customerData: {
            type: Object,
            default: {}
        }
    });
    const localCustomerData = ref({ firstName: "" , lastName: "", customer_id: null});
    const localContactFullNameData = ref({ firstName: "" , lastName: ""});
    const router = useRouter();
    const data = ref({
        customer_id: 0,
        customerGroupId: 0,
        code: "",
        social_reason: "",
        tva_ce: "",
        firstname: "",
        lastname: "",
        email: "",
        website: "",
        phone: "",
        mobile_phone: "",
        fax: "",
        discountTypeId: 0,
        mailingLanguageId: 0,
        is_agent: false,
        contact_origin_label: "",
        contact_origin_id: 11,  // Updated from OriginContactId
        commentaire: "",
    });


    const errorCommentaire = ref("");
    const errorContactOrigin = ref("");
    const error = ref({});
    // const isParticular = ref(false);
    // const isParticular = computed(() => !!props.customerData.customer_id);
    // OR Solution 2: Using watchEffect if you prefer ref
const isParticular = ref(false);
watchEffect(() => {
    isParticular.value = !!props.customerData.customer_id;
});
    const isAutreSelectedOriginType = ref(false);
    let codeSuffix = ref(1);
    const createCustomer = async () => {
        if (data.value.contact_origin_label === "") {
            errorContactOrigin.value = "Le type d'origine est obligatoire.";
        }
        if (data.value.contact_origin_label === "Autre" && data.value.commentaire === "") {
            errorCommentaire.value = "Commentaire est Obligatoire Pour Autre.";
        } else {
            errorCommentaire.value = "";
        }
        try{
            if(props.customerData.customer_id){
                error.value = {};
                const res = await axiosInstance.put("api/updateCustomer/" + props.customerData.customer_id,data.value);
                window.showMessage("Mise a jour avec succées.")
            }else{
                const res = await axiosInstance.post("/api/createCustomer",data.value);
                localCustomerData.value.customer_id = res.data.response.customer_id;
                localCustomerData.value.firstName = res.data.response.firstname;
                localCustomerData.value.lastName = res.data.response.lastname;
                window.showMessage("Client créé avec succès.");
                console.log("customer id after response : " , localCustomerData.value.customer_id)
            }
        }catch(e){
             // Improved error handling with checks for the response structure
            if (e.response && e.response.data) {
                if (e.response.data.violations) {
                    error.value = formatErrorViolations(e.response.data.violations);
                } else if (e.response.data.message) {
                    window.showMessage(e.response.data.message, "error");
                } else {
                    window.showMessage("Erreur inconnue", "error");
                }
            } else {
                // If the error does not have a response object (network errors, etc.)
                window.showMessage("Erreur de réseau ou serveur", "error");
                console.log(e);
            }
            // if(e.response.data.violations){
            //     error.value = formatErrorViolations(e.response.data.violations)
            // }
            // window.showMessage(e.message,'error')
        }
    };
    const affectData = (newVal) => {
        data.value.customer_id = newVal.customer_id;
        data.value.customerGroupId = newVal.customerGroup.customer_group_id;
        data.value.discountTypeId = newVal.discountRule.discount_rule_id;
        data.value.social_reason = newVal.socialReason;
        data.value.website = newVal.website;
        data.value.code = newVal.code;
        data.value.tva_ce = newVal.tva_ce;
        data.value.mailingLanguageId = newVal.mailingLanguage;
        data.value.is_agent =  newVal.is_agent;
        data.value.firstname =  newVal.firstname;
        data.value.lastname =  newVal.lastname;
        data.value.contact_origin_id = newVal.contact_origin_id ?? null;
        data.value.commentaire = newVal.commentaire;
        data.value.contact_origin_label = newVal.contact_origin_label;
    };
    
    const changeCode = (Rs) => {
        if(Rs){
            data.value.code = Rs.substr(0,4) + codeSuffix.value.toString().padStart(2, '0'); 
        }else{
            data.value.code = "";
        }
    };
    const incrimentSuffix = (Rs) => {
        codeSuffix.value++;
        changeCode(data.value.social_reason);
    };
    watch(() => props.customerData, (newVal) => {
        if (newVal) {
            affectData(newVal); // Update all customer fields
        }
    });
    watch(
        () => data.value.social_reason,
        (newVal) => {
            if(data.value.customer_id === 0 ){
                changeCode(newVal);
            }
        }
    );
    watch(
        () => data.value.customerGroupId,
        (groupId) => {
            if(groupId === publicDiscountTypeId){
                isParticular.value = true;
                data.value.discountTypeId = particularCustomerGroupId;
            }else{
                isParticular.value = false;
                data.value.discountTypeId = 0;
            }
        }, { deep: true });
    watch(() => data.value.contact_origin_label, (newLabel, oldLabel) => {
        if (newLabel !== oldLabel) {  // Prevents unnecessary updates
            console.log("New label detected:", newLabel);
            errorContactOrigin.value = "";
            isAutreSelectedOriginType.value = newLabel === "Autre";
            
            // Reset commentaire only if the label is different
            if (!isAutreSelectedOriginType.value) {
                data.value.commentaire = "";
            }
        }
    });
    watch(() => data.value.commentaire, (newCommentaire, oldCommentaire) => {
        if (newCommentaire !== oldCommentaire) {
            console.log("Commentaire updated:", oldCommentaire);
            console.log("Commentaire updated:", newCommentaire);
        }
    });

  
</script>
<style>
    .custom-textarea {
        align-items: baseline !important;

    }
</style>

