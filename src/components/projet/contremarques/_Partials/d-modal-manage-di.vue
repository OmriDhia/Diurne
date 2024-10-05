<template>
    <div>
        <d-base-modal id="modalDIManage" title="Demande d'image" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-2 align-items-center">
                        <div class="col-auto pe-2 ps-2 text-black">
                            Format<span class="required">*</span>:
                        </div>
                        <div class="col-auto pe-1 ps-2">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="formatA3-2" v-model="data.format" name="format2" value="A3"/>
                                <label class="custom-control-label text-black" for="formatA3-2"> {{ $t('A3') }} </label>
                            </div>
                        </div>
                        <div class="col-auto pe-1 ps-1">
                            <div class="radio-success custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="formatA4-1" v-model="data.format" name="format2" value="A4"/>
                                <label class="custom-control-label text-black" for="formatA4-1"> {{ $t('A4') }} </label>
                            </div>
                        </div>
                        <div class="col-12" v-if="error.format">
                            <div  class="invalid-feedback">{{ $t("Le champ format est abligatoire.") }}</div>
                        </div>
                    </div>
                    <div class="row p-2 align-items-center">
                        <div class="col-sm-12">
                            <d-unit-measurements :required="true" v-model="data.unit_id" :error="error.unit_id"></d-unit-measurements>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center"  v-if="!props.contremarqueId">
                        <div class="col-sm-12 col-xl-9">
                            <d-contremarque-dropdown :required="true" v-model="data.contremarque_id" :error="error.contrmarque_id"></d-contremarque-dropdown>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-9">
                            <d-input :required="true" type="date" label="Deadline" v-model="data.deadline" :error="error.deadline"></d-input>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center" v-if="props.diId">
                        <div class="col-sm-6 col-xl-6">
                            <d-input :disabled="true" v-model="data.transmition_date" label="Date trasmission"></d-input>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="col-auto p-0">
                                <div class="custom-control custom-radio">
                                    <input type="checkbox" class="custom-control-input" id="transmis1" v-model="data.transmitted_to_studio"/>
                                    <label class="custom-control-label text-black" for="transmis1"> {{ $t('Transmis') }} </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="saveDI">Enregistrer</button>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { ref, onMounted, watch } from 'vue';
    import axiosInstance from '../../../../config/http';
    import dInput from '../../../../components/base/d-input.vue';
    import dBaseModal from '../../../../components/base/d-base-modal.vue';
    import dContremarqueDropdown from '../../../common/d-contremarque-dropdown.vue';
    import dUnitMeasurements from '../../../common/d-unit-measurements.vue';
    import { formatErrorViolations, Helper } from '../../../../composables/global-methods';
    import contremaqueService from "../../../../Services/contremarque-service";
    
    const props = defineProps({
        contremarqueId: {
            type: Number
        },
        diId: {
            type: Object
        },
    });

    const data = ref({
        format: "",
        deadline: new Date(),
        transmitted_to_studio: false,
        transmition_date: null,
        unit_id: 0,
        contremarque_id: 0
    });
    const error = ref({});

    const saveDI = async () =>{
        try{
            if(props.contremarqueId){
                data.value.contremarque_id = parseInt(props.contremarqueId);
            }

            if(props.diId){
                const res = await axiosInstance.put(`/api/projectDi/${props.diId}/update`,data.value);
                window.showMessage("Mise a jour avec succées.")
            }else{
                const res = await axiosInstance.post("/api/createProjectDi",data.value);
                window.showMessage("Ajout avec succées.");
            }

            document.querySelector("#modalDIManage .btn-close").click();
        }catch (e){
            if(e.response.data.violations){
                error.value = formatErrorViolations(e.response.data.violations);
            }
            window.showMessage(e.message,'error')
        }
    };
    const getDI = async (id) =>{
        try{
            if(id){
                const d = await contremaqueService.getProjectDiById(id);
                affectData(d);
            }
        }catch (e){
            console.log(e.message)
        }
    };

    onMounted(() => {
        if(props.diId){
            getDI(props.diId);  
        }
    });
    const affectData = (di) =>{
        if(di){
            data.value.format = di.format;
            data.value.deadline = di.deadline ? Helper.FormatDate(di.deadline.date,"YYYY-MM-DD") : '';
            data.value.transmitted_to_studio = di.transmitted_to_studio;
            data.value.transmition_date = di.transmition_date ? Helper.FormatDate(di.transmition_date.date) : '';
            data.value.unit_id = parseInt(di.unit.id);
            data.value.contremarque_id = di.contremarque;
        }
    };
    watch(
        () => props.diId,
        (diId) => {
            getDI(diId);
        }
    );

    const initData = () => {
        data.value = {
            format: "",
            deadline: new Date(),
            transmitted_to_studio: false,
            transmition_date: null,
            unit_id: 0,
            contremarque_id: 0
        };
    };
    const emit = defineEmits(['onClose']);

    const handleClose = () => {
        initData();
        error.value = {};
        emit('onClose')
    };
</script>
