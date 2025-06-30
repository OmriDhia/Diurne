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
                <d-panel-title
                  class-name="ps-2"
                  title="Validation forme"
                  link=""
                ></d-panel-title>
                <d-radio-validation
                  v-model="form.shapeValidation"
                  field-name="shape"
                  :show-validation="showValidation"
                />
                <d-input label="Largeur réelle" v-model="form.realWidth" />
                <d-input label="Longueur réelle" v-model="form.realLength" />
                <d-input label="Surface" v-model="form.surface" />
                <d-input label="Diagonale A" v-model="form.diagonalA" />
                <d-input label="Diagonale B" v-model="form.diagonalB" />
                <label>Date de fin de production</label>
                <d-input label="" type="date" v-model="form.productionEnd" />
                <label>commentaire</label>
                <d-textarea label="" v-model="form.globalComment" />
              </fieldset>
            </div>

            <div class="col-md-9">
              <div class="row">
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Validation graphique"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.graphicValidation"
                      field-name="graphic"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Respect des instructions"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.instructionRespect"
                      field-name="instruction"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Réparation"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.repairValidation"
                      field-name="repair"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Serrage"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.tighteningValidation"
                      field-name="tightening"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Qualité laine"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.woolQuality"
                      field-name="wool"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Forme spéciale"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.specialShape"
                      field-name="specialShape"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Qualité soie"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.silkQuality"
                      field-name="silk"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Corps/Ondu/Coins"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.bodyWaveCorners"
                      field-name="bodyWaveCorners"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Auteur du velour"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.velourAuthorValidation"
                      field-name="velourAuthor"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Washing"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.washingValidation"
                      field-name="washing"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Cleaning"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.cleaningValidation"
                      field-name="cleaning"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Carving"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.carvingValidation"
                      field-name="carving"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Couleur tissue"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.tissueColorValidation"
                      field-name="tissueColor"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Frange"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.fringeRepairValidation"
                      field-name="fringeRepair"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Non binding"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.nonBindingValidation"
                      field-name="nonBinding"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Signature"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.signatureValidation"
                      field-name="signature"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
                <div class="col-md-4">
                  <fieldset>
                    <d-panel-title
                      class-name="ps-2"
                      title="Sans backing"
                      link=""
                    ></d-panel-title>
                    <d-radio-validation
                      v-model="form.sansBackingValidation"
                      field-name="sansBacking"
                      :show-validation="showValidation"
                    />
                  </fieldset>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col">
                  <label class="mb-0">Commentaire:</label>
                  <d-textarea label="" v-model="form.globalComment" />
                </div>
              </div>
            </div>
          </div>

          <!-- Respect section -->
          <div class="row mt-4">
            <div class="col-md-3">
              <fieldset>
                <d-panel-title
                  class-name="ps-2"
                  title="Respect plan"
                  link=""
                ></d-panel-title>
                <d-radio-validation
                  v-model="form.respectPlanValidation"
                  field-name="respectPlan"
                  :show-validation="showValidation"
                />
              </fieldset>
            </div>
            <div class="col-md-3">
              <fieldset>
                <d-panel-title
                  class-name="ps-2"
                  title="Respect hauteur de port"
                  link=""
                ></d-panel-title>
                <d-radio-validation
                  v-model="form.respectHeightValidation"
                  field-name="respectHeight"
                  :show-validation="showValidation"
                />
              </fieldset>
            </div>
            <div class="col-md-3">
              <fieldset>
                <d-panel-title
                  class-name="ps-2"
                  title="Respect fosse"
                  link=""
                ></d-panel-title>
                <d-radio-validation
                  v-model="form.respectPitValidation"
                  field-name="respectPit"
                  :show-validation="showValidation"
                />
              </fieldset>
            </div>
            <div class="col-md-3">
              <fieldset>
                <d-panel-title
                  class-name="ps-2"
                  title="Respect autre tapis"
                  link=""
                ></d-panel-title>
                <d-radio-validation
                  v-model="form.respectOtherCarpetValidation"
                  field-name="respectOtherCarpet"
                  :show-validation="showValidation"
                />
              </fieldset>
            </div>
            <div class="col-md-3">
              <fieldset>
                <d-panel-title
                  class-name="ps-2"
                  title="Respect longeur Max/Min"
                  link=""
                ></d-panel-title>
                <d-radio-validation
                  v-model="form.respectLengthValidation"
                  field-name="respectLength"
                  :show-validation="showValidation"
                />
              </fieldset>
            </div>
            <div class="col-md-3">
              <fieldset>
                <d-panel-title
                  class-name="ps-2"
                  title="Respect largeur Max/Min"
                  link=""
                ></d-panel-title>
                <d-radio-validation
                  v-model="form.respectWidthValidation"
                  field-name="respectWidth"
                  :show-validation="showValidation"
                />
              </fieldset>
            </div>
            <div class="col-md-3">
              <fieldset>
                <d-panel-title
                  class-name="ps-2"
                  title="Distance mur/haut"
                  link=""
                ></d-panel-title>
                <d-radio-validation
                  v-model="form.distanceTopValidation"
                  field-name="distanceTop"
                  :show-validation="showValidation"
                />
              </fieldset>
            </div>
            <div class="col-md-3">
              <fieldset>
                <d-panel-title
                  class-name="ps-2"
                  title="Distance mur/bas"
                  link=""
                ></d-panel-title>
                <d-radio-validation
                  v-model="form.distanceBottomValidation"
                  field-name="distanceBottom"
                  :show-validation="showValidation"
                />
              </fieldset>
            </div>
            <div class="col-md-3">
              <fieldset>
                <d-panel-title
                  class-name="ps-2"
                  title="Distance mur/droite"
                  link=""
                ></d-panel-title>
                <d-radio-validation
                  v-model="form.distanceRightValidation"
                  field-name="distanceRight"
                  :show-validation="showValidation"
                />
              </fieldset>
            </div>
            <div class="col-md-3">
              <fieldset>
                <d-panel-title
                  class-name="ps-2"
                  title="Distance mur/gauche"
                  link=""
                ></d-panel-title>
                <d-radio-validation
                  v-model="form.distanceLeftValidation"
                  field-name="distanceLeft"
                  :show-validation="showValidation"
                />
              </fieldset>
            </div>
            <div class="col-md-3">
              <fieldset>
                <d-panel-title
                  class-name="ps-2"
                  title="Respect couleur"
                  link=""
                ></d-panel-title>
                <d-radio-validation
                  v-model="form.respectColorValidation"
                  field-name="respectColor"
                  :show-validation="showValidation"
                />
              </fieldset>
            </div>
            <div class="col-md-3">
              <fieldset>
                <d-panel-title
                  class-name="ps-2"
                  title="Respect matière"
                  link=""
                ></d-panel-title>
                <d-radio-validation
                  v-model="form.respectMaterialValidation"
                  field-name="respectMaterial"
                  :show-validation="showValidation"
                />
              </fieldset>
            </div>
            <div class="col-md-3">
              <fieldset>
                <d-panel-title
                  class-name="ps-2"
                  title="État commande"
                  link=""
                ></d-panel-title>
                <div class="d-flex gap-3">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="orderStatus"
                      id="orderStatusSent"
                      value="true"
                      v-model="form.orderStatus"
                    />
                    <label class="form-check-label" for="orderStatusSent">
                      Envoyée
                    </label>
                  </div>
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="orderStatus"
                      id="orderStatusNotSent"
                      value="false"
                      v-model="form.orderStatus"
                    />
                    <label class="form-check-label" for="orderStatusNotSent">
                      Non envoyée
                    </label>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="col-md-3">
              <fieldset>
                <d-panel-title class-name="ps-2" title="Pénalité" link=""></d-panel-title>
                <d-input type="date" v-model="form.penaltyDate" />
              </fieldset>
            </div>
            <div class="col-md-3">
              <fieldset>
                <d-panel-title
                  class-name="ps-2"
                  title="Respect velour"
                  link=""
                ></d-panel-title>
                <d-radio-validation
                  v-model="form.respectVelvetValidation"
                  field-name="respectVelvet"
                  :show-validation="showValidation"
                />
              </fieldset>
            </div>
            <div class="col-md-3">
              <fieldset>
                <d-panel-title
                  class-name="ps-2"
                  title="Respect remarque"
                  link=""
                ></d-panel-title>
                <d-radio-validation
                  v-model="form.respectNoteValidation"
                  field-name="respectNote"
                  :show-validation="showValidation"
                />
              </fieldset>
            </div>
          </div>

          <div class="row p-2">
            <div class="col-md-12">
              <d-compositions
                v-if="data?.workShopOrder?.imageCommand?.carpetSpecification"
                :disabled="true"
                :hideBtn="true"
                :compositionData="
                  data.workShopOrder.imageCommand.carpetSpecification.carpedComposition
                "
                :carpetSpecificationId="
                  data.workShopOrder.imageCommand.carpetSpecification.id
                "
                :layersValidations="layersValidations ? layersValidations : null"
              ></d-compositions>
            </div>
          </div>
        </template>
      </d-panel>
    </template>
    <template v-slot:footer>
      <div class="row p-2 justify-content-end">
        <div class="col-auto">
          <button class="btn btn-custom pe-5 ps-5" @click="goToWorkshop">
            Retour à la workShop
          </button>
        </div>
        <div class="col-auto">
          <button class="btn btn-custom pe-5 ps-5" @click="saveCheckingList">
            Enregistrer
          </button>
        </div>
      </div>
    </template>
  </d-base-page>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import checkingListService from "@/Services/checkingList-service";
