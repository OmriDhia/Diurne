<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title icon="image" :title="'Image commande'"></d-page-title>
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
                                    <d-input :disabled="true" v-model="commercial" label="Transmi. ADV"></d-input>
                                    <d-input :disabled="true" v-model="commercial" label="Validation client"></d-input>
                                    <d-input :disabled="true" v-model="commercial" label="Etat de tapis"></d-input>
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
                                            <!-- image légende -->
                                        </div>
                                        <div class="col-6">
                                            <div class="checkbox-default custom-control custom-checkbox pt-4">
                                                <input disabled type="checkbox" class="custom-control-input"
                                                       id="hasConstraints"
                                                       v-model="customerInstruction.hasConstraints"/>
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
                                                            disabled
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
                                :disabled="false"
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
                                                            v-model="currentObject.studioComment"
                                                            class="w-100 h-130-forced block-custom-border"
                                                        ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-8">
                            <d-designer-list></d-designer-list>
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-md-12">
                            <d-images-list :imageCommandeId="orderImageId"></d-images-list>
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
const id_di = route.params.id_di;
const orderImageId = route.params.id ?? null;

const carpetStatusId = ref(null);
const collectionId = ref(null);
const modelId = ref(null);
const qualityId = ref(null);
const datas = ref([]);
const selected = ref(null);
const selectedData = ref({});
const format = ref('');
const unitOfMesurements = ref('');
const contremarque = ref({});
const customer = ref({});
const transDate = ref();
const carpetDesign = ref([]);
const deadline = ref(null);
const commercial = ref(null);
const currentObject = ref({});
const customerInstruction = ref({});
const contremarqueId = ref(null);
const locationId = ref(null);
const contremarqueObject = ref({});
const specification = ref({});
const dataCarpetOrder = ref({
    location_id: 0,
    status_id: carpetStatus.nonTransmisId,
});
const handleDesignerAdded = (status) => {
    // You can perform other actions here, like updating the list of designers, etc.
    dataCarpetOrder.value.status_id = status;
};
const errorCarpetOrder = ref({});
const error = ref({});

const errorCarpetOrdeSpecification = ref({});
const dataSpecification = ref({
    reference: '',
    description: '',
    collectionId: 0,
    modelId: 0,
    qualityId: 0,
    hasSpecialShape: false,
    isOversized: false,
    specialShapeId: 0,
    dimensions: {},
    materials: [],
});
const projectDi = ref({});
const compositionData = ref({});
const designerComposition = ref([]);
const carpetSpecificationId = ref(0);
const firstLoad = ref(true);

const setHideForTrans = () => {
    hideForTrans.value = (dataCarpetOrder.value.status_id === carpetStatus.transmisId || dataCarpetOrder.value.status_id === carpetStatus.nonTransmisId);
    hideForAttributePause.value = (dataCarpetOrder.value.status_id === carpetStatus.attribuId || dataCarpetOrder.value.status_id === carpetStatus.enPauseId || dataCarpetOrder.value.status_id === carpetStatus.enCoursId)
};
// const disableForCommercial = computed(() => {
//     return ((store.getters.isCommertial || store.getters.isCommercialManager) && !store.getters.isNonTrasmisStatus) || store.getters.isFinStatus;
// });
const disableForCommercial = computed(() => {
    return (store.getters.isCommertial || store.getters.isCommercialManager) && !store.getters.isNonTrasmisStatus;
});
const disableDocuments = computed(() => {
    return !store.getters.isNonTrasmisStatus;
});
const CommercialAccess = computed(() => {
    return store.getters.isCommertial || store.getters.isCommercialManager || store.getters.isSuperAdmin;
});
const DesignerAccess = computed(() => {
    return store.getters.isDesigner || store.getters.isDesignerManager || store.getters.isSuperAdmin;
});
const disableForDesigner = computed(() => {
    return store.getters.isDesigner || store.getters.isDesignerManager || !store.getters.isNonTrasmisStatus;
});
const displayFin = computed(() => {
    const arrayTypes = Object.values(selectedImageTypes.value);
    return (store.getters.isDesigner || store.getters.isDesignerManager || store.getters.isSuperAdmin) && !store.getters.isFinStatus && arrayTypes.includes('Vignette') && (arrayTypes.includes('Légende A3') || arrayTypes.includes('Légende A4'));
});
const CommercialAccessADV = computed(() => {
    return (store.getters.isCommertial || store.getters.isCommercialManager || store.getters.isSuperAdmin) && store.getters.isFinStatus;
});
watch(CommercialAccessADV, (newValue, oldValue) => {
    console.log('new value', newValue);
});

