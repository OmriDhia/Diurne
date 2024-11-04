<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title icon="image" :title="'Maquette'"></d-page-title>

        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-2 mt-3">
                <div class="row ms-2 mt-2 mb-2">
                    <div class="col-xl-auto col-md-12 d-flex">
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
                    <div class="col-xl-auto col-md-12 xs-12">
                        <d-unit-measurements v-model="unitOfMesurements.id" :disabled="true"></d-unit-measurements>
                    </div>
                    <div class="col-md-4 col xs-12">

                    </div>
                    <div class="col-xl-3 col-md-4 col xs-12">

                    </div>
                </div>
            </div>
            
            <div class="panel br-6 p-2 mt-3">
                <div class="container p-0">
                <div class="row m-2">
                   <div class="col-md-12 col-xl-9 pe-4">
                       <div class="container p-0">
                           <div class="row">
                               <div class="col-xl-4 col-md-12">
                                   <d-input :disabled="true" v-model="customer.customerName" label="Client"></d-input>
                                   <d-input :disabled="true" v-model="contremarque.designation" label="Contremarque"></d-input>
                                   <d-location-dropdown :contremarqueId="projectDi.contremarque" v-model="dataCarpetOrder.location_id" :error="errorCarpetOrder.location_id"></d-location-dropdown>
                                   <d-input :disabled="true" v-model="transDate" label="Date trasmission"></d-input>
                                   <d-input :disabled="true" v-model="projectDi.demande_number" label="N° de la demande"></d-input>
                                   <d-input :disabled="true" v-model="deadline" label="Deadline"></d-input>
                                   <d-input :disabled="true" v-model="commercial" label="Commercial"></d-input>
                               </div>
                               <div class="col-xl-8 col-md-12 pe-2">
                                   <d-attachments :carpetDesignOrderId="carpetDesignOrderId"></d-attachments>
                               </div>
                           </div>
                           <div class="row align-items-center justify-content-between mt-5" v-if="carpetDesignOrderId">
                               <!--div class="col-md-auto col-sm-6">
                                   <d-input></d-input>
                               </div-->
                               <div class="col-md-auto col-sm-6">
                                   <d-carpet-status-dropdown v-model="dataCarpetOrder.status_id"></d-carpet-status-dropdown>
                               </div>
                               <div class="col-md-auto col-sm-6">
                                   <button class="btn btn-custom text-uppercase">Copie id</button>
                               </div>
                               <div class="col-md-auto col-sm-6">
                                   <button class="btn btn-custom mb-2 text-uppercase" @click="applyDefaultMaterials">Générer matière par defaut</button>
                               </div>
                           </div>
                           <div class="row ps-2 mt-4 mb-2"  v-if="carpetDesignOrderId && firstLoad">
                               <div class="alert alert-light-primary alert-dismissible border-0 mb-4" role="alert">
                                   <strong>Pour des raisons de performance, la sauvegarde automatique sera bloquée pendant 5 secondes.</strong>
                               </div>
                           </div>
                           <div class="row ps-2 mt-4 mb-2"  v-if="carpetDesignOrderId">
                               <div class="col-xl-4 col-md-12">
                                    <d-materials-list  :disabled="disableForDesigner" :firstLoad="firstLoad" @changeMaterials="saveCarpetOrderSpecifications" :materialsProps="currentMaterials"></d-materials-list>
                               </div>
                               <div class="col-xl-8 col-md-12">
                                   <div class="row">
                                       <div class="col-xl-6 col-md-12">
                                            <d-collections-dropdown :disabled="disableForDesigner" v-model="dataSpecification.collectionId" :error="errorCarpetOrdeSpecification.collectionId"></d-collections-dropdown>
                                       </div>
                                       <div class="col-xl-6 col-md-12">
                                           <d-model-dropdown :disabled="disableForDesigner" v-model="dataSpecification.modelId" :error="errorCarpetOrdeSpecification.modelId"></d-model-dropdown>
                                       </div>
                                       <div class="col-xl-6 col-md-12">
                                           <d-qualities-dropdown :disabled="disableForDesigner" v-model="dataSpecification.qualityId" :error="errorCarpetOrdeSpecification.qualityId"></d-qualities-dropdown>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <div class="row ps-2 mt-4 mb-2 justify-content-between"  v-if="carpetDesignOrderId">
                               <d-measurements-di :firstLoad="firstLoad" @changeMeasurements="saveCarpetOrderSpecifications" :dimensionsProps="currentDimensions" ></d-measurements-di>
                           </div>
                           <div class="row ps-2 mt-4 mb-2 justify-content-between"  v-if="carpetDesignOrderId">
                               <div class="col-12"> 
                                   <div class="text-black p-0 pb-2">Description de l'image</div>
                               </div>
                               <div class="col-12">
                                   <textarea v-model="dataSpecification.description" class="w-100 h-130-forced block-custom-border"></textarea>
                               </div>
                           </div>
                           <div class="row ps-2 mt-4 mb-2 justify-content-between"  v-if="carpetDesignOrderId">
                               <d-transmis-studio @transmisStudio="updateCarpetDesignStatus($event)"></d-transmis-studio>
                           </div>
                           <div class="row ps-2 mt-4 mb-2 justify-content-between"  v-if="carpetSpecificationId">
                               <div class="col-12">
                                   <d-compositions  :disabled="disableForCommercial" :compositionData="compositionData" :carpetSpecificationId="carpetSpecificationId" v-if="carpetDesignOrderId"></d-compositions>
                               </div>
                           </div>
                           <div class="row ps-2 mt-4 mb-2 justify-content-between"  v-if="carpetDesignOrderId">
                               <d-transmis-adv @transmisAdv="updateCarpetDesignStatus($event)"></d-transmis-adv>
                           </div>
                       </div>
                   </div>
                    <div class="col-md-12 col-xl-3 ps-1" v-if="carpetDesignOrderId">
                        <d-designer-list  @endCarpetDesignOrder="updateCarpetDesignStatus($event)" :carpetDesignOrderId="carpetDesignOrderId" :designersProps="currentCarpetObject.designers"></d-designer-list>
                        <d-images-list :carpetDesignOrderId="carpetDesignOrderId"></d-images-list>
                        <d-designer-composition-list :designerComposition="designerComposition" :carpetSpecificationId="carpetSpecificationId" v-if="carpetSpecificationId"></d-designer-composition-list>
                    </div>
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
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';
import { ref, onMounted, watch, computed } from 'vue';
import {carpetStatus, filterContremarque} from '../../../composables/constants';
import contremarqueService from "../../../Services/contremarque-service";
import dCarpetStatusDropdown from "../../../components/common/d-carpet-status-dropdown.vue"
import dMeasurementsDi from "../../../components/projet/contremarques/d-mesurement-di.vue"
import { useMeta } from '/src/composables/use-meta';
import {Helper} from "../../../composables/global-methods";
import dCollectionsDropdown from "../../../components/projet/contremarques/dropdown/d-collections-dropdown.vue";
import dModelDropdown from "../../../components/projet/contremarques/dropdown/d-model-dropdown.vue";
import dQualitiesDropdown from "../../../components/projet/contremarques/dropdown/d-qualities-dropdown.vue";
import dMaterialsDropdown from "../../../components/projet/contremarques/dropdown/d-materials-dropdown.vue";
import dMaterialsList from "../../../components/projet/contremarques/_Partials/d-materials-list.vue";
import dDesignerList from "../../../components/projet/contremarques/d-designer-list.vue";
import dImagesList from "../../../components/projet/contremarques/d-images-list.vue";
import dLocationDropdown from "../../../components/projet/contremarques/dropdown/d-location-dropdown.vue";
import dCompositions from "../../../components/projet/contremarques/d-compositions.vue";
import dDesignerCompositionList from "../../../components/projet/contremarques/d-designer-composition-list.vue";
import dAttachments from "../../../components/projet/contremarques/_Partials/d-attachments.vue";
import dTransmisStudio from "../../../components/projet/contremarques/d-transmis-studio.vue";
import dTransmisAdv from "../../../components/projet/contremarques/d-transmis-adv.vue";

