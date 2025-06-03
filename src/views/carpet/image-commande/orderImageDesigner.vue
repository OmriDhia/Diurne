<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title icon="image" :title="'Image commande'"></d-page-title>
        <template v-if="loading">
            <d-animated-skeleton :loading="loading"></d-animated-skeleton>
        </template>
        <template v-else>
            <div class="row layout-top-spacing mt-3 p-2">
                <div class="panel br-6 p-2 mt-3">
                    <div class="row ms-2 mt-2 mb-2 align-items-center">
                        <div class="col-xl-auto col-md-12 d-flex">
                            <div class="col-auto pe-1 ps-2 text-black">Format:</div>
                            <div class="col-auto pe-1 ps-2">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="formatA3" v-model="format"
                                           name="format" value="A3" disabled/>
                                    <label class="custom-control-label text-black" for="formatA3"> {{ $t('A3') }} </label>
                                </div>
                            </div>
                            <div class="col-auto pe-1 ps-1">
                                <div class="radio-success custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="formatA4" v-model="format"
                                           name="warningAdd" value="A4" disabled/>
                                    <label class="custom-control-label text-black" for="formatA4"> {{ $t('A4') }} </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-auto col-md-12 xs-12">
                            <d-unit-measurements v-model="unitOfMesurements.id" :disabled="true"></d-unit-measurements>
                        </div>
                        <div class="col-md-4 col xs-12"></div>
                        <div class="col-xl-3 col-md-4 col xs-12">
                            <d-location-dropdown
                                :disabled="true"
                                :contremarqueId="projectDi.contremarque"
                                v-model="locationId"
                            ></d-location-dropdown>
                        </div>
                    </div>
                </div>
    
                <div class="panel br-6 p-2 mt-3">
                    <div class="container p-0">
                        <div class="d-flex flex-column flex-md-row">
                            <div class="col-md-12 col-xl-4 p-2">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12">
                                        <d-input :disabled="true" v-model="customer.customerName" label="Client"></d-input>
                                        <d-input :disabled="true" v-model="contremarqueObject.designation"
                                                 label="Contremarque"></d-input>
                                        <d-input :disabled="true" v-model="transDate" label="Date trasmission"></d-input>
                                        <d-input :disabled="true" v-model="projectDi.demande_number"
                                                 label="N° de la demande"></d-input>
                                        <d-input :disabled="true" v-model="commercial" label="Commercial"></d-input>
                                        <d-input :disabled="true" v-model="currentObject.commandNumber"
                                                 label="N° de la commande"></d-input>
                                        <d-input :disabled="true" label="Transmi. ADV"></d-input>
                                        <d-input :disabled="true" label="Validation client"></d-input>
                                        <d-input :disabled="true" v-model="status.name" label="Etat de tapis"></d-input>
                                    </div>
                                </div>
                                <div class="row align-items-center justify-content-between mt-5">
                                    <d-images :locationId="locationId" :contremarqueId="contremarqueId"
                                              :diId="projectDi.project_di"></d-images>
                                </div>
                            </div>
                            <div class="col-md-12 col-xl-8 p-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row ps-2 mt-4 mb-2 justify-content-between">
                                            <div class="col-6">
                                                <img :src="$Helper.getImagePath(currentObject?.images?.[0])" class="card-img-top cursor-pointer"
                                                      alt="Image Preview" />
                                            </div>
                                            <div class="col-6">
                                                <div class="checkbox-default custom-control custom-checkbox pt-4">
                                                    <input disabled type="checkbox" class="custom-control-input"
                                                           id="hasConstraints"
                                                           v-model="customerInstruction.hasCustomerConstraints"/>
                                                    <label class="custom-control-label" for="hasConstraints">Contraintes et
                                                        remarque</label>
                                                </div>
                                                <div class="checkbox-default custom-control custom-checkbox pt-2">
                                                    <input disabled type="checkbox" class="custom-control-input"
                                                           id="hasValidateSample"
                                                           v-model="customerInstruction.hasValidateSample"/>
                                                    <label class="custom-control-label" for="hasValidateSample">Ech validée
                                                        de réf.</label>
                                                </div>
                                                <div class="checkbox-default custom-control custom-checkbox pt-2">
                                                    <input disabled type="checkbox" class="custom-control-input"
                                                           id="hasFinitionInstruction"
                                                           v-model="customerInstruction.hasFinitionInstruction"/>
                                                    <label class="custom-control-label font-size-0-8"
                                                           for="hasFinitionInstruction">Finition</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row ps-2 mt-4 mb-2 justify-content-between">
                                            <div class="col-12">
                                                <d-collections-dropdown v-model="collectionId" :disabled="true"
                                                                        :hideBtn="true"></d-collections-dropdown>
                                            </div>
                                            <div class="col-12">
                                                <d-model-dropdown v-model="modelId" :disabled="true"
                                                                  :hideBtn="true"></d-model-dropdown>
                                            </div>
                                            <div class="col-12">
                                                <d-qualities-dropdown v-model="qualityId" :disabled="true"
                                                                      :hideBtn="true"></d-qualities-dropdown>
                                            </div>
                                            <div class="col-12">
                                                <d-input label="Variation" v-model="qualityId" :disabled="true"></d-input>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row ps-2 mt-4 mb-2 justify-content-between">
                                            <div class="col-12">
                                                <div class="text-black p-0 pb-2">Commentaire commercial</div>
                                            </div>
                                            <div class="col-12">
                                                            <textarea
                                                                @change="updateImageCommand(null)"
                                                                v-model="currentObject.commercialComment"
                                                                class="w-100 h-130-forced block-custom-border"
                                                            ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row ps-2 mt-4 mb-2 justify-content-between">
                                            <div class="col-12">
                                                <div class="text-black p-0 pb-2">Commentaire ADV <span
                                                    class="required">*</span></div>
                                            </div>
                                            <div class="col-12">
                                                            <textarea
                                                                @change="updateImageCommand(null)"
                                                                v-model="currentObject.advComment"
                                                                class="w-100 h-130-forced block-custom-border"
                                                            ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <d-measurements-di :hideCalcul="true" :disabled="true"
                                                       :dimensionsProps="currentObject?.carpetDesignOrder?.carpetSpecification?.carpetDimensions"></d-measurements-di>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row p-2 align-items-center">
                                    <div class="col-auto">
                                        <button class="btn btn-custom pe-5 ps-5">Créer un RN</button>
                                    </div>
                                    <div class="col-3">
                                        <d-input label="RN" v-model="currentObject.rn" :pt="false"></d-input>
                                    </div>
                                    <div class="col-xl-1 col-md-2">
                                        <d-input v-model="currentObject.rn" :pt="false"></d-input>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-custom pe-5 ps-5">Voir RN</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-12">
                                <d-compositions
                                    :disabled="true"
                                    :hideBtn="true"
                                    :compositionData="currentObject?.carpetSpecification?.carpedComposition"
                                    :carpetSpecificationId="currentObject?.carpetSpecification?.id"
                                ></d-compositions>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-12 col-lg-4">
                                <div class="row ps-2 mt-4 mb-2 justify-content-between">
                                    <div class="col-12">
                                        <div class="text-black p-0 pb-2">Commentaire studio</div>
                                    </div>
                                    <div class="col-12">
                                                            <textarea
                                                                @change="updateImageCommand(null)"
                                                                v-model="currentObject.studioComment"
                                                                class="w-100 h-130-forced block-custom-border"
                                                            ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-8">
                                <d-designer-list :imageCommandId="currentObject.id" :designers-props="currentObject.imageCommandDesignerAssignments"></d-designer-list>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-12">
                                <d-images-list @imageLists="getOrderImage" :imagesProps="currentObject.technicalImages" :imageCommandId="currentObject.id"></d-images-list>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row p-2 justify-content-between">
                <div class="col-auto">
                    <button class="btn btn-custom pe-5 ps-5" @click="goToImages">Retour à la liste</button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-custom pe-5 ps-5" @click="annulerImageCommande(currentObject.id)">Annuler image
                        commande
                    </button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-custom pe-5 ps-5" @click="imageToStudio(currentObject.id)">Transmettre l'image au
                        studio
                    </button>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import dInput from '../../../components/base/d-input.vue';
