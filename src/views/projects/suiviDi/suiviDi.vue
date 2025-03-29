<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title :title="'Suivi des Maquettes'"></d-page-title>
        <d-modal-create-di></d-modal-create-di>
        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-2">
                <!--div class="row p-2">
                    <div class="col-auto">
                        <button class="btn btn-custom pe-5 ps-5"  data-bs-toggle="modal" data-bs-target="#modalCreateDI">NOUVELLE DI</button>
                    </div>
                </div-->
                <div class="row d-flex justify-content-center align-items-center p-2">
                    <div class="col-md-6 col-sm-12">
                        <div class="row">
                            <d-customer-dropdown v-model="filter.customer"></d-customer-dropdown>
                        </div>
                        <div class="row">
                            <d-input label="Contremarque" v-model="filter.contremarque" ></d-input>
                        </div>
                        <div class="row">
                            <d-input label="N° de DI" v-model="filter.diNumber" ></d-input>
                        </div>
                        <div class="row">
                            <d-carpet-status-dropdown v-model="filter.carpetStatus"></d-carpet-status-dropdown>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
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
                                        :pageSizeOptions="[10, 25, 50, 75, 100]" noDataContent="Aucun contact trouvé."
                                        paginationInfo="Affichage de {0} à {1} sur {2} entrées" :sortable="true"
                                        @change="changeServer" class="advanced-table text-nowrap">
                            <template #image="data">
                                <div class="d-flex justify-content-center">
                                    <img :src="$Helper.getImagePathNew(data.value.image_path, data.value.image_name)" alt="Carpet Image" class="img-thumbnail" style="width: 80px; height: auto;">
                                </div>
                            </template>
                            <template #image_name="data">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-truncate" :title="data.value.image_name">
                                        {{ truncateText(data.value.image_name, 14) }}
                                    </strong>
                                    <div>
                                        <vue-feather type="search" stroke-width="1" class="cursor-pointer" 
                                            @click="goTodetails(data.value.di_id, data.value.order_design_id)">
                                        </vue-feather>
                                    </div>
                                </div>
                            </template>

                            <template #diNumber="data">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ data.value.diNumber}}</strong>
                                    <div>
                                        <vue-feather type="search"  stroke-width="1" class="cursor-pointer" @click="goToContreMarqueDetails(data.value.contremarque_id)"></vue-feather>
                                    </div>
                                </div>
                            </template>
                            <template #diDate="data">
                                <div class="d-flex justify-content-between">
                                    {{ $Helper.FormatDate(data.value.diDate,"DD/MM/YYYY")}}
                                </div>
                            </template>
                            <template #lastAssignmentDate="data">
                                <div class="d-flex justify-content-between">
                                    {{ $Helper.FormatDate(data.value.lastAssignmentDate,"DD/MM/YYYY")}}
                                </div>
                            </template>
                            <template #deadline="data">
                                <div class="d-flex justify-content-between">
                                    {{ $Helper.FormatDate(data.value.deadline,"DD/MM/YYYY")}}
                                </div>
                            </template>
                            <template #wrong_image="data">
                                <div class="d-flex justify-content-between">
                                    <div title="test" class="t-dot" :class="data.value.wrong_image === 0 ? 'bg-warning' :'bg-success'"></div>
                                </div>
                            </template>
                        </vue3-datatable>
                    </div>
                </div>
                <d-modal-manage-di :diId="selectedDiId" @onClose="handleClose"></d-modal-manage-di>
            </div>
        </div>
    </div>
</template>

<script setup>
import dInput from '../../../components/base/d-input.vue';
import dBtnFullscreen from '../../../components/base/d-btn-fullscreen.vue';
import dCustomerDropdown from '../../../components/common/d-customer-dropdown.vue';
import dCarpetStatusDropdown from '../../../components/common/d-carpet-status-dropdown.vue';
import dPageTitle from '../../../components/common/d-page-title.vue';
import VueFeather from 'vue-feather';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import dModalManageDi from "../../../components/projet/contremarques/_Partials/d-modal-manage-di.vue"
import axiosInstance from '../../../config/http';
import { ref, reactive, onMounted } from 'vue';
import {FILTER_SUIVI_DI_STORAGE_NAME, filterSuiviDi} from '../../../composables/constants';
import { useMeta } from '/src/composables/use-meta';
import { Helper } from "../../../composables/global-methods";
import { useRoute } from "vue-router";

