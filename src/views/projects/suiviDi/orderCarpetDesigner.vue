<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title icon="image" :title="'Maquette'"></d-page-title>

        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-2 mt-3">
                <div class="row ms-2 mt-2 mb-2">
                    <div class="col-xl-auto col-md-12 d-flex">
                        <div class="col-auto pe-1 ps-2 text-black">Format:</div>
                        <div class="col-auto pe-1 ps-2">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="formatA3" v-model="format" name="format" value="A3" disabled />
                                <label class="custom-control-label text-black" for="formatA3"> {{ $t('A3') }} </label>
                            </div>
                        </div>
                        <div class="col-auto pe-1 ps-1">
                            <div class="radio-success custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="formatA4" v-model="format" name="warningAdd" value="A4" disabled />
                                <label class="custom-control-label text-black" for="formatA4"> {{ $t('A4') }} </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-auto col-md-12 xs-12">
                        <d-unit-measurements v-model="unitOfMesurements.id" :disabled="true"></d-unit-measurements>
                    </div>
                    <div class="col-md-4 col xs-12"></div>
                    <div class="col-xl-3 col-md-4 col xs-12"></div>
                </div>
            </div>

            <div class="panel br-6 p-2 mt-3">
                <div class="container p-0">
                    <div class="row m-2">
                        <div class="col-md-12 pe-4">
                            <div class="container p-0">
                                <div class="d-flex flex-column flex-md-row">
                                    <div class="col-md-12 col-xl-9 pe-4">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-12">
                                                <d-input :disabled="true" v-model="customer.customerName" label="Client"></d-input>
                                                <d-input :disabled="true" v-model="contremarque.designation" label="Contremarque"></d-input>
                                                <d-location-dropdown
                                                    :disabled="store.getters.isFinStatus"
                                                    :contremarqueId="projectDi.contremarque"
                                                    v-model="dataCarpetOrder.location_id"
                                                    :error="errorCarpetOrder.location_id"
                                                ></d-location-dropdown>
                                                <d-input :disabled="true" v-model="transDate" label="Date trasmission"></d-input>
                                                <d-input :disabled="true" v-model="projectDi.demande_number" label="N° de la demande"></d-input>
                                                <d-input :disabled="true" v-model="deadline" label="Deadline"></d-input>
                                                <d-input :disabled="true" v-model="commercial" label="Commercial"></d-input>
                                            </div>
                                            <div class="col-xl-8 col-md-12 pe-2">
                                                <d-attachments :disable="disableDocuments" :carpetDesignOrderId="carpetDesignOrderId"></d-attachments>
                                            </div>
                                        </div>
                                        <div class="row align-items-center justify-content-between mt-5" v-if="carpetDesignOrderId">
                                            <div class="col-md-auto col-sm-6">
                                                <d-carpet-status-dropdown :disabled="true" v-model="dataCarpetOrder.status_id"></d-carpet-status-dropdown>
                                            </div>
                                            <div class="col-md-auto col-sm-6" v-if="!disableForDesigner">
                                                <button class="btn btn-custom mb-2 text-uppercase" @click="applyDefaultMaterials">Générer matière par defaut</button>
                                            </div>
                                        </div>
                                        <div class="row ps-2 mt-4 mb-2" v-if="carpetDesignOrderId && firstLoad">
                                            <div class="alert alert-light-primary alert-dismissible border-0 mb-4" role="alert">
                                                <strong>Pour des raisons de performance, la sauvegarde automatique sera bloquée pendant 5 secondes.</strong>
                                            </div>
                                        </div>
                                        <div class="row ps-2 mt-4 mb-2" v-if="carpetDesignOrderId">
                                            <div class="col-xl-4 col-md-12">
                                                <d-materials-list
                                                    :error="errorCarpetDesignOrder.materialsRate"
                                                    :disabled="disableForDesigner"
                                                    :firstLoad="firstLoad"
                                                    @changeMaterials="saveCarpetOrderSpecifications"
                                                    :materialsProps="currentMaterials"
                                                ></d-materials-list>
                                            </div>
                                            <div class="col-xl-8 col-md-12">
                                                <div class="row">
                                                    <div class="col-xl-6 col-md-12">
                                                        <!-- :error="errorCarpetOrdeSpecification.collectionId" -->
                                                        <d-collections-dropdown
                                                            :disabled="disableForDesigner"
                                                            v-model="dataSpecification.collectionId"
                                                            :error="error?.collectionId"
                                                            :errorCollection="errorCarpetDesignOrder.collectionID"
                                                        ></d-collections-dropdown>
                                                    </div>
                                                    <div class="col-xl-6 col-md-12">
                                                        <!-- :error="errorCarpetOrdeSpecification.modelId" -->
                                                        <d-model-dropdown
                                                            :disabled="disableForDesigner"
                                                            v-model="dataSpecification.modelId"
                                                            :error="error?.modelId"
                                                            :errorModel="errorCarpetDesignOrder.ModelID"
                                                        ></d-model-dropdown>
                                                    </div>
                                                    <div class="col-xl-6 col-md-12">
                                                        <!--:error="errorCarpetOrdeSpecification.qualityId" -->
                                                        <d-qualities-dropdown
                                                            :disabled="disableForDesigner"
                                                            v-model="dataSpecification.qualityId"
                                                            :error="error?.qualityId"
                                                            :errorQuality="errorCarpetDesignOrder.qualityID"
                                                        ></d-qualities-dropdown>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row ps-2 mt-4 mb-2 justify-content-between" v-if="carpetDesignOrderId">
                                            <d-measurements-di
                                                :disabled="disableForDesigner"
                                                :firstLoad="firstLoad"
                                                @changeMeasurements="saveCarpetOrderSpecifications"
                                                :dimensionsProps="currentDimensions"
                                                :error="errorCarpetDesignOrder.measurments"
                                            ></d-measurements-di>
                                        </div>
                                        <div class="row ps-2 mt-4 mb-2 justify-content-between" v-if="carpetDesignOrderId">
                                            <div class="col-12">
                                                <div class="text-black p-0 pb-2">Description de l'image</div>
                                            </div>
                                            <div class="col-12">
                                                <textarea
                                                    :disabled="disableForDesigner"
                                                    v-model="dataSpecification.description"
                                                    :class="{ 'is-invalid': error?.description }"
                                                    class="w-100 h-130-forced block-custom-border"
                                                ></textarea>
                                                <div v-if="error?.description" class="invalid-feedback">La description est obligatoire.</div>
                                            </div>
                                        </div>
                                        <div class="row ps-2 mt-4 mb-2 justify-content-between" v-if="carpetDesignOrderId && store.getters.isNonTrasmisStatus">
                                            <d-transmis-studio @transmisStudio="updateCarpetDesignStatus($event)"></d-transmis-studio>
                                        </div>
                                        <div class="row ps-2 mt-4 mb-2 justify-content-between">
                                            <div class="col-12" v-if="carpetSpecificationId">
                                                <d-compositions
                                                    :disabled="CommercialAccess"
                                                    :compositionData="compositionData"
                                                    :carpetSpecificationId="carpetSpecificationId"
                                                    v-if="carpetDesignOrderId"
                                                ></d-compositions>
                                            </div>
                                            <div class="row ps-2 mt-4 mb-2 justify-content-between" v-if="carpetDesignOrderId">
                                                <d-transmis-adv
                                                    :customerInstruction="currentCarpetObject?.customerInstruction"
                                                    :id_di="id_di"
                                                    :carpetDesignOrderId="carpetDesignOrderId"
                                                    @transmisAdv="updateCarpetDesignStatus($event)"
                                                    :disabled="!CommercialAccessADV"
                                                ></d-transmis-adv>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-3 ps-1 d-flex flex-column" v-if="carpetDesignOrderId">
                                        <d-designer-list
                                            :disabled="CommercialAccess"
                                            @endCarpetDesignOrder="updateCarpetDesignStatus($event)"
                                            :carpetDesignOrderId="carpetDesignOrderId"
                                            :designersProps="currentCarpetObject.designers"
                                            :imageTypeNames="selectedImageTypes"
                                            @designerAdded="handleDesignerAdded"
                                        ></d-designer-list>
                                        <d-images-list
                                            :status="dataCarpetOrder.status_id"
                                            :disabled="!DesignerAccess"
                                            @imageTypesUpdated="updateDesignerList"
                                            :carpetDesignOrderId="carpetDesignOrderId"
                                        ></d-images-list>
                                        <d-designer-composition-list
                                            :disabled="CommercialAccess"
                                            :designerComposition="designerComposition"
                                            :carpetSpecificationId="carpetSpecificationId"
                                            @updateMaterials="updateMaterials($event)"
                                            v-if="carpetSpecificationId"
                                        ></d-designer-composition-list>
                                        <div class="row align-items-end mt-auto" v-if="displayFin">
                                            <div class="col-md-12">
                                                <button class="disbaled btn btn-custom text-center w-100 mb-3" @click="FinDIStatus(6)">FIN</button>
                                            </div>
                                            <div class="col-md-12" v-if="DesignerAccess">
                                                <button class="disbaled btn btn-custom text-center w-100" @click="CreateVariation()">Créer Variation</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    import dBtnOutlined from '../../../components/base/d-btn-outlined.vue';
    import dDelete from '../../../components/common/d-delete.vue';
    import VueFeather from 'vue-feather';
    import axiosInstance from '../../../config/http';
    import { useRoute, useRouter } from 'vue-router';
    import { useStore } from 'vuex';
    import { ref, onMounted, watch, computed } from 'vue';
    import { carpetStatus, filterContremarque } from '../../../composables/constants';
    import contremarqueService from '../../../Services/contremarque-service';
    import dCarpetStatusDropdown from '../../../components/common/d-carpet-status-dropdown.vue';
    import dMeasurementsDi from '../../../components/projet/contremarques/d-mesurement-di.vue';
    import { useMeta } from '/src/composables/use-meta';
    import { Helper, formatErrorViolations } from '../../../composables/global-methods';
    import dCollectionsDropdown from '../../../components/projet/contremarques/dropdown/d-collections-dropdown.vue';
    import dModelDropdown from '../../../components/projet/contremarques/dropdown/d-model-dropdown.vue';
    import dQualitiesDropdown from '../../../components/projet/contremarques/dropdown/d-qualities-dropdown.vue';
    import dMaterialsDropdown from '../../../components/projet/contremarques/dropdown/d-materials-dropdown.vue';
    import dMaterialsList from '../../../components/projet/contremarques/_Partials/d-materials-list.vue';
    import dDesignerList from '../../../components/projet/contremarques/d-designer-list.vue';
    import dImagesList from '../../../components/projet/contremarques/d-images-list.vue';
    import dLocationDropdown from '../../../components/projet/contremarques/dropdown/d-location-dropdown.vue';
    import dCompositions from '../../../components/projet/contremarques/d-compositions.vue';
    import dDesignerCompositionList from '../../../components/projet/contremarques/d-designer-composition-list.vue';
    import dAttachments from '../../../components/projet/contremarques/_Partials/d-attachments.vue';
    import dTransmisStudio from '../../../components/projet/contremarques/d-transmis-studio.vue';
    import dTransmisAdv from '../../../components/projet/contremarques/d-transmis-adv.vue';
    import moment from 'moment';
    // src/views/projects/suiviDi/orderCarpetDesigner.vue
    // src/composables/global-methods.js
    const selectedImageTypes = ref([]);
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
    useMeta({ title: 'Maquette' });

    const store = useStore();

    const route = useRoute();
    const router = useRouter();
    const id_di = route.params.id_di;
    const carpetDesignOrderId = route.params.carpetDesignOrderId ?? null;

    const carpetStatusId = ref(null);
    const collectionId = ref(null);
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
    const currentCarpetObject = ref({});
    const currentMaterials = ref({});
    const currentDimensions = ref({});
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
        return (store.getters.isDesigner || store.getters.isDesignerManager || store.getters.isSuperAdmin) && !store.getters.isFinStatus;
    });
    const CommercialAccessADV = computed(() => {
        return (store.getters.isCommertial || store.getters.isCommercialManager) && store.getters.isFinStatus;
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
                // transDate.value = projectDi.value.transmition_date ? Helper.FormatDate(projectDi.value.transmition_date.date) : '';
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

    const getOrderCarpet = async (id) => {
        try {
            if (id) {
                const res = await axiosInstance.get(`/api/carpet-design-orders/${id}`);
                currentCarpetObject.value = res.data.response;
                dataCarpetOrder.value.location_id = currentCarpetObject.value.location && currentCarpetObject.value.location.location_id ? currentCarpetObject.value.location.location_id : 0;
                dataCarpetOrder.value.status_id = currentCarpetObject.value.status && currentCarpetObject.value.status.id ? currentCarpetObject.value.status.id : 0;
                transDate.value = currentCarpetObject.value.transmition_date ? Helper.FormatDate(currentCarpetObject.value.transmition_date.date) : '';
                applyCarpetStatus(dataCarpetOrder.value.status_id);
                const dSP = currentCarpetObject.value.carpetSpecification;
                if (dSP) {
                    carpetSpecificationId.value = dSP.id;
                    currentDimensions.value = dSP.carpetDimensions;
                    currentMaterials.value = dSP.carpetMaterials;
                    compositionData.value = dSP.carpedComposition;
                    designerComposition.value = dSP.designMaterials;
                    dataSpecification.value = {
                        id: dSP.id,
                        reference: '',
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
        } catch (e) {
            console.log(e);
        }
    };

    onMounted(() => {
        getOrderCarpet(carpetDesignOrderId);
        getProjectDI();
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
            router.push({ name: 'di_orderDesigner_update', params: { id_di: id_di, carpetDesignOrderId: response.data.response.id } });
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
                    router.push({ name: 'di_orderDesigner_update', params: { id_di: id_di, carpetDesignOrderId: res.data.response.id } });
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
        if (totalRate < 100) {
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

    const updateCarpetDesignStatus = async (statusId) => {
        dataCarpetOrder.value.status_id = statusId;
        console.log(statusId);
        ValidateBeforeTransmission();
        if (!errorTransmis.value) {
            await saveCarpetOrder(statusId);
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
        { deep: true }
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
        if (statusId === 0){
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
        { deep: true }
    );
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