import dCustomerDropdown from '../../../components/common/d-customer-dropdown.vue';
import dPageTitle from '../../../components/common/d-page-title.vue';
import dUnitMeasurements from '../../../components/common/d-unit-measurements.vue';
import dBtnOutlined from '../../../components/base/d-btn-outlined.vue';
import dDelete from '../../../components/common/d-delete.vue';
import VueFeather from 'vue-feather';
import axiosInstance from '../../../config/http';
import {useRoute, useRouter} from 'vue-router';
import {useStore} from 'vuex';
import {ref, onMounted, watch, computed} from 'vue';
import {carpetStatus} from '../../../composables/constants';
import contremarqueService from '../../../Services/contremarque-service';
import dMeasurementsDi from '../../../components/projet/contremarques/d-mesurement-di.vue';
import {useMeta} from '/src/composables/use-meta';
import {Helper, formatErrorViolations} from '../../../composables/global-methods';
import dCollectionsDropdown from '../../../components/projet/contremarques/dropdown/d-collections-dropdown.vue';
import dModelDropdown from '../../../components/projet/contremarques/dropdown/d-model-dropdown.vue';
import dQualitiesDropdown from '../../../components/projet/contremarques/dropdown/d-qualities-dropdown.vue';
import dDesignerList from '../../../components/tapis/image-commande/d-designer-list.vue';
import dImagesList from '../../../components/tapis/image-commande/d-images-list.vue';
import dLocationDropdown from '../../../components/projet/contremarques/dropdown/d-location-dropdown.vue';
import dCompositions from '../../../components/projet/contremarques/d-compositions.vue';
import moment from 'moment';
import dImages from "../../../components/tapis/image-commande/d-images.vue";
import DAnimatedSkeleton from "@/components/base/d-animated-skeleton.vue";
// src/views/projects/suiviDi/orderImageDesigner.vue
// src/composables/global-methods.js
const selectedImageTypes = ref([]);
const hideForTrans = ref(false);
const hideForAttributePause = ref(false);
// Handle designer addition from the child component

