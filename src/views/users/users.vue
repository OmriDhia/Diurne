<template>
    <div class="layout-px-spacing mt-5">
        <d-page-title title="Gestion des utilisateurs" icon="users"></d-page-title>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="panel br-6 ps-3 p-0">
                    <div class="row layout-top-spacing pb-3 pt-3 ps-4" v-if="$hasPermission('create user')">
                        <button class="btn btn-primary w-auto ps-5 pe-5 btn-lg" @click.prevent="goToNewUser"> Nouveau
                            utilisateur
                        </button>
                    </div>
                    <div class="row layout-top-spacing  align-items-end  mb-5">
                        <div class="col-7">
                            <div class="row m-2 mb-4">
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-3"><label for="name" class="form-label">Nom:</label></div>
                                        <div class="col-9"><input v-model="search.lastname" id="name" type="text"
                                                                  class="form-control" /></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-3"><label for="login" class="form-label">Login:</label></div>
                                        <div class="col-9"><input v-model="search.email" id="login" type="text"
                                                                  class="form-control" /></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-2 mt-4">
                                <div class="col-6">
                                    <div class="row align-items-center">
                                        <div class="col-3"><label for="surname" class="form-label">Prénom:</label></div>
                                        <div class="col-9"><input v-model="search.firstname" id="surname" type="text"
                                                                  class="form-control" /></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <d-profile v-model="search.profile"></d-profile>
                                </div>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="row m-2">
                                <button class="btn btn-light btn-search" @click.prevent="doSearch"> Recherche</button>
                                <button v-if="filterActive" class="btn btn-outline-secondary btn-reset"
                                        @click.prevent="doReset">
                                    Reset filtre
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-5 ms-2 mb-5">
                        <div class="vue3-datatable w-75">
                            <vue3-datatable :rows="rows" :columns="cols" :loading="loading" :isServerMode="true"
                                            :totalRows="total_rows" :page="params.current_page"
                                            :pageSize="params.pagesize"
                                            :pageSizeOptions="[10, 20, 30, 40, 50]"
                                            noDataContent="Aucun utilisateur trouvé."
                                            paginationInfo="Affichage de {0} à {1} sur {2} entrées" :sortable="true"
                                            @change="changeServer" class="advanced-table text-nowrap">
                                <template #firstname="data">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ data.value.firstname + ' ' + data.value.lastname }}</strong>
                                        <router-link :to="'/users/account-setting/' + data.value.id"
                                                     v-if="$hasPermission('update user')">
                                            <vue-feather type="search" stroke-width="1"
                                                         class="cursor-pointer"></vue-feather>
                                        </router-link>

                                    </div>
                                </template>
                                <template #login="data">
                                    <strong>{{ data.value.email }}</strong>
                                </template>
                                <template #profile="data">
                                    <strong>{{ (data.value.profile && data.value.profile.name) ? data.value.profile.name : '--'
                                        }}</strong>
                                </template>
                                <template #delete="data">
                                    <d-delete
                                        :api="`/api/user/${data.value.id}`"
                                        message="Voulez-vous vraiment supprimer cet utilisateur ?"
                                        @isDone="getUsers"
                                    ></d-delete>
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
    import dProfile from '../../components/common/d-profile.vue';
    import VueFeather from 'vue-feather';
    import dPageTitle from '../../components/common/d-page-title.vue';
    import dDelete from '../../components/common/d-delete.vue';
    import { useRouter } from 'vue-router';

    const router = useRouter();

    import { useMeta } from '/src/composables/use-meta';

    useMeta({ title: 'Users list' });

    const loading = ref(true);
    const total_rows = ref(0);

    const params = reactive({
        current_page: 1,
        pagesize: 20
    });

    const search = reactive({
        firstname: null,
        lastname: null,
        email: null,
        profile: null
    });
    const rows = ref(null);
    const filterActive = ref(false);

    const cols = ref([
        { field: 'firstname', title: 'Nom & Prénom' },
        { field: 'login', title: 'Login' },
        { field: 'manager', title: 'Manager', sort: false },
        { field: 'salary', title: 'salarié actif', sort: false },
        { field: 'profile', title: 'Droit' },
        { field: 'delete', title: '', sort: false }
    ]) || [];

    onMounted(() => {
        getUsers();
    });
    const getUsers = async () => {
        try {
            loading.value = true;
            let url_users = `/api/users?page=${params.current_page}&itemPerPage=${params.pagesize}`;
            url_users += getFilterParams();
            const response = await axiosInstance.get(url_users);
            const data = await response.data.response;
            total_rows.value = data.count;
            rows.value = data.users;
        } catch {
        }

        loading.value = false;
    };
    const changeServer = (data) => {
        params.current_page = data.current_page;
        params.pagesize = data.pagesize;

        getUsers();
    };
    const doSearch = () => {
        filterActive.value = true;
        getUsers();
    };
    const getFilterParams = () => {
        let param = '';
        if (search.firstname) {
            param += '&filter[firstname]=' + search.firstname;
        }
        if (search.profile) {
            param += '&filter[profileId]=' + search.profile;
        }
        if (search.lastname) {
            param += '&filter[lastname]=' + search.lastname;
        }
        if (search.email) {
            param += '&filter[email]=' + search.email;
        }
        return param;
    };
    const doReset = () => {
        filterActive.value = false;
        search.email = null;
        search.firstname = null;
        search.lastname = null;
        search.profile = null;
        getUsers();
    };
    const goToNewUser = () => {
        router.push({ name: 'account-setting' });
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
