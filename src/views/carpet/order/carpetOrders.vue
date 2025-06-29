<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title icon="file-text" :title="'Commande'"></d-page-title>

        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-2">
                <div class="row p-2">
                    <div class="col-auto">
                        <button class="btn btn-custom pe-5 ps-5" @click="goToNewDevis">Nouveau Devis</button>
                    </div>
                </div>
                <div class="row d-flex justify-content-center align-items-start p-2">
                    <div class="col-md-6 col-sm-12">
                        <div class="row">
                            <d-input label="Client" v-model="filter.customer"></d-input>
                        </div>
                        <div class="row">
                            <d-input label="Contremarque" v-model="filter.contremarque"></d-input>
                        </div>
                        <div class="row">
                            <d-input label="Commercial" v-model="filter.commercial"></d-input>
                        </div>
                        <div class="row">
                            <d-input label="Devis" type="text" v-model="filter.devis"></d-input>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="row mt-2">
                            <div class="col-auto" v-if="filterActive">
                                <button class="btn btn-outline-secondary btn-reset" @click.prevent="doReset">Reset filtre</button>
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
                                                <input
                                                    type="checkbox"
                                                    class="custom-control-input"
                                                    :checked="!col.hide"
                                                    :id="col.field"
                                                    @change="col.hide = !$event.target.checked"
                                                    :name="col.field"
                                                />
                                                <label class="custom-control-label text-black" :for="col.field"> {{ col.title }} </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <d-btn-fullscreen></d-btn-fullscreen>
                        </div>
                        <vue3-datatable
                            :rows="rows"
                            :columns="cols"
                            :loading="loading"
                            :isServerMode="true"
                            :sortColumn="params.orderBy"
                            :sortDirection="params.orderWay"
                            :totalRows="total_rows"
                            :page="params.current_page"
                            :pageSize="params.pagesize"
                            :pageSizeOptions="[10, 25, 50, 75, 100]"
                            noDataContent="Aucun devis trouvé."
                            paginationInfo="Affichage de {0} à {1} sur {2} entrées"
                            :sortable="true"
                            @change="changeServer"
                            class="advanced-table text-nowrap"
                        >
                            <template #reference="data">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ data.value.reference }}</strong>
                                    <router-link :to="'/projet/commande/manage/' + data.value.cloned_quote" v-if="$hasPermission('update carpet')">
                                        <vue-feather type="search" stroke-width="1" class="cursor-pointer"></vue-feather>
                                    </router-link>
                                </div>
                            </template>
                            <template #contremarque="data">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ data.value.designation }}</strong>
                                    <router-link :to="'/projet/contremarques/manage/' + data.value.contremarque_id" v-if="$hasPermission('update contremarque')">
                                        <vue-feather type="search" stroke-width="1" class="cursor-pointer"></vue-feather>
                                    </router-link>
                                </div>
                            </template>
                            <template #customer="data">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ data.value.customer }}</strong>
                                    <router-link :to="'/contacts/manage/' + data.value.customer_id" v-if="$hasPermission('update contremarque')">
                                        <vue-feather type="search" stroke-width="1" class="cursor-pointer"></vue-feather>
                                    </router-link>
                                </div>
                            </template>
                            <template #creationDate="data">
                                <div class="d-flex justify-content-between">
                                    {{ data.value.created_at ? $Helper.FormatDate(data.value.created_at) : '' }}
                                </div>
                            </template>
                            <template #validationDate="data">
                                <div class="d-flex justify-content-between">
                                    {{ data.value.validated_at ? $Helper.FormatDate(data.value.validated_at) : '' }}
                                </div>
                            </template>
                        </vue3-datatable>
                    </div>
                </div>
            </div>
            <d-modal-event :customerId="selectedCustomerId" :contramarqueId="selectedContremarqueId"></d-modal-event>
        </div>
    </div>
</template>

