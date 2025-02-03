<template>
    <div>
        <div class="row justify-content-between">
            <div class="col-auto">
                <d-btn-outlined icon="map" label="Nouvelle composition" data-bs-toggle="modal" data-bs-target="#modalNewComposition"></d-btn-outlined>
            </div>
            <div class="col-auto">
                <d-btn-fullscreen></d-btn-fullscreen>
            </div>
        </div>
        
        <d-base-modal id="modalNewComposition" :title="(step === 1) ? 'Nouveau composition': 'Choix couleurs des fils'" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-8">
                    <template v-if="step === 1">
                        <d-input label="trame" v-model="data.trame"></d-input>
                        <d-input type="number" label="fil" v-model="data.threadCount" v-if="!props.carpetCompositionId"></d-input>
                        <d-input type="number" label="couche" v-model="data.layerCount" v-if="!props.carpetCompositionId"></d-input>
                    </template>
                    <template v-else>
                       <div class="row" v-for="(thread, index) in threads" :key="index">
                           <d-colors-dominants-dropdown v-model="thread.techColorId" :index="index + 1"></d-colors-dominants-dropdown>
                       </div>
                    </template>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button v-if="step === 1" class="btn btn-custom pe-2 ps-2" @click.prevent="addComposition" :disabled="loadStepOne">
                    suivant
                    <vue-feather type="loader" animation="spin" v-if="loadStepOne"></vue-feather>
                </button>
                <button v-else class="btn btn-custom pe-2 ps-2" @click.prevent="addThreads" :disabled="loadStepTwo">
                    Enregistrer
                    <vue-feather type="loader" animation="spin" v-if="loadStepTwo"></vue-feather>
                </button>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { ref, onMounted, watch } from "vue";
    import VueFeather from 'vue-feather';
    import dInput from "../../../base/d-input.vue";
    import axiosInstance from "../../../../config/http";
    import { formatErrorViolations } from "../../../../composables/global-methods";
    import dBtnOutlined from "../../../base/d-btn-outlined.vue";
    import dColorsDominantsDropdown from "../dropdown/d-colors-dominants-dropdown.vue"
    import dBaseModal from "../../../base/d-base-modal.vue";
    import dBtnFullscreen from "../../../base/d-btn-fullscreen.vue";
    import contremarqueService from "../../../../Services/contremarque-service";
    
    const props = defineProps({
        carpetSpecificationId: {
            type: Number,
        },
    });
    
    const emit = defineEmits(['onClose', 'newCarpetComposition', 'addThreads']);
    
    const color = ref(null);
    const data = ref({
        trame: "",
        threadCount: 0,
        layerCount: 0
    });
    const step = ref(1);
    const threads = ref([]);
    const loadStepOne = ref(false);
    const loadStepTwo = ref(false);
    
    let carpetCompositionId = 0;
    

    const addComposition = async () => {
        if (step.value === 1 && props.carpetSpecificationId) {
            try {
                loadStepOne.value = true;
                const query = {
                    trame: data.value.trame,
                    threadCount: parseInt(data.value.threadCount),
                    layerCount: parseInt(data.value.layerCount)
                };
                const res = await axiosInstance.post(`/api/CarpetSpecification/${props.carpetSpecificationId}/CarpetComposition/create`, query);
                carpetCompositionId = res.data.response.id;
                
                for (let i = 0; i < data.value.threadCount; i++) {
                    threads.value.push(
                        {techColorId: 0}
                    );
                }
                step.value = 2;
            } catch (e) {
                window.showMessage('Erreur au niveau de la création du composition de tapis', 'error')
                throw new Error(e.message);
            } finally {
                loadStepOne.value = false; 
            }
        } else {
            window.showMessage('Identifiant de spécification tapis inccorrect.', 'error')
        }
    }
    
    const addThreads = async () => {
        if(step.value === 2 && carpetCompositionId){
            try{
                loadStepTwo.value = true;
                const ths = await Promise.all(
                    threads.value.map(async (f, index) => {
                        try {
                            const result = await axiosInstance.post(`/api/CarpetComposition/${carpetCompositionId}/Thread/create`, {
                                threadNumber: index + 1,
                                techColorId: f.techColorId.id
                            });
                            return result.data.response.techColor;
                        } catch (error) {
                            console.error(`Error creating thread ${index + 1}:`, error);
                            return null;
                        }
                    })
                );


                loadStepTwo.value = false;
                const validThreads = ths.filter(thread => thread !== null);
                emit('newCarpetComposition', carpetCompositionId);
                emit('addThreads', {threads: validThreads, layerCount: data.value.layerCount});
                
                document.querySelector("#modalNewComposition .btn-close").click();
            }catch(err){
                console.log(err.message);
            }
        }
    };
    const handleClose = () => {
        color.value = null;
        trame.value = "";
        emit('onClose')
    }
</script>
