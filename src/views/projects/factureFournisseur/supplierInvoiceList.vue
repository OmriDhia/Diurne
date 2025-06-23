<template>
    <div class="layout-px-spacing mt-4 list-fournisseur-invoice">
        <d-page-title icon="file-text" :title="'Facture Fournisseur'"></d-page-title>

        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-2">
                <div class="row p-2">
                    <div class="col-auto">
                        <button class="btn btn-custom pe-5 ps-5" @click="goToNewInvoice">Nouvelle Facture</button>
                    </div>
                </div>
                <div class="row d-flex justify-content-center align-items-start p-2">
                    <div class="col-md-6 col-sm-12">
                        <d-input label="Numéro facture" v-model="filter.invoiceNumber" />
                        <div class="row align-items-center pt-2">
                            <label for="inv-date-from" class="col-4">Date facture</label>
                            <div class="col-8 d-flex justify-content-between align-items-center">
                                <input id="inv-date-from" class="form-control custom-date" type="date" v-model="filter.date_from" />
                                <label for="inv-date-to" class="custom-between">au</label>
                                <input id="inv-date-to" class="form-control custom-date" type="date" v-model="filter.date_to" />
                            </div>
                        </div>
                        <d-input label="Fournisseur" v-model="filter.supplier" />
                        <div class="row mt-2 justify-content-end">
                            <div class="col-auto" v-if="filterActive">
                                <button class="btn btn-outline-secondary btn-reset" @click.prevent="doReset">Reset filtre</button>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-custom pe-3 ps-3" @click.prevent="doSearch">Recherche</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel br-6 p-2 mt-3" id="fullscreen">
                <div class="row mt-2 mb-4">
                    <div class="vue3-datatable w-100">
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
                            noDataContent="Aucune facture trouvée."
                            paginationInfo="Affichage de {0} à {1} sur {2} entrées"
                            :sortable="true"
                            @change="changeServer"
                            class="advanced-table text-nowrap"
                        >
                            <template #actions="data">
                                <router-link :to="'/facture-fournisseur/view/' + data.value.id">
                                    <vue-feather type="search" stroke-width="1" class="cursor-pointer"></vue-feather>
                                </router-link>
                            </template>
                        </vue3-datatable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import VueFeather from 'vue-feather';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import dInput from '../../../components/base/d-input.vue';
import dPageTitle from '../../../components/common/d-page-title.vue';
import axiosInstance from '../../../config/http';
import { useRouter } from 'vue-router';
import { useMeta } from '/src/composables/use-meta';

useMeta({ title: 'Facture Fournisseur' });

const router = useRouter();
const loading = ref(false);
const rows = ref([]);
const total_rows = ref(0);
const filterActive = ref(false);

const params = reactive({
    current_page: 1,
    pagesize: 50,
    orderBy: 'invoiceNumber',
    orderWay: 'desc',
});

const filter = ref({
    invoiceNumber: '',
    supplier: '',
    date_from: '',
    date_to: '',
});

const cols = ref([
    { field: 'invoiceNumber', title: 'Numéro facture' },
    { field: 'invoiceDate', title: 'Date de facture' },
    { field: 'supplier', title: 'Fournisseur' },
    { field: 'amount', title: 'Montant' },
    { field: 'actions', title: '', sort: false },
]);

onMounted(() => {
    getInvoices();
});

const getInvoices = async () => {
    try {
        loading.value = true;
        let url = `/api/supplier-invoices?page=${params.current_page}&limit=${params.pagesize}`;
        url += `&orderBy=${params.orderBy}&orderWay=${params.orderWay}`;
        url += getFilterParams();
        const res = await axiosInstance.get(url);
        const data = res.data.response || {};
        rows.value = data.invoices || [];
        total_rows.value = data.count || 0;
    } catch (e) {
        console.error(e);
    }
    loading.value = false;
};

const changeServer = (data) => {
    params.current_page = data.current_page;
    params.pagesize = data.pagesize;
    params.orderBy = data.sort_column;
    params.orderWay = data.sort_direction;
    getInvoices();
};

const doSearch = () => {
    filterActive.value = true;
    getInvoices();
};

const doReset = () => {
    filterActive.value = false;
    filter.value = { invoiceNumber: '', supplier: '', date_from: '', date_to: '' };
    getInvoices();
};

const getFilterParams = () => {
    let p = '';
    if (filter.value.invoiceNumber) p += `&invoiceNumber=${filter.value.invoiceNumber}`;
    if (filter.value.supplier) p += `&supplier=${filter.value.supplier}`;
    if (filter.value.date_from) p += `&dateFrom=${filter.value.date_from}`;
    if (filter.value.date_to) p += `&dateTo=${filter.value.date_to}`;
    return p;
};

const goToNewInvoice = () => {
    router.push({ name: 'supplier-invoice-create' });
};
</script>

<style>
.custom-date {
    width: 45%;
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
    border: 1px solid #ced4da;
}
</style>

