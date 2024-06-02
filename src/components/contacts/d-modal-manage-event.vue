<template>
    <div>
        <div class="modal animated fadeInDown" id="modalEventManage" tabindex="-1" role="dialog" aria-labelledby="fadeinModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-capitalize" id="fadeinModalLabel">évènement</h5>
                        <button type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-12">
                                <d-customer-dropdown v-model="data.customerId"  :error="error.customerId"></d-customer-dropdown>
                                <d-nomenclatures v-model="data.nomenclatureId" :error="error.nomenclatureId"></d-nomenclatures>
                                <d-input :type="'date'" label="Date évènement" v-model="data.event_date" :error="error.event_date"></d-input>
                                <d-input label="Contremarque" :disabled="true"></d-input>
                                <d-input label="Devis" :disabled="true"></d-input>
                                <div class="row align-items-center">
                                    <div class="col-lg-8 col-md-12">
                                        <d-input :type="'date'" :error="error.next_reminder_deadline" label="Date next relance" v-model="data.next_reminder_deadline"></d-input>
                                    </div>
                                    <div class="col-lg-4 col-md-12">
                                        <div class="checkbox-primary custom-control custom-checkbox text-color rounded">
                                            <input type="checkbox" class="custom-control-input" id="reminderDisabled" v-model="data.reminder_disabled"/>
                                            <label class="custom-control-label text-dark" for="reminderDisabled"> {{ $t('Plus de relance') }} </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <label class="form-label" for="textComment"> {{ $t('Commentaire') }} </label>
                                <textarea class="form-control" v-model="data.commentaire" style="height: 250px" id="textComment"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-custom pe-2 ps-2" @click.prevent="saveEvent">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import { ref } from "vue";
    import VueFeather from 'vue-feather';
    import dNomenclatures from "../common/d-nomenclatures.vue";
    import dCustomerDropdown from "../common/d-customer-dropdown.vue";
    import dInput from "../base/d-input.vue";
    import axiosInstance from "../../config/http";
    import { formatErrorViolations } from "../../composables/global-methods"
    
    const props = defineProps({
        eventId : {
            type: Number
        },
        id: {
            type: String
        },
        eventData: {
            type: Object,
        }
    });
    
    const data = ref({
        nomenclatureId: null,
        customerId: null,
        contremarqueId: 0,
        quoteId: 0,
        reminder_disabled: false,
        commentaire: "",
        event_date: null,
        next_reminder_deadline: null,
        people_present: []
    });
    const error = ref({});
    const saveEvent = async () => {
        try{
            
            const res = await axiosInstance.post("/api/createEvent",data);
            window.showMessage("Ajout avec succées.");
            document.querySelector("#modalEventManage .btn-close").click();
            data.value = {
                nomenclatureId: null,
                customerId: null,
                contremarqueId: 0,
                quoteId: 0,
                reminder_disabled: false,
                commentaire: null,
                event_date: null,
                next_reminder_deadline: null,
                people_present: []
            };
        }catch (e){
            if(e.response.data.violations){
                error.value = formatErrorViolations(e.response.data.violations);
                console.log(error.value)
            }
            window.showMessage(e.message,'error')
        }
    }
</script>
