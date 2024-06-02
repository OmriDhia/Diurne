<template>
    <div class="col-sm-12 col-md-6">
        <div class="row p-2">
            <d-customer-type :error="error.customerGroupId" v-model="data.customerGroupId"></d-customer-type>
        </div>
        <div class="row p-2 pe-3">
            <div class="col-sm-12 col-md-6 pe-4">
                <d-input label="Code contact" v-model="data.code" :error="error.code"></d-input>
            </div>
            <div class="col-sm-12 col-md-6 pe-4">
                <d-input label="CE TVA" v-model="data.tva_ce" :error="error.tva_ce"></d-input>
            </div>
        </div>
        <div class="row p-2">
            <d-discount  :error="error.customerGroupId" v-model="data.discountTypeId"></d-discount>
        </div>
        <div class="row p-2">
            <d-input label="Raison social" :error="error.social_reason" v-model="data.social_reason"></d-input>
        </div>
        <div class="row p-2">
            <d-input label="Site web" :error="error.website" v-model="data.website"></d-input>
        </div>
        <div class="row p-2">
            <d-languages label="Site web" :error="error.website" v-model="data.mailingLanguageId"></d-languages>
        </div>
        <div class="row align-content-end justify-content-end p-2 pe-3">
            <div class="col-auto p-1">
                <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="createCustomer">
                    <vue-feather type="save" size="14"></vue-feather>
                </button>
            </div>
            <div class="col-auto p-1 pe-4" v-if="data.customer_id">
                <d-delete :api="`/api/customer/${data.customer_id}/delete`"></d-delete>
            </div>
        </div>


    </div>
</template>

<script setup>
    import {ref, defineProps, onMounted, watch} from 'vue';
    import axiosInstance from "../../config/http";
    import VueFeather from 'vue-feather';
    import dCustomerType from "../../components/common/d-customer-type.vue";
    import dDiscount from "../../components/common/d-discount.vue";
    import dLanguages from "../../components/common/d-langages.vue";
    import dInput from "../../components/base/d-input.vue";
    import dDelete from "../common/d-delete.vue";
    import {formatErrorViolations} from "../../composables/global-methods";

    const props = defineProps({
        customerData: {
            type: Object,
            default: {}
        }
    });
    
    const data = ref({
        customer_id: 0,
        customerGroupId: 0,
        code: "",
        social_reason: "",
        tva_ce: "",
        website: "",
        discountTypeId: 0,
        mailingLanguageId: 0,
    });
    const error = ref({});

    const createCustomer = async () => {
        try{
            if(props.customerData.customer_id){
                error.value = {};
                const res = await axiosInstance.put("api/updateCustomer/" + props.customerData.customer_id,data.value);
                window.showMessage("Mise a jour avec succées.")
            }else{
                const res = await axiosInstance.post("/api/createCustomer",data.value);
                location.href = "/contacts/manage/" + res.data.response.customer_id
            }
        }catch(e){
            if(e.response.data.violations){
                error.value = formatErrorViolations(e.response.data.violations)
            }
            window.showMessage(e.message,'error')
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
        data.value.mailingLanguageId = newVal.mailingLanguageId;
    }; 
    watch(
        () => props.customerData,
        (newVal) => {
           affectData(newVal)
        }
    );
</script>
<style>

</style>