useMeta({ title: 'Maquette' });

const store = useStore();

const route = useRoute();
const router = useRouter();
const id_di = route.params.id_di;
const carpetDesignOrderId = route.params.carpetDesignOrderId ?? null ;

const carpetStatusId = ref(null);
const collectionId = ref(null);
const datas = ref([]);
const selected = ref(null);
const selectedData = ref({});
const format = ref("");
const unitOfMesurements = ref("");
const contremarque = ref({});
const customer = ref({});
const transDate = ref("");
const carpetDesign = ref([]);
const deadline = ref(null);
const commercial = ref(null);
const currentCarpetObject = ref({});
const currentMaterials = ref({});
const currentDimensions = ref({});
const specification = ref({});
const dataCarpetOrder = ref({
    location_id: 0,
    status_id: 0
});
const errorCarpetOrder = ref({});
const errorCarpetOrdeSpecification = ref({});
const dataSpecification = ref({
    reference: "",
    description: "",
    collectionId: 0,
    modelId: 0,
    qualityId: 0,
    hasSpecialShape: false,
    isOversized: false,
    specialShapeId: 0,
    dimensions: {},
    materials: []
});
const projectDi = ref({});
const compositionData = ref({});
const designerComposition = ref([]);
const carpetSpecificationId = ref(0);
const firstLoad = ref(true);

