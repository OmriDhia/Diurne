<template>
  <d-base-page>
    <template #title>
      <d-page-title title="Checking List"></d-page-title>
    </template>

    <template #body>
      <d-panel>
        <template #panel-header>
          <d-panel-title title="Informations g\u00e9n\u00e9rales"></d-panel-title>
        </template>
        <template #panel-body>
          <div class="row">
            <div class="col-md-4 col-sm-12">
              <d-input label="RN" v-model="form.rn" />
            </div>
            <div class="col-md-4 col-sm-12">
              <d-input type="date" label="Date de fin de production" v-model="form.productionEndDate" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 col-sm-12">
              <d-input label="Largeur r\u00e9elle" v-model="form.largeurReelle" />
            </div>
            <div class="col-md-4 col-sm-12">
              <d-input label="Longueur r\u00e9elle" v-model="form.longueurReelle" />
            </div>
            <div class="col-md-4 col-sm-12">
              <d-input label="Surface" v-model="form.surface" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 col-sm-12">
              <d-input label="Diagonale A" v-model="form.diagonaleA" />
            </div>
            <div class="col-md-4 col-sm-12">
              <d-input label="Diagonale B" v-model="form.diagonaleB" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <d-textarea label="Commentaire" v-model="form.commentaire1" :rows="3" />
            </div>
          </div>
        </template>
      </d-panel>

      <d-panel class="mt-3">
        <template #panel-header>
          <d-panel-title title="Contr\u00f4les"></d-panel-title>
        </template>
        <template #panel-body>
          <div class="row" v-for="item in validationFields" :key="item.key">
            <div class="col-md-6 col-sm-12 py-1">
              {{ item.label }}
            </div>
            <div class="col-md-6 col-sm-12 py-1">
              <div class="d-flex radio-group">
                <div class="custom-control custom-radio me-3">
                  <input :id="item.key + '-yes'" type="radio" class="custom-control-input" :name="item.key" :value="true" v-model="form[item.key]" />
                  <label class="custom-control-label" :for="item.key + '-yes'">{{ item.yesLabel || 'Valid\u00e9e' }}</label>
                </div>
                <div class="custom-control custom-radio">
                  <input :id="item.key + '-no'" type="radio" class="custom-control-input" :name="item.key" :value="false" v-model="form[item.key]" />
                  <label class="custom-control-label" :for="item.key + '-no'">{{ item.noLabel || 'Non valid\u00e9e' }}</label>
                </div>
              </div>
            </div>
          </div>
          <hr />
          <div class="row" v-for="item in pertinenceFields" :key="item.key">
            <div class="col-md-6 col-sm-12 py-1">
              {{ item.label }}
            </div>
            <div class="col-md-6 col-sm-12 py-1">
              <div class="d-flex radio-group">
                <div class="custom-control custom-radio me-3">
                  <input :id="item.key + '-yes'" type="radio" class="custom-control-input" :name="item.key" :value="true" v-model="form[item.key]" />
                  <label class="custom-control-label" :for="item.key + '-yes'">{{ item.yesLabel || 'Pertinent' }}</label>
                </div>
                <div class="custom-control custom-radio">
                  <input :id="item.key + '-no'" type="radio" class="custom-control-input" :name="item.key" :value="false" v-model="form[item.key]" />
                  <label class="custom-control-label" :for="item.key + '-no'">{{ item.noLabel || 'Non pertinent' }}</label>
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-12">
              <d-textarea label="Commentaire" v-model="form.commentaire2" :rows="3" />
            </div>
          </div>
        </template>
      </d-panel>
    </template>

    <template #footer>
      <div class="row p-2 justify-content-between">
        <div class="col-auto">
          <router-link to="/home" class="btn btn-custom">Retour</router-link>
        </div>
        <div class="col-auto">
          <button class="btn btn-custom" @click="save">Enregistrer</button>
        </div>
      </div>
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

const form = ref({
  rn: '',
  productionEndDate: '',
  largeurReelle: '',
  longueurReelle: '',
  surface: '',
  diagonaleA: '',
  diagonaleB: '',
  commentaire1: '',
  commentaire2: '',
  validationForme: true,
  validationGraphique: true,
  serrage: true,
  qualiteSoie: true,
  washing: true,
  respectInstructions: true,
  qualiteLaine: true,
  corpsOnduCoins: true,
  cleaning: true,
  frangeValidation: true,
  auteurVelour: true,
  carving: true,
  frange2: true,
  respectPlan: true,
  respectLongueur: true,
  distanceMurDroite: true,
  etatCommande: true,
  couleurTissue: true,
  nonBinding: true,
  respectHauteurPorte: true,
  respectLargeur: true,
  distanceMurGauche: true,
  signature: true,
  respectFosse: true,
  distanceMurHaut: true,
  respectCouleur: true,
  respectVelour: true,
  reparation: true,
  formeSpeciale: true,
  sansBacking: true,
  respectAutreTapis: true,
  distanceMurBas: true,
  respectMatiere: true,
  respectRemarque: true
});

const validationFields = [
  { key: 'validationForme', label: 'Validation forme' },
  { key: 'validationGraphique', label: 'Validation graphique' },
  { key: 'serrage', label: 'Serrage' },
  { key: 'qualiteSoie', label: 'Qualit\u00e9 soie' },
  { key: 'washing', label: 'Washing' },
  { key: 'respectInstructions', label: 'Respect des instructions' },
  { key: 'qualiteLaine', label: 'Qualit\u00e9 laine' },
  { key: 'corpsOnduCoins', label: 'Corps / Ondu / Coins' },
  { key: 'cleaning', label: 'Cleaning' },
  { key: 'frangeValidation', label: 'Frange' },
  { key: 'auteurVelour', label: 'Auteur du velour' },
  { key: 'carving', label: 'Carving' },
  { key: 'frange2', label: 'Frange' }
];

const pertinenceFields = [
  { key: 'respectPlan', label: 'Respect plan' },
  { key: 'respectLongueur', label: 'Respect longueur MAX / MIN' },
  { key: 'distanceMurDroite', label: 'Distance mur / droite' },
  { key: 'etatCommande', label: 'Etat commande', yesLabel: 'Envoy\u00e9e', noLabel: 'Non envoy\u00e9e' },
  { key: 'couleurTissue', label: 'Couleur tissue' },
  { key: 'nonBinding', label: 'Non binding' },
  { key: 'respectHauteurPorte', label: 'Respect hauteur de porte' },
  { key: 'respectLargeur', label: 'Respect largeur MAX / MIN' },
  { key: 'distanceMurGauche', label: 'Distance mur / gauche' },
  { key: 'signature', label: 'Signature' },
  { key: 'respectFosse', label: 'Respect fosse' },
  { key: 'distanceMurHaut', label: 'Distance mur / haut' },
  { key: 'respectCouleur', label: 'Respect couleur' },
  { key: 'respectVelour', label: 'Respect velour' },
  { key: 'reparation', label: 'Reparation' },
  { key: 'formeSpeciale', label: 'Forme sp\u00e9ciale' },
  { key: 'sansBacking', label: 'Sans backing' },
  { key: 'respectAutreTapis', label: 'Respect autre tapis' },
  { key: 'distanceMurBas', label: 'Distance mur / bas' },
  { key: 'respectMatiere', label: 'Respect mati\u00e8re' },
  { key: 'respectRemarque', label: 'Respect remarque' }
];

const save = () => {
  console.log(form.value);
};
</script>

<style scoped>
.radio-group .custom-control {
  margin-right: 1rem;
}
</style>
