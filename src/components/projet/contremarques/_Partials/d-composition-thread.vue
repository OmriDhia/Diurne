<template>
    <div>
        <div class="row justify-content-between">
            <div class="col-auto">
                <d-btn-outlined icon="plus" label="Ajouter un fil" data-bs-toggle="modal" data-bs-target="#modalCompositionThread"></d-btn-outlined>
            </div>
            <div class="col-auto">
                <d-btn-fullscreen></d-btn-fullscreen>
            </div>
        </div>
        
        <d-base-modal id="modalCompositionThread" title="Composition" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-8">
                    <d-input label="trame" v-model="trame" v-if="!props.carpetCompositionId"></d-input>
                    <d-colors-dominants-dropdown v-model="color"></d-colors-dominants-dropdown>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="addThread">Ajouter</button>
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
    
    const props = defineProps({
        carpetSpecificationId: {
            type: Number,
        },
        carpetCompositionId: {
            type: Number,
        },
    });
    
    const emit = defineEmits(['onClose', 'addThread']);
    
    const color = ref(null);
    const trame = ref("");

    const addThread = async () => {
        if(!props.carpetCompositionId){
            try{
                const res = await axiosInstance.post(`/api/CarpetSpecification/${props.carpetSpecificationId}/CarpetComposition/create`,{
                    trame: trame.value,
                    threadCount: 1,
                    layerCount: 0
                });
            }catch(e){
                console.log(e.message);
            }
        }
        if((res && res.data.response)  || props.carpetCompositionId){
            try{
                const carpetCompositionId = res.data.response.id ? res.data.response.id : props.carpetCompositionId
                const res = await axiosInstance.post(`/api/CarpetComposition/${carpetCompositionId}/Thread/create`, {
                    threadNumber: 0,
                    techColorId: color.value.id
                });
                emit('addThread', res.data.response);
                document.querySelector("#modalCreateDI .btn-close").click();
            }catch(err){
                console.log(e.message);
            }
        }
    };
    
    const handleClose = () => {
        color.value = null;
        trame.value = "";
        emit('onClose')
    }
</script>
