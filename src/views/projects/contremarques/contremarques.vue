<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title icon="file-text" :title="'Contremarques'"></d-page-title>

        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-2">
                <div class="row p-2">
                    <div class="col-auto">
                        <button class="btn btn-custom pe-5 ps-5" @click="goToNewContremarque">Nouveau contremarque</button>
                    </div>
                </div>
                <div class="row d-flex justify-content-center align-items-center p-2">
                    <div class="col-md-4 col-sm-12">
                        <div class="row">
                            <d-customer-dropdown v-model="filter.customer"></d-customer-dropdown>
                        </div>
                        <div class="row">
                            <d-input label="Contremarque" v-model="filter.contremarque" ></d-input>
                        </div>
                        <div class="row">
                            <d-input label="Commercial" v-model="filter.commercial" ></d-input>
                        </div>
                        <div class="row">
                            <d-input label="Date de fin" type="date" v-model="filter.endDate" ></d-input>
                        </div>
                        <div class="row">
                            <d-input label="Prescripteur" v-model="filter.prescriptor" ></d-input>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-12">
                        <div class="row mt-2">
                            <div class="col-md-3 col-sm-12">
                                <div class="custom-control custom-radio">
                                    <input type="checkbox" class="custom-control-input" id="pendingProject" v-model="filter.pendingProject"/>
                                    <label class="custom-control-label text-black" for="pendingProject"> {{ $t('Projet en cours') }} </label>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="custom-control custom-radio">
                                    <input type="checkbox" class="custom-control-input" id="projectRelance" v-model="filter.projectRelance"/>
                                    <label class="custom-control-label text-black" for="projectRelance"> {{ $t('Relance dépassée') }} </label>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="custom-control custom-radio">
                                    <input type="checkbox" class="custom-control-input" id="projectRelanceX" v-model="filter.projectRelanceX"/>
                                    <label class="custom-control-label text-black" for="projectRelanceX"> {{ $t('Relance dépassée dans la semaine') }} </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4 col-sm-12">
                                <div class="custom-control custom-radio">
                                    <input type="checkbox" class="custom-control-input" id="projectWithoutRelance" v-model="filter.projectWithoutRelance"/>
                                    <label class="custom-control-label text-black" for="projectWithoutRelance"> {{ $t('Projet sans relance') }} </label>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="custom-control custom-radio">
                                    <input type="checkbox" class="custom-control-input" id="allProjects" v-model="filter.allProjects"/>
                                    <label class="custom-control-label text-black" for="allProjects"> {{ $t('Tous les projets') }} </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-auto" v-if="filterActive">
                                <button class="btn btn-outline-secondary btn-reset" @click.prevent="doReset">
                                    Reset filtre </button>
                            </div>
                            <div class="col-auto me-2">
                                <button class="btn btn-custom pe-3 ps-3" @click.prevent="doSearch">Recherche</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel br-6 p-2 mt-3">
                <div class="row mt-5 ms-2 mb-5">
                    <div class="vue3-datatable w-100">
                        <div class="mb-2 relative">
                            <div class="btn-group custom-dropdown mb-4 me-2 btn-group-lg">
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
                        <vue3-datatable :rows="rows" :columns="cols" :loading="loading" :isServerMode="true"
                                        :totalRows="total_rows" :page="params.current_page" :pageSize="params.pagesize"
                                        :pageSizeOptions="[10, 25, 50, 75, 100]" noDataContent="Aucun contact trouvé."
                                        paginationInfo="Affichage de {0} à {1} sur {2} entrées" :sortable="true"
                                        @change="changeServer" class="advanced-table text-nowrap">
                            <template #designation="data">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ data.value.designation}}</strong>
                                    <router-link :to="'/projet/contremarques/manage/' + data.value.contremarque_id"  v-if="$hasPermission('update contremarque')">
                                        <vue-feather type="search"  stroke-width="1" class="cursor-pointer"></vue-feather>
                                    </router-link>
                                </div>
                            </template>
                            <template #target_date="data">
                                <div class="d-flex justify-content-between">
                                    {{ $Helper.FormatDate(data.value.target_date.date)}}
                                </div>
                            </template>
                            <template #createdAt="data">
                                <div class="d-flex justify-content-between">
                                    {{ $Helper.FormatDate(data.value.createdAt.date)}}
                                </div>
                            </template>
                            <template #lastEvent="data">
                                <div class="d-flex justify-content-between">
                                    {{ data.value.last_event.subject }}
                                </div>
                            </template>
                            <template #lastEventDate="data">
                                <div class="d-flex justify-content-between">
                                    {{ $Helper.FormatDate(data.value.last_event.event_date)}}
                                </div>
                            </template>
                            <template #relanceDate="data">
                                <div class="d-flex justify-content-between">
                                    {{ data.value.last_event.next_reminder_deadline ? $Helper.FormatDate(data.value.last_event.next_reminder_deadline) : ''}}
                                </div>
                            </template>
                        </vue3-datatable>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
