<script setup lang="ts">
    import { ref } from 'vue';
    import SelectInput from '../ui/SelectInput.vue';
    import RadioButton from '../ui/RadioButton.vue';
    import dInput from '../../../components/base/d-input.vue';
    import DCurrency from '@/components/common/d-currency.vue';
    import DPanelTitle from '@/components/common/d-panel-title.vue';
    // Form data
    const formData = ref({
        infoCommande: {
            dateCmdAtelier: '24-10-2021',
            dateFinTheo: '24-10-2021',
            dateFinAtelierPrev: '',
            delaisProd: '2',
            pourcentCommande: '0.00',
            deviseAchat: '',
            largeurCmd: '30',
            largeurReelle: '0',
            longueurCmd: '30',
            longueurReelle: '0',
            srfCmd: '0.9',
            srfReelle: '0',
            anneeGrilleTarif: ''
        },
        tarifSpecial: true,
        prixAchat: [
            { material: 'Wool', price: '32' },
            { material: 'Silk', price: '32' },
            { material: 'Hemp', price: '32' }
        ],
        reductionTapis: '',
        complexiteAtelier: false,
        multiLevelAtelier: true,
        formeSpeciale: true,
        tapisDuProjet: {
            fabricant: '',
            typeCommande: '',
            rn: 'M0025',
            exemplaire: '3'
        },
        prixAchatTapis: {
            auM2: '32',
            cmd: '2.88',
            theorique: '2.88',
            facture: '0'
        },
        others: {
            penalite: '0',
            transport: '0',
            taxe: '0',
            margeBrute: '0',
            referenceSurFacture: '0',
            numeroDuFacture: '0'
        },
        dateValidationClient: '24-10-2021',
        disponibleVente: false,
        envoye: false,
        receptionParis: true
    });

    const materialOptions = [
        { value: 'Wool', label: 'Wool' },
        { value: 'Silk', label: 'Silk' },
        { value: 'Hemp', label: 'Hemp' }
    ];

    const checkingLists = [
        { id: '551', label: 'Checking List n° 551' },
        { id: '691', label: 'Checking List n° 691' },
        { id: '6951', label: 'Checking List n° 6951' }
    ];

    // Methods
    const generateRN = () => {
        console.log('Generating RN...');
    };

    const controlCoherence = () => {
        console.log('Controlling coherence...');
    };

    const updatePrixTapis = () => {
        console.log('Updating tapis price...');
    };

    const voir = () => {
        console.log('Viewing DI commande...');
    };

    const attribuer = () => {
        console.log('Attributing...');
    };

    const enregistrer = () => {
        console.log('Saving form...');
    };

    const commandeAtelier = () => {
        console.log('Workshop command...');
    };

    const createNewCheckingList = () => {
        console.log('Creating new checking list...');
    };
</script>

