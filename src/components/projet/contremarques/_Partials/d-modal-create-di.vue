<template>
    <div>
        <d-base-modal id="modalCreateDI" title="Demande d'image" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-xl-9">
                            <d-contremarque-dropdown required="true" v-model="data.contremarque_id" :error="error.contrmarque_id" v-if="!props.contremarqueId"></d-contremarque-dropdown>
                        </div>
                    </div>
                    <div class="row p-2 align-items-center">
                        <div class="col-auto pe-2 ps-2 text-black">
                            Format<span class="required">*</span>:
                        </div>
                        <div class="col-auto pe-1 ps-2">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="formatA3-1" v-model="data.format" name="format-1" value="A3"/>
                                <label class="custom-control-label text-black" for="formatA3-1"> {{ $t('A3') }} </label>
                            </div>
                        </div>
                        <div class="col-auto pe-1 ps-1">
                            <div class="radio-success custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="formatA4-1" v-model="data.format" name="format-1" value="A4"/>
                                <label class="custom-control-label text-black" for="formatA4-1"> {{ $t('A4') }} </label>
                            </div>
                        </div>
                        <div class="col-12" v-if="error.format">
                            <div  class="invalid-feedback">{{ $t("Le champ format est abligatoire.") }}</div>
                        </div>
                    </div>
                    <div class="row p-2 align-items-center">
                        <div class="col-sm-12">
                            <d-unit-measurements required="true" v-model="data.unit_id" :error="error.unit_id"></d-unit-measurements>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input required="true" type="date" label="Deadline" v-model="data.price_min" :error="error.price_min"></d-input>
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
    import VueFeather from 'vue-feather';
    import { ref, onMounted, watch } from "vue";
    import axiosInstance from "../../../../config/http";
    import dInput from "../../../../components/base/d-input.vue";
    import dBaseModal from "../../../../components/base/d-base-modal.vue";
    import dPanelTitle from "../../../../components/common/d-panel-title.vue";
    import dContremarqueDropdown from "../../../common/d-contremarque-dropdown.vue"
    import {formatErrorViolations, Helper} from "../../../../composables/global-methods";
    import dUnitMeasurements from "../../../common/d-unit-measurements.vue"
    
    const props = defineProps({
        contremarqueId: {
            type: Number
        },
        diId: {
            type: Number
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
            
            document.querySelector("#modalCreateDI .btn-close").click();
            initData();
        }catch (e){
            if(e.response.data.violations){
                error.value = formatErrorViolations(e.response.data.violations);
            }
            window.showMessage(e.message,'error')
        }
    };
    const getDI = async (id) =>{
        try{
          
        }catch (e){
           
        }
    };
    
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
    }

    watch(
    () => props.diId,
    (newVal) => {
        getDI(newVal)
    }
    );
</script>
