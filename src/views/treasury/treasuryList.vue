<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title icon="dollar-sign" :title="'Reglement'"></d-page-title>

        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-2">

                <div class="row d-flex justify-content-center align-items-start p-2">
                    <div class="col-md-6 col-sm-12">
                        <div class="row">

                            <d-customer-dropdown v-model="filter.customer" :showCustomer="true"></d-customer-dropdown>
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
                <div class="row p-2">
                    <div class="col-auto">
                        <button class="btn btn-primary pe-5 ps-5" @click="goToNewReglement">
                            Nouveau Réglement
                        </button>
                    </div>
                </div>
                <d-data-grid ref="dataGrid" :fetchData="fetchData" :saveData="saveData" :addData="addData"
                             :disableAddNew="true"
                             :deleteData="deleteData" :columns="processedColumns" :rows="rows" title="Règlements"
                             rowKey="id" :showViewButton="true"
                             @view="handleView" :isServerMode="true"
                             :initialSort="{ field: params.orderBy, direction: params.orderWay }"
                             @sort-change="handleSortChange" :loading="loading" />
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, reactive, onMounted, computed, toRaw, watch } from 'vue';
    import dDataGrid from '../../components/base/d-data-grid.vue';
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
    const currencies = ref([]);
    const customers = ref([]);
    const commercials = ref([]);
    const defaultCommercials = ref([]);
    const dataGrid = ref(null);
    const router = useRouter();
    const paymentTypes = ref([]);

    const customersCache = ref(new Map());
    const commercialsCache = ref(new Map());

    const params = reactive({
        current_page: 1,
        pagesize: 50,
        orderBy: 'dateOfReceipt',
        orderWay: 'desc'
    });

    const filter = ref({ ...filterOrderPayment });
    const filterActive = ref(false);

    const columns = ref([
        { key: 'dateOfReceipt', label: 'Date réception', type: 'date', editable: false },
        {
            key: 'paymentMethod',
            label: 'Type de règlement',
            type: 'custom',
            editable: true,
            component: 'd-payment-type-dropdown-settings',
            props: {
                paymentTypes: paymentTypes.value
            }
        },
        { key: 'accountLabel', label: 'Libellé sur le compte', type: 'text', editable: true },
        {
            key: 'customer',
            label: 'Client',
            type: 'custom',
            editable: true,
            component: 'd-customer-dropdown-settings',
            idKey: 'id',
            nameKey: 'name',
            props: {
                isCommercial: false
            }
        },
        {
            key: 'commercial',
            label: 'Commercial',
            type: 'custom',
            editable: true,
            component: 'd-commercial-dropdown-settings',
            idKey: 'id',
            nameKey: 'name',
            props: {
                isCommercial: true
            }
        },
        { key: 'paymentAmountHt', label: 'Montant', type: 'number', editable: true },
        {
            key: 'currency',
            label: 'Devise',
            editable: true,
            type: 'custom',
            component: 'd-currency-settings',
            idKey: 'id',
            nameKey: 'name'
        }
    ]);

    const processedColumns = computed(() => {
        return columns.value.map(col => {
            const newCol = { ...col };
            if (newCol.component === 'd-currency-settings') {
                newCol.props = {
                    ...(newCol.props || {}),
                    currencies: currencies.value
                };
            } else if (newCol.component === 'd-customer-dropdown-settings') {
                newCol.props = {
                    ...(newCol.props || {}),
                    customers: customers.value
                };
            } else if (newCol.component === 'd-commercial-dropdown-settings') {
                newCol.props = {
                    ...(newCol.props || {}),
                    commercials: commercials.value
                };
            } else if (newCol.component === 'd-payment-type-dropdown-settings') {
                newCol.props = {
                    ...(newCol.props || {}),
                    paymentTypes: paymentTypes.value
                };
            }
            return newCol;
        });
    });

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

    watch(
        () => filter.value.customer,
        async (newCustomer) => {
            const customerId = extractCustomerId(newCustomer);

            await loadCommercialsForCustomer(customerId);

        }
    );

    const fetchData = async ({ page, itemsPerPage, sort }) => {
        try {
            loading.value = true;
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

            return {
                response: {
                    data: rows.value,
                    meta: {
                        total_items: data.total || data.count || payments.length,
                        total_pages: Math.ceil((data.total || data.count || payments.length) / itemsPerPage),
                        current_page: page,
                        items_per_page: itemsPerPage
                    }
                }
            };
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
            const payload = {
                dateOfReceipt: formatDateForApi(row.dateOfReceipt),
                paymentMethod: row.paymentMethod?.id ?? row.paymentMethod ?? null,
                accountLabel: row.accountLabel || null,
                paymentAmountHt: row.paymentAmountHt !== null && row.paymentAmountHt !== undefined
                    ? String(row.paymentAmountHt)
                    : null,
                currency: row.currency?.id ?? row.currency ?? null,
                customerId: row.customer?.id ?? null,
                commercialId: row.commercial?.user_id ?? null
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
            const payload = {
                dateOfReceipt: formatDateForApi(row.dateOfReceipt),
                paymentMethod: row.paymentMethod || null,
                accountLabel: row.accountLabel || null,
                paymentAmountHt: row.paymentAmountHt !== null && row.paymentAmountHt !== undefined
                    ? String(row.paymentAmountHt)
                    : null,
                currency: row.currency?.id ?? row.currency ?? null,
                customer: row.customer?.customerId ?? row.customer ?? null,
                commercial: row.commercial?.commercialId ?? row.commercial ?? null
            };

            const { data } = await axiosInstance.post('/api/order-payments', payload);
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
        if (dataGrid.value?.refresh) {
            await dataGrid.value.refresh();
        }
    };

    const doReset = async () => {
        filter.value = { ...filterOrderPayment };
        filterActive.value = false;
        Helper.removeStorage(FILTER_ORDER_PAYMENT_STORAGE_NAME);
        if (dataGrid.value?.refresh) {
            await dataGrid.value.refresh();
        }
    };

    const goToNewReglement = () => {
        router.push({ name: 'reglement_create' });
    };

    const handleView = (row) => {
        console.log('View clicked, row:', row);
        if (!row?.id) {
            console.error('Invalid row data:', row);
            return;
        }
        router.push({ name: 'reglement_view', params: { id: row.id } });
    };

    const handleSortChange = (sort) => {
        params.orderBy = sort.field;
        params.orderWay = sort.direction;
        dataGrid.value?.refresh();
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
</style>
