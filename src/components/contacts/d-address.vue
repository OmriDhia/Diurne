<template>
            <div id="toggleAccordionAddress" class="accordion ps-1">
                <div class="card mb-1">
                    <header class="card-header" role="tab">
                        <section class="mb-0 mt-0">
                            <div class="collapsed" role="menu" data-bs-toggle="collapse" data-bs-target="#headingAddressOne1" aria-expanded="true" aria-controls="headingAddressOne1">
                                Nouveau adresse
                                <div class="icons">
                                    <vue-feather type="chevron-down" size="14"></vue-feather>
                                </div>
                            </div>
                        </section>
                    </header>
                    <div id="headingAddressOne1" class="collapse show" aria-labelledby="headingAddressOne1" data-bs-parent="#toggleAccordionAddress">
                        <div class="card-body">
                            <div class="row p-1 align-items-center">
                                <div class="col-sm-12 col-md-6">
                                    <d-address-type v-model="data.addressTypeId" :error="error.addressTypeId"></d-address-type>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <d-input label="Code postale" v-model="data.zip_code" :error="error.zip_code"></d-input>
                                </div>
                            </div>
                            <div class="row p-1 align-items-center">
                                <div class="col-sm-12 col-md-6">
                                    <d-input label="Nom" v-model="data.fullName" :error="error.fullName"></d-input>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <d-input label="Adresse" v-model="data.address1" :error="error.address1"></d-input>
                                </div>
                            </div>
                            <div class="row p-1 align-items-center">
                                <div class="col-sm-12 col-md-6">
                                    <d-input label="Tél." v-model="data.phone" :error="error.phone"></d-input>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <d-input label="Ville" v-model="data.city" :error="error.city"></d-input>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-6 col-sm-12 pe-md-0">
                                    <div class="row">
                                    <div class="col-md-auto p-md-0 ps-md-2">
                                        <div class="checkbox-primary custom-control custom-checkbox text-color rounded">
                                            <input type="checkbox" v-model="data.is_f_valide" class="custom-control-input" id="addressAdd1"/>
                                            <label class="custom-control-label" for="addressAdd1"> F-valide </label>
                                        </div>
                                    </div>
                                    <div class="col-md-auto p-md-0 ps-md-1">
                                        <div class="checkbox-primary custom-control custom-checkbox text-color rounded">
                                            <input type="checkbox" v-model="data.is_l_valide" class="custom-control-input" id="addressAdd2"/>
                                            <label class="custom-control-label" for="addressAdd2">L-valide</label>
                                        </div>
                                    </div>
                                        <div class="col-md-auto p-md-0 ps-md-1">
                                            <div class="checkbox-primary custom-control custom-checkbox text-color rounded">
                                                <input type="checkbox" v-model="data.is_wrong" class="custom-control-input" id="addressAdd3"/>
                                                <label class="custom-control-label" for="addressAdd3">Erronée</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <d-countries v-model="data.countryId"></d-countries>
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
                                    <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="addAddress">
                                        <vue-feather type="save" size="14"></vue-feather>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <perfect-scrollbar tag="div" class="h-250"
                                   :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 300, suppressScrollX: true }">
                <template v-for="(item, index) in props.addressData">
                    <div class="card mb-1">
                        <header class="card-header" role="tab">
                            <section class="mb-0 mt-0">
                                <div class="" role="menu" data-bs-toggle="collapse" :data-bs-target="'#address'+index" aria-expanded="true" :aria-controls="'address'+index">
                                    {{ 'Adresse ' + (index+1) }}
                                    <div class="icons">
                                        <vue-feather type="chevron-down" size="14"></vue-feather>
                                    </div>
                                </div>
                            </section>
                        </header>
                        <div :id="'address'+index" class="collapse" :class="{show: index === 0}" :aria-labelledby="'address'+index">
                            <div class="card-body">
                                <d-address-form :addressData="item" :customerId="props.customerId" :index="index"></d-address-form>
                            </div>
                        </div>
                    </div>
                </template>
                </perfect-scrollbar>
            </div>
</template>

<script setup>
    import {defineProps, ref} from 'vue';
    import axiosInstance from "../../config/http";
    import VueFeather from 'vue-feather';
    import dContactForm from "./_partial/d-contact-form.vue"
    import dGender from "../../components/common/d-gender.vue";
    import dInput from "../../components/base/d-input.vue";
    import {formatErrorViolations} from "../../composables/global-methods";
    import '../../assets/sass/components/tabs-accordian/custom-accordions.scss';
    import dAddressType from "../common/d-address-type.vue";
    import dCountries from "../common/d-countries.vue";
    import dAddressForm from "./_partial/d-address-form.vue";
    import perfectScroll from "../plugins/perfect-scrollbar1.vue";
   
    const props = defineProps({
        addressData: {
            type: Array,
            default: []
        },
        customerId: {
            type: Number
        }
    });

    const data = ref({
        fullName: null,
        address1: null,
        city: null,
        zip_code: null,
        state: 'test',
        is_f_valide: null,
        is_l_valide: null,
        is_wrong: null,
        comment: null,
        phone: null,
        mobile_phone: null,
        addressTypeId: -1,
        countryId: -1
    });
    const error = ref({});

    const addAddress = async () => {
        try{
            if(props.customerId){
                error.value = {};
                const res = await axiosInstance.post("api/createAddress",data.value);
                const res2 = await axiosInstance.post("api/AssignAddressToCustomer",{
                    addressId: res.data.response.address_id,
                    customerId: props.customerId
                });
                window.showMessage("Ajout avec succées.");
                window.location.reload();
            }
        }catch(e){
            if(e.response.data.violations){
                error.value = formatErrorViolations(e.response.data.violations)
            }
            window.showMessage(e.message,'error')
        }
    };
</script>
<style>

</style>