const updateDesignerList = (types) => {
    selectedImageTypes.value = types;
};
watch(
    () => selectedImageTypes.value,
    (newSelectedImageTypes) => {
        // console.log('Selected Image Types:', newSelectedImageTypes);
    }
);
const materials = ref([]);
const updateMaterials = (updatedMaterials) => {
    // Push the new material to the materials array
    materials.value = updatedMaterials;
};
watch(
    () => materials.value,
    (newMaterials) => {
        // console.log('Updated materials:', newMaterials);
    }
);
const checkIfMaterialsIsNotEmpty = () => {
    // Check if the array is not empty
    if (materials.value && materials.value.length > 0) {
        return true;
    }
    window.showMessage('Le Table de matiéres ne peut pas être vide', 'error');
    return false;
};
useMeta({title: 'Maquette'});

const store = useStore();

const route = useRoute();
const router = useRouter();
const orderImageId = route.params.id ?? null;

const loading = ref(false);
const collectionId = ref(null);
const modelId = ref(null);
const qualityId = ref(null);
const selected = ref(null);
const format = ref('');
const unitOfMesurements = ref('');
const contremarque = ref({});
const customer = ref({});
const transDate = ref();
const deadline = ref(null);
const commercial = ref(null);
const currentObject = ref({});
const customerInstruction = ref({});
const contremarqueId = ref(null);
const locationId = ref(null);
const contremarqueObject = ref({});
const status = ref({});

const error = ref({});
const projectDi = ref({});

const getOrderImage = async () => {
    try {
        loading.value = true;
        if (orderImageId) {
            const res = await axiosInstance.get(`/api/image-command/${orderImageId}`);
            currentObject.value = res.data.response;
            locationId.value = currentObject.value?.carpetDesignOrder?.location?.location_id;
            contremarqueId.value = currentObject.value?.carpetDesignOrder.location.contremarque_id;
            collectionId.value = currentObject.value?.carpetSpecification.collection.id;
            modelId.value = currentObject.value?.carpetSpecification.model.id;
            qualityId.value = currentObject.value?.carpetSpecification.quality.id;
            customerInstruction.value = currentObject.value?.carpetDesignOrder.customerInstruction;
            status.value = currentObject.value?.status || {};
            // console.log('image-commande: ', currentObject.value);
            // console.log('image-commande carpetDesignOrder: ', currentObject.value?.carpetDesignOrder);
            // console.log('image-commande carpetSpecification: ', currentObject.value?.carpetSpecification);
            if (contremarqueId.value) {
                contremarqueObject.value = await contremarqueService.getContremarqueById(contremarqueId.value)
                customer.value = contremarqueObject.value.customer;
                commercial.value = contremarqueObject.value.commercials ? contremarqueObject.value.commercials[0].firstname + ' ' + contremarqueObject.value.commercials[0].lastname : '';
            }
            projectDi.value = await contremarqueService.getProjectDiById(currentObject.value.carpetDesignOrder.projectDi);
            if (projectDi.value) {
                unitOfMesurements.value = projectDi.value.unit;
                format.value = projectDi.value.format;
                transDate.value = projectDi.value.transmition_date ? Helper.FormatDate(projectDi.value.transmition_date.date) : '';
            }
            console.log('contremarqueObject: ', contremarqueObject.value);
        }
        loading.value = false;
    } catch (e) {
        loading.value = false;
        console.log(e);
    }
};

onMounted(() => {
    getOrderImage();
});

const updateImageCommand = async (statusId) => {
    try {
        const payload = {
            commercialComment: currentObject.value.commercialComment,
            advComment: currentObject.value.advComment,
            commandNumber: currentObject.value.commandNumber,
            rn: currentObject.value.rn,
            studioComment: currentObject.value.studioComment,
            status_id: statusId ? statusId : currentObject.value.status
        }
        if (orderImageId) {
            const res = await axiosInstance.put(`/api/image-command/${orderImageId}`, payload);
            window.showMessage('Mise à jour avec succées.');
        }
    } catch (e) {
        console.log(e);
        if (e.response.data && e.response.data.violations) {
            error.value = formatErrorViolations(e.response.data.violations);
        }
        window.showMessage(e.message, 'error');
    }
};

const goToImages = () => {
    router.push({name: 'images'});
}
const annulerImageCommande = async (id) => {
    if (orderImageId) {
        const res = await axiosInstance.put(`/api/cancelImageCommand/${orderImageId}`);
        window.showMessage('Image commande a été annuler avec succées.');
        setTimeout(goToImages,2000)
    }
}
const imageToStudio = (id) => {
    updateImageCommand(carpetStatus.transmisId);
}
</script>

<style>
.is-invalid {
    border: 1px solid red !important;
    /* background-color: #ffe6e6 !important; */
}

.invalid-feedback {
    color: red;
    font-size: 12px;
    margin-top: 4px;
}
</style>
