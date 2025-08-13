<template>
    <div class="layout-px-spacing mt-4 list-facture-client">
        <d-page-title icon="file-text" :title="'FACTURE FOURNISSEUR'"></d-page-title>

        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-2">
                <div class="row p-2">
                    <div class="col-auto">
                        <button class="btn btn-custom pe-5 ps-5" @click="goToNewInvoice">Nouvelle Facture</button>
                    </div>
                </div>
                <div class="row d-flex justify-content-center align-items-start p-2">
                    <div class="col-md-6 col-sm-12 list-facture-client--item">
                        <d-user-dropdown v-model="filter.auteur" />
                        <!--/api/users intégrer l'api por l'auteur -->
                        <d-input label="Numéro facture" v-model="filter.invoiceNumber" />
                        <d-input label="Atelier" v-model="filter.atelier" />
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <!-- <d-date-picker label="Du" v-model="filter.date_from" />
            <d-date-picker label="Au" v-model="filter.date_to" /> -->
                        <d-rn-number-dropdown v-model="filter.rn"></d-rn-number-dropdown>
                        <div class="row">
                            <label for="date_from" class="col-4">Début Recherche :</label>
                            <div class="col-8 d-flex justify-content-between align-items-center">
                                <input id="date_from" class="form-control custom-date" type="date" v-model="filter.startDate" />
                                <label for="date_to" class="custom-between">au</label>
                                <input id="date_to" class="form-control custom-date" type="date" v-model="filter.endDate" />
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
                            <template #invoice_number="data">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-truncate" :title="data.value.invoice_number">
                                        {{ truncateText(data.value.invoice_number, 14) }}
                                    </strong>
                                    <router-link :to="{ name: 'fournisseur-invoice-edit', params: { id: data.value.id } }">
                                        <vue-feather type="search" stroke-width="1" class="cursor-pointer"></vue-feather>
                                    </router-link>
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
    import { ref, reactive, onMounted, nextTick, watch } from 'vue';
    import VueFeather from 'vue-feather';
    import Vue3Datatable from '@bhplugin/vue3-datatable';
    import dInput from '../../../components/base/d-input.vue';
    import dCustomerDropdown from '../../../components/common/d-customer-dropdown.vue';
    import dContremarqueDropdown from '../../../components/common/d-contremarque-dropdown.vue';
    import dPageTitle from '../../../components/common/d-page-title.vue';
    import dDatePicker from '../../../components/base/d-date-picker.vue';
    import dUserDropdown from '../../../components/common/d-user-dropdown.vue';
    import axiosInstance from '../../../config/http';
    import { useRouter } from 'vue-router';
    import { filterFactureFournisseur, FILTER_FOURNISSEUR_INVOICE_STORAGE_NAME } from '../../../composables/constants';
    import { Helper } from '../../../composables/global-methods';
    import { useMeta } from '/src/composables/use-meta';
    import dRnNumberDropdown from '../../../components/common/d-rn-number-dropdown.vue';
    useMeta({ title: 'Facture Client' });

    const router = useRouter();
    const loading = ref(false);
    const rows = ref([]);
    const total_rows = ref(0);
    const filterActive = ref(false);

    const params = reactive({
        current_page: 1,
        pagesize: 50,
        orderBy: 'invoice_number',
        orderWay: 'desc',
    });

    const filter = ref({ ...filterFactureFournisseur });

    const cols = ref([
        { field: 'invoice_number', title: 'Numéro facture' },
        { field: 'invoice_date', title: 'Date de facture' },
        { field: 'supplier', title: 'Atelier' }, //atelier == supplier??
        { field: 'rn', title: 'RN' }, //?
    ]);

    onMounted(() => {
        const f = Helper.getStorage(FILTER_FOURNISSEUR_INVOICE_STORAGE_NAME);
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
            let url = `/api/supplierInvoices?page=${params.current_page}&limit=${params.pagesize}`;
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

    const changeServer = (data) => {
        params.current_page = data.current_page;
        params.pagesize = data.pagesize;
        params.orderBy = data.sort_column;
        params.orderWay = data.sort_direction;
        getInvoices();
    };

    const doSearch = () => {
        filterActive.value = true;
        Helper.setStorage(FILTER_FOURNISSEUR_INVOICE_STORAGE_NAME, filter.value);
        getInvoices();
    };

    const doReset = () => {
        filterActive.value = false;
        filter.value = { ...filterFactureFournisseur };
        Helper.setStorage(FILTER_FOURNISSEUR_INVOICE_STORAGE_NAME, filter.value);
        getInvoices();
    };

    const getFilterParams = () => {
        let p = '';
        if (filter.value.auteur) p += `&authorId=${filter.value.auteur}`;
        if (filter.value.invoiceNumber) p += `&invoiceNumber=${filter.value.invoiceNumber}`;
        if (filter.value.rn) p += `&rn=${filter.value.rn}`;
        if (filter.value.startDate) p += `&dateFrom=${filter.value.startDate}`;
        if (filter.value.endDate) p += `&dateTo=${filter.value.endDate}`;
        return p;
    };

    const goToNewInvoice = () => {
        router.push({ name: 'fournisseur-invoice-create' });
    };

    const truncateText = (text, length) => {
        if (!text) return '';
        return text.length > length ? text.substring(0, length) + '...' : text;
    };
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