useMeta({ title: 'Contremarque' });
const route = useRoute();
const loading = ref(true);
const loadingAttribution = ref(false);
const total_rows = ref(0);

const params = reactive({
    current_page: 1,
    pagesize: 50,
    orderBy: 'order_design_id',
    orderWay: 'desc'
});
const truncateText = (text, length) => {
    if (!text) return '';
    return text.length > length ? text.substring(0, length) + '...' : text;
};

const filter = ref(Object.assign({}, filterSuiviDi));
const filterActive = ref(false);
const rows = ref(null);
const selectedDiId = ref(0);
const contremarqueId = ref(null);

const cols = ref([
    { field: 'order_design_id', title: 'ID' },
    { field: 'image', title: 'Image' },
    { field: 'image_name', title: 'Nom' },
    { field: 'diNumber', title: 'N° de la DI' },
    { field: 'diDate', title: 'Date de la DI' },
    { field: 'customer', title: 'Client'},
    { field: 'contremarque', title: 'contremarque' },
    { field: 'location', title: 'Emplacement'},
    { field: 'designer', title: 'Designer'},
    { field: 'lastAssignmentDate', title: 'Date d\'attribution'},
    { field: 'deadline', title: 'Deadline'},
    { field: 'carpet_status', title: 'Etat de tapis dans le DI'},
    { field: 'wrong_image', title: 'Image eronnée'},
]) || [];
const getImageUrl = (imageName) => {
    if (!imageName) return ''; // Handle missing images
    return `/uploads/attachments/${imageName}`;
};
onMounted(() => {
    const f = Helper.getStorage(FILTER_SUIVI_DI_STORAGE_NAME);
    if(f && Helper.hasDefinedValue(f)){
        filter.value = f;
        filterActive.value = true;
    }
    contremarqueId.value = route.query.contremarqueId || null;
    getDI();
});
const getDI = async () => {
    try {
        loading.value = true;
        let url = `/api/carpetDesignOrders/all?page=${params.current_page}&itemsPerPage=${params.pagesize}`;
        if(params.orderBy){
            url += `&orderBy=${params.orderBy}`
            if(params.orderWay){
                url += `&orderWay=${params.orderWay}`
            }else{
                url += `&orderWay=asc`
            }
        }

        // Append contremarqueId in the required format
        if (contremarqueId.value) {
            url += `&filter[contremarqueId]=${contremarqueId.value}`;
        }
        
        url += getFilterParams();
        const response = await axiosInstance.get(url);
        const data = response.data;
        total_rows.value = data.response.count;
        rows.value = data.response.carpetDesignOrders;
    } catch { }

    loading.value = false;
};
const changeServer = (data) => {
    params.current_page = data.current_page;
    params.pagesize = data.pagesize;
    params.orderBy = data.sort_column;
    params.orderWay = data.sort_direction;
    getDI();
};
const doSearch = () => {
    filterActive.value = true;
    Helper.setStorage(FILTER_SUIVI_DI_STORAGE_NAME, filter.value);
    getDI();
};
const getFilterParams = () => {

    let param = "";
    if (filter.value.customer) {
        param += "&filter[customer]=" + filter.value.customer
    }
    if (filter.value.contremarque) {
        param += "&filter[contremarque]=" + filter.value.contremarque
    }
    if (filter.value.diNumber) {
        param += "&filter[diNumber]=" + filter.value.diNumber
    }
    if (filter.value.carpetStatus) {
        param += "&filter[statusId]=" + filter.value.carpetStatus
    }
    return param;
};

const doReset = () => {
    filterActive.value = false;
    filter.value = Object.assign({}, filterSuiviDi);
    Helper.setStorage(FILTER_SUIVI_DI_STORAGE_NAME, filter.value);
    getDI();
};
const handleUpdateDI = async (diId) => {
    selectedDiId.value = diId;
};
const goTodetails = (id_di,carperOrderId = 0) => {
    location.href = `/projet/dis/model/${id_di}/update/${carperOrderId}`;
}
const goToContreMarqueDetails = (id_contremarque) => {
    location.href = `/projet/contremarques/projectdis/${id_contremarque}`;
}
const handleClose = () => {
    //selectedDiId.value = null;
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
