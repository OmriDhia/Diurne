<template>
    <div class="panel br-6 p-2">
        <div class="row p-2">
            <div class="col-auto">
                <button class="btn btn-custom pe-5 ps-5" @click="goToNewContact">Nouveau contact</button>
            </div>
        </div>
        <div class="row align-items-start pe-2 ps-2 mt-3">
            <div class="col-md-4 col-sm-12">
                <div class="row">
                    <d-customer-type v-model="filter.customerTypeId"></d-customer-type>
                </div>
                <div class="row">
                    <d-input label="Raison social" v-model="filter.rs" ></d-input>
                </div>
                <div class="row">
                    <d-input label="CE TVA" v-model="filter.tva_ce" ></d-input>
                </div>
                <div class="row">
                    <d-input label="Site web" v-model="filter.webSite" ></d-input>
                </div>
                <div class="row">
                    <d-input label="Contact" v-model="filter.contact" ></d-input>
                </div>
                <div class="row">
                    <d-input label="Commercial" v-model="filter.commercial" ></d-input>
                </div>
                <div class="row">
                    <d-input label="Prescripteur" v-model="filter.pres" ></d-input>
                </div>
            </div>
            <div class="col-md-8 col-sm-12">
                <div class="row align-items-center">
                    <div class="col-md-6 col-sm-12">
                        <d-nomenclatures v-model="filter.subject"></d-nomenclatures>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="custom-control custom-radio">
                            <input type="checkbox" class="custom-control-input" id="onlyLastEvent" v-model="filter.onlyLastEvent" name="onlyLastEvent"/>
                            <label class="custom-control-label text-dark" for="onlyLastEvent"> {{ $t('Seul dernier évènement') }} </label>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 col-sm-12">
                        <div class="row align-items-center">
                            <div class="col-md-4 col-sm-12">
                                Date évènement:
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <input  id="eventDate_from" class="form-control" type="date" v-model="filter.eventDate_from">
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <input  id="eventDate_to" class="form-control" type="date" v-model="filter.eventDate_to">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                {{ $t('Evènement sans projet')}}:
                            </div>
                            <div class="col-auto pe-1 ps-2">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="warnig-add-yes" v-model="filter.hasNoProject" name="warningAdd" value="true"/>
                                    <label class="custom-control-label text-dark" for="warnig-add-yes"> {{ $t('Oui') }} </label>
                                </div>
                            </div>
                            <div class="col-auto pe-1 ps-1">
                                <div class="radio-success custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="warnig-add-no" v-model="filter.hasNoProject" name="warningAdd" value="false"/>
                                    <label class="custom-control-label text-dark" for="warnig-add-no"> {{ $t('Non') }} </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 col-sm-12">
                        <div class="row align-items-center">
                            <div class="col-md-4 col-sm-12">
                                Date relance:
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <input id="next_reminder_deadline_from" class="form-control" type="date" v-model="filter.next_reminder_deadline_from">
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <input id="next_reminder_deadline_to" class="form-control" type="date" v-model="filter.next_reminder_deadline_to">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                {{ $t('Evènement avec next step')}}:
                            </div>
                            <div class="col-auto pe-1 ps-2">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="finiched-add-yes" v-model="filter.hasNextStep" name="finichedAdd" value="true"/>
                                    <label class="custom-control-label text-dark" for="finiched-add-yes"> {{ $t('Oui') }} </label>
                                </div>
                            </div>
                            <div class="col-auto pe-1 ps-1">
                                <div class="radio-success custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="finiched-add-no" v-model="filter.hasNextStep" name="finichedgAdd" value="false"/>
                                    <label class="custom-control-label text-dark" for="finiched-add-no"> {{ $t('Non') }} </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row align-items-center justify-content-end pe-5 mt-5">
                    <div class="col-auto">
                        <button class="btn btn-outline-custom">
                            éditer list emailing
                            <vue-feather type="file-plus" size="14"></vue-feather>
                        </button>
                    </div>
                    <div class="col-auto">
                        <div class="row justify-content-end">
                            <div class="col-auto p-0">
                                <button v-if="filterActive" class="btn btn-outline-secondary btn-reset" @click.prevent="doReset">
                                    Reset filtre </button>
                            </div>
                            <div class="col-auto p-0 me-2">
                                <button class="btn btn-custom pe-3 ps-3" @click.prevent="doSearch">Recherche</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-center pe-3 ps-2 mt-3 mb-4">
            <div class="col-auto pe-1 ps-1">
                <div class="radio-success custom-control custom-radio">
                    <input type="checkbox" class="custom-control-input" id="hasOnlyOneContact" v-model="filter.hasOnlyOneContact"/>
                    <label class="custom-control-label text-dark" for="hasOnlyOneContact"> {{ $t('Seul un contact') }} </label>
                </div>
            </div>
            <div class="col-auto pe-1 ps-2">
                <div class="custom-control custom-radio">
                    <input type="checkbox" class="custom-control-input" id="customer-active" v-model="filter.active"/>
                    <label class="custom-control-label text-dark" for="customer-active"> {{ $t('Seul client actif') }} </label>
                </div>
            </div>
            <div class="col-auto pe-1 ps-1">
                <div class="custom-control custom-radio">
                    <input type="checkbox" class="custom-control-input" id="comercial-valid" v-model="filter.hasInvalidCommercial"/>
                    <label class="custom-control-label text-dark" for="comercial-valid"> {{ $t('Commercial à valider') }} </label>
                </div>
            </div>
        </div>
    </div>
    <div class="panel br-6 p-2 mt-3">
        <div class="row mt-5 ms-2 mb-5">
            <div class="vue3-datatable w-100">
                <vue3-datatable :rows="rows" :columns="cols" :loading="loading" :isServerMode="true"
                                :totalRows="total_rows" :page="params.current_page" :pageSize="params.pagesize"
                                :pageSizeOptions="[10, 25, 50, 75, 100]" noDataContent="Aucun contact trouvé."
                                paginationInfo="Affichage de {0} à {1} sur {2} entrées" :sortable="true"
                                @change="changeServer" class="advanced-table text-nowrap">
                    <template #customer="data">
                        <div class="d-flex justify-content-between">
                            <strong>{{ data.value.customer}}</strong>
                            <router-link :to="'/contacts/manage/' + data.value.id"  v-if="$hasPermission('update contact')">
                                <vue-feather type="search"  stroke-width="1" class="cursor-pointer"></vue-feather>
                            </router-link>

                        </div>
                    </template>
                    <template #subject="data">
                        {{ data.value.subject }}
                        <d-modal-event :customerId="data.value.customer_id"></d-modal-event>
                    </template>
                    <template #has_wrong_address="data">
                        <div title="test" class="t-dot" :class="data.value.has_wrong_address === 'true' ? 'bg-success' :'bg-danger'"></div>
                    </template>
                </vue3-datatable>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref , reactive, onMounted} from 'vue';
    import dInput from '../base/d-input.vue';
    import dCustomerType from "../common/d-customer-type.vue";
    import VueFeather from 'vue-feather';
    import Vue3Datatable from '@bhplugin/vue3-datatable';
    import axiosInstance from '../../config/http';
    import { filterEvent } from "../../composables/constants";
    import dNomenclatures from "../common/d-nomenclatures.vue";
    import dModalEvent from "./d-modal-event.vue";
    
    const loading = ref(true);
    const total_rows = ref(0);

    const params = reactive({
        current_page: 1,
        pagesize: 50,
    });

    const filter = ref(filterEvent);
    const rows = ref(null);
    const filterActive = ref(false);
    const selectedCustomerId = ref(null);

    const cols = ref([
        { field: 'customer', title: 'Raison social'},
        { field: 'contact', title: 'Contact' },
        { field: 'commercial', title: 'Commercial'},
        { field: 'phone', title: 'Tél. fixe'},
        { field: 'mobile_phone', title: 'Tél. portable'},
        { field: 'email', title: 'Email'},
        { field: 'subject', title: 'Evènement client'},
        { field: 'event_date', title: 'Date Ev.' },
        { field: 'next_step', title: 'Next step', sort: false },
    ]) || [];

    onMounted(() => {
        getCustomers();
    });
    const getCustomers = async () => {
        try {
            loading.value = true;
            let url = `/api/events?page=${params.current_page}&itemsPerPage=${params.pagesize}`;
            url += getFilterParams();
            const response = await axiosInstance.get(url);
            const data = await response.data.response;
            total_rows.value = data.count;
            rows.value = data.events;
        } catch { }

        loading.value = false;
    };
    const changeServer = (data) => {
        params.current_page = data.current_page;
        params.pagesize = data.pagesize;

        getCustomers();
    };
    const doSearch = () => {
        filterActive.value = true
        getCustomers();
    };
    const getFilterParams = () => {

        let param = "";
        if (filter.value.firstname) {
            param += "&filter[firstname]=" + filter.value.firstname
        }
        if (filter.value.lastname) {
            param += "&filter[lastname]=" + filter.value.lastname
        }
        if (filter.value.rs) {
            param += "&filter[socialReason]=" + filter.value.rs
        }
        if (filter.value.customerTypeId) {
            param += "&filter[customerGroupId]=" + filter.value.customerTypeId
        }
        if (filter.value.subject) {
            param += "&filter[nomenclatureId]=" + filter.value.subject
        }
        if (filter.value.tva_ce) {
            param += "&filter[tva_ce]=" + filter.value.tva_ce
        }
        if (filter.value.commercial) {
            param += "&filter[commercial]=" + filter.value.commercial
        }
        if (filter.value.webSite) {
            param += "&filter[website]=" + filter.value.webSite
        }
        if (filter.value.active && filter.value.active !== 'all') {
            param += "&filter[active]=" + filter.value.active
        }
        if (filter.value.hasInvalidCommercial) {
            param += "&filter[hasInvalidCommercial]=" + filter.value.hasInvalidCommercial
        }
        if (filter.value.hasOnlyOneContact) {
            param += "&filter[hasOnlyOneContact]=" + filter.value.hasOnlyOneContact
        }
        if (filter.value.hasNoProject) {
            param += "&filter[hasNoProject]=" + filter.value.hasNoProject
        }
        if (filter.value.hasNextStep) {
            param += "&filter[hasNextStep]=" + filter.value.hasNextStep
        }
        if (filter.value.eventDate_from) {
            param += "&filter[eventDate_from]=" + filter.value.eventDate_from
        }
        if (filter.value.eventDate_to) {
            param += "&filter[eventDate_to]=" + filter.value.eventDate_to
        }
        if (filter.value.next_reminder_deadline_from) {
            param += "&filter[next_reminder_deadline_from]=" + filter.value.next_reminder_deadline_from
        }
        if (filter.value.next_reminder_deadline_to) {
            param += "&filter[next_reminder_deadline_to]=" + filter.value.next_reminder_deadline_to
        }

        return param;
    };
    const doReset = () => {
        filterActive.value = false;
        filter.value = {
            firstname: null,
            lastname: null,
            rs: null,
            tva_ce: null,
            commercial: null,
            webSite: null,
            customerTypeId: null,
            hasInvalidCommercial: null,
            active: null,
            hasOnlyOneContact: null,
            hasNoProject: null,
            hasNextStep: null,
            eventDate_from: null,
            eventDate_to: null,
            next_reminder_deadline_from: null,
            next_reminder_deadline_to: null,
            subject: null,
            onlyLastEvent: null,
            pres: null,
        };
        getCustomers();
    };
    const goToNewContact = () => {
        location.href = '/contacts/manage'
    };
</script>

<style>
    .advanced-table .progress-bar {
        width: 80%;
        min-width: 120px;
        height: 8px;
        background-color: #ebedf2;
        border-radius: 9999px;
        display: flex;
    }

    .advanced-table .progress-line {
        height: 8px;
        border-radius: 9999px;
    }

    .btn-reset{
        box-shadow: none !important;
        margin-right: 5px;
    }
</style>

