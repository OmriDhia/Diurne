<template>
    <div class="layout-px-spacing mt-4 list-facture-client">
        <d-page-title icon="file-text" :title="'Facture Client'"></d-page-title>

        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-2">
                <div class="row p-2">
                    <div class="col-auto">
                        <button class="btn btn-custom pe-5 ps-5" @click="goToNewInvoice">Nouvelle Facture</button>
                    </div>
                </div>
                <div class="row d-flex justify-content-center align-items-start p-2">
                    <div class="col-md-6 col-sm-12 list-facture-client--item">
                        <d-customer-dropdown v-model="filter.customer" />
                        <d-input label="Numéro de facture" v-model="filter.invoiceNumber" />
                        <d-contremarque-dropdown class="contremarque" :customerId="filter.customer" v-model="filter.contremarque" />
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <!-- <d-date-picker label="Du" v-model="filter.date_from" />
            <d-date-picker label="Au" v-model="filter.date_to" /> -->
                        <!-- <d-input label="RN" class="pb-2" v-model="filter.rn" /> -->
                        <d-rn-number-dropdown v-model="filter.rn"></d-rn-number-dropdown>
                        <div class="row">
                            <label for="cleared" class="col-4">Soldé</label>
                            <div class="col-8">
                                <select id="cleared" class="form-control" v-model="filter.cleared">
                                    <option :value="null">Tous</option>
                                    <option :value="true">Oui</option>
                                    <option :value="false">Non</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="date_from" class="col-4">Date Env</label>
                            <div class="col-8 d-flex justify-content-between align-items-center">
                                <input id="date_from" class="form-control custom-date" type="date" v-model="filter.date_from" />
                                <label for="date_to" class="custom-between">au</label>
                                <input id="date_to" class="form-control custom-date" type="date" v-model="filter.date_to" />
                            </div>
                        </div>

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
                            <template #customer="data">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-truncate" :title="data.value.customer">
                                        {{ truncateText(data.value.customer, 14) }}
                                    </strong>
                                    <router-link :to="'/contacts/manage/' + data.value.customer_id" v-if="$hasPermission('update contact')">
                                        <vue-feather type="search" stroke-width="1" class="cursor-pointer"></vue-feather>
                                    </router-link>
                                </div>
                            </template>
                            <template #contremarque="data">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-truncate" :title="data.value.contremarque">
                                        {{ truncateText(data.value.contremarque, 14) }}
                                    </strong>
                                    <router-link :to="'/projet/contremarques/manage/' + data.value.contremarque_id">
                                        <vue-feather type="search" stroke-width="1" class="cursor-pointer"></vue-feather>
                                    </router-link>
                                </div>
                            </template>
                            <template #invoice_number="data">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-truncate" :title="data.value.invoice_number">
                                        {{ truncateText(data.value.invoice_number, 14) }}
                                    </strong>
                                    <router-link :to="{ name: 'client-invoice-edit', params: { id: data.value.id } }">
                                        <vue-feather type="search" stroke-width="1" class="cursor-pointer"></vue-feather>
                                    </router-link>
                                </div>
                            </template>
                            <template #amount_ht="data">
                                <span>{{ formatNumber(data.value.amount_ht) }}</span>
                            </template>
                            <template #amount_tva="data">
                                <span>{{ formatNumber(data.value.amount_tva) }}</span>
                            </template>
                            <template #amount_ttc="data">
                                <span :title="formatNumber(data.value.amount_ttc)">
                                    {{ formatNumber(data.value.amount_ttc) }}
                                </span>
                            </template>
                            <template #payment="data">
                                <span>{{ formatNumber(data.value.payment) }}</span>
                            </template>
                            <template #cleared="data">
                                <span :class="data.value.cleared ? 'text-success' : 'text-danger'">
                                    {{ data.value.cleared ? 'Oui' : 'Non' }}
                                </span>
                            </template>
                        </vue3-datatable>
                    </div>
                </div>
                <div class="row mt-3" v-if="rows.length > 0">
                    <div class="col-auto">
                        <strong>Total Montant HT: {{ formatNumber(totalAmountHt) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, reactive, onMounted, nextTick, watch } from 'vue';
    import VueFeather from 'vue-feather';
    import Vue3Datatable from '@bhplugin/vue3-datatable';
    import dInput from '../../../components/base/d-input.vue';
    import dCustomerDropdown from '../../../components/common/d-customer-dropdown.vue';
    import dContremarqueDropdown from '../../../components/common/d-contremarque-dropdown.vue';
    import dPageTitle from '../../../components/common/d-page-title.vue';
    import dDatePicker from '../../../components/base/d-date-picker.vue';
    import axiosInstance from '../../../config/http';
    import { useRouter } from 'vue-router';
    import { filterClientInvoice, FILTER_CLIENT_INVOICE_STORAGE_NAME } from '../../../composables/constants';
    import { Helper } from '../../../composables/global-methods';
    import { useMeta } from '/src/composables/use-meta';
    import dRnNumberDropdown from '../../../components/common/d-rn-number-dropdown.vue';
    import { computed } from 'vue';
    useMeta({ title: 'Facture Client' });

    const router = useRouter();
    const loading = ref(false);
    const rows = ref([]);
    const total_rows = ref(0);
    const filterActive = ref(false);

    const params = reactive({
        current_page: 1,
        pagesize: 50,
        orderBy: 'customer',
        orderWay: 'desc',
    });

    const filter = ref({ ...filterClientInvoice, cleared: null });

    const cols = ref([
        { field: 'invoice_number', title: 'Numéro facture' },
        { field: 'invoice_date', title: 'Date de facture' },
        { field: 'customer', title: 'Raison sociale' },
        { field: 'contremarque', title: 'Contremarque' },
        { field: 'amount_ht', title: 'Montant HT' },
        { field: 'amount_tva', title: 'TVA' },
        { field: 'amount_ttc', title: 'Montant TTC' },
        { field: 'payment', title: 'Règlement' },
        { field: 'cleared', title: 'Soldé' },
        { field: 'actions', title: '', sort: false },
    ]);

    onMounted(() => {
        const f = Helper.getStorage(FILTER_CLIENT_INVOICE_STORAGE_NAME);
        if (f && Helper.hasDefinedValue(f)) {
            filter.value = f;
            filterActive.value = true;
        }
        getInvoices();
    });

    const setCellTitles = () => {
        const cells = document.querySelectorAll('.vue3-datatable td');
        cells.forEach(cell => {
            cell.setAttribute('title', cell.textContent || '');
        });
    };

    watch(rows, async () => {
        await nextTick();
        setCellTitles();
    });

    const getInvoices = async () => {
        try {
            loading.value = true;
            let url = `/api/customerInvoices?page=${params.current_page}&limit=${params.pagesize}`;
            url += `&orderBy=${params.orderBy}&orderWay=${params.orderWay}`;
            url += getFilterParams();
            const res = await axiosInstance.get(url);
            const data = res.data.data || {};
            rows.value = data || [];

            total_rows.value = res.data.meta.total || 0;
        } catch (e) {
            console.error(e);
        }
        loading.value = false;
    };
    function formatNumber(num) {
        return parseFloat(Number(num).toFixed(3));
    }
    const changeServer = (data) => {
        params.current_page = data.current_page;
        params.pagesize = data.pagesize;
        params.orderBy = data.sort_column;
        params.orderWay = data.sort_direction;
        getInvoices();
    };

    const doSearch = () => {
        filterActive.value = true;
        Helper.setStorage(FILTER_CLIENT_INVOICE_STORAGE_NAME, filter.value);
        getInvoices();
    };

    const doReset = () => {
        filterActive.value = false;
        filter.value = { ...filterClientInvoice };
        filter.value.customer = null;
        Helper.setStorage(FILTER_CLIENT_INVOICE_STORAGE_NAME, filter.value);
        getInvoices();
    };

    const getFilterParams = () => {
        let p = '';
        if (filter.value.customer) p += `&customerId=${filter.value.customer}`;
        if (filter.value.invoiceNumber) p += `&invoiceNumber=${filter.value.invoiceNumber}`;
        if (filter.value.rn) p += `&rn=${filter.value.rn}`;
        if (filter.value.date_from) p += `&fromDate=${filter.value.date_from}`;
        if (filter.value.date_to) p += `&toDate=${filter.value.date_to}`;
        if (filter.value.contremarque) p += `&contremarque=${filter.value.contremarque}`;
        if (filter.value.cleared !== null) p += `&cleared=${filter.value.cleared ? 1 : 0}`;
        return p;
    };

    const goToNewInvoice = () => {
        router.push({ name: 'client-invoice-create' });
    };

    const truncateText = (text, length) => {
        if (!text) return '';
        return text.length > length ? text.substring(0, length) + '...' : text;
    };

    const totalAmountHt = computed(() => {
        return rows.value.reduce((sum, row) => sum + parseFloat(row.amount_ht || 0), 0);
    });
</script>
<style>
    .text-size-16 {
        font-size: 16px !important;
    }
    .custom-date {
        width: 45%;
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        border: 1px solid #ced4da;
    }
</style>
