<template>
    <div>
        <d-base-modal id="modalAgentManageHistoriqueRn" title="Historique stockage RN" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-8 px-2">
                    <d-evenement-rn-dropdown v-model="formData.event" :error="error.eventTypeId" rootClass="pink-bg"/>
                </div>
                <div class="col-8 mt-2 px-2">
                    <d-emplacement-rn-dropdown v-model="formData.emplacement" rootClass="pink-bg" :disabled="formData.event != 12"/>
                </div>
                <div class="col-8 px-2">
                    <d-input type="datetime-local" label="Date debut"
                             v-model="formData.startDate" :error="error.beginAt"/>
                </div>
                <div class="col-8 px-2">
                    <d-input type="datetime-local" label="Date fin"
                             v-model="formData.endDate"  :error="error.endAt"/>
                </div>
                <div class="col-8 px-2">
                    <d-customer-dropdown v-model="formData.customer" :error="error.customerId"/>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom text-uppercase py-2"
                        @click="saveHistoriqueRn">Enregistrer
                </button>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { ref, onMounted, watch } from "vue";
    import dBaseModal from "../../base/d-base-modal.vue";
    import dInput from "../../base/d-input.vue";
    import DEvenementRnDropdown from "@/components/workshop/dropdown/d-evenement-rn-dropdown.vue";
    import DCustomerDropdown from "@/components/common/d-customer-dropdown.vue";
    import DEmplacementRnDropdown from "@/components/workshop/dropdown/d-emplacement-rn-dropdown.vue";
    import userService from "@/Services/user-service.js";
    import axiosInstance from "@/config/http.js";
    import {formatErrorViolations, Helper} from "@/composables/global-methods.js";

    const props = defineProps({
        workshopOrderId: {
            type: [Number, null]
        },
        rn: {
            type: [String, null]
        }
    });
    const emit = defineEmits(['onClose']);
    const error =  ref({});
    const formData = ref(
        {
            id: null,
            event: null,
            emplacement: 3,
            startDate: null,
            endDate: null,
            customer: null,
        }
    );
    const carpetId = ref(null);
    
    const saveHistoriqueRn = async() => {
        try {
            const payload = {
                eventTypeId: formData.value.event,
                locationId: formData.value.emplacement,
                customerId: formData.value.customer,
                workshopOrderId: props.workshopOrderId,
                beginAt: Helper.FormatDateTime(formData.value.startDate,'YYYY-MM-DD HH:mm:ss'),
                endAt: Helper.FormatDateTime(formData.value.endDate,'YYYY-MM-DD HH:mm:ss'),
                carpetId: carpetId.value,
            }
            if(!carpetId.value){
                window.showMessage("Identifiant tapis introuvable!", "error");
                return;
            }
            if(formData.value.id){
                const res = await axiosInstance.put(`/api/workshopRnHistories/${formData.value.id}`, payload)
                
            } else {
                const res = await axiosInstance.post(`/api/workshopRnHistories`, payload)
            }

            document.querySelector("#modalAgentManageHistoriqueRn .btn-close").click();
            window.showMessage("Mise a jour historique stock avec succées.")
        } catch (e) {
            if (e.response.data.violations) {
                error.value = formatErrorViolations(e.response.data.violations);
            }else if(e.status === 500 && e.response.data?.response?.message?.includes('Duplicate entry')){
                window.showMessage("Une historique stock existe déja pour cette RN", 'error');
                return
            }
            console.log(e);
            window.showMessage(e.message, 'error');
        }
    }
    const getCarpetId = async() => {
        try {
             const res = await axiosInstance.get(`/api/carpet/rnNumber/${props.rn}`)
            carpetId.value = res.data.response.id;
        } catch (error) {
            console.log(error);
        }
    }
    
    onMounted(() => {
        /* const user = userService.getUserInfo();
        formData.value.customer = user.id; */
        getCarpetId()
    })
    
    const handleClose = () => {
        emit("onClose");
    }
</script>
