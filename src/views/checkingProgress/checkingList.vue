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
                            <d-input label="Auteur" type="date" v-model="form.auteur" />
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
                                <d-radio-validation v-model="form.validationForme" :options="validationOptions" />
                                <d-input label="Largeur réelle" v-model="form.largeurReelle" />
                                <d-input label="Longueur réelle" v-model="form.longueurReelle" />
                                <d-input label="Surface" v-model="form.surface" />
                                <d-input label="Diagonale A" v-model="form.diagonaleA" />
                                <d-input label="Diagonale B" v-model="form.diagonaleB" />
                                <d-textarea label="" v-model="form.commentaireForme" />
                                <label>Date de fin de production</label>
                                <d-input label="" type="date" v-model="form.finProduction" />
                                <label>commentaire</label>
                                <d-textarea label="" v-model="form.commentaireGlobal" />
                                <label>Forme / progress / Report / Couche</label>
                                <d-textarea label="" v-model="form.formeProgress" />


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
                                        <d-radio-validation v-model="form.validationGraphique"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireGraphique" />
                                    </fieldset>

                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Respect des instructions"
                                                       link=""></d-panel-title>
                                        <d-radio-validation v-model="form.respectInstructions"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireSerrage" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Réparation" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.reparation" :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireLaine" />
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Serrage" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.serrage"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireInstructions" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Qualité laine " link=""></d-panel-title>
                                        <d-radio-validation v-model="form.qualiteLaine" :options="pertinentOptions" />
                                        <d-textarea label="" v-model="form.commentaireReparation" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Forme spéciale" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.formeSpeciale" :options="pertinentOptions" />
                                        <d-textarea label="" v-model="form.commentaireFormeSpeciale" />
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Qualité soie" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.qualiteSoie"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireInstructions" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Corps/Ondu/Coins"
                                                       link=""></d-panel-title>
                                        <d-radio-validation v-model="form.corpsOnduCoins"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireReparation" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Auteur du velour"
                                                       link=""></d-panel-title>
                                        <d-radio-validation v-model="form.formeSpeciale" :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireFormeSpeciale" />
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Washing" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.respectInstructions"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireInstructions" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Cleaning" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.reparation" :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireReparation" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Carving" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.formeSpeciale" :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireFormeSpeciale" />
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Couleur tissue" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.respectInstructions"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireInstructions" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Frange" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.reparation" :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireReparation" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Frange" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.formeSpeciale" :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireFormeSpeciale" />
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Non binding" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.respectInstructions"
                                                            :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireInstructions" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Signature" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.reparation" :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireReparation" />
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset>
                                        <d-panel-title class-name="ps-2" title="Sans backing" link=""></d-panel-title>
                                        <d-radio-validation v-model="form.formeSpeciale" :options="validationOptions" />
                                        <d-textarea label="" v-model="form.commentaireFormeSpeciale" />
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <label class="mb-0">Commentaire:</label>
                                    <d-textarea label="" v-model="form.formeProgress" />
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
                                <d-radio-validation v-model="form.validationGraphique"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.commentaireGraphique" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect hauteur de port"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.validationGraphique"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.commentaireGraphique" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect fosse"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.validationGraphique"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.commentaireGraphique" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect autre tapis"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.validationGraphique"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.commentaireGraphique" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect longeur Max/Min"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.validationGraphique"
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
                                <d-radio-validation v-model="form.validationGraphique"
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
                                <d-radio-validation v-model="form.validationGraphique"
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
                                <d-radio-validation v-model="form.validationGraphique"
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
                                <d-radio-validation v-model="form.validationGraphique"
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
                                <d-radio-validation v-model="form.validationGraphique"
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
                                <d-radio-validation v-model="form.validationGraphique"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.commentaireGraphique" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect matière"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.validationGraphique"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.commentaireGraphique" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect velour"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.validationGraphique"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.commentaireGraphique" />
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                <d-panel-title class-name="ps-2" title="Respect remarque"
                                               link=""></d-panel-title>
                                <div class="col-auto pe-0">
                                    <slot name="extraBtn"></slot>
                                </div>
                                <d-radio-validation v-model="form.validationGraphique"
                                                    :options="pertinentOptions" />
                                <d-textarea label="" v-model="form.commentaireGraphique" />
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
        { label: 'Validée', value: 'validee' },
        { label: 'Non validée', value: 'non_validee' }
    ];

    const pertinentOptions = [
        { label: 'Pertinent', value: 'pertinent' },
        { label: 'Non pertinent', value: 'non_pertinent' }
    ];

    const form = ref({
        rn: '',
        auteur: '',
        date: '',
        largeurReelle: '',
        longueurReelle: '',
        surface: '',
        diagonaleA: '',
        diagonaleB: '',
        commentaireForme: '',
        validationForme: '',
        validationGraphique: '',
        commentaireGraphique: '',
        serrage: '',
        commentaireSerrage: '',
        qualiteLaine: '',
        commentaireLaine: '',
        respectInstructions: '',
        commentaireInstructions: '',
        reparation: '',
        commentaireReparation: '',
        formeSpeciale: '',
        commentaireFormeSpeciale: '',
        finProduction: '',
        commentaireGlobal: '',
        formeProgress: ''
    });
</script>
