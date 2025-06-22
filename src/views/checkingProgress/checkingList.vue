<template>
    <d-base-page>
        <template #title>
            <d-page-title title="Checking List" />
        </template>

        <template v-slot:body>
            <d-panel>
                <template v-slot:panel-header>
                    <d-panel-title title="Informations générales" />
                </template>

                <template v-slot:panel-body>
                    <div class="row">
                        <div class="col-md-4">
                            <d-input label="RN" v-model="form.rn" disabled />
                        </div>
                        <div class="col-md-4">
                            <d-input label="Auteur" type="text" v-model="form.author" disabled />
                        </div>
                        <div class="col-md-4">
                            <d-input label="Date" type="date" v-model="form.date" disabled />
                        </div>
                    </div>

                    <!-- Validation Sections -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Validation forme" link=""></d-panel-title>
                                <d-radio-validation v-model="form.shapeValidation" :options="validationOptions" />
                                <d-input label="Largeur réelle" v-model="form.realWidth" />
                                <d-input label="Longueur réelle" v-model="form.realLength" />
                                <d-input label="Surface" v-model="form.surface" />
                                <d-input label="Diagonale A" v-model="form.diagonalA" />
                                <d-input label="Diagonale B" v-model="form.diagonalB" />
                                <d-textarea label="" v-model="form.shapeComment" />
                                <label>Date de fin de production</label>
                                <d-input label="" type="date" v-model="form.productionEnd" />
                                <label>commentaire</label>
                                <d-textarea label="" v-model="form.globalComment" />
                                <label>Forme / progress / Report / Couche</label>
                                <d-textarea label="" v-model="form.shapeProgress" />


                            </fieldset>
                        </div>

                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Validation graphique"
                                                       link=""></d-panel-title>
                                        <div class="col-auto pe-0">
                                            <slot name="extraBtn"></slot>
                                        </div>
                                        <d-radio-validation v-model="form.graphicValidation"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.graphicComment" />
                                    </fieldset>

                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Respect des instructions"
                                                       link=""></d-panel-title>
                                        <d-radio-validation v-model="form.instructionRespect"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.instructionComment" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Réparation" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.repairValidation"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.repairComment" />
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Serrage" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.tighteningValidation"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.tighteningComment" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Qualité laine " link=""></d-panel-title>
                                        <d-radio-validation v-model="form.woolQuality" :options="pertinentOptions" />
                                        <d-textarea label="" v-model="form.woolComment" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Forme spéciale" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.specialShape" :options="pertinentOptions" />
                                        <d-textarea label="" v-model="form.specialShapeComment" />
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Qualité soie" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.silkQuality" :options="validationOptions" />
                                        <d-textarea label="" v-model="form.silkComment" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Corps/Ondu/Coins"
                                                       link=""></d-panel-title>
                                        <d-radio-validation v-model="form.bodyWaveCorners"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.bodyWaveCornersComment" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Auteur du velour"
                                                       link=""></d-panel-title>
                                        <d-radio-validation v-model="form.velourAuthorValidation"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.velourAuthorComment" />
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Washing" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.washingValidation"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.washingComment" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Cleaning" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.cleaningValidation"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.cleaningComment" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Carving" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.carvingValidation"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.carvingComment" />
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Couleur tissue" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.tissueColorValidation"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.tissueColorComment" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Frange" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.fringeRepairValidation"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.fringeRepairComment" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Non binding" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.nonBindingValidation"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.nonBindingComment" />
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Signature" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.signatureValidation"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.signatureComment" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Sans backing" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.sansBackingValidation"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.sansBackingComment" />
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <label class="mb-0">Commentaire:</label>
                                    <d-textarea label="" v-model="form.shapeProgress" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--       ####### Respect section  #######           -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect plan"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.respectPlanValidation"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.respectPlanComment" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect hauteur de port"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.respectHeightValidation"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.respectHeightComment" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect fosse"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.respectPitValidation"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.respectPitComment" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect autre tapis"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.respectOtherCarpetValidation"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.respectOtherCarpetComment" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect longeur Max/Min"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.respectLengthValidation"
                                                    :options="pertinentOptions" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect largeur Max/Min"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.respectWidthValidation"
                                                    :options="pertinentOptions" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Distance mur/haut"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.distanceTopValidation"
                                                    :options="pertinentOptions" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Distance mur/bas"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.distanceBottomValidation"
                                                    :options="pertinentOptions" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Distance mur/droite"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.distanceRightValidation"
                                                    :options="pertinentOptions" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Distance mur/gauche"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.distanceLeftValidation"
                                                    :options="pertinentOptions" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect couleur"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.respectColorValidation"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.respectColorComment" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect matière"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.respectMaterialValidation"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.respectMaterialComment" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect velour"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.respectVelvetValidation"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.respectVelvetComment" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect remarque"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.respectNoteValidation"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.respectNoteComment" />
                            </fieldset>
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-md-12">
                            <d-compositions
                                v-if="data?.workShopOrder?.imageCommand?.carpetSpecification"
                                :disabled="true"
                                :hideBtn="true"
                                :compositionData="data.workShopOrder.imageCommand.carpetSpecification.carpedComposition"
                                :carpetSpecificationId="data.workShopOrder.imageCommand.carpetSpecification.id"
                                :layersValidations="layersValidations"
                            ></d-compositions>
                        </div>
                    </div>
                </template>
            </d-panel>
        </template>
        <template v-slot:footer>
            <div class="row p-2 justify-content-end ">
                <div class="col-auto">
                    <button class="btn btn-custom pe-5 ps-5" @click="goToWorkshop">Retour à la workShop</button>

                </div>
                <div class="col-auto">
                    <button class="btn btn-custom pe-5 ps-5" @click="saveCheckingList">Enregistrer</button>
                </div>
            </div>

        </template>
    </d-base-page>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import checkingListService from '@/Services/checkingList-service';
    import dBasePage from '@/components/base/d-base-page.vue';
    import dInput from '@/components/base/d-input.vue';
    import dTextarea from '@/components/base/d-textarea.vue';
    import dPanel from '@/components/common/d-panel.vue';
    import dPanelTitle from '@/components/common/d-panel-title.vue';
    import dPageTitle from '@/components/common/d-page-title.vue';
    import DRadioValidation from '@/components/checkingProgress/d-radio-validation.vue';
    import DBtnOutlined from '@/components/base/d-btn-outlined.vue';
    import DCompositions from '@/components/projet/contremarques/d-compositions.vue';

    const data = ref(null);
    const layersValidations = ref([]);
    const validationOptions = [
        { label: 'Validée' },
        { label: 'Non validée' }
    ];

    const pertinentOptions = [
        { label: 'Pertinent' },
        { label: 'Non pertinent' }
    ];

    const route = useRoute();
    const router = useRouter();
    const checkingListId = route.params.id;
    const quote_id = route.query.quote_id || null;

    const form = ref({
        rn: '',
        author: '',
        date: '',
        realWidth: '',
        realLength: '',
        surface: '',
        diagonalA: '',
        diagonalB: '',
        shapeComment: '',
        shapeValidation: null,
        graphicValidation: null,
        graphicComment: '',
        instructionRespect: null,
        instructionComment: '',
        repairValidation: null,
        repairComment: '',
        tighteningValidation: null,
        tighteningComment: '',
        woolQuality: null,
        woolComment: '',
        specialShape: null,
        specialShapeComment: '',
        silkQuality: null,
        silkComment: '',
        bodyWaveCorners: null,
        bodyWaveCornersComment: '',
        velourAuthorValidation: null,
        velourAuthorComment: '',
        washingValidation: null,
        washingComment: '',
        cleaningValidation: null,
        cleaningComment: '',
        carvingValidation: null,
        carvingComment: '',
        tissueColorValidation: null,
        tissueColorComment: '',
        fringeRepairValidation: null,
        fringeRepairComment: '',
        fringeShapeValidation: null,
        fringeShapeComment: '',
        nonBindingValidation: null,
        nonBindingComment: '',
        signatureValidation: null,
        signatureComment: '',
        sansBackingValidation: null,
        sansBackingComment: '',
        productionEnd: '',
        globalComment: '',
        shapeProgress: '',
        respectPlanValidation: null,
        respectPlanComment: '',
        respectHeightValidation: null,
        respectHeightComment: '',
        respectPitValidation: null,
        respectPitComment: '',
        respectOtherCarpetValidation: null,
        respectOtherCarpetComment: '',
        respectLengthValidation: null,
        respectWidthValidation: null,
        distanceTopValidation: null,
        distanceBottomValidation: null,
        distanceRightValidation: null,
        distanceLeftValidation: null,
        respectColorValidation: null,
        respectColorComment: '',
        respectMaterialValidation: null,
        respectMaterialComment: '',
        respectVelvetValidation: null,
        respectVelvetComment: '',
        respectNoteValidation: null,
        respectNoteComment: ''
    });

    const fillForm = (data) => {
        form.value.rn = data.workShopOrder?.workshopInformation?.rn || '';
        form.value.author = data.author || '';
        form.value.date = data.date?.date?.split(' ')[0] || '';
        form.value.productionEnd = data.dateEndProd?.date?.split(' ')[0] || '';
        layersValidations.value = data.layersValidations || [];
        if (data.shapeValidation) {
            console.log(data.shapeValidation.id);
            form.value.shapeValidationId = data.shapeValidation.id;
            form.value.shapeValidation = data.shapeValidation.shapeValidation;
            form.value.realWidth = data.shapeValidation.realWidth || '';
            form.value.realLength = data.shapeValidation.realLength || '';
            form.value.surface = data.shapeValidation.surface || '';
            form.value.diagonalA = data.shapeValidation.diagonalA || '';
            form.value.diagonalB = data.shapeValidation.diagonalB || '';
            form.value.shapeComment = data.shapeValidation.comment || '';
            form.value.shapeValidation = data.shapeValidation.shapeValidation;
        }
        if (data.qualityCheck) {
            console.log(data.qualityCheck.id);
            form.value.qualityCheckId = data.qualityCheck.id;
            form.value.graphicValidation = data.qualityCheck.graphicValidation;
            form.value.graphicComment = data.qualityCheck.graphicComment || '';
            form.value.instructionRespect = data.qualityCheck.instructionComplianceValidation;
            form.value.instructionComment = data.qualityCheck.instructionComment || '';
            form.value.repairValidation = data.qualityCheck.repairRelevantValidation;
            form.value.repairComment = data.qualityCheck.repairComment || '';
            form.value.tighteningValidation = data.qualityCheck.tightnessValidation;
            form.value.tighteningComment = data.qualityCheck.tightnessComment || '';
            form.value.woolQuality = data.qualityCheck.woolQualityValidation;
            form.value.woolComment = data.qualityCheck.woolComment || '';
            form.value.silkQuality = data.qualityCheck.silkQualityValidation;
            form.value.silkComment = data.qualityCheck.silkComment || '';
            form.value.specialShape = data.qualityCheck.specialShapeRelevantValidation;
            form.value.specialShapeComment = data.qualityCheck.specialShapeComment || '';
            form.value.bodyWaveCorners = data.qualityCheck.corpsOnduCoinsValidation;
            form.value.bodyWaveCornersComment = data.qualityCheck.corpsOnduCoinsComment || '';
            form.value.velourAuthorValidation = data.qualityCheck.velourAuthorValidation;
            form.value.velourAuthorComment = data.qualityCheck.velourComment || '';
            form.value.washingValidation = data.qualityCheck.washingValidation;
            form.value.washingComment = data.qualityCheck.wachingComment || '';
            form.value.cleaningValidation = data.qualityCheck.cleaningValidation;
            form.value.cleaningComment = data.qualityCheck.cleaningComment || '';
            form.value.carvingValidation = data.qualityCheck.carvingValidation;
            form.value.carvingComment = data.qualityCheck.carvingComment || '';
            form.value.tissueColorValidation = data.qualityCheck.fabricColorValidation;
            form.value.tissueColorComment = data.qualityCheck.fabricColorComment || '';
            form.value.fringeRepairValidation = data.qualityCheck.frangeValidation;
            form.value.fringeRepairComment = data.qualityCheck.frangComment || '';
            form.value.nonBindingValidation = data.qualityCheck.noBindingValidation;
            form.value.nonBindingComment = data.qualityCheck.noBindingComment || '';
            form.value.signatureValidation = data.qualityCheck.signatureValidation;
            form.value.signatureComment = data.qualityCheck.signatureComment || '';
            form.value.sansBackingValidation = data.qualityCheck.withoutBackingValidation;
            form.value.sansBackingComment = data.qualityCheck.withoutBackingComment || '';
        }
        if (data.qualityRespect) {
            console.log(data.qualityRespect.id);
            form.value.qualityRespectId = data.qualityRespect.id;
            form.value.respectPlanValidation = data.qualityRespect.respectPlanValid;
            form.value.respectPlanComment = data.qualityRespect.respectPlanComment || '';
            form.value.respectHeightValidation = data.qualityRespect.respectDoorHeightValid;
            form.value.respectHeightComment = data.qualityRespect.respectDoorHeightComment || '';
            form.value.respectPitValidation = data.qualityRespect.respectFossValide;
            form.value.respectPitComment = data.qualityRespect.respectFossComment || '';
            form.value.respectOtherCarpetValidation = data.qualityRespect.respectOtherCarpetValid;
            form.value.respectOtherCarpetComment = data.qualityRespect.respectOtherCarpetComment || '';
            form.value.respectLengthValidation = data.qualityRespect.respectMaxMinLengthValid;
            form.value.respectWidthValidation = data.qualityRespect.respectMaxMinWidthValid;
            form.value.distanceRightValidation = data.qualityRespect.respectwallDistanceRightValid;
            form.value.distanceLeftValidation = data.qualityRespect.respectwallDistanceLeftValid;
            form.value.distanceTopValidation = data.qualityRespect.wallDistanceTopValid;
            form.value.distanceBottomValidation = data.qualityRespect.wallDistanceBottomValid;
            form.value.respectColorValidation = data.qualityRespect.respectColorValid;
            form.value.respectColorComment = data.qualityRespect.respectColorComment || '';
            form.value.respectMaterialValidation = data.qualityRespect.respectMaterialValid;
            form.value.respectMaterialComment = data.qualityRespect.respectMaterialComment || '';
            form.value.respectVelvetValidation = data.qualityRespect.respectVelourValid;
            form.value.respectVelvetComment = data.qualityRespect.respectVelourComment || '';
            form.value.respectNoteValidation = data.qualityRespect.respectRemarkValid;
            form.value.respectNoteComment = data.qualityRespect.respectRemarkComment || '';
        }
    };

    const loadCheckingList = async () => {
        if (!checkingListId) {
            return;
        }
        try {
            data.value = await checkingListService.getCheckingListById(checkingListId);
            console.log(data.value.workShopOrder.imageCommand.carpetSpecification.carpedComposition);
            fillForm(data.value);
        } catch (e) {
            console.error('Failed to load checking list:', e);
        }
    };

    const saveCheckingList = async () => {
        if (!checkingListId) {
            return;
        }
        try {
            await checkingListService.updateCheckingList(checkingListId, {
                dateEndProd: form.value.productionEnd,
                comment: form.value.globalComment,
                shapeProgress: form.value.shapeProgress
            });

            await checkingListService.updateShapeValidation(form.value.shapeValidationId, {
                shape_validation: form.value.shapeValidation,
                real_width: form.value.realWidth,
                real_length: form.value.realLength,
                surface: form.value.surface,
                diagonal_a: form.value.diagonalA,
                diagonal_b: form.value.diagonalB,
                comment: form.value.shapeComment
            });

            await checkingListService.updateQualityCheck(form.value.qualityCheckId, {
                graphic_validation: form.value.graphicValidation,
                graphic_comment: form.value.graphicComment,
                instruction_compliance_validation: form.value.instructionRespect,
                instruction_comment: form.value.instructionComment,
                repair_relevant_validation: form.value.repairValidation,
                repair_comment: form.value.repairComment,
                tightness_validation: form.value.tighteningValidation,
                tightness_comment: form.value.tighteningComment,
                wool_quality_validation: form.value.woolQuality,
                wool_comment: form.value.woolComment,
                silk_quality_validation: form.value.silkQuality,
                silk_comment: form.value.silkComment,
                special_shape_relevant_validation: form.value.specialShape,
                special_shape_comment: form.value.specialShapeComment,
                corps_ondu_coins_validation: form.value.bodyWaveCorners,
                corps_ondu_coins_comment: form.value.bodyWaveCornersComment,
                velour_author_validation: form.value.velourAuthorValidation,
                velour_comment: form.value.velourAuthorComment,
                washing_validation: form.value.washingValidation,
                waching_comment: form.value.washingComment,
                cleaning_validation: form.value.cleaningValidation,
                cleaning_comment: form.value.cleaningComment,
                carving_validation: form.value.carvingValidation,
                carving_comment: form.value.carvingComment,
                fabric_color_validation: form.value.tissueColorValidation,
                fabric_color_comment: form.value.tissueColorComment,
                frange_validation: form.value.fringeRepairValidation,
                frang_comment: form.value.fringeRepairComment,
                no_binding_validation: form.value.nonBindingValidation,
                no_binding_comment: form.value.nonBindingComment,
                signature_validation: form.value.signatureValidation,
                signature_comment: form.value.signatureComment,
                without_backing_validation: form.value.sansBackingValidation,
                without_backing_comment: form.value.sansBackingComment
            });

            await checkingListService.updateQualityRespect(form.value.qualityRespectId, {
                respect_plan_valid: form.value.respectPlanValidation,
                respect_plan_comment: form.value.respectPlanComment,
                respect_door_height_valid: form.value.respectHeightValidation,
                respect_door_height_comment: form.value.respectHeightComment,
                respect_max_min_length_valid: form.value.respectLengthValidation,
                respect_max_min_width_valid: form.value.respectWidthValidation,
                respectwall_distance_right_valid: form.value.distanceRightValidation,
                respectwall_distance_left_valid: form.value.distanceLeftValidation,
                respect_foss_valide: form.value.respectPitValidation,
                respect_foss_comment: form.value.respectPitComment,
                respect_other_carpet_valid: form.value.respectOtherCarpetValidation,
                respect_other_carpet_comment: form.value.respectOtherCarpetComment,
                respect_color_valid: form.value.respectColorValidation,
                respect_color_comment: form.value.respectColorComment,
                respect_material_valid: form.value.respectMaterialValidation,
                respect_material_comment: form.value.respectMaterialComment,
                respect_remark_valid: form.value.respectNoteValidation,
                respect_remark_comment: form.value.respectNoteComment,
                respect_velour_valid: form.value.respectVelvetValidation,
                respect_velour_comment: form.value.respectVelvetComment,
                wall_distance_top_valid: form.value.distanceTopValidation,
                wall_distance_bottom_valid: form.value.distanceBottomValidation
            });

            window.showMessage('Mise à jour avec succées.');
        } catch (e) {
            console.error('Failed to update checking list:', e);
            window.showMessage('Erreur mise à jour', 'error');
        }
    };

    const goToWorkshop = () => {
        router.back();
    };

    onMounted(loadCheckingList);
</script>
