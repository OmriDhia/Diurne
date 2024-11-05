<template>
    <div>
        <d-base-modal id="modalLocationManage" title="Emplacement contremarque" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-carpet-dropdown required="true" v-model="data.carpetTypeId" :error="error.carpetTypeId"></d-carpet-dropdown>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input label="Description" v-model="data.description" :error="error.description"></d-input>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input required="true" label="Prix min" v-model="data.price_min" :error="error.price_min"></d-input>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input required="true" label="Prix max" v-model="data.price_max" :error="error.price_max"></d-input>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-6">
                            <d-input type="datetime-local" label="Date devis" v-model="data.quote_processing_date" :error="error.quote_processing_date"></d-input>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="custom-control custom-radio">
                                <input type="checkbox" class="custom-control-input" id="quote_processed" v-model="data.quote_processed" name="quote_processed" value="true"/>
                                <label class="custom-control-label text-black" for="quote_processed"> {{ $t('Devis traité') }} </label>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="saveLocation">Enregistrer</button>
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
    import {formatErrorViolations, Helper} from "../../../../composables/global-methods";
    import dCarpetDropdown from "../../../../components/common/d-carpet-dropdown.vue";
  
    const props = defineProps({
        locationData: {
            type: Object,
        },
        contremarqueId:{
            type: Number
        }
    });
    
    const data = ref({
        contremarqueId: 0,
        carpetTypeId: 0,
        description: "",
        quote_processed: true,
        quote_processing_date: {},
        price_min: 0,
        price_max: 0,
        createdAt: new Date(),
    });
    const error = ref({});
    
    onMounted(()=>{
        if(props.locationData){
            affectData(props.locationData);  
        }
    });
    
    const saveLocation = async () =>{
        try{
            data.value.price_min = parseFloat(data.value.price_min);
            data.value.price_max = parseFloat(data.value.price_max);
            data.value.quote_processing_date = data.value.quote_processing_date ? Helper.FormatDate(data.value.quote_processing_date,"YYYY-MM-DD HH:mm:ss") : {};
            data.value.createdAt = Helper.FormatDate(data.value.createdAt,"YYYY-MM-DD HH:mm:ss");
            data.value.contremarqueId = props.contremarqueId;
            if(data.value.location_id){
                const res = await axiosInstance.put("/api/updateLocation/" + data.value.location_id,data.value);
                window.showMessage("Mise à jour avec succées.");
            }else{
                const res = await axiosInstance.post("/api/createLocation",data.value);
                window.showMessage("Ajout avec succées.");
            }
            document.querySelector("#modalLocationManage .btn-close").click();
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
            contremarqueId: 0,
            carpetTypeId: 0,
            description: "",
            quote_processed: true,
            quote_processing_date: {},
            price_min: 0,
            price_max: 0,
            createdAt: new Date(),
        };
    };

    const affectData = (newVal) => {
        if(newVal){
            data.value = {
                contremarqueId: newVal.contremarque_id,
                carpetTypeId: newVal.carpetType_id,
                description: newVal.description,
                quote_processed: newVal.quote_processed,
                quote_processing_date: (newVal.quote_processing_date) ? Helper.FormatDate(newVal.quote_processing_date.date,'YYYY-MM-DD HH:mm:ss') : {},
                price_min: newVal.price_min,
                price_max: newVal.price_max,
                createdAt: new Date(newVal.created_at.date),
            };
        }
    };
    watch(
        () => props.locationData,
        (newVal) => {
            affectData(newVal)
        }
    );
    const emit = defineEmits(['onClose']);

    const handleClose = () => {
        initData();
        error.value = {};
        emit('onClose')
    }
</script>
