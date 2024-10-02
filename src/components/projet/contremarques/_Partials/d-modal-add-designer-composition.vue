<template>
    <div>
        <d-base-modal id="modalAddDesignerComposition" title="Ajout matière de l'image" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                           <d-materials-dropdown v-model="data.materialId"></d-materials-dropdown>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input label="Rate" type="number" v-model="data.rate"></d-input>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click="AddDesignerComposition">Ajouter</button>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { ref} from "vue";
    import dInput from "../../../../components/base/d-input.vue";
    import dBaseModal from "../../../../components/base/d-base-modal.vue";
    import dCarpetDropdown from "../../../../components/common/d-carpet-dropdown.vue";
    import dMaterialsDropdown from "../dropdown/d-materials-dropdown.vue";
    import axiosInstance from "../../../../config/http";
    
    const props = defineProps({
        carpetSpecificationId: {
            type: Number
        }
    });
    
    const data = ref({
        id: 0,
        materialId: 0,
        rate: 0,
        carpetSpecificationId: 0,
    });
    const error = ref({});
    
    const emit = defineEmits(['onClose','addDesignerComposition']);
    
    const AddDesignerComposition = async () => {
        error.value = {};
        if(props.carpetSpecificationId){
            data.value.carpetSpecificationId = props.carpetSpecificationId;
            try{
                const res = await axiosInstance.post('/api/createDesignerComposition', data.value);
                emit('addDesignerComposition', res.data.response);
                window.showMessage('Ajout avec succès.');
                document.querySelector("#modalAddDesignerComposition .btn-close").click();
            }catch (e) {
                console.error(e)
            } 
        }else{
            window.showMessage(`L'identifiant de spécification tapis n'existe pas.`, 'error')
        }
    };
    const handleClose = () => {
        data.value = {
            materialId: 0,
            rate: 0,
            carpetSpecificationId: props.carpetSpecificationId,
        };
        error.value = {};
        emit('onClose')
    }
</script>
