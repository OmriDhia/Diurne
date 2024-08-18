<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title :title="'Demande Image'"></d-page-title>

        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-2">
                <div class="row p-2">
                    <div class="table-checkable table-highlight-head table-responsive">
                        <table role="table" aria-busy="false" aria-colcount="5" class="w-50 histories-event-table table b-table table-striped">
                            <thead>
                                <tr>
                                    <th>Numéro di</th>
                                    <th>Date création</th>
                                    <th>Date transmis</th>
                                    <th>transmis</th>
                                </tr>
                            </thead>
                            <tbody role="rowgroup">
                            <tr v-for="(item, i) in datas" :key="item.project_di" :class="{'selectedDi': i === selected}" @click="handleSelected(i)">
                                <td aria-colindex="1" role="cell">
                                    <div class="row align-items-center" v-if="i === selected">
                                        <div class="col-auto pe-0"><vue-feather type="chevron-right" class="pt-1"></vue-feather></div>
                                        <div class="col-auto ps-0">{{ item.demande_number }}</div>
                                    </div>
                                    <div v-else>
                                        {{ item.demande_number }}
                                    </div>
                                </td>
                                <td aria-colindex="2" role="cell" class="">
                                    {{ $Helper.FormatDate(item.createdAt.date,"DD/MM/YYYY")}}
                                </td>
                                <td aria-colindex="2" role="cell" class="">
                                    {{ $Helper.FormatDate(item.transmition_date.date,"DD/MM/YYYY")}}
                                </td>
                                <td aria-colindex="2" role="cell" class="">
                                    <div title="test" class="t-dot" :class="item.transmitted_to_studio ? 'bg-success' :'bg-danger'"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel br-6 p-2 mt-3">
                <div class="row ms-2 mt-2 mb-2">
                    <div class="col-xl-2 col-md-4 col-xs-12 d-flex">
                        <div class="col-auto pe-1 ps-2 text-black">
                            Format: 
                        </div>
                        <div class="col-auto pe-1 ps-2">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="formatA3" v-model="selectedData.format" name="format" value="A3" disabled/>
                                <label class="custom-control-label text-black" for="formatA3"> {{ $t('A3') }} </label>
                            </div>
                        </div>
                        <div class="col-auto pe-1 ps-1">
                            <div class="radio-success custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="formatA4" v-model="selectedData.format" name="warningAdd" value="A4" disabled/>
                                <label class="custom-control-label text-black" for="formatA4"> {{ $t('A4') }} </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col xs-12">
                        <d-unit-measurements v-model="unitOfMesurements.id" :disabled="true"></d-unit-measurements>
                    </div>
                    <div class="col-xl-3 col-md-4 col xs-12">

                    </div>
                    <div class="col-xl-3 col-md-4 col xs-12">

                    </div>
                </div>
            </div>
            <div class="panel br-6 p-2 mt-3">
                <div class="row ms-2 mt-2 mb-2">
                    <div class="col-xl-4 col-md-12">
                        <d-input :disabled="true" v-model="customer.socialReason" label="Client"></d-input>
                        <d-input :disabled="true" v-model="contremarque.designation" label="Contremarque"></d-input>
                        <d-input :disabled="true" v-model="transDate" label="Date trasmission"></d-input>
                        <d-input :disabled="true" v-model="selectedData.demande_number" label="N° de la demande"></d-input>
                        <d-input :disabled="true" v-model="comment" label="Deadline"></d-input>
                        <d-input :disabled="true" v-model="comment" label="Commertial"></d-input>
                    </div>
                    <div class="col-xl-8 col-md-12 pe-2">
                        <div class="row m-2 block-custom-border">
                            <div class="col-md-12 bg-theme text-center p-2">
                                Document joints à la DI : 
                            </div>
                            <perfect-scrollbar tag="div" class="h-200-forced col-12"
                                               :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 200, suppressScrollX: true }">
                                
                            </perfect-scrollbar>
                            <div class="col-md-12">
                                <div class="row justify-content-center">
                                    <div class="col-auto">
                                        <button class="btn btn-custom pe-5 ps-5 mb-2">Parcourir</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row p-2 mt-3">
                    <div class="table-checkable table-highlight-head table-responsive w-75">
                        <table role="table" aria-busy="false" aria-colcount="5" class="table b-table table-striped">
                            <thead>
                            <tr>
                                <th>Emplacement</th>
                                <th>Etat tapis</th>
                                <th>Numéro</th>
                                <th>Collection</th>
                                <th>Modèle</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody role="rowgroup">
                            <tr v-for="(item, i) in carpetDesign" :key="item.id">
                                <td aria-colindex="1" role="cell">
                                </td>
                                <td aria-colindex="2" role="cell" class="">
                                </td>
                                <td aria-colindex="3" role="cell" class="">
                                </td>
                                <td aria-colindex="4" role="cell" class="">
                                    {{item.carpetSpecification.collection ? item.carpetSpecification.collection.reference : ''}}
                                </td>
                                <td aria-colindex="5" role="cell" class="">
                                    {{item.carpetSpecification.model ? item.carpetSpecification.model.code : ''}}
                                </td>
                                <td aria-colindex="6" role="cell" class="p-0">
                                    <div class="row ps-4 align-items-center">
                                        <div class="col-auto p-1">
                                            <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle">
                                                <vue-feather type="search" size="14"></vue-feather>
                                            </button>
                                        </div>
                                        <!--div class="col-auto p-1">
                                            <d-btn-outlined label="Copie  " icon="arrow-right" buttonClass="ps-1"></d-btn-outlined>
                                        </div-->
                                        <div class="col-auto p-1">
                                            <d-delete :api="''"></d-delete>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="w-25">
                        <div class="row">
                            <div class="col-auto">
                                <button class="btn btn-custom pe-4 ps-4">Nouveau</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gap-3 ps-2 mt-4">
                    <div class="col-auto">
                        <d-btn-outlined label="Editer la demande de l'image projet " icon="arrow-right" buttonClass="ps-4"></d-btn-outlined>
                    </div>
                    <!--div class="col-auto">
                        <button class="btn btn-custom pe-5 ps-5">COPIER LA DI</button>
                    </div-->
                    <div class="col-auto">
                        <button class="btn btn-custom pe-5 ps-5">NOUVELLE DI</button>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
