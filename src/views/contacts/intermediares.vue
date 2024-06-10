<template>
    <d-base-page>
        <template v-slot:title>
            <d-page-title title="Agents"></d-page-title>
        </template>
        <template v-slot:header>
            <div class="panel br-6 p-2">
                <div class="row d-flex justify-content-start p-3">
                    <div class="col-auto">
                        <button class="btn btn-custom pe-5 ps-5" data-bs-toggle="modal" data-bs-target="#modalAgentManage">Nouveau Intermediaire</button>
                    </div>
                    <d-modal-manage-agent :agentData="agentData" @onClose="onClose"></d-modal-manage-agent>
                </div>
                <div class="row justify-content-end p-3 align-items-center">
                    <div class="col-lg-4 col-md-12">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <d-input label="Nom" v-model="filter.lastname"></d-input>
                            </div>
                            <div class="col-12">
                                <d-input label="Prénom" v-model="filter.firstname"></d-input>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <d-input label="Email" v-model="filter.email"></d-input>
                            </div>
                            <div class="col-12">
                                <d-intermediary-type v-model="filter.intermediaryTypeId"></d-intermediary-type>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="row align-items-end justify-content-end">
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
        </template>

        <template v-slot:body>
            <div class="panel br-6 p-2 mt-3">
                <div class="row mt-5 ms-2 mb-5">
                    <div class="vue3-datatable w-100">
                        <vue3-datatable :rows="rows" :columns="cols" :loading="loading" :isServerMode="true"
                                        :totalRows="total_rows" :page="params.current_page" :pageSize="params.pagesize"
                                        :pageSizeOptions="[10, 25, 50, 75, 100]" noDataContent="Aucun agent trouvé."
                                        paginationInfo="Affichage de {0} à {1} sur {2} entrées" :sortable="true"
                                        @change="changeServer" class="advanced-table text-nowrap">

                            <template #lastname="data">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ data.value.lastname}}</strong>
                                        <button type="button"
                                                class="btn btn-icon p-0"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalAgentManage"
                                                @click.prevent="editAgents(data.value)"
                                        >
                                            <vue-feather type="edit"></vue-feather>
                                        </button>
                                </div>
                            </template>
                            <template #address="data">
                                <div class="d-flex justify-content-center">
                                    <div class="col-auto">
                                        <button type="button"
                                                class="btn btn-icon p-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalAgentManageAddress"
                                                @click.prevent="editAddress(data.value)"
                                        >
                                            <vue-feather type="map"></vue-feather>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </vue3-datatable>
                    </div>
                </div>
                <d-modal-manage-agent-address :addressData="addressData" :intermediaryId="intermediaryId"></d-modal-manage-agent-address>
            </div>
        </template>
        
    </d-base-page>
</template>

<script setup>
import axiosInstance from "../../config/http";
import { useMeta } from '/src/composables/use-meta';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import VueFeather from 'vue-feather';
import { ref, watch, onMounted, reactive } from 'vue';
import dInput from "../../components/base/d-input.vue";
import dBasePage from "../../components/base/d-base-page.vue";
import dPageTitle from "../../components/common/d-page-title.vue";
import dModalManageAgent from "../../components/contacts/d-modal-manage-agent.vue";
import dModalManageAgentAddress from "../../components/contacts/_partial/d-modal-manage-agent-address.vue";
import DIntermediaryType from "../../components/common/d-intermediary-type.vue";

useMeta({ title: 'Agents' });

const loading = ref(true);
const total_rows = ref(0);

const params = reactive({
    current_page: 1,
    pagesize: 50,
    orderBy: 'firstname',
    orderWay: 'asc'
});

const filter = ref({
    firstname: "",
    lastname: "",
    email: "",
    intermediaryTypeId: 0,
});
const rows = ref(null);
const filterActive = ref(false);
const agentData = ref({});
const addressData = ref([]);
const intermediaryId = ref(null);

const cols = ref([
    { field: 'lastname', title: 'Nom' },
    { field: 'firstname', title: 'Prénom' },
    { field: 'email', title: 'Email' },
    { field: 'phone', title: 'Tél. fixe', sort: false},
    { field: 'intermediaryType', title: 'Type', sort: false},
    { field: 'address', title: 'Adresse', sort: false},
    { field: 'swift_code', title: 'Code swift', sort: false },
    { field: 'bank_name', title: 'Bank name', sort: false },
    { field: 'iban', title: 'IBAN', sort: false },
]) || [];

onMounted(() => {
    getAgents();
});
const getAgents = async () => {
    try {
        loading.value = true;
        let url_customers = `/api/contact/intermediaries?page=${params.current_page}&itemPerPage=${params.pagesize}&orderBy=${params.orderBy}&orderWay=${params.orderWay}`;
        url_customers += getFilterParams();
        const response = await axiosInstance.get(url_customers);
        const data = response.data.response;
        total_rows.value = data.count;
        rows.value = data.intermediaries;
    } catch(e) { 
        console.error(e.toString())
    }

    loading.value = false;
};
const changeServer = (data) => {
    params.current_page = data.current_page;
    params.pagesize = data.pagesize;
    params.orderBy = data.sort_column;
    params.orderWay = data.sort_direction;

    getAgents();
};
const doSearch = () => {
    filterActive.value = true;
    getAgents();
};
const getFilterParams = () => {

    let param = "";
    if (filter.value.firstname) {
        param += "&filter[firstname]=" + filter.value.firstname
    }
    if (filter.value.lastname) {
        param += "&filter[lastname]=" + filter.value.lastname
    }
    if (filter.value.email) {
        param += "&filter[email]=" + filter.value.email
    }
    if (filter.value.intermediaryTypeId) {
        param += "&filter[intermediaryTypeId]=" + filter.value.intermediaryTypeId
    }
    return param;
};
const doReset = () => {
    filterActive.value = false;
    filter.value = {
        lastname: "",
        firstname: "",
        email: "",
    };
    getAgents();
}
const onClose = () => {
    addressData.value = [];
    intermediaryId.value = null;
    getAgents();
};

const editAgents = (agent) => {
    agentData.value = agent; 
};
const editAddress = (agent) => {
    addressData.value = agent.addresses; 
    intermediaryId.value = agent.id; 
}
    
</script>
<style>
    .text-size-16{
        font-size: 16px !important;
    }
</style>