const disableForCommercial = computed(() => {
    return store.getters.isDesigner || store.getters.isDesignerManager || store.getters.isFinStatus;
});
const disableForDesigner = computed(() => {
    return store.getters.isCommertial || store.getters.isCommercialManager || store.getters.isFinStatus;
});

const getProjectDI = async () => {
    try{
        projectDi.value = await contremarqueService.getProjectDiById(id_di);
        if(projectDi.value){
            unitOfMesurements.value = projectDi.value.unit;
            format.value = projectDi.value.format;
            deadline.value = projectDi.value.deadline ? Helper.FormatDate(projectDi.value.deadline.date) : '';
            transDate.value = projectDi.value.transmition_date ? Helper.FormatDate(projectDi.value.transmition_date.date) : '';
            contremarque.value = await contremarqueService.getContremarqueById(projectDi.value.contremarque);
            commercial.value = (contremarque.value.commercials) ? contremarque.value.commercials[0].firstname + " " + contremarque.value.commercials[0].lastname : "";
            customer.value = contremarque.value.customer;
        }
        
    }catch (e){
        console.log(e);
        console.log("Erreur get events customer")
    }
};

const getOrderCarpet = async (id) => {
    try{
        if(id){
            const res = await axiosInstance.get(`/api/carpet-design-orders/${id}`);
            currentCarpetObject.value = res.data.response;
            dataCarpetOrder.value.location_id =  (currentCarpetObject.value.location && currentCarpetObject.value.location.location_id) ? currentCarpetObject.value.location.location_id : 0;
            dataCarpetOrder.value.status_id =  (currentCarpetObject.value.status && currentCarpetObject.value.status.id) ? currentCarpetObject.value.status.id : 0;
            store.commit('setCarpetDesignOrderStatus', dataCarpetOrder.value.status_id);
            store.commit('setIsFinStatus', dataCarpetOrder.value.status_id === carpetStatus.finiId);
            const dSP = currentCarpetObject.value.carpetSpecification;
            if(dSP){
                carpetSpecificationId.value = dSP.id;
                currentDimensions.value = dSP.carpetDimensions;
                currentMaterials.value = dSP.carpetMaterials;
                compositionData.value = dSP.carpedComposition;
                designerComposition.value = dSP.designMaterials;
                dataSpecification.value = {
                    id: dSP.id,
                    reference: "",
                    description: dSP.description,
                    collectionId: dSP.collection ? dSP.collection.id : 0,
                    modelId: dSP.model ? dSP.model.id : 0,
                    qualityId: dSP.quality ? dSP.quality.id : 0,
                    hasSpecialShape: dSP.has_special_shape,
                    isOversized: dSP.is_oversized,
                    specialShapeId: dSP.specialShape ? dSP.specialShape.id : 0,
                };
            }
        }
    }catch (e){
        console.log(e)
    }
};

