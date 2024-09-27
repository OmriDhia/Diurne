<template>
    <div>
        <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#modalCompositionSteps">Ajouter Composition</button>
        <d-base-modal id="modalCompositionSteps" title="Composition" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center justify-content-center">
                       
                    </div>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="nextStep">Suivant</button>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { ref, onMounted, watch } from "vue";
    import VueFeather from 'vue-feather';
    import dInput from "../../../base/d-input.vue";
    import axiosInstance from "../../../../config/http";
    import { formatErrorViolations } from "../../../../composables/global-methods"

    const props = defineProps({
        carpetDesignOrderId: {
            type: Number,
        }
    });

    const nextStep = () => {
      
    }
    const emit = defineEmits(['onClose']);
    
    const handleClose = () => {
        emit('onClose')
    }
</script>
