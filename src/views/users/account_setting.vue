<template>
    <div class="layout-px-spacing mt-5">

        <d-page-title title="Gestion des utilisateurs" icon="users"></d-page-title>

        <div class="account-settings-container layout-top-spacing">
            <div class="account-content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                        <form id="general-info" class="section general-info">
                            <div class="info">
                                <d-panel-title
                                    :title="(id) ? 'Mise à jour utilisateur' : 'Nouveau utilisateur'" link="/users"></d-panel-title>
                                <div class="row">
                                    <div class="col-7">
                                        <div class="row m-2 mb-4">
                                            <div class="col-6">
                                                <div class="row align-items-center">
                                                    <div class="col-3"><label for="name" class="form-label">Nom:</label>
                                                    </div>
                                                    <div class="col-9"><input v-model="userObj.lastname" id="name"
                                                            type="text" class="form-control" /></div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="row align-items-center">
                                                    <div class="col-3"><label for="login"
                                                            class="form-label">Login:</label></div>
                                                    <div class="col-9"><input v-model="userObj.email" id="login"
                                                            type="text" class="form-control" /></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-2 mt-4">
                                            <div class="col-6">
                                                <div class="row align-items-center">
                                                    <div class="col-3"><label for="surname"
                                                            class="form-label">Prénom:</label></div>
                                                    <div class="col-9"><input v-model="userObj.firstname" id="surname"
                                                            type="text" class="form-control" /></div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <d-profile v-model="userObj.profile"></d-profile>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="row m-2">
                                            <d-password v-model="userObj.password"></d-password>
                                        </div>
                                        <div class="row m-2 mt-4">
                                            <button class="btn btn-primary w-auto btn-lg ps-5 pe-5"
                                                @click.prevent="save">
                                                Enregistrer </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import { useRoute } from 'vue-router';
import dProfile from "../../components/common/d-profile.vue";
import dPassword from "../../components/base/d-password.vue";
import "/src/assets/sass/scrollspyNav.scss";
import "/src/assets/sass/users/account-setting.scss";
import dPageTitle from '../../components/common/d-page-title.vue';
import dPanelTitle from '../../components/common/d-panel-title.vue'

import { useMeta } from "/src/composables/use-meta";
import axiosInstance from "../../config/http";
import userService from "../../Services/user-service";
import user from "../../store/modules/user";

useMeta({ title: "Account Setting" });

const route = useRoute();
const { id } = route.params;

if (id) {
    onMounted(() => {
        getcurrentUser();
    });
}

const userObj = reactive({
    firstname: null,
    lastname: null,
    email: null,
    profile: null,
    password: null
});

const save = async () => {
    try {
        if (id) {
            const res_user = await axiosInstance.put(`/api/user/${id}/update`, {
                firstname: userObj.firstname,
                lastname: userObj.lastname,
                email: userObj.email,
                password: userObj.password
            })
        } else {
            const res_user = await axiosInstance.post('/api/createUser', {
                firstname: userObj.firstname,
                lastname: userObj.lastname,
                email: userObj.email,
                password: userObj.password
            })
        }


        const res_profile = await axiosInstance.post('/api/AssignProfileToUser', {
            email: userObj.email,
            profileId: parseInt(userObj.profile)
        })

        window.showMessage("L'utilisateur a été enregistré avec succès.");
        location.href = "/users"
    } catch (error) {
        window.showMessage("Quelque chose s'est mal passé.", 'error');
    }

};

const getcurrentUser = async () => {
    try {
        const res = await userService.getUserInfoApi(id);
        userObj.firstname = res.firstname;
        userObj.lastname = res.lastname;
        userObj.email = res.email;
    } catch { }
}
</script>
<style>
.eye-password {
    top: inherit !important;
    right: inherit !important;
    margin-top: 10px;
    margin-left: 33%;
}

.general-info .info h6 {
    margin: 7px 0px !important;
}
</style>
