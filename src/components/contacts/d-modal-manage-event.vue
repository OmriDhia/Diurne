<template>
    <div ref="modalEventManage" class="modal animated fadeInDown" :id="`modalEventManage${action}`" tabindex="-1" role="dialog" aria-labelledby="fadeinModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fadeinModalLabel">évènement</h5>
                    <button type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" class="btn-close"  @click="handleClose"></button>
                </div>
                <div class="modal-body">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-12">
                            <d-customer-dropdown :required="true" v-model="eventCustomerId" :error="error.customerId"></d-customer-dropdown>
                            <d-nomenclatures :required="true" v-model="data.nomenclatureId" :error="error.nomenclatureId" @changeNomenclature="changeNomenclature"></d-nomenclatures>
                            <d-input :required="true" :type="'date'" label="Date évènement" v-model="data.event_date" :error="error.event_date"></d-input>
                            <d-contremarque-dropdown v-model="data.contremarqueId" :error="error.contremarqueId" :customerId="eventCustomerId" :disabled="data.contremarqueId === 0"></d-contremarque-dropdown>
                            <d-input label="Devis" :disabled="true"></d-input>
                            <div class="row align-items-center">
                                <div class="col-lg-8 col-md-12">
                                    <d-input :type="'date'" label="Date next relance" v-model="data.next_reminder_deadline" :error="error.next_reminder_deadline"></d-input>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="checkbox-primary custom-control custom-checkbox text-color rounded">
                                        <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            :id="reminderCheckboxId"
                                            v-model="data.reminder_disabled"
                                        />
                                        <label class="custom-control-label text-dark" :for="reminderCheckboxId"> {{ $t('Plus de relance') }} </label>
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
                                <d-contact-customer-dropdown :customerId="eventCustomerId" v-model="contactId"></d-contact-customer-dropdown>
                            </div>
                            <div class="ps-3 mt-4">
                                <d-users-dropdown v-model="userId" :multiple="true"></d-users-dropdown>
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
</template>

