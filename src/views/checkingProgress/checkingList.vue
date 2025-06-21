<template>
    <d-base-page>
        <template #title>
            <d-page-title title="Checking List" />
        </template>

        <template #body>
            <d-panel>
                <template #panel-header>
                    <d-panel-title title="Informations générales" />
                </template>

                <template #panel-body>
                    <div class="row">
                        <div class="col-md-4">
                            <d-input label="RN" v-model="form.rn" />
                        </div>
                        <div class="col-md-4">
                            <d-input label="Auteur" type="date" v-model="form.author" />
                        </div>
                        <div class="col-md-4">
                            <d-input label="Date" type="date" v-model="form.date" />
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
                                        <d-panel-title class-name="ps-2" title="Frange" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.fringeShapeValidation"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.fringeShapeComment" />
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Non binding" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.nonBindingValidation"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.nonBindingComment" />
                                    </fieldset>
                                </div>
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
                    <!-- Additional Fields & Comments -->

                </template>
            </d-panel>
        </template>
    </d-base-page>
</template>

<script setup>
    import { ref } from 'vue';
    import dBasePage from '@/components/base/d-base-page.vue';
    import dInput from '@/components/base/d-input.vue';
    import dTextarea from '@/components/base/d-textarea.vue';
    import dPanel from '@/components/common/d-panel.vue';
    import dPanelTitle from '@/components/common/d-panel-title.vue';
    import dPageTitle from '@/components/common/d-page-title.vue';
    import DRadioValidation from '@/components/checkingProgress/d-radio-validation.vue';

    const validationOptions = [
        { label: 'Validée' },
        { label: 'Non validée' }
    ];

    const pertinentOptions = [
        { label: 'Pertinent' },
        { label: 'Non pertinent' }
    ];

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
</script>