</template>

<script setup>
import dInput from '../../../components/base/d-input.vue';
import dCustomerDropdown from '../../../components/common/d-customer-dropdown.vue';
import dPageTitle from '../../../components/common/d-page-title.vue';
import dUnitMeasurements from '../../../components/common/d-unit-measurements.vue';
import dBtnOutlined from "../../../components/base/d-btn-outlined.vue"
import dDelete from "../../../components/common/d-delete.vue"
import VueFeather from 'vue-feather';
import axiosInstance from '../../../config/http';
import { useRoute } from 'vue-router';
import { ref, onMounted, watch } from 'vue';
import { filterContremarque } from '../../../composables/constants';
import contremarqueService from "../../../Services/contremarque-service";

import Store from "../../../store/index";

import { useMeta } from '/src/composables/use-meta';
import {Helper} from "../../../composables/global-methods";
useMeta({ title: 'Demande Image' });

const route = useRoute();
const contremarque_id = route.params.id;
const datas = ref([]);
const selected = ref(null);
const selectedData = ref({});
const comment = ref("");
const format = ref("");
const unitOfMesurements = ref("");
const contremarque = ref({});
const customer = ref({});
const transDate = ref("");
const carpetDesign = ref([]);

const getProjectDIS = async () => {
    try{
        const res = await axiosInstance.get(`/api/contremarque/${contremarque_id}/projectDis`);
        datas.value = res.data.response.projectDis;
        contremarque.value = await contremarqueService.getContremarqueById(contremarque_id);
        customer.value = contremarque.value.customer;
        await handleSelected(0);
    }catch{
        console.log("Erreur get events customer")
    }
};
const handleSelected = async (index) => {
    selected.value = index;
    comment.value = datas.value[index].commentaire;
    selectedData.value = datas.value[index];
    unitOfMesurements.value = selectedData.value.unit;
    transDate.value = Helper.FormatDate(selectedData.value.transmition_date.date);
    carpetDesign.value = await contremarqueService.getcarpetDesign(contremarque_id,selectedData.value.project_di)
};
onMounted(()=>{
    getProjectDIS();
});
/*watch(
    () => props.customerId,
    (newVal) => {
        console.log(newVal);
        getEventHistories(newVal)
    }
);*/
</script>

<style scoped>
    .table > thead > tr> th{
        background-color: #D4EFF8;
        color: black;
        font-weight: 700;
        font-size: 12px;
    }
    .table > thead > tr > th:first-child{
        border-radius: 20px 0px 0px 20px;
    }
    .table > thead > tr > th:last-child{
        border-radius: 0px 20px 20px 0px;
    }
    .table > tbody > tr > td:last-child{
        border-radius: 0px 10px 10px 0px;
    }
    .table > tbody > tr > td:first-child{
        border-radius: 10px 0px 0px 10px;
    }
    .table > tbody > tr > td {
        font-size: 0.8rem;
        border: none;
    }
    .table > tbody > tr > td:not(:last-child) {
        border-right: 1px solid #D4EFF8;
    }
    .table-striped>tbody>tr:nth-of-type(odd)>* {
        --bs-table-accent-bg: var(--bs-table-bg);
    }
    .table>:not(caption)>*>*{
        --bs-table-accent-bg: #EDF9FD;
    }
</style>
