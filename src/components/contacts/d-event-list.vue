<template>
    <div class="panel br-6 p-2">
        <div class="row p-2">
            <div class="col-auto">
                <button class="btn btn-custom pe-5 ps-5" data-bs-toggle="modal" data-bs-target="#modalEventManage">Nouveau évènement</button>
                <d-modal-manage-event></d-modal-manage-event>
            </div>
        </div>
        <div class="row align-items-start pe-2 ps-2 mt-3">
            <div class="col-md-4 col-sm-12">
                <div class="row">
                    <d-customer-type-dropdown :multiple="true" v-model="filter.customerTypeId"></d-customer-type-dropdown>
                </div>
                <div class="row">
                    <d-input label="Raison social" v-model="filter.rs" ></d-input>
                </div>
                <div class="row">
                    <d-input label="Nom" v-model="filter.lastname" ></d-input>
                </div>
                <div class="row">
                    <d-input label="Prénom" v-model="filter.firstname" ></d-input>
                </div>
                <div class="row">
                    <d-input label="Email" v-model="filter.email" ></d-input>
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
                            <label class="custom-control-label text-black" for="onlyLastEvent"> {{ $t('Seul dernier évènement') }} </label>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 col-sm-12">
                        <div class="row align-items-center">
                            <div class="col-md-4 col-sm-12 text-black">
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
                            <div class="col-md-7 text-black">
                                {{ $t('Evènement sans projet')}}:
                            </div>
                            <div class="col-auto pe-1 ps-2">
                                <div class="custom-control custom-radio">
                                    <input type="checkbox" class="custom-control-input" id="warnig-add-yes" v-model="filter.hasNoProjectY" name="warningAdd" value="true"/>
                                    <label class="custom-control-label text-black" for="warnig-add-yes"> {{ $t('Oui') }} </label>
                                </div>
                            </div>
                            <div class="col-auto pe-1 ps-1">
                                <div class="radio-success custom-control custom-radio">
                                    <input type="checkbox" class="custom-control-input" id="warnig-add-no" v-model="filter.hasNoProjectN" name="warningAdd" value="false"/>
                                    <label class="custom-control-label text-black" for="warnig-add-no"> {{ $t('Non') }} </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 col-sm-12">
                        <div class="row align-items-center">
                            <div class="col-md-4 col-sm-12 text-black">
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
                            <div class="col-md-7 text-black">
                                {{ $t('Evènement avec next step')}}:
                            </div>
                            <div class="col-auto pe-1 ps-2">
                                <div class="custom-control custom-radio">
                                    <input type="checkbox" class="custom-control-input" id="finiched-add-yes" v-model="filter.hasNextStepY" name="finichedAdd" value="true"/>
                                    <label class="custom-control-label text-black" for="finiched-add-yes"> {{ $t('Oui') }} </label>
                                </div>
                            </div>
                            <div class="col-auto pe-1 ps-1">
                                <div class="radio-success custom-control custom-radio">
                                    <input type="checkbox" class="custom-control-input" id="finiched-add-no" v-model="filter.hasNextStepN" name="finichedgAdd" value="false"/>
                                    <label class="custom-control-label text-black" for="finiched-add-no"> {{ $t('Non') }} </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center justify-content-end pe-5 mt-5">
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
                    <label class="custom-control-label text-black" for="hasOnlyOneContact"> {{ $t('Seul un contact') }} </label>
                </div>
            </div>
            <div class="col-auto pe-1 ps-2">
                <div class="custom-control custom-radio">
                    <input type="checkbox" class="custom-control-input" id="customer-active" v-model="filter.active"/>
                    <label class="custom-control-label text-black" for="customer-active"> {{ $t('Seul client actif') }} </label>
                </div>
            </div>
            <div class="col-auto pe-1 ps-1">
                <div class="custom-control custom-radio">
                    <input type="checkbox" class="custom-control-input" id="comercial-valid" v-model="filter.hasInvalidCommercial"/>
                    <label class="custom-control-label text-black" for="comercial-valid"> {{ $t('Commercial à valider') }} </label>
                </div>
            </div>
        </div>
    </div>
    <div class="panel br-6 p-2 mt-3" id="fullscreen">
        <div class="row mt-2 mb-4">
            <div class="vue3-datatable w-100">
                <div class="row mb-4 relative align-items-center justify-content-between">
                    <div class="col-auto">
                        <div class="btn-group custom-dropdown me-2 btn-group-lg">
                            <button class="btn btn-outline-custom p-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Cacher / Montrer Colonnes
                            </button>
                            <ul class="dropdown-menu p-2">
                                <li v-for="col in cols" :key="col.field">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" :checked="!col.hide" :id="col.field" @change="col.hide = !$event.target.checked" :name="col.field"/>
                                        <label class="custom-control-label text-black" :for="col.field"> {{ col.title }} </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <d-btn-fullscreen></d-btn-fullscreen>
                </div>
                <vue3-datatable :rows="rows" :columns="cols" :loading="loading" :isServerMode="true" :sortColumn="params.orderBy" :sortDirection="params.orderWay"
                                :totalRows="total_rows" :page="params.current_page" :pageSize="params.pagesize"
                                :pageSizeOptions="[10, 25, 50, 75, 100]" noDataContent="Aucun évènement trouvé."
                                paginationInfo="Affichage de {0} à {1} sur {2} entrées" :sortable="true"
                                @change="changeServer" class="advanced-table text-nowrap">
                    <template #customer="data">
                        <div class="d-flex justify-content-between">
                            <strong>{{ data.value.customer}}</strong>
                            <router-link :to="'/contacts/manage/' + data.value.customer_id"  v-if="$hasPermission('update contact')">
                                <vue-feather type="search"  stroke-width="1" class="cursor-pointer"></vue-feather>
                            </router-link>
                        </div>
                    </template>
                    <template #subject="data">
                        <div class="d-flex justify-content-between">
                            <strong>{{ data.value.subject }}</strong>
                            <button type="button" class="btn btn-icon p-0"  data-bs-toggle="modal" data-bs-target="#ModalUpdateEventContact" @click="selectCustomer(data.value.customer_id)">
                                <vue-feather type="file-text"></vue-feather>
                            </button>
                        </div>
                    </template>
                    <template #has_wrong_address="data">
                        <div title="test" class="t-dot" :class="data.value.has_wrong_address === 'true' ? 'bg-success' :'bg-danger'"></div>
                    </template>
                </vue3-datatable>
            </div>
        </div>
        <d-modal-event :customerId="selectedCustomerId"></d-modal-event>
    </div>
