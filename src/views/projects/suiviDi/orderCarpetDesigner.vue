<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title icon="image" :title="'Maquette'"></d-page-title>

        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-2 mt-3">
                <div class="row ms-2 mt-2 mb-2">
                    <div class="col-xl-2 col-md-4 col-xs-12 d-flex">
                        <div class="col-auto pe-1 ps-2 text-black">
                            Format: 
                        </div>
                        <div class="col-auto pe-1 ps-2">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="formatA3" v-model="format" name="format" value="A3" disabled/>
                                <label class="custom-control-label text-black" for="formatA3"> {{ $t('A3') }} </label>
                            </div>
                        </div>
                        <div class="col-auto pe-1 ps-1">
                            <div class="radio-success custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="formatA4" v-model="format" name="warningAdd" value="A4" disabled/>
                                <label class="custom-control-label text-black" for="formatA4"> {{ $t('A4') }} </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col xs-12">
                        <d-unit-measurements v-model="unitOfMesurements.id" :disabled="true"></d-unit-measurements>
                    </div>
                    <div class="col-md-4 col xs-12">

                    </div>
                    <div class="col-xl-3 col-md-4 col xs-12">

                    </div>
                </div>
            </div>
            
            <div class="panel br-6 p-2 mt-3">
                <div class="row ms-2 mt-2 mb-2">
                   <div class="col-md-12 col-xl-9">
                       <div class="row">
                           <div class="col-xl-4 col-md-12">
                               <d-input :disabled="true" v-model="customer.socialReason" label="Client"></d-input>
                               <d-input :disabled="true" v-model="contremarque.designation" label="Contremarque"></d-input>
                               <d-input :disabled="true" v-model="transDate" label="Date trasmission"></d-input>
                               <d-input :disabled="true" v-model="selectedData.demande_number" label="N° de la demande"></d-input>
                               <d-input :disabled="true" v-model="deadline" label="Deadline"></d-input>
                               <d-input :disabled="true" v-model="commercial" label="Commercial"></d-input>
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
                       <div class="row align-items-center">
                           <div class="col-md-2 col-sm-6">
                               <d-input></d-input>
                           </div>
                           <div class="col-md-4 col-sm-6">
                               <d-carpet-status-dropdown v-model="carpetStatusId"></d-carpet-status-dropdown>
                           </div>
                           <div class="col-md-2 col-sm-6">
                               <button class="btn btn-custom text-uppercase">Copie id</button>
                           </div>
                           <div class="col-md-4 col-sm-6">
                               <button class="btn btn-custom mb-2 text-uppercase">Générer matière par defaut</button>
                           </div>
                       </div>
                       <div class="row ms-2 mt-4 mb-2">
                           <div class="col-xl-4 col-md-12">
                                <d-materials-list></d-materials-list>
                           </div>
                           <div class="col-xl-8 col-md-12">
                               <div class="row">
                                   <div class="col-xl-6 col-md-12">
                                        <d-collections-dropdown v-model="collectionId"></d-collections-dropdown>
                                   </div>
                                   <div class="col-xl-6 col-md-12">
                                       <d-model-dropdown v-model="collectionId"></d-model-dropdown>
                                   </div>
                                   <div class="col-xl-6 col-md-12">
                                       <d-qualities-dropdown v-model="collectionId"></d-qualities-dropdown>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
                    <div class="col-md-12 col-xl-3">
                        <d-designer-list></d-designer-list>
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
import dCarpetStatusDropdown from "../../../components/common/d-carpet-status-dropdown.vue"

import Store from "../../../store";

import { useMeta } from '/src/composables/use-meta';
import {Helper} from "../../../composables/global-methods";
import dCollectionsDropdown from "../../../components/projet/contremarques/dropdown/d-collections-dropdown.vue";
import dModelDropdown from "../../../components/projet/contremarques/dropdown/d-model-dropdown.vue";
import dQualitiesDropdown from "../../../components/projet/contremarques/dropdown/d-qualities-dropdown.vue";
import dMaterialsDropdown from "../../../components/projet/contremarques/dropdown/d-materials-dropdown.vue";
import dMaterialsList from "../../../components/projet/contremarques/_Partials/d-materials-list.vue";
import dDesignerList from "../../../components/projet/contremarques/d-designer-list.vue";

useMeta({ title: 'Demande Image' });

const route = useRoute();
const id_di = route.params.id_di;
const id = route.params.id;
const carpetStatusId = ref(null);
const collectionId = ref(null);
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
const deadline = ref(null);
const commercial = ref(null);

</script>
