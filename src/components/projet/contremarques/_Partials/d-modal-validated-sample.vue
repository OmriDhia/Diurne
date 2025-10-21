<template>
    <div>
        <d-base-modal id="modalManageValidatedSimple" title="ECH Validé de référence" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center justify-content-center">
                        <div class="col-sm-9">
                            <d-input label="RN Ech validé" v-model="data.rnValidatedSample"></d-input>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-end">
                                    Couleurs
                                </div>
                                <div class="col-md-auto">
                                    <div class="checkbox-default custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="libColor"
                                               v-model="data.color" />
                                        <label class="custom-control-label" for="libColor"></label>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control" v-model="data.libColor">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-end">
                                    Velours
                                </div>
                                <div class="col-md-auto">
                                    <div class="checkbox-default custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="libVelvet"
                                               v-model="data.velvet" />
                                        <label class="custom-control-label" for="libVelvet"></label>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control" v-model="data.libVelvet">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-end">
                                    Matière
                                </div>
                                <div class="col-md-auto">
                                    <div class="checkbox-default custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="libMaterial"
                                               v-model="data.material" />
                                        <label class="custom-control-label" for="libMaterial"></label>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control" v-model="data.libMaterial">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-1 justify-content-center mt-3">
                        <div class="col-sm-12">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    REMARQUE CLIENT SUR ECH
                                </div>
                                <div class="col-md-12">
                                    <textarea class="form-control" v-model="data.customerNoteOnSample"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="saveFinishing">Enregistrer</button>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { ref, onMounted, watch } from 'vue';
    import VueFeather from 'vue-feather';
    import axiosInstance from '../../../../config/http';
    import dInput from '../../../../components/base/d-input.vue';
    import dBaseModal from '../../../../components/base/d-base-modal.vue';
    import dPanelTitle from '../../../../components/common/d-panel-title.vue';
    import { formatErrorViolations } from '../../../../composables/global-methods';
    import { customerInstructionObject } from '../../../../composables/constants';
    import contremarqueService from '../../../../Services/contremarque-service';

    const props = defineProps({
        carpetDesignOrderId: {
            type: Number
        },
        customerInstructionId: {
            type: Number
        },
        validateSimpleData: {
            type: Object
        }
    });

    const emit = defineEmits(['onClose', 'updateCustomerInstruction', 'updateCustomerInstructionId']);
    const validateSimpleId = ref(null);
    const data = ref({
        id: null,
        rnValidatedSample: '',
        color: false,
        libColor: '',
        velvet: false,
        libVelvet: '',
        material: false,
        libMaterial: '',
        customerNoteOnSample: ''
    });
    const error = ref({});

    const saveFinishing = async () => {
        try {
            let customerInstructionId = props.customerInstructionId;
            let customerInstruction = Object.assign({}, customerInstructionObject);
            customerInstruction.hasValidateSample = true;
            if (!customerInstructionId && props.carpetDesignOrderId) {
                const res = await contremarqueService.addUpdatecustomerInstruction(props.carpetDesignOrderId, customerInstruction);
                customerInstructionId = parseInt(res.id);
                emit('updateCustomerInstructionId', customerInstructionId);
            }

            if (customerInstructionId) {
                if (data.value.id) {
                    const res = await axiosInstance.put(`/api/customerInstruction/${customerInstructionId}/validatedSamples/${data.value.id}/update`, data.value);
                    window.showMessage('Mise a jour avec succées.');
                } else {
                    const res = await axiosInstance.post(`/api/customerInstruction/${customerInstructionId}/validated-sample/create`, data.value);
                    data.value.id = parseInt(res.data.response.id);
                    window.showMessage('Ajout avec succées.');
                    window.location.reload();
                }
            }
            emit('updateCustomerInstruction', {
                instruction: 'validateSimple',
                id: data.value.id
            });
            document.querySelector('#modalManageValidatedSimple .btn-close').click();
        } catch (e) {
            console.log(e);
            if (e.response?.data?.violations) {
                error.value = formatErrorViolations(e.response.data.violations);
            }
            window.showMessage(e.message, 'error');
        }
    };

    const setData = () => {
        if (props.validateSimpleData) {
            const { customerInstruction, ...validateSimple } = props.validateSimpleData;
            data.value = validateSimple;
        }
    };

    watch(() => props.validateSimpleData, setData, { deep: true });

    onMounted(setData);

    const handleClose = () => {
        error.value = {};
        emit('onClose');
    };
</script>
