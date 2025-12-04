<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title :title="'Image commande'"></d-page-title>
        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-2">
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
                            <d-input label="Commande" v-model="filter.command"></d-input>
                        </div>
                        <div class="row">
                            <d-designer-dropdown label="Designer" v-model="filter.designerId"
                            ></d-designer-dropdown>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="row">
                            <d-input label="Status" v-model="filter.status"></d-input>
                        </div>
                        <div class="row">
                            <d-collections-dropdown v-model="filter.collection" :hideBtn="true"
                                                    :showOnlyDropdown="true"></d-collections-dropdown>
                        </div>
                        <div class="row">
                            <d-model-dropdown v-model="filter.model" :hideBtn="true"
                                              :showOnlyDropdown="true"></d-model-dropdown>
                        </div>
                        <div class="row">
                            <d-qualities-dropdown v-model="filter.quality" :hideBtn="true"
                                                  :showOnlyDropdown="true"></d-qualities-dropdown>
                        </div>
                        <div class="row">
                            <d-location-dropdown v-model="filter.location" :hideBtn="true"
                                                 :showOnlyDropdown="true"></d-location-dropdown>
                        </div>
                        <div class="row mt-2">
                            <div class="col-auto" v-if="filterActive">
                                <button class="btn btn-outline-secondary btn-reset" @click.prevent="doReset">Reset
                                    filtre
                                </button>
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
                                    <button class="btn btn-outline-custom p-2 dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Cacher / Montrer Colonnes
                                    </button>
                                    <ul class="dropdown-menu p-2">
                                        <li v-for="col in cols" :key="col.field">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" :checked="!col.hide"
                                                       :id="col.field" @change="col.hide = !$event.target.checked"
                                                       :name="col.field" />
                                                <label class="custom-control-label text-black" :for="col.field">
                                                    {{ col.title }} </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <d-btn-fullscreen></d-btn-fullscreen>
                        </div>
                        <vue3-datatable :rows="rows" :columns="cols" :loading="loading" :isServerMode="true"
                                        :sortColumn="params.orderBy" :sortDirection="params.orderWay"
                                        :totalRows="total_rows" :page="params.current_page" :pageSize="params.pagesize"
                                        :pageSizeOptions="[10, 25, 50, 75, 100]"
                                        noDataContent="Aucun Image commande trouvé."
                                        paginationInfo="Affichage de {0} à {1} sur {2} entrées" :sortable="true"
                                        @change="changeServer" class="advanced-table text-nowrap">
                            <template #image="data">
                                <div class="d-flex justify-content-center">
                                    <img :src="$Helper.getImagePath(data.value.images?.[0]?.attachment)"
                                         alt="Carpet Image" class="img-thumbnail" style="width: 80px; height: auto;">
                                </div>
                            </template>
                            <template #collection="data">
                                {{ data.value.carpetSpecification?.collection }}
                            </template>
                            <template #model="data">
                                {{ data.value.carpetSpecification?.model }}
                            </template>
                            <template #quality="data">
                                {{ data.value.carpetSpecification?.quality }}
                            </template>
                            <template #image_name="data">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        {{ data.value.images?.[0]?.image_reference }}
                                    </div>
                                    <div>
                                        <vue-feather type="search" stroke-width="1" class="cursor-pointer"
                                                     @click="goToImageDetails(data.value.id)"></vue-feather>
                                    </div>
                                </div>
                            </template>
                            <template #width="data">
                                {{ data.value.carpetSpecification?.dimensions?.[1]?.values[0].value }}
                            </template>
                            <template #height="data">
                                {{ data.value.carpetSpecification?.dimensions?.[0]?.values[0].value }}
                            </template>

                            <template #contremarque="data">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ data.value.carpetDesignOrder.contremarque }}</strong>
                                    <router-link
                                        :to="'/projet/contremarques/manage/' + data.value.carpetDesignOrder.contremarque_id"
                                        v-if="$hasPermission('update contremarque')">
                                        <vue-feather type="search" stroke-width="1"
                                                     class="cursor-pointer"></vue-feather>
                                    </router-link>
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
    import dPageTitle from '../../../components/common/d-page-title.vue';
    import dDesignerDropdown from '../../../components/common/d-designer-dropdown.vue';
    import dCollectionsDropdown from '../../../components/projet/contremarques/dropdown/d-collections-dropdown.vue';
    import dModelDropdown from '../../../components/projet/contremarques/dropdown/d-model-dropdown.vue';
    import dQualitiesDropdown from '../../../components/projet/contremarques/dropdown/d-qualities-dropdown.vue';
    import dLocationDropdown from '../../../components/projet/contremarques/dropdown/d-location-dropdown.vue';
    import VueFeather from 'vue-feather';
    import Vue3Datatable from '@bhplugin/vue3-datatable';
    import dModalManageDi from '../../../components/projet/contremarques/_Partials/d-modal-manage-di.vue';
    import axiosInstance from '../../../config/http';
    import { ref, reactive, onMounted } from 'vue';
    import { FILTER_IMAGE_COMMAND_STORAGE_NAME, filterImageCommand } from '../../../composables/constants';
    import { useMeta } from '/src/composables/use-meta';
    import { Helper } from '../../../composables/global-methods';
    import { useRoute, useRouter } from 'vue-router';

    useMeta({ title: 'Contremarque' });
    const route = useRoute();
    const router = useRouter();
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

    const filter = ref(Object.assign({}, filterImageCommand));
    const filterActive = ref(false);
    const rows = ref(null);
    const selectedDiId = ref(0);
    const contremarqueId = ref(null);

    const cols = ref([
        { field: 'id', title: 'ID' },
        { field: 'image', title: 'Image' },
        { field: 'image_name', title: 'Nom' },
        { field: 'Rn', title: 'Rn' },
        { field: 'collection', title: 'collection' },
        { field: 'model', title: 'Modèle' },
        { field: 'quality', title: 'Qualité' },
        { field: 'width', title: 'Longueur' },
        { field: 'height', title: 'largeur' },
        { field: 'contremarque', title: 'contremarque' }
    ]) || [];
    const getImageUrl = (imageName) => {
        if (!imageName) return ''; // Handle missing images
        return `/uploads/attachments/${imageName}`;
    };
    onMounted(() => {
        const f = Helper.getStorage(FILTER_IMAGE_COMMAND_STORAGE_NAME);
        if (f && Helper.hasDefinedValue(f)) {
            filter.value = f;
            filterActive.value = true;
        }
        contremarqueId.value = route.query.contremarqueId || null;
        getDI();
    });
    const getDI = async () => {
        try {
            loading.value = true;
            let url = `/api/image-commands?page=${params.current_page}&limit=${params.pagesize}`;
            /*if(params.orderBy){
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
            }*/

            url += getFilterParams();
            const response = await axiosInstance.get(url);
            const data = response.data;
            total_rows.value = data.response.meta.total;
            rows.value = data.response.data;
            console.log(rows.value[0]);
        } catch {
        }

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
        Helper.setStorage(FILTER_IMAGE_COMMAND_STORAGE_NAME, filter.value);
        getDI();
    };
    const getFilterParams = () => {

        let param = '';
        if (filter.value.customer) {
            param += '&customer=' + filter.value.customer;
        }
        if (filter.value.contremarque) {
            param += '&contremarque=' + filter.value.contremarque;
        }
        if (filter.value.commercial) {
            param += '&commercial=' + filter.value.commercial;
        }
        if (filter.value.command) {
            param += '&command=' + filter.value.command;
        }
        if (filter.value.status) {
            param += '&status=' + filter.value.status;
        }
        if (filter.value.model) {
            param += '&model=' + filter.value.model;
        }
        if (filter.value.collection) {
            param += '&collection=' + filter.value.collection;
        }
        if (filter.value.quality) {
            param += '&quality=' + filter.value.quality;
        }
        if (filter.value.location) {
            param += '&location=' + filter.value.location;
        }
        if (filter.value.designerId) {
            param += '&designerId=' + filter.value.designerId;
        }
        return param;
    };

    const doReset = () => {
        filterActive.value = false;
        filter.value = Object.assign({}, filterImageCommand);
        Helper.setStorage(FILTER_IMAGE_COMMAND_STORAGE_NAME, filter.value);
        getDI();
    };

    const goToContreMarqueDetails = (id_contremarque) => {
        router.push({ name: 'projectsListManage', params: { id: id_contremarque } });
    };
    const goToImageDetails = (id) => {
        router.push({ name: 'imagesCommadeDetails', params: { id: id } });
    };
    const handleClose = () => {
        selectedDiId.value = 0;
    };

</script>
<style>
    .text-size-16 {
        font-size: 16px !important;
    }

</style>