</template>

<script setup>
import dInput from '../../../components/base/d-input.vue';
import dCustomerDropdown from '../../../components/common/d-customer-dropdown.vue';
import dPageTitle from '../../../components/common/d-page-title.vue';
import VueFeather from 'vue-feather';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import axiosInstance from '../../../config/http';
import { ref, reactive, onMounted } from 'vue';
import { filterContremarque } from '../../../composables/constants';

import { useMeta } from '/src/composables/use-meta';
useMeta({ title: 'Contremarque' });

const loading = ref(true);
const loadingAttribution = ref(false);
const total_rows = ref(0);

const params = reactive({
    current_page: 1,
    pagesize: 50,
    orderBy: 'designation',
    orderWay: 'asc'
});

const filter = ref(filterContremarque);
const filterActive = ref(false);
const rows = ref(null);

const cols = ref([
    { field: 'designation', title: 'Contremarque' },
    { field: 'customer_name', title: 'Client' },
    { field: 'createdAt', title: 'Date création' },
    { field: 'target_date', title: 'Date cible projet'},
    { field: 'commercial_name', title: 'Commercial' },
    { field: 'lastEvent', title: 'Dernière évènement', sort: false },
    { field: 'lastEventDate', title: 'Date dernier évèn.', sort: false  },
    { field: 'relanceDate', title: 'Date next relance', sort: false },
]) || [];

onMounted(() => {
    getContremarques();
});
const getContremarques = async () => {
    try {
        loading.value = true;
        let url = `/api/contremarques?page=${params.current_page}&limit=${params.pagesize}&order=${params.orderBy}&orderWay=${params.orderWay}`;
        url += getFilterParams();
        const response = await axiosInstance.get(url);
        const data = response.data;
        total_rows.value = data.count;
        rows.value = data.contremarques;
    } catch { }

    loading.value = false;
};
const changeServer = (data) => {
    params.current_page = data.current_page;
    params.pagesize = data.pagesize;
    params.orderBy = data.sort_column;
    params.orderWay = data.sort_direction;

    getContremarques();
};
const doSearch = () => {
    filterActive.value = true
    getContremarques();
};
const getFilterParams = () => {

    let param = "";
    if (filter.value.customer) {
        param += "&customerId=" + filter.value.customer.id
    }
    if (filter.value.contremarque) {
        param += "&designation=" + filter.value.contremarque
    }
    if (filter.value.endDate) {
        param += "&targetDate=" + filter.value.endDate
    }
    if (filter.value.commercial) {
        param += "&commercialName=" + filter.value.commercial
    }
    return param;
};
/*watch(type, (newValue, oldValue) => {
    if(newValue === 'contact'){
        title.value = 'Contacts';
    }else{
        title.value = 'évènement';  
    }
});*/

const doReset = () => {
    filterActive.value = false;
    filter.value = {
        customer: null,
        contremarque: null,
        commercial: null,
        endDate: null,
        prescriptor: null,
        pendingProject: null,
        projectRelance: null,
        projectRelanceX: null,
        projectWithoutRelance: null,
        allProjects: null,
    };
};

const goToNewContremarque = () => {
    location.href = "/projet/contremarques/manage"
};
    
</script>
<style>
    .text-size-16{
        font-size: 16px !important;
    }
</style>
