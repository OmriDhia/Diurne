<template>
    <div>
        <d-base-modal id="modalAgentManage" title="Agent" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-6">
                            <d-gender required="true" v-model="data.gender_id" :error="error.gender_id"></d-gender>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <d-input label="Tel. portable" v-model="data.mobile_phone" :error="error.mobile_phone"></d-input>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-6">
                            <d-input required="true" label="Nom" v-model="data.lastname" :error="error.lastname"></d-input>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <d-input label="Tél. fixe" v-model="data.phone" :error="error.phone"></d-input>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-6">
                            <d-input required="true" label="Prénom" v-model="data.firstname" :error="error.firstname"></d-input>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <d-input required="true" label="Email" v-model="data.email" :error="error.email"></d-input>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-6">
                            <d-input label="fax" v-model="data.fax" :error="error.fax"></d-input>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <d-input label="IBAN" v-model="data.iban" :error="error.iban"></d-input>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-12">
                            <d-input label="Nom de la banque" v-model="data.bankName" :error="error.bankName"></d-input>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="saveAgent">Enregistrer</button>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { ref, onMounted, watch } from "vue";
    import VueFeather from 'vue-feather';
    import dInput from "../base/d-input.vue";
    import axiosInstance from "../../config/http";
    import { formatErrorViolations } from "../../composables/global-methods"
    import dPanelTitle from "../common/d-panel-title.vue";
    import dGender from "../common/d-gender.vue";
    import dBaseModal from "../base/d-base-modal.vue";
    import {intermediaryType} from "../../composables/constants";

    const props = defineProps({
        agentData: {
            type: Object,
        }
    });
    
    const data = ref({
        gender_id: 0,
        firstname: "",
        lastname: "",
        email: "",
        phone: "",
        mobile_phone: "",
        fax: "",
        sale_contdition: "",
        commission: 0,
        bankName: "",
        iban: "",
        swiftCode:"",
        intermediaryTypeId: 0
    });
    const error = ref({});
    const intermediaryTypeArray = ref([]);
    
    onMounted(()=>{
        getIntermediaryType();
        affectData(props.agentData);
    });
    
    const getIntermediaryType= async () => {
        try {
            const res = await axiosInstance.get("api/intermediaryTypes");
            intermediaryTypeArray.value = res.data.response.intermediaryTypes;
        }catch (e){
            console.error(e.toString());
        }
    };
    
    const saveAgent = async () =>{
        const ag = intermediaryTypeArray.value.filter(a => a.name === intermediaryType.agent)[0]
        data.value.intermediaryTypeId = ag ? ag.intermediaryType_id : 0;
        try{
            if(data.value.id){
                const res = await axiosInstance.put("/api/updateIntermediary/" + data.value.id,data.value);
                window.showMessage("Mise à jour avec succées.");
            }else{
                const res = await axiosInstance.post("/api/createIntermediary",data.value);
                window.showMessage("Ajout avec succées.");
            }
            
            document.querySelector("#modalAgentManage .btn-close").click();
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
            gender_id: 0,
            firstname: "",
            lastname: "",
            email: "",
            phone: "",
            mobile_phone: "",
            fax: "",
            sale_contdition: "",
            commission: 0,
            bank_name: "",
            iban: "",
            swiftCode:"",
            intermediaryTypeId: 0
        };
    };

    const affectData = (newVal) => {
        const ag = intermediaryTypeArray.value.filter(a => a.name === intermediaryType.agent)[0]
        data.value.intermediaryTypeId = ag ? ag.intermediaryType_id : 0;
        data.value.id = newVal.id;
        data.value.firstname = newVal.firstname;
        data.value.lastname = newVal.lastname;
        data.value.gender_id = newVal.gender_id;
        data.value.bankName = newVal.bank_name;
        data.value.email = newVal.email;
        data.value.iban = newVal.iban;
        data.value.mobile_phone = newVal.mobile_phone;
        data.value.phone = newVal.phone;
        data.value.swift_code = newVal.swift_code;
    };
    watch(
        () => props.agentData,
        (newVal) => {
            affectData(newVal)
        }
    );
    const emit = defineEmits(['onClose']);

    const handleClose = () => {
        initData();
        emit('onClose')
    }
</script>
