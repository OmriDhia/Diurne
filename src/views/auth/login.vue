<template>
    <div class="form full-form auth-cover">
        <div class="form-container">
            <div class="form-image">
                <div class="l-image">
                    <logo></logo>
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
                                        <input :type="showPassword ? 'text' : 'password'" class="form-control"
                                            placeholder="Password" v-model="password" />
                                        <span class="eye-password cursor-pointer" @click="showPassword = !showPassword"
                                            data-bs-toggle="tooltip" data-bs-placement="right"
                                            :title="showPassword ? 'Hide Password' : 'Show Password'">
                                            <i :class="['far', showPassword ? 'fa-eye' : 'fa-eye-slash']"></i>
                                        </span>

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
                                                <svg v-if="loginLoad"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="feather feather-loader spin me-2"
                                                >
                                                    <line x1="12" y1="2" x2="12" y2="6"></line>
                                                    <line x1="12" y1="18" x2="12" y2="22"></line>
                                                    <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                                                    <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                                                    <line x1="2" y1="12" x2="6" y2="12"></line>
                                                    <line x1="18" y1="12" x2="22" y2="12"></line>
                                                    <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                                                    <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                                                </svg>
                                                {{$t('Connexion')}} 
                                            </button>
                                            <router-link to="/auth/pass-recovery" class="forgot-pass-link">{{$t('mot de passe oublié?')}}</router-link>
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

import { useMeta } from "/src/composables/use-meta";
import { ref } from "vue";
useMeta({ title: "Login Cover" });

const showPassword = ref(false)
</script>

<script>
import store from "../../store";
import router from "../../router";
import userService from "../../composables/user-service";
import logo from "../../components/common/svg/logo.vue";
import { TOKEN_STORAGE_NAME } from "../../composables/constants";

export default {
    components:{
        logo
    },
    data(){
        return {
            email: null,
            password: null,
            errorMessage: null,
            loginLoad: false
        }
    },
  methods: {
    async login() {
      try {
        this.loginLoad = true;
        const userData = await userService.doLogin({
          email: this.email,
          password: this.password
        });
        localStorage.setItem(TOKEN_STORAGE_NAME,userData.token);
        store.commit('setIsAuthenticate',true);
        window.location.href = '/home';
      } catch (error) {
        this.loginLoad = false;
        this.errorMessage = error.message;
      }
    }
  } 
}
</script>
