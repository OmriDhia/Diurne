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
        <div class="col-sm-12 col-md-6" v-if="!props.isParticular">
            <d-input required="true" label="Nom" v-model="data.lastname" :error="error.lastname"></d-input>
        </div>
        <div class="col-sm-12 col-md-6">
            <d-input label="Tél. fixe" v-model="data.phone" :error="error.phone"></d-input>
        </div>
    </div>
    <div class="row p-1 align-items-center">
        <div class="col-sm-12 col-md-6" v-if="!props.isParticular">
            <d-input label="Prénom" v-model="data.firstname" :error="error.firstname"></d-input>
        </div>
        <div class="col-sm-12 col-md-6">
            <d-input label="Email" v-model="data.email" :error="error.email"></d-input>
        </div>
    </div>
    <div class="row align-items-center justify-content-between">
        <div class="col-md-auto">
            <!-- <div class="row">
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
            </div> -->
        </div>
        <!-- <div class="col-md-auto">
            <div class="row">
                <div class="col-auto p-1">
                    <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="updateContact">
                        <vue-feather type="save" size="14"></vue-feather>
                    </button>
                </div>
                <div class="col-auto p-1 pe-3">
                    <d-delete :api="`/api/contact/${data.contact_id}/delete`"></d-delete>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script setup>
    import {defineProps, ref, watch, onMounted} from 'vue';
    import axiosInstance from "../../../config/http";
    import VueFeather from 'vue-feather';
    import dDelete from "../../common/d-delete.vue";
    import dGender from "../../common/d-gender.vue";
    import dInput from "../../base/d-input.vue";
    import {formatErrorViolations} from "../../../composables/global-methods";
    import '../../../assets/sass/components/tabs-accordian/custom-accordions.scss';

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
        },
        isParticular: {
            type: Boolean,
        },
    });
    const data = ref({
    gender_id: props.contactData.gender_id || 0,
    firstname: props.contactData.firstname || "",
    lastname: props.contactData.lastname || "",
    email: props.contactData.email || "",
    phone: props.contactData.phone || "",
    mobile_phone: props.contactData.mobile_phone || "",
});
   
    const error = ref({});

    const emit = defineEmits(["updateContactData"]);
    // Watch for changes and emit updates
    watch(data, (newData) => {
        emit("updateContactData", newData);
    }, { deep: true });
    
</script>
<style>

</style>
