<template>
    <div class="form full-form auth-cover">
        <div class="form-container">
            <div class="form-image">
                <div class="l-image">
                    <img src="/src/assets/images/logo/DIURNE.png">
                </div>
            </div>
            <div class="form-form">
                <div class="form-form-wrap bg-grey">
                    <div class="form-container">
                        <div class="form-content">
                            <h1 class="">
                                {{ $t('Connexion') }}
                            </h1>
                            <p class="signup-link"> {{ $t('Vous avez un compte ? Connectez-vous') }} </p>
                            <div v-if="errorMessage" class="alert alert-light-danger alert-dismissible border-0 mb-2 mt-2" role="alert">
                                <strong>{{ errorMessage }}</strong>
                            </div>
                            <form class="text-start">
                                <div class="form">
                                    <div id="username-field" class="field-wrapper input">
                                        <input type="text" class="form-control" placeholder="Email" v-model="email"/>
                                    </div>
                                    <div id="password-field" class="field-wrapper input mb-2">
                                        <d-password v-model="password" ></d-password>
                                    </div>
                                    <div class="d-sm-flex justify-content-between">
                                        <div class="field-wrapper text-center keep-logged-in">
                                            <div class="checkbox-outline-primary custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" value="true"
                                                    id="chkRemember" />
                                                <label class="custom-control-label" for="chkRemember">{{$t('Restez connectés')}}</label>
                                            </div>
                                        </div>
                                        <div class="field-wrapper text-right">
                                            <button @click.prevent="login" class="btn btn-primary text-uppercase mb-4 me-3" :disabled="loginLoad">
                                                <btn-load-icon v-if="loginLoad"></btn-load-icon>
                                                {{$t('Connexion')}} 
                                            </button>
                                            <router-link to="/pass-recovery" class="forgot-pass-link">{{$t('mot de passe oublié?')}}</router-link>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import "/src/assets/sass/authentication/auth.scss";
import "/src/assets/sass/font-icons/fontawesome/css/regular.css";
import "/src/assets/sass/font-icons/fontawesome/css/fontawesome.css";
import btnLoadIcon from "../../components/common/svg/btn-load-icon.vue";
import DPassword from "../../components/base/d-password.vue";
import userService from "../../composables/user-service";
import { TOKEN_STORAGE_NAME } from "../../composables/constants";
import { ref } from 'vue';
import { useStore } from 'vuex';

import { useMeta } from "/src/composables/use-meta";
useMeta({ title: "Login" });

const store = useStore();

const email = ref(null);
const password = ref(null);
const errorMessage = ref(null);
const loginLoad = ref(false);

const login = async  () => {
      try {
        loginLoad.value = true;
        const userData = await userService.doLogin({
          email: email.value,
          password: password.value
        });

        localStorage.setItem(TOKEN_STORAGE_NAME,userData.token);
        store.commit('setIsAuthenticate',true);

        const info = await userService.getUserInfoApi(userData.user_id);
        userService.setUserInfo(info);

        window.location.href = '/home';
      } catch (error) {
        loginLoad.value = false;
        errorMessage.value = error.message;
      }
    }
</script>
