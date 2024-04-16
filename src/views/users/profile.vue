<template>
    <div class="layout-px-spacing mt-5">
        <d-page-title title="Gestion des profiles" icon="user"></d-page-title>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="panel br-6 p-2">
                    <d-panel-title title="Mise à jour profile" link="/users/profiles"></d-panel-title>
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
                                        <button class="btn btn-secondary w-auto ps-5 pe-5 btn-lg"
                                            @click.prevent="updateProfile"> Enregistrer
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
                    <d-panel-title title="Listes des permission"></d-panel-title>
                    <div class="row align-items-center  mb-3 p-2">
                        <div class="container pricing-table">
                            <div id="pricingWrapper" class="row">
                                <template v-for="(permission, key) in permissions" :key="key">
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="card stacked mt-3">
                                            <div class="card-header pt-0">
                                                <h3 class="card-title mt-3 mb-1">{{ $t(key) }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-group list-group-minimal mb-3">
                                                    <template v-for="perm in permission" :key="perm.permission_id">
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-center">
                                                            <div
                                                                class="checkbox-success custom-control custom-checkbox text-color rounded">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    :id="'permission_' + perm.permission_id"
                                                                    :checked="hasPermession(perm.name)"
                                                                    @change="handleChange(perm.permission_id, hasPermession(perm.name))" />
                                                                <label class="custom-control-label"
                                                                    :for="'permission_' + perm.permission_id"> {{
                                                                    $t(perm.name) }} </label>
                                                            </div>
                                                        </li>

                                                    </template>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue';
import '../../assets/sass/scrollspyNav.scss';
import axiosInstance from '../../config/http';
import dPageTitle from '../../components/common/d-page-title.vue';
import dPanelTitle from '../../components/common/d-panel-title.vue';
import { useMeta } from '/src/composables/use-meta';
import { useRoute } from 'vue-router';

useMeta({ title: 'Profiles list' });

const loading = ref(true);
const filterActive = ref(false);
const searchName = ref(null);
const name = ref(null);

const params = reactive({
    current_page: 1,
    pagesize: 5,
});
const route = useRoute();
const profile_id = route.params.id;
const profile = ref(null)
const permissions = ref(null)


onMounted(() => {
    getProfile();
    getPermessions();
});

const getProfile = async () => {
    try {
        const response = await axiosInstance.get(`api/profile/${profile_id}`);
        profile.value = response.data.response
        name.value = profile.value.name
    } catch (error) {
        window.showMessage("Ce profile n'existe pas!", 'error');
    }
};

const getPermessions = async () => {
    try {
        const response = await axiosInstance.get(`api/permissions`);
        permissions.value = response.data.response.permissions
    } catch (error) {
        window.showMessage("Erreur de chargement de la liste des permissions!", 'error');
    }
};

const hasPermession = (permission) => {
    return profile.value.permissions.indexOf(permission) > -1
};

const handleChange = async (id, checked) => {
    if (checked) {
        await removePermissionFromProfile(id)
    } else {
        await givePermissionToProfile(id)
    }
}

const updateProfile = async () => {
    try {
        const response = await axiosInstance.post(`api/profile/${profile_id}/update`, {
            name: name.value
        });
        window.showMessage("Profile a été enregistré avec succès");
    } catch (error) {
        if (error.response.data && error.response.data.detail === "duplicate") {
            window.showMessage("le profile dont le nom " + name.value + " existe déja !", 'error');
        } else {
            window.showMessage("Veuillez vérifier le nom de votre profile !", 'error');
        }

    }
};

const givePermissionToProfile = async (permissionId) => {
    try {
        loading.value = true;
        const response = await axiosInstance.post('api/givePermissionToProfile', {
            permissionId: permissionId,
            profileId: parseInt(profile.value.id)
        });
        window.showMessage("L'ajout de la permission a été effectué avec succès.");
        await getProfile();
    } catch {
        window.showMessage("Échec lors de l'ajout de la permission.", 'error');
    }

    loading.value = false;
};

const removePermissionFromProfile = async (permissionId) => {
    try {
        loading.value = true;
        const response = await axiosInstance.post('api/removePermissionFromProfile', {
            permissionId: permissionId,
            profileId: parseInt(profile.value.id)
        });
        window.showMessage("La suppression de la permission a été effectué avec succès.");
        await getProfile();
    } catch {
        window.showMessage("Échec lors de la suppression de la permission.", 'error');
    }

    loading.value = false;
};
</script>