import dBasePage from "@/components/base/d-base-page.vue";
import dInput from "@/components/base/d-input.vue";
import dTextarea from "@/components/base/d-textarea.vue";
import dPanel from "@/components/common/d-panel.vue";
import dPanelTitle from "@/components/common/d-panel-title.vue";
import dPageTitle from "@/components/common/d-page-title.vue";
import DRadioValidation from "@/components/checkingProgress/d-radio-validation.vue";
import DBtnOutlined from "@/components/base/d-btn-outlined.vue";
import DCompositions from "@/components/projet/contremarques/d-compositions.vue";

const data = ref(null);
const layersValidations = ref([]);
const showValidation = ref(false);

const route = useRoute();
const router = useRouter();
const checkingListId = route.params.id;
const quote_id = route.query.quote_id || null;

// Helper function to create validation object
const createValidationObject = (
  relevant = null,
  validation = null,
  seen = null,
  comment = ""
) => ({
  relevant: relevant === null ? null : relevant,
  validation: validation === null ? null : validation,
  seen: seen === null ? null : seen,
  comment: comment || "",
});

const form = ref({
  rn: "",
  author: "",
  date: "",
  realWidth: "",
  realLength: "",
  surface: "",
  diagonalA: "",
  diagonalB: "",
  productionEnd: "",
  globalComment: "",

  // Shape Validation
  shapeValidation: createValidationObject(),

  // Quality Check fields
  graphicValidation: createValidationObject(),
  instructionRespect: createValidationObject(),
  repairValidation: createValidationObject(),
  tighteningValidation: createValidationObject(),
  woolQuality: createValidationObject(),
  specialShape: createValidationObject(),
  silkQuality: createValidationObject(),
  bodyWaveCorners: createValidationObject(),
  velourAuthorValidation: createValidationObject(),
  washingValidation: createValidationObject(),
  cleaningValidation: createValidationObject(),
  carvingValidation: createValidationObject(),
  tissueColorValidation: createValidationObject(),
  fringeRepairValidation: createValidationObject(),
  nonBindingValidation: createValidationObject(),
  signatureValidation: createValidationObject(),
  sansBackingValidation: createValidationObject(),

  // Quality Respect fields
  respectPlanValidation: createValidationObject(),
  respectHeightValidation: createValidationObject(),
  respectPitValidation: createValidationObject(),
  respectOtherCarpetValidation: createValidationObject(),
  respectLengthValidation: createValidationObject(),
  respectWidthValidation: createValidationObject(),
  distanceTopValidation: createValidationObject(),
  distanceBottomValidation: createValidationObject(),
  distanceRightValidation: createValidationObject(),
  distanceLeftValidation: createValidationObject(),
  respectColorValidation: createValidationObject(),
  respectMaterialValidation: createValidationObject(),
  respectVelvetValidation: createValidationObject(),
  respectNoteValidation: createValidationObject(),

  // Envoi et Pénalité fields
  orderStatus: true,
  penaltyDate: "",
});

