<template>
    <div class="form full-form auth-cover">
        <div class="form-container">
            <div class="form-form">
                <div class="form-form-wrap bg-grey">
                    <div class="form-container">
                        <div class="form-content">
                            <h1 class="">{{ $t('Réinitialisation de mot de passe') }}</h1>
                            <p class="signup-link">{{ $t('Veuillez saisir votre nouveau mot de passe !') }}</p>
                            <div v-if="errorMessage" class="alert alert-light-danger alert-dismissible border-0 mb-2 mt-2" role="alert">
                                <strong>{{ $t(errorMessage) }}</strong>
                            </div>
                            <div v-if="successMessage" class="alert alert-light-success alert-dismissible border-0 mb-2 mt-2" role="alert">
                                <strong>{{ successMessage }}</strong>
                            </div>
                            <form class="text-start">
                                <div class="form">
                                    <div id="password-field" class="field-wrapper input mb-2">
                                        <d-password v-model="password" ></d-password>
                                    </div>
                                    <div id="re-password-field" class="field-wrapper input mb-2">
                                        <d-password :id="'rePassword'" v-model="rePassword" :placeholder="'Re-Password'"></d-password>
                                    </div>
                                    <div class="d-sm-flex justify-content-end">
                                        <div class="field-wrapper">
                                            <button @click.prevent="doSubmit" class="btn btn-primary" :disabled="loginLoad || rePassword !== password">
                                                <btn-load-icon v-if="loginLoad"></btn-load-icon>
                                                {{ $t('Valider') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-image">
                <div class="l-image">
                    <img src="/assets/images/logo/DIURNE.png">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import userService from "../../composables/user-service";
import btnLoadIcon from "../../components/common/svg/btn-load-icon.vue";
import DPassword from "../../components/base/d-password.vue";
import "/src/assets/sass/authentication/auth.scss";
import { useMeta } from "/src/composables/use-meta";

export default {
    components:{
        btnLoadIcon,
        DPassword
    },
    setup(){
        useMeta({ title: "Register Cover" });
    },
    data(){
        return {
            token: this.$route.params.token,
            rePassword: null,
            password: null,
            errorMessage: null,
            loginLoad: false,
            successMessage : null,
        }
    },
  methods: {
    async doSubmit() {
        this.errorMessage = null;
        this.successMessage = null;
      try { 
        this.loginLoad = true;
        const res = await userService.passReset(this.token, {password: this.password});
        this.successMessage = 'Votre mot de passe a été réinitialisé avec succès.';
      } catch (error) {
        this.errorMessage = error.message;
      }

      this.loginLoad = false;
    }
  } 
}
</script>

