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
                                                    <d-input label="Nom" v-model="userObj.lastname" :required="true" :error="error.lastname"> </d-input>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="row align-items-center">
                                                    <d-input label="E-mail" v-model="userObj.email" :required="true" :error="error.email"> </d-input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-2 mt-4">
                                            <div class="col-6">
                                                <d-input label="Prénom" v-model="userObj.firstname" :required="true" :error="error.firstname"> </d-input>
                                            </div>
                                            <div class="col-6">
                                                <d-profile v-model="userObj.profile" :error="error.profileId"></d-profile>
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
import { useRoute, useRouter } from 'vue-router';
import dProfile from "../../components/common/d-profile.vue";
import dPassword from "../../components/base/d-password.vue";
import "/src/assets/sass/scrollspyNav.scss";
import "/src/assets/sass/users/account-setting.scss";
import dPageTitle from '../../components/common/d-page-title.vue';
import dPanelTitle from '../../components/common/d-panel-title.vue'

import { useMeta } from "/src/composables/use-meta";
import axiosInstance from "../../config/http";
import userService from "../../Services/user-service";
import dInput from "../../components/base/d-input.vue";
import {formatErrorViolations} from "../../composables/global-methods.js";

useMeta({ title: "Account Setting" });

const route = useRoute();
const { id } = route.params;
const router = useRouter();
const error = ref({})

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
        error.value = {}
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
        router.push({name: "users"}); 
    } catch (e) {
        if(e.response.data.violations){
            error.value = formatErrorViolations(e.response.data.violations)
            if(error.value.email && error.value.email.includes("is not a valid email")){
                error.value.email = "Veuillez entrer un email valide."
            }
        }
        
        if(e.response.data.detail === "duplicate"){
            window.showMessage("Un compte utilisateur avec cet email existe déjà.", 'error');
            error.value.email = "Email existe déjà."
        }else{
            window.showMessage("Quelque chose s'est mal passé.", 'error'); 
        }
        
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