<script setup>
    import dInput from '../../../components/base/d-input.vue';
    import dBtnFullscreen from '../../../components/base/d-btn-fullscreen.vue';
    import dCustomerDropdown from '../../../components/common/d-customer-dropdown.vue';
    import dPageTitle from '../../../components/common/d-page-title.vue';
    import VueFeather from 'vue-feather';
    import Vue3Datatable from '@bhplugin/vue3-datatable';
    import axiosInstance from '../../../config/http';
    import { ref, reactive, onMounted } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import { filterDevis, FILTER_DEVIS_STORAGE_NAME } from '../../../composables/constants';
    import moment from 'moment';
    import { Helper } from '../../../composables/global-methods';
    import dModalEvent from '../../../components/projet/contremarques/_Partials/d-modal-event.vue';

    import { useMeta } from '/src/composables/use-meta';

    useMeta({ title: 'Tapis' });

    const loading = ref(true);
    const loadingAttribution = ref(false);
    const total_rows = ref(0);
    const route = useRoute();
    const router = useRouter();

    const params = reactive({
        current_page: 1,
        pagesize: 50,
        orderBy: '',
        orderWay: '',
    });

    const filter = ref(Object.assign({}, filterDevis));
    const filterActive = ref(false);
    const rows = ref(null);

    const cols =
        ref([
            { field: 'reference', title: 'Numéro devis' },
            { field: 'contremarque', title: 'Contremarque' },
            { field: 'customer', title: 'Client' },
            { field: 'commercial', title: 'Commercial' },
            { field: 'creationDate', title: 'Date création' },
            { field: 'validationDate', title: 'Date validation' },
        ]) || [];

    onMounted(() => {
        const f = Helper.getStorage(FILTER_DEVIS_STORAGE_NAME);
        if (f && Helper.hasDefinedValue(f)) {
            filter.value = f;
            filterActive.value = true;
        }
        getCarpetOrders();
    });

    const getCarpetOrders = async () => {
        try {
            loading.value = true;
            let url = `/api/carpetOrders?page=${params.current_page}&limit=${params.pagesize}`;
            if (params.orderBy) {
                url += `&orderBy=${params.orderBy}`;
            }
            if (params.orderWay) {
                url += `&orderWay=${params.orderWay}`;
            }
            if (route.query.contremarqueId) {
                url += `&contremarqueId=${route.query.contremarqueId}`;
            }
            if (route.query.locationId) {
                url += `&locationId=${route.query.locationId}`;
            }
            url += getFilterParams();
            const response = await axiosInstance.get(url);
            const data = response.data;
            total_rows.value = data.count;
            rows.value = data.carpetOrders;
        } catch {}

        loading.value = false;
    };

    const changeServer = (data) => {
        params.current_page = data.current_page;
        params.pagesize = data.pagesize;
        params.orderBy = data.sort_column;
        params.orderWay = data.sort_direction;
        getCarpetOrders();
    };

    const doSearch = () => {
        filterActive.value = true;
        Helper.setStorage(FILTER_DEVIS_STORAGE_NAME, filter.value);
        getCarpetOrders();
    };

    const getFilterParams = () => {
        let param = '';
        if (filter.value.customer) {
            param += '&customer=' + filter.value.customer;
        }
        if (filter.value.contremarque) {
            param += '&contremarque=' + filter.value.contremarque;
        }
        if (filter.value.devis) {
            param += '&reference=' + filter.value.devis;
        }
        if (filter.value.commercial) {
            param += '&commercial=' + filter.value.commercial;
        }
        return param;
    };

    const doReset = () => {
        filterActive.value = false;
        filter.value = Object.assign({}, filterDevis);
        Helper.setStorage(FILTER_DEVIS_STORAGE_NAME, filter.value);
        getCarpetOrders();
    };

    const goToNewDevis = () => {
        if (route.query.contremarqueId) {
            router.push({ name: 'devisManage', query: { contremarqueId: route.query.contremarqueId } });
        } else {
            router.push({ name: 'devisManage' });
        }
    };
</script>
<style>
    .text-size-16 {
        font-size: 16px !important;
    }
</style>
