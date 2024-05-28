<template>
    <div class="row p-1 align-items-center">
        <div class="col-sm-12 col-md-6">
            <d-gender v-model="data.gender_id" :error="error.gender_id"></d-gender>
        </div>
        <div class="col-sm-12 col-md-6">
            <d-input label="Tel. portable" v-model="data.mobile_phone" :error="error.mobile_phone"></d-input>
        </div>
    </div>
    <div class="row p-1 align-items-center">
        <div class="col-sm-12 col-md-6">
            <d-input label="Nom" v-model="data.lastname" :error="error.lastname"></d-input>
        </div>
        <div class="col-sm-12 col-md-6">
            <d-input label="Tél. fixe" v-model="data.phone" :error="error.phone"></d-input>
        </div>
    </div>
    <div class="row p-1 align-items-center">
        <div class="col-sm-12 col-md-6">
            <d-input label="Prénom" v-model="data.firstname" :error="error.firstname"></d-input>
        </div>
        <div class="col-sm-12 col-md-6">
            <d-input label="Email" v-model="data.email" :error="error.email"></d-input>
        </div>
    </div>
    <div class="row align-items-center justify-content-between">
        <div class="col-md-auto">
            <div class="row">
            <div class="col-md-auto">
                <div class="checkbox-primary custom-control custom-checkbox text-color rounded">
                    <input type="checkbox" v-model="data.mailing" class="custom-control-input" :id="'contactCheckbox1-'+props.index"/>
                    <label class="custom-control-label" :for="'contactCheckbox1-'+props.index"> Mailing </label>
                </div>
            </div>
            <div class="col-md-auto">
                <div class="checkbox-primary custom-control custom-checkbox text-color rounded">
                    <input type="checkbox" v-model="data.mailing_with_calligraphie" class="custom-control-input" :id="'contactCheckbox2-'+props.index"/>
                    <label class="custom-control-label" :for="'contactCheckbox2-'+props.index"> Mailing calligraphique </label>
                </div>
            </div>
            </div>
        </div>
        <div class="col-md-auto">
            <div class="row">
            <div class="col-auto p-1">
                <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="updateContact">
                    <vue-feather type="save" size="14"></vue-feather>
                </button>
            </div>
            <div class="col-auto p-1 pe-3">
                <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle">
                    <vue-feather type="x" :size="14"></vue-feather>
                </button>
            </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {defineProps, ref, watch, onMounted} from 'vue';
    import axiosInstance from "../../config/http";
    import VueFeather from 'vue-feather';
    import dGender from "../../components/common/d-gender.vue";
    import dInput from "../../components/base/d-input.vue";
    import {formatErrorViolations} from "../../composables/global-methods";
    import '../../assets/sass/components/tabs-accordian/custom-accordions.scss';

    const props = defineProps({
        contactData: {
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
        contact_id: null,
        gender_id: -1,
        firstname: null,
        lastname: null,
        email: null,
        mailing: true,
        mailing_with_calligraphie: true,
        phone: null,
        mobile_phone: null,
        fax: null,
        user_id: null,
        customerId: props.customerId
    });
    const error = ref({});

    const updateContact = async () => {
        try{
            if(props.customerId){
                error.value = {};
                const res = await axiosInstance.put("api/updateContact/" + data.value.contact_id + "/" + props.customerId,data.value);
                window.showMessage("Mise a jour avec succées.")
            }
        }catch(e){
            if(e.response.data.violations){
                error.value = formatErrorViolations(e.response.data.violations)
            }
            window.showMessage(e.message,'error')
        }
    };
    onMounted(() => {
        affectData(props.contactData)
    })
    const affectData = (contact) => {
        data.value.contact_id = contact.contact_id;
        data.value.gender_id = contact.gender_id;
        data.value.firstname = contact.firstname;
        data.value.lastname = contact.lastname;
        data.value.email = contact.email;
        data.value.mailing = contact.mailing;
        data.value.mailing_with_calligraphie = contact.mailing_with_caligraphie;
        data.value.phone = contact.phone;
        data.value.mobile_phone = contact.mobile_phone;
        data.value.fax = contact.fax;
        data.value.user_id = contact.user_id;
        data.value.customerId = props.customerId
    };
   watch(
        () => props.contactData,
        (newVal, oldVal) => {
            affectData(newVal);
        }
    );
</script>
<style>

</style>