<script setup>
    import { ref, watch, onMounted, nextTick, defineExpose, getCurrentInstance } from "vue";
    import VueFeather from 'vue-feather';
    import dNomenclatures from "../common/d-nomenclatures.vue";
    import dCustomerDropdown from "../common/d-customer-dropdown.vue";
    import dInput from "../base/d-input.vue";
    import axiosInstance from "../../config/http";
    import { formatErrorViolations, Helper } from "../../composables/global-methods";
    import dPanelTitle from "../common/d-panel-title.vue";
    import dUsersDropdown from "../common/d-users-dropdown.vue";
    import dContremarqueDropdown from "../common/d-contremarque-dropdown.vue";
    import dContactCustomerDropdown from "../common/d-contact-customer-dropdown.vue";
    import { Modal } from "bootstrap";
    import moment from "moment";

    const props = defineProps({
        customerId: {
            type: Number
        },
        contremarqueId: {
            type: Number
        },
        id: {
            type: String
        },
        eventData: {
            type: [Object, null],
        },
        saveAndStay: {
            type: Boolean,
            default: true
        },
        action: {
            type: String,
            default: ""
        }
    });

    const { uid } = getCurrentInstance();
    const reminderCheckboxId = `reminderDisabled-${props.action || 'default'}-${uid}`;

    const data = ref({
        nomenclatureId: null,
        customerId: null,
        contremarqueId: 0,
        quoteId: 0,
        reminder_disabled: false,
        commentaire: "",
        event_date:  moment().format('YYYY-MM-DD'),
        next_reminder_deadline: "",
        people_present: {
            contacts: [],
            users: []
        }
    });
    const modalEventManage = ref(null);
    let modalInstance = null;
    const contactId = ref([]);
    const eventCustomerId = ref(0);
    const userId = ref([]);
    const error = ref({});

    const saveEvent = async () => {
        if (!data.value.next_reminder_deadline && !data.value.reminder_disabled) {
            error.value = {
                ...error.value,
                next_reminder_deadline: "Veuillez renseigner une date de prochaine relance ou cocher \"Plus de relance\"."
            };
            window.showMessage(
                "Veuillez renseigner une date de prochaine relance ou cocher \"Plus de relance\".",
                "error"
            );
            return;
        }
        try {
            data.value.people_present.contacts = contactId.value;
            data.value.people_present.users = userId.value;
            data.value.customerId = eventCustomerId.value;
            

            data.value.people_present = {
                contacts: [...contactId.value], 
                users: [...userId.value]
            };

            let res;
            if (data.value.event_id) {
                res = await axiosInstance.put("api/updateEvent/" + data.value.event_id, data.value);
                window.showMessage("Mise à jour avec succès.");
            } else {
                res = await axiosInstance.post("/api/createEvent", data.value);
                window.showMessage("Ajout avec succès.");
            }

            if (props.saveAndStay && !data.value.event_id) {
                affectData(res.data.response);
            } else {
                document.querySelector(`#modalEventManage${props.action} .btn-close`).click();
                initData();
                eventCustomerId.value = 0;
            }
        } catch (e) {
            console.error("Erreur lors de l'enregistrement :", e);
            if (e.response?.data?.violations) {
                error.value = formatErrorViolations(e.response.data.violations);
            }
            window.showMessage(e.message, 'error');
        }
    };

    const initData = (event) => {
        data.value = {
            nomenclatureId: null,
            customerId: null,
            contremarqueId: 0,
            quoteId: 0,
            reminder_disabled: false,
            commentaire: null,
            event_date: moment().format('YYYY-MM-DD'),
            next_reminder_deadline: "",
            people_present: {
                contacts: [],
                users: []
            }
        };
    };

    const affectData = (event) => {
        if (event) {
            Object.assign(data.value, {
                event_id: event.event_id,
                nomenclatureId: event.nomenclature?.nomenclature_id ?? null,
                customerId: props.customerId,
                contremarqueId: event.contramarqueId ?? 0,
                quoteId: event.quoteId ?? 0,
                reminder_disabled: event.reminder_disabled || false,
                commentaire: event.commentaire ?? "",
                event_date: event.event_date ? Helper.FormatDate(event.event_date.date, "YYYY-MM-DD") : "",
                next_reminder_deadline: event.next_reminder_deadline 
                    ? Helper.FormatDate(event.next_reminder_deadline.date, "YYYY-MM-DD") 
                    : "",
                people_present: event.people_present ?? { contacts: [], users: [] }
            });

            eventCustomerId.value = props.customerId;
            userId.value = event.people_present?.users ?? [];
            contactId.value = event.people_present?.contacts ?? [];
            
            console.log("Données après affectation:", JSON.stringify(data.value, null, 2));
        }
    };
    const changeNomenclature = (event) => {
        if (event.is_automatic && event.automatic_followup_delay > 0 ){
            data.value.reminder_disabled = true;
            data.value.next_reminder_deadline = moment()
                .add(event.automatic_followup_delay, 'days')
                .format('YYYY-MM-DD');
        }else if(props.eventData && props.eventData.nomenclature?.nomenclature_id === data.value.nomenclatureId){
            data.value.reminder_disabled = props.eventData.reminder_disabled || false;
            data.value.next_reminder_deadline = props.eventData.next_reminder_deadline
                ? Helper.FormatDate(event.next_reminder_deadline.date, "YYYY-MM-DD")
                : ""
        } else {
            data.value.reminder_disabled = false;
            data.value.next_reminder_deadline = ""
        }
    };

    const emit = defineEmits(['onClose']);
    const handleClose = () => {
        if (!data.value.event_id) {
            initData();
        }
        error.value = {};
        emit('onClose')
    };

    onMounted(() => {
        if (modalEventManage.value) {
            modalInstance = new Modal(modalEventManage.value, { backdrop: "static" });
        }else{
            console.log("Modal instance vide")
        }
        affectData(props.eventData);
    });

    watch(
        () => props.eventData,
        (event) => {
            affectData(event);
        },
        { deep: true }
    );
    watch(
        () => props.customerId,
        () => {
            data.value.customerId = props.customerId;
        },
        { deep: true }
    );

    watch(
        () => [data.value.next_reminder_deadline, data.value.reminder_disabled],
        ([nextReminder, reminderDisabled]) => {
            if (nextReminder || reminderDisabled) {
                const { next_reminder_deadline, ...rest } = error.value;
                error.value = rest;
            }
        },
        { deep: true }
    );

    const show = () => {
        if (modalInstance) {
            modalInstance.show();
        } else {
            console.error("Modal instance is null!");
        }
    };
    
    const setCustomerId = (customerId) => {
        data.value.customerId = customerId;
        eventCustomerId.value = customerId;
    };
    const setContremarqueId = (contremarqueId) => {
        data.value.contremarqueId = contremarqueId;
    };

    const hide = () => {
        if (modalInstance) {
            modalInstance.hide();
        }
    };

    defineExpose({ show, hide, setCustomerId, setContremarqueId });
</script>
