<template>
    <div>
        <d-base-modal id="modalEventManage" title="évènement" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12">
                        <d-customer-dropdown :required="true" v-model="eventCustomerId" :error="error.customerId"></d-customer-dropdown>
                        <d-nomenclatures :required="true" v-model="data.nomenclatureId" :error="error.nomenclatureId"></d-nomenclatures>
                        <d-input :required="true" :type="'date'" label="Date évènement" v-model="data.event_date" :error="error.event_date"></d-input>
                        <d-contremarque-dropdown v-model="data.contremarqueId" :error="error.contremarqueId" :customerId="eventCustomerId"></d-contremarque-dropdown>
                        <d-input label="Devis" :disabled="true"></d-input>
                        <div class="row align-items-center">
                            <div class="col-lg-8 col-md-12">
                                <d-input :type="'date'" label="Date next relance" v-model="data.next_reminder_deadline" :error="error.next_reminder_deadline"></d-input>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="checkbox-primary custom-control custom-checkbox text-color rounded">
                                    <input type="checkbox" class="custom-control-input" id="reminderDisabled" v-model="data.reminder_disabled" />
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
                            <d-contact-customer-dropdown :customerId="eventCustomerId" v-model="contactId"></d-contact-customer-dropdown>
                        </div>
                        <div class="ps-3 mt-4">
                            <d-users-dropdown v-model="userId" :multiple="true"></d-users-dropdown>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="saveEvent">Enregistrer</button>
            </template>
        </d-base-modal>
    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from "vue";
    import VueFeather from 'vue-feather';
    import dNomenclatures from "../common/d-nomenclatures.vue";
    import dCustomerDropdown from "../common/d-customer-dropdown.vue";
    import dInput from "../base/d-input.vue";
    import axiosInstance from "../../config/http";
    import { formatErrorViolations, Helper } from "../../composables/global-methods";
    import dPanelTitle from "../common/d-panel-title.vue";
    import dContactDropdown from "../common/d-contact-dropdown.vue";
    import dUsersDropdown from "../common/d-users-dropdown.vue";
    import dContremarqueDropdown from "../common/d-contremarque-dropdown.vue";
    import dContactCustomerDropdown from "../common/d-contact-customer-dropdown.vue";
    import contactService from "../../Services/contact-service";
    import dBaseModal from "../base/d-base-modal.vue";

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
        }
    });

    const data = ref({
        nomenclatureId: null,
        customerId: null,
        contremarqueId: 0,
        quoteId: 0,
        reminder_disabled: false,
        commentaire: "",
        event_date: "",
        next_reminder_deadline: "",
        people_present: {
            contacts: [],
            users: []
        }
    });
    const contactId = ref([]);
    const contactCustomer = ref([]);
    const eventCustomerId = ref(0);
    const userId = ref([]);
    const error = ref({});

    const saveEvent = async () => {
        try {
            data.value.people_present.contacts = contactId.value;
            data.value.people_present.users = userId.value;
            data.value.customerId = eventCustomerId.value;
            if (data.value.event_id) {
                const res = await axiosInstance.put("api/updateEvent/" + data.value.event_id, data.value);
                window.showMessage("Mise a jour avec succées.");
            } else {
                const res = await axiosInstance.post("/api/createEvent", data.value);
                window.showMessage("Ajout avec succées.");
            }
            document.querySelector("#modalEventManage .btn-close").click();
            eventCustomerId.value = 0;
        } catch (e) {
            if (e.response.data.violations) {
                error.value = formatErrorViolations(e.response.data.violations);
            }
            window.showMessage(e.message, 'error')
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
            event_date: "",
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
                nomenclatureId: event.nomenclature.nomenclature_id,
                customerId: props.customerId,
                contremarqueId: event.contramarqueId ? event.contramarqueId : 0,
                quoteId: event.quoteId ? event.quoteId : 0,
                reminder_disabled: event.reminder_disabled || false,
                commentaire: event.commentaire,
                event_date: Helper.FormatDate(event.event_date.date, "YYYY-MM-DD"),
                next_reminder_deadline: event.next_reminder_deadline
                    ? Helper.FormatDate(event.next_reminder_deadline.date, "YYYY-MM-DD")
                    : "",
                people_present: event.people_present
            });

            eventCustomerId.value = props.customerId;
            userId.value = event.people_present?.users || [];
            contactId.value = event.people_present?.contacts || [];
        }
        console.log("Data after assignment:", data.value);
    };

    /*const getCustomerContacts = (customerId) => {
        contactCustomer.value = contactService.getContactsByCustomerId(customerId)
    };*/

    const emit = defineEmits(['onClose']);
    const handleClose = () => {
        if (!data.value.event_id) {
            initData();
        }
        error.value = {};
        emit('onClose')
    };

    onMounted(() => {
        console.log("Mounted and event data is:", props.eventData);
        affectData(props.eventData);
    });

    watch(
        () => props.eventData,
        (event) => {
            console.log("Event data changed:", event);
            affectData(event);
        }
    );
</script>
