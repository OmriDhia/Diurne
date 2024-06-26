<template>
    <div>
        <div class="modal animated fadeInDown" id="modalEventManage" tabindex="-1" role="dialog" aria-labelledby="fadeinModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-capitalize" id="fadeinModalLabel">évènement</h5>
                        <button type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-12">
                                <d-customer-dropdown required="true" v-model="eventCustomerId"  :error="error.customerId"></d-customer-dropdown>
                                <d-nomenclatures required="true" v-model="data.nomenclatureId" :error="error.nomenclatureId"></d-nomenclatures>
                                <d-input required="true" :type="'date'" label="Date évènement" v-model="data.event_date" :error="error.event_date"></d-input>
                                <d-input label="Contremarque" :disabled="true"></d-input>
                                <d-input label="Devis" :disabled="true"></d-input>
                                <div class="row align-items-center">
                                    <div class="col-lg-8 col-md-12">
                                        <d-input required="true" :type="'date'" :error="error.next_reminder_deadline" label="Date next relance" v-model="data.next_reminder_deadline"></d-input>
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
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-12 ps-0">
                                <d-panel-title title="Personne présente"></d-panel-title>
                                <div class="ps-3">
                                    <d-customer-dropdown v-model="contactId" multiple="true"></d-customer-dropdown>
                                </div>
                                <div class="ps-3 mt-4">
                                    <d-users-dropdown v-model="userId"></d-users-dropdown>
                                </div>
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
    import dPanelTitle from "../common/d-panel-title.vue";
    import dContactDropdown from "../common/d-contact-dropdown.vue";
    import dUsersDropdown from "../common/d-users-dropdown.vue";
    
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
        people_present: {
            contacts: [],
            users: []
        }
    });
    const contactId = ref([]);
    const eventCustomerId = ref({id: 0});
    const userId = ref([]);
    const error = ref({});
    const saveEvent = async () => {
        try{
            data.value.people_present.contacts = contactId.value.map(e => {
                return e.id
            });
            data.value.people_present.users = userId.value.map(e => {
                return e.id
            });
            data.value.customerId = eventCustomerId.value.id;
            const res = await axiosInstance.post("/api/createEvent",data.value);
            window.showMessage("Ajout avec succées.");
            document.querySelector("#modalEventManage .btn-close").click();
            eventCustomerId.value = {id: 0};
            data.value = {
                nomenclatureId: null,
                customerId: null,
                contremarqueId: 0,
                quoteId: 0,
                reminder_disabled: false,
                commentaire: null,
                event_date: null,
                next_reminder_deadline: null,
                people_present: {
                    contacts: [],
                    users: []
                }
            };
        }catch (e){
            if(e.response.data.violations){
                error.value = formatErrorViolations(e.response.data.violations);
            }
            window.showMessage(e.message,'error')
        }
    }
</script>
