<template>
    <div class="form full-form auth-cover">
        <div class="form-container">
            <div class="form-form">
                <div class="form-form-wrap bg-grey">
                    <div class="form-container">
                        <div class="form-content">
                            <h1 class="">{{ $t('Récupération de mot de passe') }}</h1>
                            <p class="signup-link">{{ $t('Entrez votre email et les instructions vous seront envoyées!') }}</p>
                            <div v-if="errorMessage"
                                class="alert alert-light-danger alert-dismissible border-0 mb-2 mt-2" role="alert">
                                <strong>{{ errorMessage }}</strong>
                            </div>
                            <div v-if="successMessage"
                                class="alert alert-light-success alert-dismissible border-0 mb-2 mt-2" role="alert">
                                <strong>{{ successMessage }}</strong>
                            </div>
                            <form class="text-start">
                                <div class="form">
                                    <div id="email-field" class="field-wrapper input">
                                        <input type="email" class="form-control" placeholder="Email" v-model="email" />
                                    </div>
                                    <div class="d-sm-flex justify-content-end">
                                        <div class="field-wrapper">
                                            <button @click.prevent="doSubmit" class="btn btn-primary"
                                                :disabled="loginLoad">
                                                <btn-load-icon v-if="loginLoad"></btn-load-icon>
                                                {{ $t('Réinitialiser') }}
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
                    <img src="/src/assets/images/logo/DIURNE.png">
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import userService from "../../composables/user-service";
import btnLoadIcon from "../../components/common/svg/btn-load-icon.vue";
import "/src/assets/sass/authentication/auth.scss";
import { useMeta } from "/src/composables/use-meta";

export default {
    components: {
        btnLoadIcon
    },
    setup(){
        useMeta({ title: "Password Recovery" });
    },
    data() {
        return {
            email: null,
            password: null,
            errorMessage: null,
            loginLoad: false,
            successMessage: null,
        }
    },
    methods: {
        async doSubmit() {
            this.errorMessage = null;
            this.successMessage = null;
            try {
                this.loginLoad = true;
                const res = await userService.passRecover({ email: this.email });
                this.successMessage = 'Un lien de réinitialisation de mot de passe a été envoyé à votre adresse e-mail avec succès.';
            } catch (error) {
                this.errorMessage = error.message;
            }

            this.loginLoad = false;
        }
    }
}
</script>
