<template>
    <div class="row p-1 align-items-center">
        <div class="col-sm-12 col-md-6">
            <d-address-type required="true" v-model="data.addressTypeId" :error="error.addressTypeId"></d-address-type>
        </div>
        <div class="col-sm-12 col-md-6">
            <d-input required="true" label="Code postale" v-model="data.zip_code" :error="error.zip_code"></d-input>
        </div>
    </div>
    <div class="row p-1 align-items-center">
        <div class="col-sm-12 col-md-6">
            <d-input required="true" label="Nom" v-model="data.fullName" :error="error.fullName"></d-input>
        </div>
        <div class="col-sm-12 col-md-6">
            <d-input required="true" label="Adresse" v-model="data.address1" :error="error.address1"></d-input>
        </div>
    </div>
    <div class="row p-1 align-items-center">
        <div class="col-sm-12 col-md-6">
            <d-input label="Tél." v-model="data.phone" :error="error.phone"></d-input>
        </div>
        <div class="col-sm-12 col-md-6">
            <d-input required="true" label="Ville" v-model="data.city" :error="error.city"></d-input>
        </div>
    </div>
    <div class="row align-items-center">
        <div class="col-md-6 col-sm-12">
            <div class="row">
                <div class="col-md-auto">
                    <div class="checkbox-primary custom-control custom-checkbox text-color rounded">
                        <input type="checkbox" v-model="data.is_f_valide" class="custom-control-input" :id="'addressCheckbox1-'+props.index"/>
                        <label class="custom-control-label" :for="'addressCheckbox1-'+props.index"> F-valide </label>
                    </div>
                </div>
                <div class="col-md-auto">
                    <div class="checkbox-primary custom-control custom-checkbox text-color rounded">
                        <input type="checkbox" v-model="data.is_l_valide" class="custom-control-input" :id="'addressCheckbox2-'+props.index"/>
                        <label class="custom-control-label" :for="'addressCheckbox2-'+props.index">L-valide</label>
                    </div>
                </div>
                <div class="col-md-auto">
                    <div class="checkbox-primary custom-control custom-checkbox text-color rounded">
                        <input type="checkbox" v-model="data.is_wrong" class="custom-control-input" :id="'addressCheckbox3-'+props.index"/>
                        <label class="custom-control-label" :for="'addressCheckbox3-'+props.index">Erronée</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <d-countries required="true" v-model="data.countryId"></d-countries>
        </div>
    </div>
    <div class="row p-1 align-items-center">
        <div class="col-md-3 col-sm-12"><label class="form-label">Remarques adresses:</label></div>
        <div class="col-md-9 col-sm-12">
            <textarea v-model="data.comment" class="form-control"></textarea>
        </div>
    </div>
    <div class="row align-items-center justify-content-end">
        <div class="col-auto p-1">
            <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="updateAddress">
                <vue-feather type="save" size="14"></vue-feather>
            </button>
        </div>
        <div class="col-auto p-1 pe-3">
            <d-delete :api="`/api/address/${data.address_id}/delete`"></d-delete>
        </div>
    </div>
</template>

<script setup>
    import {defineProps, ref, watch, onMounted} from 'vue';
    import axiosInstance from "../../../config/http";
    import VueFeather from 'vue-feather';
    import dInput from "../../base/d-input.vue";
    import {formatErrorViolations} from "../../../composables/global-methods";
    import '../../../assets/sass/components/tabs-accordian/custom-accordions.scss';
    import dAddressType from "../../common/d-address-type.vue";
    import dCountries from "../../common/d-countries.vue";
    import dDelete from "../../common/d-delete.vue";
    import store from "../../../store/index";
    import $i18n from "../../../i18n" ;
    
    const props = defineProps({
        addressData: {
            type: Object,
            default: {}
        },
        customerId: {
            type: Number
        },
        index:{
            type: Number,
            default: 0
        }
    });

    const data = ref({
        address_id: null,
        fullName: "",
        address1: "",
        city: "",
        zip_code: "",
        state: "",
        is_f_valide: null,
        is_l_valide: null,
        is_wrong: null,
        comment: "",
        phone: "",
        mobile_phone: "",
        addressTypeId: 0,
        countryId: 0
    });
    const error = ref({});

    const updateAddress = async () => {
        try{
            error.value = {};
            const res = await axiosInstance.put("api/updateAddress/" + data.value.address_id, data.value);
            window.showMessage("Mise a jour avec succées.")
        }catch(e){
            if(e.response.data.violations){
                error.value = formatErrorViolations(e.response.data.violations)
                Object.entries(error.value).forEach(([key, value]) => {
                    window.showMessage($i18n.global.t(key) +': ' + $i18n.global.t(value),'error')
                });
            }else{
                window.showMessage(e.message,'error')
            }
            
        }
    };
    onMounted(() => {
        affectData(props.addressData)
    });
    const affectData = (address) => {
        data.value.address_id = address.address_id;
        data.value.fullName = address.full_name;
        data.value.address1 = address.address1;
        data.value.city = address.city;
        data.value.zip_code = address.postcode;
        data.value.state = address.state;
        data.value.is_f_valide = address.is_f_valide;
        data.value.is_l_valide = address.is_l_valide;
        data.value.is_wrong = address.is_wrong;
        data.value.comment = address.comment;
        data.value.phone = address.phone;
        /*data.value.mobile_phone = address.mobile_phone;*/
        data.value.addressTypeId = address.addressType.addressTypeId;
        data.value.countryId = address.country;
    };
   watch(
        () => props.addressData,
        (newVal) => {
            affectData(newVal);
        }
    );
</script>
<style>

</style>
