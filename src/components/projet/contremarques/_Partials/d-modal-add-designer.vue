<template>
    <div>
        <d-base-modal id="modalAddDesigner" title="Ajouter designeur" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                           <d-designer-dropdown v-model="data.designerId"></d-designer-dropdown>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input label="Date d'attribution" type="date" v-model="data.dateFrom"></d-input>
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
    import { ref} from "vue";
    import dInput from "../../../../components/base/d-input.vue";
    import dBaseModal from "../../../../components/base/d-base-modal.vue";
    import dDesignerDropdown from "../../../common/d-designer-dropdown.vue";

    const data = ref({
        designerId: 0,
        dateFrom: new Date() ,
        dateTo: "",
        inProgress: true,
        stopped: false,
        done: false
    });
    const emit = defineEmits(['onClose','addDesigner']);
    const addDesigner = () => {
        emit('addDesigner', data.value);
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
        emit('onClose')
    }
</script>
