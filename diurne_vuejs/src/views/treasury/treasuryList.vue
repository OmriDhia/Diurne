<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title icon="dollar-sign" :title="'Reglement'"></d-page-title>

        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-2">

                <div class="row d-flex justify-content-center align-items-start p-2">
                    <div class="col-md-6 col-sm-12">
                        <div class="row">

                            <d-customer-dropdown v-model="filter.customer"></d-customer-dropdown>
                        </div>
                        <div class="row">
                            <d-input label="Commercial" v-model="filter.commercial"></d-input>
                        </div>
                        <div class="row">
                            <d-input label="Contermarque" v-model="filter.contremarque"></d-input>
                        </div>
                        <div class="row">
                            <d-input label="Devis" v-model="filter.devis"></d-input>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="row">
                            <d-input label="Prescripteur" v-model="filter.prescriptor"></d-input>
                        </div>
                        <div class="row">
                            <d-input label="Commande" v-model="filter.commande"></d-input>
                        </div>
                        <div class="row">
                            <d-input label="Montant" v-model="filter.amount"></d-input>
                        </div>
                        <div class="row mt-2 justify-content-center">
                            <div class="col-auto" v-if="filterActive">
                                <button class="btn btn-outline-secondary btn-reset" @click.prevent="doReset">
                                    Reset filtre
                                </button>
                            </div>
                            <div class="col-auto me-2">
                                <button class="btn btn-primary pe-3 ps-3" @click.prevent="doSearch">
                                    Recherche
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel br-6 p-2 mt-3" id="fullscreen">
                <div class="row p-2 align-items-center">
                    <div class="col-auto">
                        <button class="btn btn-primary pe-5 ps-5" @click="goToNewReglement">
                            Nouveau Réglement
                        </button>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-primary pe-5 ps-5" @click="addNewPaymentRow">
                            Ajouter Paiement
                        </button>
                    </div>
                </div>

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
                        :pageSizeOptions="[10, 25, 50, 100]"
                        noDataContent="Aucun règlement trouvé."
                        paginationInfo="Affichage de {0} à {1} sur {2} entrées"
                        :sortable="true"
                        @change="changeServer"
                        class="advanced-table text-nowrap"
                    >
                        <template #dateOfReceipt="data">
                            <template v-if="isRowEditing(data.value)">
                                <input type="datetime-local"
                                       class="form-control"
                                       :value="formatDateForInput(data.value.dateOfReceipt)"
                                       @input="data.value.dateOfReceipt = $event.target.value" />
                            </template>
                            <template v-else>
                                {{ formatDateDisplay(data.value.dateOfReceipt) || '-' }}
                            </template>
                        </template>

                        <template #paymentMethod="data">
                            <template v-if="isRowEditing(data.value)">
                                <PaymentTypeSettings v-model="data.value.paymentMethod"
                                                     :paymentTypes="paymentTypes"
                                                     :isEditing="true" />
                            </template>
                            <template v-else>
                                {{ getPaymentMethodLabel(data.value.paymentMethod) }}
                            </template>
                        </template>

                        <template #accountLabel="data">
                            <template v-if="isRowEditing(data.value)">
                                <input type="text" class="form-control" v-model="data.value.accountLabel" />
                            </template>
                            <template v-else>
                                {{ data.value.accountLabel || '-' }}
                            </template>
                        </template>

                        <template #customer="data">
                            <template v-if="isRowEditing(data.value)">
                                <CustomerSettings v-model="data.value.customer"
                                                  :customers="customers"
                                                  :isEditing="true" />
                            </template>
                            <template v-else>
                                {{ getCustomerLabel(data.value.customer) }}
                            </template>
                        </template>

                        <template #commercial="data">
                            <template v-if="isRowEditing(data.value)">
                                <CommercialSettings v-model="data.value.commercial"
                                                    :commercials="rowCommercials[data.value.id] || commercials"
                                                    :isEditing="true" />
                            </template>
                            <template v-else>
                                {{ getCommercialLabel(data.value.commercial) }}
                            </template>
                        </template>

                        <template #paymentAmountHt="data">
                            <template v-if="isRowEditing(data.value)">
                                <input type="number" class="form-control" step="0.01"
                                       v-model.number="data.value.paymentAmountHt" />
                            </template>
                            <template v-else>
                                {{ formatAmount(data.value.paymentAmountHt) }}
                            </template>
                        </template>

                        <template #currency="data">
                            <template v-if="isRowEditing(data.value)">
                                <CurrencySettings v-model="data.value.currency"
                                                  :currencies="currencies"
                                                  :isEditing="true" />
                            </template>
                            <template v-else>
                                {{ getCurrencyLabel(data.value.currency) }}
                            </template>
                        </template>

                        <template #actions="data">
                            <div class="d-flex align-items-center gap-1">
                                <template v-if="isRowEditing(data.value)">
                                    <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle"
                                            @click="handleSaveRow(data.value)">
                                        <vue-feather type="save" :size="14"></vue-feather>
                                    </button>
                                    <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle"
                                            @click="cancelEditRow(data.value)">
                                        <vue-feather type="slash" :size="14"></vue-feather>
                                    </button>
                                    <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle"
                                            @click="handleView(data.value)">
                                        <vue-feather type="eye" :size="14"></vue-feather>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle"
                                            @click="startEditRow(data.value)">
                                        <vue-feather type="edit" :size="14"></vue-feather>
                                    </button>
                                    <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle"
                                            @click="confirmDeleteRow(data.value)">
                                        <vue-feather type="x" :size="14"></vue-feather>
                                    </button>
                                    <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle"
                                            @click="handleView(data.value)">
                                        <vue-feather type="eye" :size="14"></vue-feather>
                                    </button>
                                </template>
                                <button type="button" class="btn btn-dark mb-1 me-2"
                                        @click="alertCommercial(data.value)">
                                    Alerte commercial
                                </button>
                                <div class="t-dot reglement"
                                     :class="hasOrderPaymentDetails(data.value) ? 'bg-success' : 'bg-danger'"></div>
                            </div>
                        </template>
                    </vue3-datatable>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, reactive, onMounted, toRaw, watch, defineAsyncComponent } from 'vue';
    import Vue3Datatable from '@bhplugin/vue3-datatable';
    import dInput from '../../components/base/d-input.vue';
    import DCustomerDropdown from '../../components/common/d-customer-dropdown.vue';
    import dPageTitle from '../../components/common/d-page-title.vue';
    import axiosInstance from '../../config/http';
    import contactService from '../../Services/contact-service';
    import { useRouter } from 'vue-router';
    import {
        filterOrderPayment,
        FILTER_ORDER_PAYMENT_STORAGE_NAME
    } from '../../composables/constants';
    import { Helper } from '../../composables/global-methods';
    import VueFeather from 'vue-feather';
    import Swal from 'sweetalert2';

    const PaymentTypeSettings = defineAsyncComponent(() => import('../../components/common/settings/d-payment-type-dropdown-settings.vue'));
    const CustomerSettings = defineAsyncComponent(() => import('../../components/common/settings/d-customer-dropdown-settings.vue'));
    const CommercialSettings = defineAsyncComponent(() => import('../../components/common/settings/d-commercial-dropdown-settings.vue'));
    const CurrencySettings = defineAsyncComponent(() => import('../../components/common/settings/d-currency-settings.vue'));

    const API_DATETIME_FORMAT = 'YYYY-MM-DD';
    const normalizeDateValue = (rawDate) => {
        if (!rawDate) {
            return null;
        }
        const source = typeof rawDate === 'object' && rawDate.date ? rawDate.date : rawDate;
        const formatted = Helper.FormatDateTime(source, API_DATETIME_FORMAT);
        return formatted && formatted !== 'Invalid date' ? formatted : null;
    };
    const formatDateForApi = (rawDate) => {
        if (!rawDate) {
            return null;
        }
        return normalizeDateValue(rawDate);
    };

    const loading = ref(false);
    const rows = ref([]);
    const total_rows = ref(0);
    const editingRowId = ref(null);
    const rowBackups = ref(new Map());
    const currencies = ref([]);
    const customers = ref([]);
    const commercials = ref([]);
    const defaultCommercials = ref([]);
    const router = useRouter();
    const paymentTypes = ref([]);
    const newRowCounter = ref(0);

    const customersCache = ref(new Map());
    const commercialsCache = ref(new Map());
    const rowCommercials = reactive({});

    const params = reactive({
        current_page: 1,
        pagesize: 50,
        orderBy: 'dateOfReceipt',
        orderWay: 'desc'
    });

    const filter = ref({ ...filterOrderPayment });
    const filterActive = ref(false);

    const cols = ref([
        { field: 'dateOfReceipt', title: 'Date réception', is_unique: true },
        { field: 'paymentMethod', title: 'Type de règlement' },
        { field: 'accountLabel', title: 'Libellé sur le compte' },
        { field: 'customer', title: 'Client' },
        { field: 'commercial', title: 'Commercial' },
        { field: 'paymentAmountHt', title: 'Montant' },
        { field: 'currency', title: 'Devise' },
        { field: 'actions', title: 'Actions', sort: false }
    ]);

    onMounted(async () => {
        try {
            loading.value = true;
            await Promise.all([
                fetchCurrencies(),
                fetchCustomers(),
                fetchPaymentTypes(),
                fetchDefaultCommercials(),
                loadSavedFilters()
            ]);
            await fetchData({
                page: params.current_page,
                itemsPerPage: params.pagesize,
                sort: { field: params.orderBy, direction: params.orderWay }
            });
        } finally {
            loading.value = false;
        }
    });

    async function loadSavedFilters() {
        const savedFilters = Helper.getStorage(FILTER_ORDER_PAYMENT_STORAGE_NAME);
        if (savedFilters && Helper.hasDefinedValue(savedFilters)) {
            filter.value = savedFilters;
            filterActive.value = true;

            const savedCustomerId = extractCustomerId(savedFilters.customer);
            if (savedCustomerId || savedFilters.customer === 0 || savedFilters.customer === '0') {
                await loadCommercialsForCustomer(savedCustomerId);
            }
        }
    }

    async function fetchPaymentTypes() {
        try {
            const res = await axiosInstance.get('/api/payment-type');
            paymentTypes.value = res.data.response || [];
        } catch (error) {
            console.error('Failed to fetch payment types:', error);
        }
    }

    async function fetchCurrencies() {
        try {
            const res = await axiosInstance.get('/api/currency');
            currencies.value = res.data.response;
        } catch (error) {
            console.error('Failed to fetch currencies:', error);
        }
    }

    async function fetchCustomers() {
        try {
            const res = await axiosInstance.get('/api/customers/all');
            const uniqueCustomers = [];
            const seenIds = new Set();

            (res.data.response.customers || []).forEach(customer => {
                if (!seenIds.has(customer.id)) {
                    seenIds.add(customer.id);
                    uniqueCustomers.push(customer);
                    customersCache.value.set(customer.id, { response: { customerData: customer } });
                }
            });

            customers.value = uniqueCustomers;
        } catch (error) {
            console.error('Failed to fetch customers:', error);
        }
    }

    function extractCustomerId(customerValue) {
        if (!customerValue) return null;

        if (typeof customerValue === 'object') {
            return customerValue.customer_id || customerValue.customerId || customerValue.id || null;
        }

        if (typeof customerValue === 'number') {
            return customerValue;
        }

        const trimmedValue = String(customerValue).trim();
        const parsed = parseInt(trimmedValue, 10);

        if (!Number.isNaN(parsed) && String(parsed) === trimmedValue) {
            return parsed;
        }

        return null;
    }

    let lastFetchedCustomerId = null;

    async function fetchDefaultCommercials() {
        try {
            const res = await axiosInstance.get('/api/commercials');
            const fetchedCommercials = res.data.response.commercials || [];

            defaultCommercials.value = fetchedCommercials;
            commercials.value = fetchedCommercials;

            fetchedCommercials.forEach(commercial => {
                if (commercial.user_id) {
                    commercialsCache.value.set(commercial.user_id, {
                        response: {
                            commercialData: commercial
                        }
                    });
                }
            });
        } catch (error) {
            console.error('Failed to fetch commercials:', error);
        }
    }

    async function loadCommercialsForCustomer(customerId = null) {
        const normalizedId = customerId ?? null;

        if (normalizedId === lastFetchedCustomerId) {
            if (normalizedId === null) {
                filter.value.commercial = '';
                return;
            }
            return;
        }

        lastFetchedCustomerId = normalizedId;

        if (normalizedId === null) {
            filter.value.commercial = '';
            commercials.value = defaultCommercials.value.length ? [...defaultCommercials.value] : [];
            if (!commercials.value.length) {
                await fetchDefaultCommercials();
            }
            return;
        }

        try {
            const customerData = await contactService.getCustomerById(normalizedId);
            const histories = customerData?.contactCommercialHistoriesData
                || customerData?.contactCommercialHistories
                || [];

            const relatedCommercialsMap = new Map();

            histories.forEach(history => {
                const historyCommercial = history?.commercial || history?.commercialData || history;
                if (!historyCommercial) {
                    return;
                }

                const userId = historyCommercial.user_id
                    || historyCommercial.id
                    || historyCommercial.commercial_id
                    || historyCommercial.commercialId
                    || history?.commercial_id
                    || history?.commercialId;

                if (!userId || relatedCommercialsMap.has(userId)) {
                    return;
                }

                const firstname = historyCommercial.firstname || historyCommercial.firstName || '';
                const lastname = historyCommercial.lastname || historyCommercial.lastName || '';
                const name = historyCommercial.name
                    || historyCommercial.commercialName
                    || `${firstname} ${lastname}`.trim();

                relatedCommercialsMap.set(userId, {
                    ...historyCommercial,
                    user_id: userId,
                    id: historyCommercial.id || userId,
                    firstname,
                    lastname,
                    name
                });
            });

            const relatedCommercials = Array.from(relatedCommercialsMap.values());

            if (normalizedId !== lastFetchedCustomerId) {
                return;
            }

            commercials.value = relatedCommercials.length
                ? relatedCommercials
                : (defaultCommercials.value.length ? [...defaultCommercials.value] : []);

            if (relatedCommercials.length) {
                relatedCommercials.forEach(commercial => {
                    if (commercial.user_id) {
                        commercialsCache.value.set(commercial.user_id, {
                            response: {
                                commercialData: commercial
                            }
                        });
                    }
                });
            }

            if (!commercials.value.length) {
                await fetchDefaultCommercials();
            }

            if (customerData?.current_commercial) {
                filter.value.commercial = customerData.current_commercial;
            } else if (relatedCommercials.length) {
                const firstCommercial = relatedCommercials[0];
                filter.value.commercial = firstCommercial.name?.trim()
                    || `${firstCommercial.firstname || ''} ${firstCommercial.lastname || ''}`.trim()
                    || '';
            } else {
                filter.value.commercial = '';
            }
        } catch (error) {
            console.error('Failed to load customer commercials:', error);
            if (normalizedId !== lastFetchedCustomerId) {
                return;
            }
            commercials.value = defaultCommercials.value.length ? [...defaultCommercials.value] : [];
            if (!commercials.value.length) {
                await fetchDefaultCommercials();
            }

        }
    }

    // Fetch commercials for a customer (used for per-row commercial list)
    async function fetchCommercialsForCustomerId(customerId) {
        if (!customerId) return [];
        try {
            const res = await axiosInstance.get(`/api/customer/${customerId}`);
            const customerData = res.data?.response?.customerData || res.data?.response || res.data;
            const histories = customerData?.contactCommercialHistoriesData || customerData?.contactCommercialHistories || [];
            const list = [];
            const seen = new Set();
            histories.forEach(h => {
                const cid = h.commercial_id ?? h.commercialId ?? h.commercial_id ?? null;
                if (!cid || seen.has(cid)) return;
                seen.add(cid);
                const firstname = h.firstname || '';
                const lastname = h.lastname || '';
                list.push({ user_id: cid, id: cid, firstname, lastname, name: `${firstname} ${lastname}`.trim() });
            });
            return list;
        } catch (e) {
            console.error('Failed to fetch customer commercials for id', customerId, e);
            return [];
        }
    }

    // Watch when editingRowId changes: preload commercials for that row if customer present
    watch(() => editingRowId.value, async (newId) => {
        if (!newId) return;
        const row = rows.value.find(r => r.id === newId);
        if (!row) return;
        const custId = extractCustomerId(row.customer);
        if (custId) {
            const list = await fetchCommercialsForCustomerId(custId);
            rowCommercials[newId] = list;
            // if current commercial is empty but we have commercials, set to first commercial
            if ((!row.commercial || row.commercial === '') && list.length) {
                row.commercial = {
                    user_id: list[0].user_id,
                    id: list[0].id,
                    firstname: list[0].firstname,
                    lastname: list[0].lastname,
                    name: list[0].name
                };
            }
        } else {
            rowCommercials[newId] = [];
        }
    });

    // Watch the currently edited row's customer field to update per-row commercials when user changes client
    const getEditingRowCustomer = () => {
        const r = rows.value.find(rr => rr.id === editingRowId.value);
        return r ? r.customer : null;
    };

    watch(getEditingRowCustomer, async (newCust, oldCust) => {
        const editId = editingRowId.value;
        if (!editId) return;
        const custId = extractCustomerId(newCust);
        if (!custId) {
            rowCommercials[editId] = [];
            return;
        }
        const list = await fetchCommercialsForCustomerId(custId);
        rowCommercials[editId] = list;
        // if the selected commercial is not in list, set to first available
        const row = rows.value.find(r => r.id === editId);
        if (!row) return;
        const selectedCommercialId = row.commercial?.user_id ?? row.commercial?.id ?? row.commercial ?? null;
        if (list.length && !list.some(c => c.user_id == selectedCommercialId)) {
            row.commercial = {
                user_id: list[0].user_id,
                id: list[0].id,
                firstname: list[0].firstname,
                lastname: list[0].lastname,
                name: list[0].name
            };
        }
    });

    const fetchData = async ({ page = params.current_page, itemsPerPage = params.pagesize, sort } = {}) => {
        try {
            loading.value = true;
            params.current_page = page;
            params.pagesize = itemsPerPage;
            let url = `/api/order-payments?page=${page}&limit=${itemsPerPage}`;

            if (sort?.field) {
                url += `&orderBy=${sort.field}`;
                params.orderBy = sort.field;
            }
            if (sort?.direction) {
                url += `&orderWay=${sort.direction}`;
                params.orderWay = sort.direction;
            }

            url += getFilterParams();

            const response = await axiosInstance.get(url);
            const data = response.data.response;
            const payments = data.data || data;

            rows.value = await batchTransformPayments(payments, 5);
            total_rows.value = data.total || data.count || payments.length;
        } catch (error) {
            console.error('Error fetching order payments:', error);
            throw error;
        } finally {
            loading.value = false;
        }
    };

    async function batchTransformPayments(payments, batchSize = 5) {
        const result = [];
        for (let i = 0; i < payments.length; i += batchSize) {
            const batch = payments.slice(i, i + batchSize);
            const transformedBatch = await Promise.all(batch.map(payment => transformPayment(payment)));
            result.push(...transformedBatch);
        }
        return result;
    }


    async function transformPayment(payment) {
        const rawPayment = toRaw(payment);

        const customerData = await processCustomer(rawPayment);

        let commercialData = { commercialId: null, commercialName: 'N/A' };
        const orderPaymentDetails = Array.isArray(rawPayment.orderPaymentDetails)
            ? rawPayment.orderPaymentDetails
            : [];
        if (rawPayment.commercial) {
            if (typeof rawPayment.commercial === 'object') {
                const rawCommercial = toRaw(rawPayment.commercial);
                commercialData = {
                    commercialId: rawCommercial.id || rawCommercial.commercialId,
                    commercialName: rawCommercial.commercialName || rawCommercial.commercial ||
                        `${rawCommercial.firstname || ''} ${rawCommercial.lastname || ''}`.trim()
                };
            } else {
                const commercialId = rawPayment.commercial;
                const foundCommercial = commercials.value.find(c => c.user_id == commercialId);

                if (foundCommercial) {
                    commercialData = {
                        commercialId: commercialId,
                        commercialName: `${foundCommercial.firstname || ''} ${foundCommercial.lastname || ''}`.trim()
                    };
                } else {
                    commercialData = await processCommercial(rawPayment);
                }
            }
        }

        let currencyData = rawPayment.currency;
        if (typeof currencyData === 'string') {
            const found = currencies.value.find(c => c.name === currencyData);
            currencyData = found || { name: currencyData };
        }

        let paymentMethod = rawPayment.paymentMethod;
        if (typeof paymentMethod === 'string' && paymentTypes.value.length > 0) {
            const foundType = paymentTypes.value.find(t => t.label === paymentMethod);
            if (foundType) {
                paymentMethod = foundType;
            }
        }

        return {
            id: rawPayment.id,
            dateOfReceipt: normalizeDateValue(rawPayment.dateOfReceipt),
            paymentMethod: paymentMethod,
            accountLabel: rawPayment.accountLabel,
            paymentAmountHt: parseFloat(rawPayment.paymentAmountHt),
            currency: currencyData,
            customer: customerData,
            commercial: commercialData,
            orderPaymentDetails: orderPaymentDetails
        };
    }

    async function processCustomer(payment) {
        if (!payment.customer) return { customerId: null, customerName: 'N/A' };

        if (typeof payment.customer === 'object') {
            const rawCustomer = toRaw(payment.customer);
            return {
                customerId: rawCustomer.id || rawCustomer.customerId,
                customerName: rawCustomer.customerName || rawCustomer.customer ||
                    `${rawCustomer.social_reason || ''}${rawCustomer.code ? ` (${rawCustomer.code})` : ''}`.trim()
            };
        }

        const customerId = payment.customer;
        let customer = toRaw(customers.value.find(c => c.id == customerId));

        if (!customer && customerId) {
            const cached = customersCache.value.get(customerId);
            if (cached) {
                customer = cached.response?.customerData;
            } else {
                const customerResponse = await getCustomerOrCommercial(customerId, false);
                customer = customerResponse?.response?.customerData;
            }
        }

        return {
            customerId: customerId,
            customerName: customer ?
                `${customer.social_reason || ''}${customer.customerName ? ` ${customer.customerName}` : ''}`.trim() :
                `Client #${customerId}`
        };
    }

    async function processCommercial(payment) {
        if (!payment.commercial) return { commercialId: null, commercialName: 'N/A' };

        if (typeof payment.commercial === 'object') {
            const rawCommercial = toRaw(payment.commercial);
            return {
                commercialId: rawCommercial.id || rawCommercial.commercialId,
                commercialName: rawCommercial.commercialName || rawCommercial.commercial ||
                    `${rawCommercial.firstname || ''} ${rawCommercial.lastname || ''}`.trim()
            };
        }

        const commercialId = payment.commercial;
        let commercial = toRaw(commercials.value.find(c => c.user_id == commercialId));

        if (!commercial && commercialId) {
            const cached = commercialsCache.value.get(commercialId);
            if (cached) {
                commercial = cached.response?.commercialData;
            } else {
                const commercialResponse = await getCustomerOrCommercial(commercialId, true);
                commercial = commercialResponse?.response?.commercialData;
            }
        }

        return {
            commercialId: commercialId,
            commercialName: commercial ?
                `${commercial.firstname || ''} ${commercial.lastname || ''}`.trim() :
                `Commercial #${commercialId}`
        };
    }

    const saveData = async (row) => {
        try {
            console.log(row.customer);
            // Build payload matching API expected keys
            const customerId = extractCustomerId(row.customer);
            const commercialId = row.commercial?.user_id ?? row.commercial?.id ?? row.commercial?.commercialId ?? row.commercial ?? null;
            const paymentAmountHt = (row.paymentAmountHt !== null && row.paymentAmountHt !== undefined && row.paymentAmountHt !== '') ? String(row.paymentAmountHt) : null;
            const paymentAmountTtc = (row.paymentAmountTtc !== null && row.paymentAmountTtc !== undefined && row.paymentAmountTtc !== '') ? String(row.paymentAmountTtc) : (paymentAmountHt !== null ? paymentAmountHt : null);

            const payload = {
                dateOfReceipt: formatDateForApi(row.dateOfReceipt),
                paymentMethodId: row.paymentMethod?.id ?? row.paymentMethod ?? null,
                accountLabel: row.accountLabel || null,
                paymentAmountHt: paymentAmountHt,
                paymentAmountTtc: paymentAmountTtc,
                currencyId: row.currency?.id ?? row.currency ?? null,
                customerId: customerId ?? null,
                commercialId: commercialId ?? null,
                taxRuleId: row.taxRule?.id ?? row.taxRule ?? 1
            };

            const { data } = await axiosInstance.put(`/api/order-payments/${row.id}/update`, payload);
            return data.response;
        } catch (error) {
            console.error('Error updating payment:', error);
            throw error;
        }
    };

    const addData = async (row) => {
        try {
            const customerId = extractCustomerId(row.customer);
            const commercialId = row.commercial?.user_id ?? row.commercial?.id ?? row.commercial?.commercialId ?? row.commercial ?? null;
            const paymentAmountHt = (row.paymentAmountHt !== null && row.paymentAmountHt !== undefined && row.paymentAmountHt !== '') ? String(row.paymentAmountHt) : null;
            const paymentAmountTtc = (row.paymentAmountTtc !== null && row.paymentAmountTtc !== undefined && row.paymentAmountTtc !== '') ? String(row.paymentAmountTtc) : (paymentAmountHt !== null ? paymentAmountHt : null);

            const payload = {
                dateOfReceipt: formatDateForApi(row.dateOfReceipt),
                paymentMethodId: row.paymentMethod?.id ?? row.paymentMethod ?? null,
                accountLabel: row.accountLabel || null,
                paymentAmountHt: paymentAmountHt,
                paymentAmountTtc: paymentAmountTtc,
                currencyId: row.currency?.id ?? row.currency ?? null,
                customerId: customerId ?? null,
                commercialId: commercialId ?? null,
                taxRuleId: row.taxRule?.id ?? row.taxRule ?? 1
            };

            const { data } = await axiosInstance.post('/api/order-payment', payload);
            return data.response;
        } catch (error) {
            console.error('Error adding payment:', error);
            throw error;
        }
    };

    const deleteData = async (row) => {
        try {
            await axiosInstance.delete(`/api/order-payments/${row.id}`);
            return true;
        } catch (error) {
            console.error('Error deleting payment:', error);
            throw error;
        }
    };

    const getCustomerOrCommercial = async (id, isCommercial = false) => {
        try {
            if (!id) return null;

            const cache = isCommercial ? commercialsCache.value : customersCache.value;
            const cached = cache.get(id);
            if (cached) return cached;

            const endpoint = isCommercial ? `/api/commercial/${id}` : `/api/customer/${id}`;
            const response = await axiosInstance.get(endpoint);

            cache.set(id, response.data);
            return response.data;
        } catch (e) {
            console.error(`Error fetching ${isCommercial ? 'commercial' : 'customer'}:`, e);
            return null;
        }
    };

    // Helper to extract a readable name from a selected customer value (object or string/number)
    function serializeCustomerForFilter(customerValue) {
        if (!customerValue && customerValue !== 0 && customerValue !== '0') return null;

        if (typeof customerValue === 'object') {
            return customerValue.customer || customerValue.customerName || customerValue.social_reason ||
                (`${customerValue.firstname || ''} ${customerValue.lastname || ''}`.trim()) || String(customerValue.id || customerValue.customer_id || '');
        }

        return String(customerValue);
    }

    // Helper to extract readable commercial name for filter param
    function serializeCommercialForFilter(commercialValue) {
        if (!commercialValue && commercialValue !== 0 && commercialValue !== '0') return null;

        if (typeof commercialValue === 'object') {
            return commercialValue.name || commercialValue.commercialName ||
                (`${commercialValue.firstname || ''} ${commercialValue.lastname || ''}`.trim()) || String(commercialValue.id || commercialValue.user_id || '');
        }

        return String(commercialValue);
    }

    const getFilterParams = () => {
        const filters = filter.value || {};
        let param = '';

        // Map amount => minPaymentAmount & maxPaymentAmount
        if (filters.amount || filters.amount === 0) {
            const amount = String(filters.amount).trim();
            if (amount !== '') {
                param += `&minPaymentAmount=${encodeURIComponent(amount)}`;
                param += `&maxPaymentAmount=${encodeURIComponent(amount)}`;
            }
        }

        // Customer: API expects a search string (used with LIKE)
        const customerValue = serializeCustomerForFilter(filters.customer);
        if (customerValue) {
            param += `&customer=${encodeURIComponent(customerValue)}`;
        }

        // Commercial: API expects a search string (used with LIKE)
        const commercialValue = serializeCommercialForFilter(filters.commercial);
        if (commercialValue) {
            param += `&commercial=${encodeURIComponent(commercialValue)}`;
        }

        // Currency: accept object or primitive id
        if (filters.currency || filters.currency === 0) {
            const currencyId = (typeof filters.currency === 'object') ? (filters.currency.id ?? '') : filters.currency;
            if (currencyId !== '' && currencyId !== null && currencyId !== undefined) {
                param += `&currency=${encodeURIComponent(currencyId)}`;
            }
        }

        // hasNoChilds (boolean)
        if (filters.hasNoChilds !== undefined && filters.hasNoChilds !== null) {
            param += `&hasNoChilds=${filters.hasNoChilds ? 'true' : 'false'}`;
        }

        // Keep other simple filters if present (devis/commande/prescriptor/contremarque)
        const passthroughKeys = ['contremarque', 'devis', 'commande', 'prescriptor'];
        passthroughKeys.forEach(k => {
            if (filters[k] || filters[k] === 0) {
                const v = String(filters[k]);
                if (v.trim() !== '') param += `&${k}=${encodeURIComponent(v)}`;
            }
        });

        return param;
    };
    const doSearch = async () => {
        Helper.setStorage(FILTER_ORDER_PAYMENT_STORAGE_NAME, filter.value);
        filterActive.value = true;
        params.current_page = 1;
        await fetchData({
            page: params.current_page,
            itemsPerPage: params.pagesize,
            sort: { field: params.orderBy, direction: params.orderWay }
        });
    };

    const doReset = async () => {
        // Reset filter object to default template
        filter.value = { ...filterOrderPayment };
        // Mark filter inactive and remove saved filters
        filterActive.value = false;
        Helper.removeStorage(FILTER_ORDER_PAYMENT_STORAGE_NAME);

        // Reset pagination to first page
        params.current_page = 1;

        // Clear any per-row commercials and editing state
        try {
            // delete all keys from reactive rowCommercials
            Object.keys(rowCommercials).forEach(k => delete rowCommercials[k]);
        } catch (e) {
            // noop
        }
        editingRowId.value = null;

        // Ensure global commercials list is refreshed (if needed)
        await fetchDefaultCommercials();

        // Fetch data with reset params
        await fetchData({
            page: params.current_page,
            itemsPerPage: params.pagesize,
            sort: { field: params.orderBy, direction: params.orderWay }
        });
    };

    const goToNewReglement = () => {
        router.push({ name: 'reglement_create' });
    };

    const handleView = (row) => {
        if (!row?.id) {
            console.error('Invalid row data:', row);
            return;
        }
        router.push({ name: 'reglement_view', params: { id: row.id } });
    };

    const changeServer = (data) => {
        params.current_page = data.current_page;
        params.pagesize = data.pagesize;
        params.orderBy = data.sort_column || params.orderBy;
        params.orderWay = data.sort_direction || params.orderWay;
        fetchData({
            page: params.current_page,
            itemsPerPage: params.pagesize,
            sort: { field: params.orderBy, direction: params.orderWay }
        });
    };

    const isRowEditing = (row) => editingRowId.value === row?.id;

    const formatDateForInput = (dateString) => {
        if (!dateString) return '';
        try {
            const date = new Date(dateString);
            const pad = num => num.toString().padStart(2, '0');
            return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
        } catch (e) {
            console.error('Error formatting date for input:', e);
            return '';
        }
    };

    const formatDateDisplay = (dateString) => {
        if (!dateString) return '';
        const formatted = Helper.FormatDateTime(dateString, 'YYYY-MM-DD');
        return formatted && formatted !== 'Invalid date' ? formatted : '';
    };

    const getPaymentMethodLabel = (paymentMethod) => {
        if (!paymentMethod) return 'Aucun type sélectionné';
        if (typeof paymentMethod === 'object') {
            return paymentMethod.label || 'Aucun type sélectionné';
        }
        const found = paymentTypes.value.find(t => t.id == paymentMethod);
        return found ? found.label : `Type #${paymentMethod}`;
    };

    const getCustomerLabel = (customer) => {
        if (!customer) return 'N/A';
        if (typeof customer === 'object') {
            return customer.customerName || customer.customer || customer.customerId || customer.customer_id || 'N/A';
        }
        return `Client #${customer}`;
    };

    const getCommercialLabel = (commercial) => {
        if (!commercial) return 'N/A';
        if (typeof commercial === 'object') {
            return commercial.commercialName || commercial.name || `${commercial.firstname || ''} ${commercial.lastname || ''}`.trim() || 'N/A';
        }
        return `Commercial #${commercial}`;
    };

    const getCurrencyLabel = (currency) => {
        if (!currency) return 'N/A';
        if (typeof currency === 'object') {
            return currency.name || currency.code || 'N/A';
        }
        return currency;
    };

    const formatAmount = (amount) => {
        if (amount === null || amount === undefined || amount === '') return '-';
        const parsed = Number(amount);
        return Number.isNaN(parsed) ? '-' : parsed.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    };

    const hasOrderPaymentDetails = (row) => Array.isArray(row?.orderPaymentDetails) && row.orderPaymentDetails.length > 0;

    const addNewPaymentRow = () => {
        const tempId = `temp-${Date.now()}-${newRowCounter.value++}`;
        const newRow = {
            id: tempId,
            isNew: true,
            dateOfReceipt: '',
            paymentMethod: '',
            accountLabel: '',
            paymentAmountHt: '',
            // Payment amount TTC is required by API; default to same as HT until user modifies
            paymentAmountTtc: '',
            currency: '',
            // taxRule placeholder (API expects taxRuleId) - default to 1 to avoid validation errors
            taxRule: { id: 1 },
            customer: '',
            commercial: '',
            orderPaymentDetails: []
        };
        rows.value = [newRow, ...rows.value];
        rowBackups.value.set(tempId, JSON.parse(JSON.stringify(newRow)));
        editingRowId.value = tempId;
    };

    const startEditRow = (row) => {
        if (!row?.id) return;
        rowBackups.value.set(row.id, JSON.parse(JSON.stringify(row)));
        editingRowId.value = row.id;
    };

    const cancelEditRow = (row) => {
        if (!row?.id) return;
        const backup = rowBackups.value.get(row.id);
        if (row.isNew) {
            rows.value = rows.value.filter(r => r.id !== row.id);
        } else if (backup) {
            Object.assign(row, backup);
        }
        editingRowId.value = null;
    };

    const handleSaveRow = async (row) => {
        if (!row) return;
        // Client-side validation to avoid API 422 errors
        const paymentMethodId = row.paymentMethod?.id ?? row.paymentMethod ?? null;
        const currencyId = row.currency?.id ?? row.currency ?? null;
        const paymentAmountHtVal = (row.paymentAmountHt !== null && row.paymentAmountHt !== undefined && row.paymentAmountHt !== '') ? String(row.paymentAmountHt) : null;
        const paymentAmountTtcVal = (row.paymentAmountTtc !== null && row.paymentAmountTtc !== undefined && row.paymentAmountTtc !== '') ? String(row.paymentAmountTtc) : null;

        if (!paymentMethodId) {
            window.showMessage && window.showMessage('Veuillez sélectionner un type de règlement.', 'warning');
            return;
        }
        if (!currencyId) {
            window.showMessage && window.showMessage('Veuillez sélectionner une devise.', 'warning');
            return;
        }
        // Ensure we have at least one amount; prefer TTC if provided, else HT
        if (!paymentAmountTtcVal && !paymentAmountHtVal) {
            window.showMessage && window.showMessage('Veuillez saisir un montant HT ou TTC.', 'warning');
            return;
        }
        // Auto-fill TTC from HT if missing
        if (!paymentAmountTtcVal && paymentAmountHtVal) {
            row.paymentAmountTtc = paymentAmountHtVal;
        }

        // Client-side validation for customer and commercial
        const customerId = extractCustomerId(row.customer);
        const commercialId = row.commercial?.user_id ?? row.commercial?.id ?? row.commercial?.commercialId ?? row.commercial ?? null;

        if (!customerId) {
            window.showMessage && window.showMessage('Veuillez sélectionner un client.', 'warning');
            return;
        }

        if (!commercialId) {
            window.showMessage && window.showMessage('Veuillez sélectionner un commercial.', 'warning');
            return;
        }

        try {
            if (row.isNew) {
                await addData(row);
            } else {
                await saveData(row);
            }
            window?.showMessage?.('La modification a été effectuée avec succès.');
            editingRowId.value = null;
            rowBackups.value.delete(row.id);
            await fetchData({
                page: params.current_page,
                itemsPerPage: params.pagesize,
                sort: { field: params.orderBy, direction: params.orderWay }
            });
        } catch (error) {
            window?.showMessage?.('La modification n\'a pas pu être effectuée.', 'error');
            console.error('Error saving row:', error);
        }
    };

    const confirmDeleteRow = (row) => {
        if (!row?.id) return;
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: 'Cette action est irréversible !',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    await deleteData(row);
                    await fetchData({
                        page: params.current_page,
                        itemsPerPage: params.pagesize,
                        sort: { field: params.orderBy, direction: params.orderWay }
                    });
                    window?.showMessage?.('La suppression a été effectuée avec succès.');
                } catch (error) {
                    window?.showMessage?.('Erreur lors de la suppression des données.', 'error');
                    console.error('Erreur lors de la suppression des données :', error);
                }
            }
        });
    };

    const alertCommercial = (row) => {
        console.log('Alert commercial clicked', row);
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

    .btn-reset {
        box-shadow: none !important;
        margin-right: 5px;
    }

    .dropdown-item:active,
    .dropdown-item:hover {
        background: none !important;
    }

    .reglement.t-dot {
        height: 20px;
        width: 20px;
        border-radius: 50%;
        cursor: pointer;
        margin: 0 auto;
    }
</style>