<template>
    <div class="information-atelier">
        <div class="main-sections row">
            <!-- Left section -->
            <div class="left-section col-8">

                <d-panel-title title="Info commande atelier" className="ps-2"></d-panel-title>
                <div class="row">
                    <div class="col-6">

                        <div class="form-row">
                            <d-input label="Date de cmd. atelier" type="date"
                                     v-model="formData.infoCommande.dateCmdAtelier" />
                        </div>

                        <div class="form-row">
                            <d-input label="Date fin atelier Prev" type="date"
                                     v-model="formData.infoCommande.dateFinAtelierPrev"
                                     rootClass="pink-bg" />
                        </div>

                        <div class="form-row">
                            <d-input label="% commande soie" v-model="formData.infoCommande.pourcentCommande"
                                     rootClass="pink-bg" />
                        </div>

                        <div class="form-row">
                            <d-input label="Largeur cmd. atelier" v-model="formData.infoCommande.largeurCmd"
                                     rootClass="pink-bg" />
                        </div>

                        <div class="form-row">
                            <d-input label="Longueur cmd. atelier" v-model="formData.infoCommande.longueurCmd"
                                     rootClass="pink-bg" />
                        </div>

                        <div class="form-row">
                            <d-input label="Srf cmd. atelier" v-model="formData.infoCommande.srfCmd" />
                        </div>

                        <div class="form-row row ">
                            <div class="col-4 ps-0">
                                Année grille tarif :
                            </div>
                            <div class="col-8 px-2">
                                <SelectInput v-model="formData.infoCommande.anneeGrilleTarif" rootClass="pink-bg" />
                            </div>
                        </div>

                        <div class="form-row special-tarif row py-3">
                            <div class="col-12 p-0">
                                <RadioButton v-model="formData.tarifSpecial" :value="true" label="Tarif spécial" />
                            </div>

                        </div>

                        <div class="form-row material-row row" v-for="(material, index) in formData.prixAchat"
                             :key="index">
                            <div class="col-5">
                                <label>Prix d'achat :</label>
                            </div>
                            <div class="material-inputs col-7">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <SelectInput class="pt-2" v-model="material.material" :options="materialOptions"
                                                     rootClass="pink-bg" />
                                    </div>
                                    <div class="col-6">
                                        <d-input v-model="material.price" />
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="theoretical-section">
                            <div class="form-row">
                                <d-input label="Date fin Théo" type="date"
                                         v-model="formData.infoCommande.dateFinTheo" />
                            </div>

                            <div class="form-row">
                                <div class="col-12">
                                    <d-input label="Délais de prod" v-model="formData.infoCommande.delaisProd" />
                                </div>
                            </div>

                            <div class="form-row row py-2">
                                <d-currency :required="true"
                                            :error=0 model-value="">

                                </d-currency>
                            </div>

                            <div class="form-row">
                                <d-input label="Lrg. réelle" v-model="formData.infoCommande.largeurReelle" />
                            </div>

                            <div class="form-row">
                                <d-input label="Lng. réelle" v-model="formData.infoCommande.longueurReelle" />
                            </div>

                            <div class="form-row">
                                <d-input label="Srf réelle" v-model="formData.infoCommande.srfReelle" />
                            </div>

                            <div class="form-row py-2">
                                <button class="btn btn-custom  text-uppercase w-100">
                                    GESTION GRILLE TARIFAIRE
                                </button>
                            </div>

                            <div class="form-row">
                                <d-input label="% Réduc. tapis " v-model="formData.reductionTapis" />
                            </div>
                        </div>
                    </div>
                </div>


                <div class="radio-options my-4">
                    <RadioButton v-model="formData.complexiteAtelier" :value="true" label="Complexité atelier" />
                    <RadioButton v-model="formData.multiLevelAtelier" :value="true" label="Multi-level atelier" />
                    <RadioButton v-model="formData.formeSpeciale" :value="true" label="Forme spéciale" />
                </div>


                <div class="row">
                    <div class="col-6 ps-0">
                        <div class="price-row">
                            <d-input label="Prix d'achat tapis au m² " v-model="formData.prixAchatTapis.auM2" />
                        </div>
                        <div class="price-row">
                            <d-input label="Prix d'achat tapis théorique"
                                     v-model="formData.prixAchatTapis.theorique" />
                        </div>
                        <div class="price-row">
                            <d-input label="Pénalité" v-model="formData.others.penalite" />
                        </div>
                        <div class="price-row">
                            <d-input label="Taxe" v-model="formData.others.taxe" />
                        </div>
                        <div class="price-row">
                            <d-input label="Référence sur facture" v-model="formData.others.referenceSurFacture" />
                        </div>
                    </div>
                    <div class="col-6">


                        <div class="price-row">
                            <d-input label="Prix d'achat tapis Cmd" v-model="formData.prixAchatTapis.cmd" />
                        </div>


                        <div class="price-row">
                            <d-input label="Prix d'achat tapis facture" v-model="formData.prixAchatTapis.facture" />
                        </div>


                        <div class="price-row">
                            <d-input label="Transport" v-model="formData.others.transport" />
                        </div>


                        <div class="price-row">
                            <d-input label="Marge brute" v-model="formData.others.margeBrute" />
                        </div>

                        <div class="price-row">
                            <d-input label="Numéro du facture" v-model="formData.others.numeroDuFacture" />
                        </div>
                    </div>
                </div>


                <div class="details-row d-flex justify-content-center align-items-center py-2">
                    <div class="col-4"><label>Détails prix tapis</label></div>
                    <div class="col-4">
                        <button class="btn btn-outline-dark  text-uppercase" @click="updatePrixTapis">
                            MISE À JOURS
                        </button>
                    </div>


                </div>

                <div class="checking-lists">
                    <div class="list-links">
                        <a href="#" v-for="list in checkingLists" :key="list.id" class="checking-link">
                            <span class="text-decoration-underline  me-2">{{ list.label }}</span>
                        </a>
                    </div>
                    <button class="new-list-btn btn btn-custom  text-uppercase my-2"
                            @click="createNewCheckingList">NOUVELLE CHECKING LIST
                    </button>
                </div>
            </div>

            <!-- Right section -->
            <div class="right-section col-4">


                <d-panel-title title="Tapis du projet" className="ps-2"></d-panel-title>
                <div class="text-center py-2">
                    <img src="https://placehold.co/100x200?text=Carpet+Image" alt="Carpet placeholder image">
                </div>

                <button class="btn btn-outline-dark btn-reset text-uppercase w-100" @click="voir">VOIR LA DI
                    COMMANDE
                </button>

                <div class="selector-row row py-3">
                    <div class="col-7">
                        <SelectInput />
                    </div>
                    <div class="col-5">
                        <SelectInput />
                    </div>
                </div>

                <button class="btn btn-custom  text-uppercase w-100" @click="attribuer">ATTRIBUER</button>

                <div class="form-row">
                    <d-input label="Date validation client" type="date" v-model="formData.dateValidationClient" />
                </div>

                <button class="coherence-btn btn btn-custom  text-uppercase w-100 py-2"
                        @click="controlCoherence">CONTRÔLE DE COHÉRENCE
                </button>

                <div class="form-row row py-2 align-items-center">
                    <div class="col-4"><label>Fabricant :</label></div>
                    <div class="col-8">
                        <SelectInput v-model="formData.tapisDuProjet.fabricant" rootClass="pink-bg" />
                    </div>
                </div>
                <div class="form-row row py-2">
                    <div class="col-4"><label>Type commandé :</label></div>
                    <div class="col-8">
                        <SelectInput v-model="formData.tapisDuProjet.fabricant" rootClass="pink-bg" />
                    </div>
                </div>

                <div class="form-row">
                    <d-input label="RN" v-model="formData.tapisDuProjet.rn" rootClass="pink-bg" disabled />
                </div>

                <div class="form-row">
                    <d-input label="N° d'exemplaire" v-model="formData.tapisDuProjet.exemplaire" />
                </div>

                <button class="generate-btn btn btn-outline-dark   text-uppercase w-100" @click="generateRN">
                    GÉNÉRER RN
                </button>

            </div>
        </div>

        <!-- Theoretical values section -->

    </div>
</template>

<style scoped lang="scss">

</style>