</template>

<script setup>
    import { ref , reactive, onMounted} from 'vue';
    import dInput from '../base/d-input.vue';
    import dCustomerType from "../common/d-customer-type.vue";
    import VueFeather from 'vue-feather';
    import Vue3Datatable from '@bhplugin/vue3-datatable';
    import axiosInstance from '../../config/http';
    import { filterEvent, FILTER_EVENT_STORAGE_NAME } from "../../composables/constants";
    import dNomenclatures from "../common/d-nomenclatures.vue";
    import dModalEvent from "./_partial/d-modal-event.vue";
    import dModalManageEvent from "./d-modal-manage-event.vue";
    import dBtnFullscreen from '../base/d-btn-fullscreen.vue';
    import dCustomerTypeDropdown from "../common/d-customer-type-dropdown.vue";
    import { Helper } from "../../composables/global-methods";
    import { useRouter } from 'vue-router';
    
    const router = useRouter();
    const loading = ref(true);
    const total_rows = ref(0);
    const isOpen = ref(false);

    const params = reactive({
        current_page: 1,
        pagesize: 50,
        orderBy: 'customer',
        orderWay: 'asc'
    });

    const filter = ref(Object.assign({}, filterEvent));
    const rows = ref(null);
    const filterActive = ref(false);
    const selectedCustomerId = ref(null);

    const cols = ref([
        { field: 'customer', title: 'Raison social'},
        { field: 'contact', title: 'Contact' },
        { field: 'commercial', title: 'Commercial'},
        { field: 'phone', title: 'Tél. fixe'},
        { field: 'mobile_phone', title: 'Tél. portable'},
        { field: 'email', title: 'Email', sort: false},
        { field: 'subject', title: 'Evènement client', sort: false},
        { field: 'event_date', title: 'Date Ev.', sort: true },
        { field: 'next_step', title: 'Next step', sort: false },
    ]) || [];

    onMounted(() => {
        const f = Helper.getStorage(FILTER_EVENT_STORAGE_NAME);
        if(f && Helper.hasDefinedValue(f,'hasOnlyOneContact')){
            filter.value = f;
            filterActive.value = true;
        }
        getCustomers();
    });
    const getCustomers = async () => {
        try {
            loading.value = true;
            let url = `/api/events?page=${params.current_page}&itemsPerPage=${params.pagesize}&orderBy=${params.orderBy}&orderWay=${params.orderWay}`;
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
        params.orderBy = data.sort_column;
        params.orderWay = data.sort_direction;
        getCustomers();
    };
    const doSearch = () => {
        filterActive.value = true;
        Helper.setStorage(FILTER_EVENT_STORAGE_NAME, filter.value);
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
            param += "&filter[customerGroupId]=" 
                + filter.value.customerTypeId.map(e => e.customerGroup_id).join(',');
        }
        if (filter.value.subject) {
            param += "&filter[nomenclatureId]=" + filter.value.subject
        }
        if (filter.value.email) {
            param += "&filter[eamil]=" + filter.value.email
        }
        if (filter.value.commercial) {
            param += "&filter[commercial]=" + filter.value.commercial
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
        if (filter.value.hasNoProjectY) {
            param += "&filter[hasNoProject]=" + filter.value.hasNoProjectY
        }
        if (filter.value.hasNextStepY) {
            param += "&filter[hasNextStep]=" + filter.value.hasNextStepY
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
        if (filter.value.onlyLastEvent) {
            param += "&filter[onlyLastEvent]=" + filter.value.onlyLastEvent
        }
        if (filter.value.contact) {
            param += "&filter[contact]=" + filter.value.contact
        }
        if (filter.value.pres) {
            param += "&filter[prescripteur]=" + filter.value.pres
        }
        
        return param;
    };
    const doReset = () => {
        filterActive.value = false;
        filter.value = Object.assign({}, filterEvent);
        Helper.setStorage(FILTER_EVENT_STORAGE_NAME, filter.value);
        getCustomers();
    };
    const selectCustomer = (customerId) => {
        selectedCustomerId.value = customerId;
    };
    const goToNewContact = () => {
        router.push({name: 'addContact'});
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

