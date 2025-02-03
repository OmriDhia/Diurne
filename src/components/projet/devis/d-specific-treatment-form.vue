<template>
    <div class="col-md-12" v-if="props.quoteDetailId">
        <div class="accordion" id="specialTreatmentsAccordion">
            <template v-for="(treatment, index) in specialTreatments" :key="index">
                <div class="card mb-1">
                    <header class="card-header" role="tab">
                        <section class="mb-0 mt-0">
                            <div class="collapsed" role="menu" data-bs-toggle="collapse" :data-bs-target="'#specialTreatment'+index" :aria-expanded="index === specialTreatments.length -1 " :aria-controls="'specialTreatment'+index">
                                Traitement {{ index + 1 }}
                                <div class="icons">
                                    <vue-feather type="chevron-down" size="14"></vue-feather>
                                </div>
                            </div>
                        </section>
                    </header>
                    <div :id="'specialTreatment'+index" :class="{'collapse' : true, 'show':index === specialTreatments.length - 1}" :aria-labelledby="'specialTreatment'+index" data-bs-parent="#specialTreatmentsAccordion">
                        <div class="card-body">
                            <d-special-treatment v-model="treatment.treatmentId" :error="error.treatmentId" @exportTrait="changeTrait($event,index)"></d-special-treatment>
                            <d-input :disabled="true" label="Prix/unité" v-model="treatment.unitPrice" :error="error.unitPrice"></d-input>
                            <d-input v-if="treatment.totalPrice" :disabled="true" label="Prix total" v-model="treatment.totalPrice" :error="error.totalPrice"></d-input>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <div class="row justify-content-end pt-2">
            <div class="col-auto">
                <button :disabled="disabled" class="btn ms-0 btn-outline-custom" @click="saveLastTreatment">
                    Ajouter
                    <vue-feather type="plus" size="14"></vue-feather>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, watch } from "vue";
    import VueFeather from 'vue-feather';
    import dInput from "../../base/d-input.vue";
    import axiosInstance from "../../../config/http";
    import dSpecialTreatment from "../../common/d-specialTreatment.vue";
    import {formatErrorViolations, Helper} from "../../../composables/global-methods";
    import '../../../assets/sass/components/tabs-accordian/custom-accordions.scss';

    // Props
    const props = defineProps({
        quoteDetailId: {
            type: Number,
            required: true,
        },
        quantity: {
            type: Number,
            default: 1,
        },
        treatments: {
            type: Array,
            default: () => [],
        },
    });

    const emit = defineEmits(['addTreatment']);
    const specialTreatments = ref([...props.treatments]);
    const error = ref({});
    const disabled = ref(false);

    // Méthodes
    const addTreatment = () => {
        specialTreatments.value.push({
            treatmentId: 0,
            unitPrice: "",
            totalPrice: "",
        });
    };
    
    addTreatment();
    
    const removeTreatment = (index) => {
        specialTreatments.value.splice(index, 1);
    };
    
    const changeTrait = (trait, i) => {
        specialTreatments.value[i].unitPrice =  Helper.FormatNumber(trait.price);
        //specialTreatments.value[i].totalPrice =  Helper.FormatNumber(trait.price) * (props.quantity ? parseInt(props.quantity) : 1);
    };

    const saveLastTreatment = async () => {
        try {
            error.value = {};
            disabled.value = true;
            
            if (specialTreatments.value.length > 0) {
                const lastTreatment =
                    specialTreatments.value[specialTreatments.value.length - 1];

                const res = await axiosInstance.post(
                    `/api/quote-detail/${props.quoteDetailId}/carpet-specific-treatment/create`,
                    lastTreatment
                );
                const t = res.data.response;
                specialTreatments.value[specialTreatments.value.length - 1] = {
                    treatmentId: t.treatment?.id,
                    unitPrice: Helper.FormatNumber(t.unitPrice),
                    totalPrice:  Helper.FormatNumber(t.totalPrice),
                };
                addTreatment();
                emit('addTreatment',true);
                window.showMessage("Le traitement a été ajouté avec succès.");
            } else {
                window.showMessage("Aucun traitement à ajouter.", "error");
            }
        } catch (e) {
            if (e.response?.data?.violations) {
                error.value = formatErrorViolations(e.response.data.violations);
            }
            window.showMessage(e.message, "error");
        } finally {
            disabled.value = false; // Réactiver le bouton
        }
    };

    // Synchroniser avec les props en cas de mise à jour
    watch(
        () => props.treatments,
        (newTreatments) => {
            specialTreatments.value = [...newTreatments.map(t => {
                return {
                    treatmentId: t.treatment?.id,
                    unitPrice: Helper.FormatNumber(t.unitPrice),
                    totalPrice:  Helper.FormatNumber(t.totalPrice), 
                }
            })];
            addTreatment();
        }
    );
</script>

<style scoped>
    
</style>
