<template>
    <div class="layout-px-spacing mt-5">
        <d-page-title title="Gestion des profiles" icon="user"></d-page-title>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="panel br-6 p-2">
                    <div class="row layout-top-spacing  align-items-center">
                        <h6 class="">Nouveau Profile</h6>
                    </div>
                    <div class="row layout-top-spacing  align-items-center  mb-5">
                        <div class="col-8">
                            <div class="row m-2 align-items-center">
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-3"><label for="name" class="form-label">Nom:</label></div>
                                        <div class="col-9"><input v-model="name" id="name" type="text"
                                                class="form-control" /></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row ms-3">
                                        <button class="btn btn-secondary w-50 btn-lg" @click.prevent="addProfile"> Ajouter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="panel br-6 p-2">
                    <div class="row layout-top-spacing  align-items-center">
                        <h6 class="">Liste des Profiles</h6>
                    </div>
                    <div class="row layout-top-spacing  align-items-center  mb-3">
                        <div class="col-8">
                            <div class="row m-2 align-items-center">
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-3"><label for="name" class="form-label">Nom:</label></div>
                                        <div class="col-9"><input v-model="searchName" id="name" type="text"
                                                class="form-control" /></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row m-2">
                                        <button class="btn btn-light btn-search" @click.prevent="doSearch" :disabled="loading"> Recherche
                                        </button>
                                        <button v-if="filterActive" class="btn btn-outline-secondary btn-reset" @click.prevent="doReset">
                                        Reset filtre </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5 ms-2 mb-5">
                        <div class="vue3-datatable w-75">
                            <vue3-datatable :rows="rows" :columns="cols" :loading="loading" 
                            :page="params.current_page" :pageSize="params.pagesize"
                                :pageSizeOptions="[5, 10, 15]" noDataContent="Aucun profile trouvé."
                                paginationInfo="Affichage de {0} à {1} sur {2} entrées" 
                                :sortable="true"
                                class="advanced-table text-nowrap">
                                <template #profile_id="data">
                                    <strong>{{ data.value.profile_id }}</strong>
                                </template>
                                <template #name="data">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ data.value.name}}</strong>
                                        <router-link :to="'/users/profile/' + data.value.profile_id">
                                            <vue-feather type="search" stroke-width="1"
                                                class="cursor-pointer"></vue-feather>
                                        </router-link>

                                    </div>
                                </template>
                            </vue3-datatable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import axiosInstance from '../../config/http';
import VueFeather from 'vue-feather';
import dPageTitle from '../../components/common/d-page-title.vue';

import { useMeta } from '/src/composables/use-meta';
useMeta({ title: 'Profiles list' });

const loading = ref(true);
const filterActive = ref(false);
const searchName = ref(null);
const name = ref(null);

const params = reactive({
    current_page: 1,
    pagesize: 5,
});

const rows = ref(null);

const cols = ref([
    { field: 'profile_id', title: '#ID' },
    { field: 'name', title: 'Nom' }
]) || [];

onMounted(() => {
    getProfiles();
});

const addProfile = async () => {
    try {
        const response = await axiosInstance.post('api/createProfile',{
            name: name.value,
            discount: 0.0
        });
        getProfiles();
        name.value = null;
        window.showMessage("Profile a été enregistré avec succès");
    } catch(error) { 
        if(error.response.data && error.response.data.detail === "duplicate"){
            window.showMessage("le profile dont le nom " + name.value + " existe déja !",'error');
        }else{
            window.showMessage("Veuillez vérifier le nom de votre profile !",'error');
        }
           
    }
};

const getProfiles = async () => {
    try {
        loading.value = true;
        const response = await axiosInstance.get('api/profiles');
        const data = await response.data.response;
        rows.value = data.profiles;
    } catch { }

    loading.value = false;
};

const doSearch = () => {
    filterActive.value = true;
    rows.value = rows.value.filter( el => {
        return el.name.toLowerCase().includes(searchName.value.toLowerCase());
    })
};

const doReset = () => {
    filterActive.value = false;
    searchName.value = null
    getProfiles();
}
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

.btn-search {
    box-shadow: none !important;
    width: 180px;
    background-color: black !important;
    color: white !important;
}

.btn-reset {
    box-shadow: none !important;
    width: 150px;
    margin-left: 10px;
}
</style>
