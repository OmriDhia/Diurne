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
                    <!-- <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input label="Date d'attribution" type="date" v-model="data.dateFrom" :error="error.dateFrom"></d-input>
                        </div>
                    </div> -->
                    <!--div class="row p-2 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-designer-status v-model="designerStatus"></d-designer-status>
                        </div>
                    </div-->
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
    import axiosInstance from "../../../../config/http";
    import moment from "moment";


    const props = defineProps({
        imageCommandId : {
            type: Number
        }
    });
    
    //const designerStatus = ref(0);
    const error = ref({});
    const data = ref({
        imageCommandId: 0,
        designerId: 0,
        from: new Date() ,
        to: new Date(),
        inProgress: false,
        stopped: false,
        done: false,
        reasonForStopping: ""
    });
    const emit = defineEmits(['onClose','addDesigner']);
    const addDesigner = async () => {
        try{
            if(!props.imageCommandId){
                window.showMessage('Id imageCommandId undefined', 'error');
                return ;
            }
            data.value.from = moment().format("YYYY-MM-DD HH:mm:ss");
            data.value.imageCommandId = props.imageCommandId;
            const res = await axiosInstance.post(`/api/image-command/assign-designer`,data.value);
            emit('addDesigner', res.data.response);
            window.showMessage("Ajout a jour avec succées.");
            document.querySelector("#modalAddDesigner .btn-close").click();
        }catch (e){
            // if(e.response.data.violations){
            //     error.value = formatErrorViolations(e.response.data.violations);
            // }
            console.log(e);
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

<style>
.modal {
    overflow: visible !important;
}

.modal-body {
    overflow: visible !important;
}

.modal-content {
    overflow: visible !important;
}

.d-designer-dropdown {
    position: relative;
    z-index: 1050; /* Ensure it’s above the modal-footer */
}

</style>