const fillForm = (data) => {
  form.value.rn = data.workShopOrder?.workshopInformation?.rn || "";
  form.value.author = String(data.author || "");
  form.value.date = data.date?.date?.split(" ")[0] || "";
  form.value.productionEnd = data.dateEndProd?.date?.split(" ")[0] || "";
  form.value.globalComment = data.comment || "";
  layersValidations.value = data.layersValidations || [];

  if (data.shapeValidation) {
    form.value.shapeValidationId = data.shapeValidation.id;
    form.value.shapeValidation = createValidationObject(
      data.shapeValidation.shapeRelevant,
      data.shapeValidation.shapeValidation,
      data.shapeValidation.shapeSeen,
      data.shapeValidation.comment || ""
    );
    form.value.realWidth = data.shapeValidation.realWidth || "";
    form.value.realLength = data.shapeValidation.realLength || "";
    form.value.surface = data.shapeValidation.surface || "";
    form.value.diagonalA = data.shapeValidation.diagonalA || "";
    form.value.diagonalB = data.shapeValidation.diagonalB || "";
  }

  if (data.qualityCheck) {
    form.value.qualityCheckId = data.qualityCheck.id;
    form.value.graphicValidation = createValidationObject(
      data.qualityCheck.graphicRelevant,
      data.qualityCheck.graphicValidation,
      data.qualityCheck.graphicSeen,
      data.qualityCheck.graphicComment || ""
    );
    form.value.instructionRespect = createValidationObject(
      data.qualityCheck.instructionRelevant,
      data.qualityCheck.instructionComplianceValidation,
      data.qualityCheck.instructionSeen,
      data.qualityCheck.instructionComment || ""
    );
    form.value.repairValidation = createValidationObject(
      data.qualityCheck.repairRelevant,
      data.qualityCheck.repairRelevantValidation,
      data.qualityCheck.repairSeen,
      data.qualityCheck.repairComment || ""
    );
    form.value.tighteningValidation = createValidationObject(
      data.qualityCheck.tightnessRelevant,
      data.qualityCheck.tightnessValidation,
      data.qualityCheck.tightnessSeen,
      data.qualityCheck.tightnessComment || ""
    );
    form.value.woolQuality = createValidationObject(
      data.qualityCheck.woolRelevant,
      data.qualityCheck.woolQualityValidation,
      data.qualityCheck.woolSeen,
      data.qualityCheck.woolComment || ""
    );
    form.value.silkQuality = createValidationObject(
      data.qualityCheck.silkRelevant,
      data.qualityCheck.silkQualityValidation,
      data.qualityCheck.silkSeen,
      data.qualityCheck.silkComment || ""
    );
    form.value.specialShape = createValidationObject(
      data.qualityCheck.specialShapeRelevant,
      data.qualityCheck.specialShapeRelevantValidation,
      data.qualityCheck.specialShapeSeen,
      data.qualityCheck.specialShapeComment || ""
    );
    form.value.bodyWaveCorners = createValidationObject(
      data.qualityCheck.corpsOnduCoinsRelevant,
      data.qualityCheck.corpsOnduCoinsValidation,
      data.qualityCheck.corpsOnduCoinsSeen,
      data.qualityCheck.corpsOnduCoinsComment || ""
    );
    form.value.velourAuthorValidation = createValidationObject(
      data.qualityCheck.velourAuthorRelevant,
      data.qualityCheck.velourAuthorValidation,
      data.qualityCheck.velourAuthorSeen,
      data.qualityCheck.velourComment || ""
    );
    form.value.washingValidation = createValidationObject(
      data.qualityCheck.washingRelevant,
      data.qualityCheck.washingValidation,
      data.qualityCheck.washingSeen,
      data.qualityCheck.wachingComment || ""
    );
    form.value.cleaningValidation = createValidationObject(
      data.qualityCheck.cleaningRelevant,
      data.qualityCheck.cleaningValidation,
      data.qualityCheck.cleaningSeen,
      data.qualityCheck.cleaningComment || ""
    );
    form.value.carvingValidation = createValidationObject(
      data.qualityCheck.carvingRelevant,
      data.qualityCheck.carvingValidation,
      data.qualityCheck.carvingSeen,
      data.qualityCheck.carvingComment || ""
    );
    form.value.tissueColorValidation = createValidationObject(
      data.qualityCheck.fabricColorRelevant,
      data.qualityCheck.fabricColorValidation,
      data.qualityCheck.fabricColorSeen,
      data.qualityCheck.fabricColorComment || ""
    );
    form.value.fringeRepairValidation = createValidationObject(
      data.qualityCheck.frangeRelevant,
      data.qualityCheck.frangeValidation,
      data.qualityCheck.frangeSeen,
      data.qualityCheck.frangComment || ""
    );
    form.value.nonBindingValidation = createValidationObject(
      data.qualityCheck.noBindingRelevant,
      data.qualityCheck.noBindingValidation,
      data.qualityCheck.noBindingSeen,
      data.qualityCheck.noBindingComment || ""
    );
    form.value.signatureValidation = createValidationObject(
      data.qualityCheck.signatureRelevant,
      data.qualityCheck.signatureValidation,
      data.qualityCheck.signatureSeen,
      data.qualityCheck.signatureComment || ""
    );
    form.value.sansBackingValidation = createValidationObject(
      data.qualityCheck.withoutBackingRelevant,
      data.qualityCheck.withoutBackingValidation,
      data.qualityCheck.withoutBackingSeen,
      data.qualityCheck.withoutBackingComment || ""
    );
  }

  if (data.qualityRespect) {
    form.value.qualityRespectId = data.qualityRespect.id;
    form.value.respectPlanValidation = createValidationObject(
      data.qualityRespect.respectPlanRelevant,
      data.qualityRespect.respectPlanValid,
      data.qualityRespect.respectPlanSeen,
      data.qualityRespect.respectPlanComment || ""
    );
    form.value.respectHeightValidation = createValidationObject(
      data.qualityRespect.respectDoorHeightRelevant,
      data.qualityRespect.respectDoorHeightValid,
      data.qualityRespect.respectDoorHeightSeen,
      data.qualityRespect.respectDoorHeightComment || ""
    );
    form.value.respectPitValidation = createValidationObject(
      data.qualityRespect.respectFossRelevant,
      data.qualityRespect.respectFossValide,
      data.qualityRespect.respectFossSeen,
      data.qualityRespect.respectFossComment || ""
    );
    form.value.respectOtherCarpetValidation = createValidationObject(
      data.qualityRespect.respectOtherCarpetRelevant,
      data.qualityRespect.respectOtherCarpetValid,
      data.qualityRespect.respectOtherCarpetSeen,
      data.qualityRespect.respectOtherCarpetComment || ""
    );
    form.value.respectLengthValidation = createValidationObject(
      data.qualityRespect.respectMaxMinLengthRelevant,
      data.qualityRespect.respectMaxMinLengthValid,
      data.qualityRespect.respectMaxMinLengthSeen
    );
    form.value.respectWidthValidation = createValidationObject(
      data.qualityRespect.respectMaxMinWidthRelevant,
      data.qualityRespect.respectMaxMinWidthValid,
      data.qualityRespect.respectMaxMinWidthSeen
    );
    form.value.distanceTopValidation = createValidationObject(
      data.qualityRespect.wallDistanceTopRelevant,
      data.qualityRespect.wallDistanceTopValid,
      data.qualityRespect.wallDistanceTopSeen
    );
    form.value.distanceBottomValidation = createValidationObject(
      data.qualityRespect.wallDistanceBottomRelevant,
      data.qualityRespect.wallDistanceBottomValid,
      data.qualityRespect.wallDistanceBottomSeen
    );
    form.value.distanceRightValidation = createValidationObject(
      data.qualityRespect.respectwallDistanceRightRelevant,
      data.qualityRespect.respectwallDistanceRightValid,
      data.qualityRespect.respectwallDistanceRightSeen
    );
    form.value.distanceLeftValidation = createValidationObject(
      data.qualityRespect.respectwallDistanceLeftRelevant,
      data.qualityRespect.respectwallDistanceLeftValid,
      data.qualityRespect.respectwallDistanceLeftSeen
    );
    form.value.respectColorValidation = createValidationObject(
      data.qualityRespect.respectColorRelevant,
      data.qualityRespect.respectColorValid,
      data.qualityRespect.respectColorSeen,
      data.qualityRespect.respectColorComment || ""
    );
    form.value.respectMaterialValidation = createValidationObject(
      data.qualityRespect.respectMaterialRelevant,
      data.qualityRespect.respectMaterialValid,
      data.qualityRespect.respectMaterialSeen,
      data.qualityRespect.respectMaterialComment || ""
    );
    form.value.respectVelvetValidation = createValidationObject(
      data.qualityRespect.respectVelourRelevant,
      data.qualityRespect.respectVelourValid,
      data.qualityRespect.respectVelourSeen,
      data.qualityRespect.respectVelourComment || ""
    );
    form.value.respectNoteValidation = createValidationObject(
      data.qualityRespect.respectRemarkRelevant,
      data.qualityRespect.respectRemarkValid,
      data.qualityRespect.respectRemarkSeen,
      data.qualityRespect.respectRemarkComment || ""
    );

    // Envoi et Pénalité fields
    form.value.orderStatus =
      data.qualityRespect.orderStatus !== null ? data.qualityRespect.orderStatus : true;

    // Handle penaltyDate - it can be either a string or an object with date property
    if (data.qualityRespect.penaltyDate) {
      if (typeof data.qualityRespect.penaltyDate === "string") {
        form.value.penaltyDate = data.qualityRespect.penaltyDate.split(" ")[0] || "";
      } else if (data.qualityRespect.penaltyDate.date) {
        form.value.penaltyDate = data.qualityRespect.penaltyDate.date.split(" ")[0] || "";
      } else {
        form.value.penaltyDate = "";
      }
    } else {
      form.value.penaltyDate = "";
    }
  }
};

