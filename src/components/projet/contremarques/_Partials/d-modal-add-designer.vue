<template>
    <div>
        <d-base-modal id="modalAddDesigner" title="Ajouter designeur" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                           <d-designer-dropdown v-model="data.designerId" :error="error.designerId"></d-designer-dropdown>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input label="Date d'attribution" type="date" v-model="data.dateFrom" :error="error.dateFrom"></d-input>
                        </div>
                    </div>
                    <div class="row p-2 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-designer-status v-model="designerStatus"></d-designer-status>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="addDesigner">Ajouter</button>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { ref } from "vue";
    import dInput from "../../../../components/base/d-input.vue";
    import dBaseModal from "../../../../components/base/d-base-modal.vue";
    import dDesignerDropdown from "../../../common/d-designer-dropdown.vue";
    import dDesignerStatus from "./d-designer-status.vue";
    import axiosInstance from "../../../../config/http";
    import {designerStatusConst} from "../../../../composables/constants";

    const props = defineProps({
        carpetDesignOrderId : {
            type: Number
        }
    });
    
    const designerStatus = ref(1);
    const error = ref({});
    const data = ref({
        designerId: 0,
        dateFrom: new Date() ,
        dateTo: new Date(),
        inProgress: true,
        stopped: false,
        done: false
    });
    const emit = defineEmits(['onClose','addDesigner']);
    const addDesigner = async () => {
        try{
            if(!props.carpetDesignOrderId){
                window.showMessage('Id carpetDesignOrderId undefined', 'error');
                return ;
            }
            data.value.inProgress = (designerStatus.value === designerStatusConst[0].id);
            data.value.stopped = (designerStatus.value === designerStatusConst[1].id);
            data.value.done = (designerStatus.value === designerStatusConst[2].id);

            const res = await axiosInstance.post(`/api/carpetDesignOrders/${props.carpetDesignOrderId}/designerAssignment`,data.value);
            emit('addDesigner', res.data.response);
            window.showMessage("Ajout a jour avec succées.");
            document.querySelector("#modalAddDesigner .btn-close").click();
        }catch (e){
            if(e.response.data.violations){
                error.value = formatErrorViolations(e.response.data.violations);
            }
            window.showMessage(e.message,'error')
        }
        
        document.querySelector("#modalAddDesigner .btn-close").click();
    }
    const handleClose = () => {
        data.value = {
            designerId: 0,
            dateFrom: new Date() ,
            dateTo: "",
            inProgress: true,
            stopped: false,
            done: false
        };
        error.value = {};
        emit('onClose')
    }
</script>