const getProjectDI = async () => {
    try {
        projectDi.value = await contremarqueService.getProjectDiById(id_di);
        if (projectDi.value) {
            unitOfMesurements.value = projectDi.value.unit;
            format.value = projectDi.value.format;
            deadline.value = projectDi.value.deadline ? Helper.FormatDate(projectDi.value.deadline.date) : '';
            transDate.value = projectDi.value.transmition_date ? Helper.FormatDate(projectDi.value.transmition_date.date) : '';
            contremarque.value = await contremarqueService.getContremarqueById(projectDi.value.contremarque);
            commercial.value = contremarque.value.commercials ? contremarque.value.commercials[0].firstname + ' ' + contremarque.value.commercials[0].lastname : '';
            customer.value = contremarque.value.customer;
            firstLoad.value = false;
        }
    } catch (e) {
        console.log(e);
        console.log('Erreur get events customer');
    }
};

const getOrderImage = async () => {
    try {
        if (orderImageId) {
            const res = await axiosInstance.get(`/api/image-command/${orderImageId}`);
            currentObject.value = res.data.response;
            locationId.value = currentObject.value?.carpetDesignOrder?.location?.location_id;
            contremarqueId.value = currentObject.value?.carpetDesignOrder.location.contremarque_id;
            collectionId.value = currentObject.value?.carpetSpecification.collection.id;
            modelId.value = currentObject.value?.carpetSpecification.model.id;
            qualityId.value = currentObject.value?.carpetSpecification.quality.id;
            customerInstruction.value = currentObject.value?.carpetDesignOrder.customerInstruction;
            console.log('image-commande: ', currentObject.value);
            console.log('image-commande carpetDesignOrder: ', currentObject.value?.carpetDesignOrder);
            console.log('image-commande carpetSpecification: ', currentObject.value?.carpetSpecification);
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
    } catch (e) {
        console.log(e);
    }
};

onMounted(() => {
    //getOrderCarpet(carpetDesignOrderId);
    getOrderImage();
    // console.log(store.getters.isFinStatus, CommercialAccessADV.value, 'yassssssssssssssssssssssine');

    // setTimeout(() => {
    //     firstLoad.value = false;
    //     // console.log(store.getters.isFinStatus, CommercialAccessADV.value, 'yassssssssssssssssssssssine');
    // }, 1000);

    // console.log('role : ', DesignerAccess.value);
});

const CreateVariation = async () => {
    try {
        const response = await axiosInstance.post('/api/createCarpetDesignOrderVariation', {
            orderId: parseInt(carpetDesignOrderId),
            variation: '',
        });

        console.log('Variation created:', response.data);
        // Handle success response, maybe show a notification or update the UI
        router.push({
            name: 'di_orderDesigner_update',
            params: {id_di: id_di, carpetDesignOrderId: response.data.response.id}
        });
    } catch (error) {
        console.error('Error creating variation:', error);
        // Handle error, show an error message to the user
    }
};

const saveCarpetOrder = async (statusId) => {
    try {
        if (carpetDesignOrderId) {
            const res = await axiosInstance.put(`/api/carpet-design-order/${carpetDesignOrderId}`, dataCarpetOrder.value);
            window.showMessage('Mise à jour avec succées.');
            transDate.value = moment().format('YYYY-MM-DD HH:mm:ss');
            applyCarpetStatus(statusId);
        } else {
            const res = await axiosInstance.post(`/api/projectDi/${id_di}/carpet-design-order`, dataCarpetOrder.value);
            window.showMessage('Ajout avec succées.');
            setTimeout(() => {
                router.push({
                    name: 'di_orderDesigner_update',
                    params: {id_di: id_di, carpetDesignOrderId: res.data.response.id}
                });
            }, 2000);
        }
    } catch (e) {
        console.log(e);
        if (e.response.data && e.response.data.violations) {
            error.value = formatErrorViolations(e.response.data.violations);
        }
        window.showMessage(e.message, 'error');
    }
};

const errorCarpetDesignOrder = ref({
    description: false, // New error field for carpet order description
    materialsRate: false, // New error field for material rates
    collectionID: false, // New error field for collection ID
    qualityID: false, // New error field for quality ID
    ModelID: false, // New error field for model ID
    measurments: false, // New error field for measurements
});

const errorTransmis = ref(false);

const ValidateBeforeTransmission = () => {
    // validate Material Rate  100
    const totalRate = store.getters.materials.reduce((acc, m) => acc + m.rate, 0);
    if (totalRate < 100 || totalRate === 0) {
        errorCarpetDesignOrder.value.materialsRate = true;
    } else {
        errorCarpetDesignOrder.value.materialsRate = false;
    }
    // validate collection
    if (!dataSpecification.value.collectionId) {
        errorCarpetDesignOrder.value.collectionID = true;
    } else {
        errorCarpetDesignOrder.value.collectionID = false;
    }
    // validate model
    if (!dataSpecification.value.modelId) {
        errorCarpetDesignOrder.value.ModelID = true;
    } else {
        errorCarpetDesignOrder.value.ModelID = false;
    }
    // validate quality
    if (!dataSpecification.value.qualityId) {
        errorCarpetDesignOrder.value.qualityID = true;
    } else {
        errorCarpetDesignOrder.value.qualityID = false;
    }

    // validate measurements
    const measurements = store.getters.measurements;
    const areAllValuesValid = measurements.every((measurement) =>
        measurement.unit.every((unit) => {
            const value = unit.value;
            // Ensure value is a valid number and not 0
            const numericValue = Number(value); // Converts string "0" to 0, and non-numeric strings to NaN
            return value !== '' && numericValue !== 0 && !isNaN(numericValue) && value !== null && value !== undefined;
        })
    );

    if (!areAllValuesValid) {
        console.log('areAllValuesValid: ', areAllValuesValid);
        errorCarpetDesignOrder.value.measurments = true;
    } else {
        errorCarpetDesignOrder.value.measurments = false;
    }

    // validate description
    if (!dataSpecification.value.description.trim()) {
        errorCarpetDesignOrder.value.description = true;
    } else {
        errorCarpetDesignOrder.value.description = false;
    }

    if (
        errorCarpetDesignOrder.value.materialsRate ||
        errorCarpetDesignOrder.value.collectionID ||
        errorCarpetDesignOrder.value.ModelID ||
        errorCarpetDesignOrder.value.qualityID ||
        errorCarpetDesignOrder.value.measurments ||
        errorCarpetDesignOrder.value.description
    ) {
        errorTransmis.value = true;
    } else {
        errorTransmis.value = false;
    }
};

const FinDIStatus = async (statusId) => {
    // Assuming compositionData is a reactive reference
    const isEmpty = compositionData.value.length === 0;

    if (isEmpty) {
        window.showMessage('La composition de la maquettes ne doit pas etres vide.', 'error');
        console.log('The array has NOOOO elements:', compositionData.value);
    }
    const imageTypesArray = Array.from(selectedImageTypes.value);
    console.log(selectedImageTypes.value, imageTypesArray);
    //&& imageTypesArray.some((name) => name.includes('Légende'))
    if (imageTypesArray.includes('Vignette')) {
        dataCarpetOrder.value.status_id = statusId;

        if (checkIfMaterialsIsNotEmpty()) {
            await saveCarpetOrder(statusId);
        }
    } else {
        window.showMessage('Les images doivent contenir au moins une Vignettepour terminer.', 'error');
        // window.showMessage("Les images doivent contenir une Vignette et une image avec 'Légende' pour terminer.", 'error');
    }
};

const updateCarpetDesignStatus = async (statusId, forced = false) => {
    ValidateBeforeTransmission();
    if (!errorTransmis.value || forced) {
        await saveCarpetOrder(statusId);
        dataCarpetOrder.value.status_id = statusId;
    }
};
//dataCarpetOrder.location_id
watch(
    () => dataCarpetOrder.value.location_id,
    async (newID) => {
        if (!firstLoad.value) {
            await saveCarpetOrder();
        }
    },
    {deep: true}
);
watch(
    () => dataCarpetOrder.value.status_id,
    (newID) => {
        console.log('newID', newID);
        setHideForTrans();
    },
    {deep: true}
);
watch(
    () => CommercialAccessADV.value,
    (newValue, oldValue) => {
        console.log('newValue', newValue);
    }
);
const applyCarpetStatus = (statusId) => {
    store.commit('setCarpetDesignOrderStatus', statusId);
    store.commit('setIsFinStatus', statusId === carpetStatus.finiId);
    // console.log('applyCarpetStatus : ', statusId,carpetStatus.nonTransmisId);
    store.commit('setIsNonTrasmisStatus', statusId === carpetStatus.nonTransmisId);
    if (statusId === 0) {
        store.commit('setIsNonTrasmisStatus', true);
    }
};

const saveCarpetOrderSpecifications = async () => {
    try {
        const measurements = store.getters.measurements;
        const dataRequest = Object.assign({}, dataSpecification.value);
        dataRequest.dimensions = measurements.reduce((acc, dimension) => {
            acc[dimension.id] = dimension.unit.map((u) => {
                return {
                    dimension_id: u.id,
                    value: u.value ? parseFloat(u.value) : 0,
                };
            });
            return acc;
        }, {});

        dataRequest.materials = store.getters.materials;
        ValidateBeforeTransmission();
        if (carpetSpecificationId.value && !errorTransmis.value) {
            const res = await axiosInstance.put(`/api/carpetDesignOrder/${carpetDesignOrderId}/updateCarpetSpecification/${carpetSpecificationId.value}`, dataRequest);
            window.showMessage('Mise a jour avec succées.');
        } else if (!errorTransmis.value) {
            const res = await axiosInstance.post(`/api/carpetDesignOrder/${carpetDesignOrderId}/createCarpetSpecification`, dataRequest);
            window.showMessage('Ajout avec succées.');
            carpetSpecificationId.value = res.data.response.id;
        }
    } catch (e) {
        if (e.response.data && e.response.data.violations) {
            error.value = formatErrorViolations(e.response.data.violations);
            //errorCarpetDesignOrder.value.measurments = !!error.value["dimensions"];
            //errorCarpetDesignOrder.value.description = !!error.value["description"];
        }
        window.showMessage(e.message, 'error');
    }
};

const applyDefaultMaterials = async () => {
    try {
        const materials = await contremarqueService.getDefaultMaterials();
        currentMaterials.value = materials.map((m) => {
            return {
                material_id: m.materialId,
                rate: parseFloat(m.percentage),
            };
        });
        window.showMessage('Les matières par défaut sont bien appliquées.');
    } catch (e) {
        console.log(e);
    }
};

watch(
    () => dataSpecification.value,
    async (newDataSpecification) => {
        if (!firstLoad.value) {
            await saveCarpetOrderSpecifications();
        }
    },
    {deep: true}
);

const goToImages = () => {
    router.push({name: 'images'});
}
const annulerImageCommande = (id) => {
    console.log('annulerImageCommande', id);
}
const imageToStudio = (id) => {
    console.log('imageToStudio', id);
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
