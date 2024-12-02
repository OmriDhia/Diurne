<template>
    <div>
        <d-base-modal id="modalCreateVariation" title="Nouvelle variation" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input :required="true" label="Référence image" v-model="data.variation_image_reference" :error="error.variation_image_reference"></d-input>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input :required="true" label="Variation" v-model="data.variation" :error="error.variation_image_reference"></d-input>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="saveVariation">Enregistrer</button>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { ref } from "vue";
    import VueFeather from 'vue-feather';
    import axiosInstance from "../../../../config/http";
    import dInput from "../../../../components/base/d-input.vue";
    import dBaseModal from "../../../../components/base/d-base-modal.vue";
    import dPanelTitle from "../../../../components/common/d-panel-title.vue";
    import {formatErrorViolations, Helper} from "../../../../composables/global-methods";
  
    const props = defineProps({
        carpetDesignOrderId:{
            type: Number
        },
        id_di:{
            type: Number
        },
    });
    
    const data = ref({
        variation_image_reference: "",
        variation: ""
    });
    const error = ref({});
    
    const saveVariation = async () =>{
        try{
            /*if(props.carpetDesignOrderId){
                const url = `/api/carpetDesignOrder/${props.carpetDesignOrderId}/createVariation`;
                const res = await axiosInstance.post(url,data.value);
                window.showMessage("Ajout avec succées.");
            }*/
            document.querySelector("#modalCreateVariation .btn-close").click();
            location.href = `/projet/contremarques/projectdis/${props.id_di}`;
            initData();
        }catch (e){
            if(e.response.data.violations){
                error.value = formatErrorViolations(e.response.data.violations);
            }
            window.showMessage(e.message,'error')
        }
    };
    
    const initData = () => {
        data.value = {
            variation_image_reference: "",
            variation: ""
        };
    };
    
    const emit = defineEmits(['onClose']);

    const handleClose = () => {
        initData();
        error.value = {};
        emit('onClose')
    }
</script>