onMounted( () => {
    getOrderCarpet(carpetDesignOrderId);
    getProjectDI();
    setTimeout(() => {
        firstLoad.value = false;
    },5000);
});

const saveCarpetOrder = async () => {
    try{
        if(carpetDesignOrderId){
            const res = await axiosInstance.put(`/api/carpet-design-order/${carpetDesignOrderId}`,dataCarpetOrder.value);
            window.showMessage("Mise à jour avec succées.");
        }else{
            const res = await axiosInstance.post(`/api/projectDi/${id_di}/carpet-design-order`,dataCarpetOrder.value);
            window.showMessage("Ajout avec succées.");
            setTimeout(() => {
                const resolvedRoute = router.resolve({ name: 'di_orderDesigner_update', params: { id_di: id_di,carpetDesignOrderId: res.data.response.id}});
                document.location.href = resolvedRoute.href
                },2000);
        }
        
    }catch (e){
        if(e.response.data && e.response.data.violations){
            error.value = formatErrorViolations(e.response.data.violations);
        }
        window.showMessage(e.message,'error')
    }
};

const updateCarpetDesignStatus = async (statusId) => {
    dataCarpetOrder.value.status_id = statusId;
    store.commit('setCarpetDesignOrderStatus', statusId);
    store.commit('setIsFinStatus', statusId === carpetStatus.finiId);
    await saveCarpetOrder();
}

const saveCarpetOrderSpecifications = async () => {
    try{
        const measurements = store.getters.measurements;
        const dataRequest = Object.assign({}, dataSpecification.value);
        dataRequest.dimensions = measurements.reduce((acc, dimension) => {
            acc[dimension.id] = dimension.unit.map(u => {
                return {
                    dimension_id: u.id,
                    value: u.value ? parseFloat(u.value) : 0
                }
            });
            return acc;
        }, {});

        dataRequest.materials = store.getters.materials;
        
        if(carpetSpecificationId.value){
            const res = await axiosInstance.put(`/api/carpetDesignOrder/${carpetDesignOrderId}/updateCarpetSpecification/${carpetSpecificationId.value}`, dataRequest);
            window.showMessage("Mise a jour avec succées.");
        }else{
            const res = await axiosInstance.post(`/api/carpetDesignOrder/${carpetDesignOrderId}/createCarpetSpecification`, dataRequest);
            window.showMessage("Ajout avec succées.");
            carpetSpecificationId.value = res.data.response.id;
        }
        
    }catch (e){
        if(e.response.data && e.response.data.violations){
            error.value = formatErrorViolations(e.response.data.violations);
        }
        window.showMessage(e.message,'error')
    }
};

const applyDefaultMaterials = async () => {
    try{
        const materials = await contremarqueService.getDefaultMaterials();
        currentMaterials.value = materials.map(m => {
           return {
               material_id: m.materialId,
               rate: parseFloat(m.percentage)
           }
        });
        window.showMessage('Les matières par défaut sont bien appliquées.')
    }catch(e){
        console.log(e)
    }
};

watch(
    () => JSON.parse(JSON.stringify(dataCarpetOrder.value)),
    async (newCarpert, oldCarpet) => {
        if ((oldCarpet?.location_id && carpetDesignOrderId) || !carpetDesignOrderId) {
            await saveCarpetOrder();
        }
    },
    { deep: true }
);

watch(
    () => dataSpecification.value,
    async (newDataSpecification) => {
        if(!firstLoad.value){
            await saveCarpetOrderSpecifications(); 
        };
    },
    { deep: true }
);

</script>
