<template>
    <div>
        <d-base-modal id="modalAddMaterials" title="Ajout matière" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                           <d-materials-dropdown v-model="data.material_id"></d-materials-dropdown>
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
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="addMaterials">Ajouter</button>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { nextTick, ref } from "vue";
    import dInput from "../../../../components/base/d-input.vue";
    import dBaseModal from "../../../../components/base/d-base-modal.vue";
    import dCarpetDropdown from "../../../../components/common/d-carpet-dropdown.vue";
    import dMaterialsDropdown from "../dropdown/d-materials-dropdown.vue";
    
    const data = ref({
        material_id: 0,
        rate: 0,
    });
    const emit = defineEmits(['onClose','addMaterial','add-materials-click']);
    const addMaterials = async () => {
        emit('addMaterial', { ...data.value });
        await nextTick();
        emit('add-materials-click');
        document.querySelector("#modalAddMaterials .btn-close").click();
    }
    const handleClose = () => {
        data.value = {
            material_id: 0,
            rate: 0,
        };
        emit('onClose')
    }
</script>