const validateForm = () => {
  showValidation.value = true;

  // Check if all required comments are filled
  const fields = Object.values(form.value).filter(
    (field) => typeof field === "object" && field !== null && field.relevant !== undefined
  );

  for (const field of fields) {
    if (
      field.relevant === true &&
      field.validation === false &&
      field.seen === true &&
      !field.comment
    ) {
      return false;
    }
  }

  return true;
};

const loadCheckingList = async () => {
  if (!checkingListId) {
    return;
  }
  try {
    data.value = await checkingListService.getCheckingListById(checkingListId);
    fillForm(data.value);
  } catch (e) {
    console.error("Failed to load checking list:", e);
  }
};

const saveCheckingList = async () => {
  if (!checkingListId) {
    return;
  }

  if (!validateForm()) {
    window.showMessage("Veuillez remplir tous les commentaires obligatoires", "error");
    return;
  }

  try {
   
    window.showMessage("Sauvegarde en cours...", "info");

    // Save all sections in parallel for better performance
    const savePromises = [];

    // Save main checking list
    const checkingListPayload = {
      dateEndProd: form.value.productionEnd,
      comment: form.value.globalComment,
    };
    savePromises.push(
      checkingListService
        .updateCheckingList(checkingListId, checkingListPayload)
        .then(() => console.log("✓ Main checking list saved"))
        .catch((err) => {
          console.error("✗ Main checking list save failed:", err);
          throw new Error("Erreur lors de la sauvegarde des informations principales");
        })
    );

    // Save shape validation
    const shapeValidationPayload = {
      shapeValidation: form.value.shapeValidation,
      realWidth: form.value.realWidth,
      realLength: form.value.realLength,
      surface: form.value.surface,
      diagonalA: form.value.diagonalA,
      diagonalB: form.value.diagonalB,
    };
    savePromises.push(
      checkingListService
        .updateShapeValidation(form.value.shapeValidationId, shapeValidationPayload)
        .then(() => console.log("✓ Shape validation saved"))
        .catch((err) => {
          console.error("✗ Shape validation save failed:", err);
          throw new Error("Erreur lors de la sauvegarde de la validation de forme");
        })
    );

    // Save quality check
    const qualityCheckPayload = {
      graphicValidation: form.value.graphicValidation,
      instructionRespect: form.value.instructionRespect,
      repairValidation: form.value.repairValidation,
      tighteningValidation: form.value.tighteningValidation,
      woolQuality: form.value.woolQuality,
      silkQuality: form.value.silkQuality,
      specialShape: form.value.specialShape,
      bodyWaveCorners: form.value.bodyWaveCorners,
      velourAuthorValidation: form.value.velourAuthorValidation,
      washingValidation: form.value.washingValidation,
      cleaningValidation: form.value.cleaningValidation,
      carvingValidation: form.value.carvingValidation,
      tissueColorValidation: form.value.tissueColorValidation,
      fringeRepairValidation: form.value.fringeRepairValidation,
      nonBindingValidation: form.value.nonBindingValidation,
      signatureValidation: form.value.signatureValidation,
      sansBackingValidation: form.value.sansBackingValidation,
      comment: form.value.globalComment,
    };
    savePromises.push(
      checkingListService
        .updateQualityCheck(form.value.qualityCheckId, qualityCheckPayload)
        .then(() => console.log("✓ Quality check saved"))
        .catch((err) => {
          console.error("✗ Quality check save failed:", err);
          throw new Error("Erreur lors de la sauvegarde du contrôle qualité");
        })
    );


    // Fix penaltyDate handling - explicitly set to null when empty
    let penaltyDateValue = null;
    if (form.value.penaltyDate && form.value.penaltyDate.trim() !== "") {
      penaltyDateValue = form.value.penaltyDate;
    }
    // Save quality respect
    const qualityRespectPayload = {
      respectPlanValidation: form.value.respectPlanValidation,
      respectHeightValidation: form.value.respectHeightValidation,
      respectPitValidation: form.value.respectPitValidation,
      respectOtherCarpetValidation: form.value.respectOtherCarpetValidation,
      respectLengthValidation: form.value.respectLengthValidation,
      respectWidthValidation: form.value.respectWidthValidation,
      distanceTopValidation: form.value.distanceTopValidation,
      distanceBottomValidation: form.value.distanceBottomValidation,
      distanceRightValidation: form.value.distanceRightValidation,
      distanceLeftValidation: form.value.distanceLeftValidation,
      respectColorValidation: form.value.respectColorValidation,
      respectMaterialValidation: form.value.respectMaterialValidation,
      respectVelvetValidation: form.value.respectVelvetValidation,
      respectNoteValidation: form.value.respectNoteValidation,
      orderStatus:
        form.value.orderStatus === "true"
          ? true
          : form.value.orderStatus === "false"
          ? false
          : null,
      penaltyDate: penaltyDateValue,
      penaltyComment: "",
    };
    
    savePromises.push(
      checkingListService
        .updateQualityRespect(form.value.qualityRespectId, qualityRespectPayload)
        .then(() => console.log("✓ Quality respect saved"))
        .catch((err) => {
          console.error("✗ Quality respect save failed:", err);
          throw new Error("Erreur lors de la sauvegarde du respect qualité");
        })
    );

    // Wait for all saves to complete
    await Promise.all(savePromises);

    window.showMessage("Mise à jour avec succès.");
    showValidation.value = false;

    // Reload the data to ensure we have the latest state
    await loadCheckingList();
  } catch (e) {
    console.error("Failed to update checking list:", e);
    window.showMessage(e.message || "Erreur lors de la mise à jour", "error");
  }
};

const goToWorkshop = () => {
  router.back();
};

onMounted(loadCheckingList);
</script>
