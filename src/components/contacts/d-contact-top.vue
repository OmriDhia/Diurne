<template>
    <div class="col-sm-12 col-md-6">
        <div class="row pe-2">
            <div id="toggleAccordion" class="accordion">
                <div class="card mb-1">
                    <header class="card-header" role="tab">
                        <section class="mb-0 mt-0">
                            <div class="collapsed" role="menu" data-bs-toggle="collapse" data-bs-target="#headingOne1"
                                 aria-expanded="true" aria-controls="headingOne1">
                                Nouveau contact
                                <div class="icons">
                                    <vue-feather type="chevron-down" size="14"></vue-feather>
                                </div>
                            </div>
                        </section>
                    </header>
                    <div id="headingOne1" class="collapse show" aria-labelledby="headingOne1"
                         data-bs-parent="#toggleAccordion">
                        <div class="card-body">
                            <div class="row p-1 align-items-center">
                                <div class="col-sm-12 col-md-6">
                                    <d-gender required="true" v-model="data.gender_id"
                                              :error="error.gender_id"></d-gender>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <d-input label="Tel. portable" v-model="data.mobile_phone"
                                             :error="error.mobile_phone"></d-input>
                                </div>
                            </div>
                            <div class="row p-1 align-items-center">
                                <div class="col-sm-12 col-md-6">
                                    <d-input required="true" label="Nom" v-model="data.lastname"
                                             :error="error.lastname"></d-input>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <d-input label="Tél. fixe" v-model="data.phone" :error="error.phone"></d-input>
                                </div>
                            </div>
                            <div class="row p-1 align-items-center">
                                <div class="col-sm-12 col-md-6">
                                    <d-input label="Prénom" v-model="data.firstname" :error="error.firstname"></d-input>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <d-input label="Email" v-model="data.email" :error="error.email"></d-input>
                                </div>
                            </div>
                            <div class="row align-items-center justify-content-between">
                                <div class="col-md-auto">
                                    <div class="row">
                                        <div class="col-md-auto">
                                            <div
                                                class="checkbox-primary custom-control custom-checkbox text-color rounded">
                                                <input type="checkbox" v-model="data.mailing"
                                                       class="custom-control-input" id="contactAdd1" />
                                                <label class="custom-control-label" for="contactAdd1"> Mailing </label>
                                            </div>
                                        </div>
                                        <div class="col-md-auto">
                                            <div
                                                class="checkbox-primary custom-control custom-checkbox text-color rounded">
                                                <input type="checkbox" v-model="data.mailing_with_calligraphie"
                                                       class="custom-control-input" id="contactAdd2" />
                                                <label class="custom-control-label" for="contactAdd2"> Mailing
                                                    calligraphique </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-auto">
                                    <div class="row">
                                        <div class="col-auto p-1">
                                            <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle"
                                                    @click="addContact">
                                                <vue-feather type="save" size="14"></vue-feather>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <template v-for="(item, index) in props.contactData">
                    <div class="card mb-1">
                        <header class="card-header" role="tab">
                            <section class="mb-0 mt-0">
                                <div class="" role="menu" data-bs-toggle="collapse" :data-bs-target="'#contact'+index"
                                     aria-expanded="false" :aria-controls="'contact'+index">
                                    {{ item.firstname + ' ' + item.lastname }}
                                    <div class="icons">
                                        <vue-feather type="chevron-down" size="14"></vue-feather>
                                    </div>
                                </div>
                            </section>
                        </header>
                        <div :id="'contact'+index" class="collapse" :aria-labelledby="'contact'+index"
                             data-bs-parent="#toggleAccordion">
                            <div class="card-body">
                                <d-contact-form :contactData="item" :customerId="props.customerId"
                                                :index="index"></d-contact-form>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        <div class="row mt-2 pe-2 justify-content-end">
            <d-btn-outlined icon="plus" label="Ajouter" @click.prevent="addNewContact"></d-btn-outlined>
        </div>
    </div>
</template>

<script setup>
    import { defineProps, ref, watch, watchEffect } from 'vue';
    import axiosInstance from '../../config/http';
    import VueFeather from 'vue-feather';
    import dContactForm from './_partial/d-contact-form.vue';
    import dGender from '../../components/common/d-gender.vue';
    import dInput from '../../components/base/d-input.vue';
    import { formatErrorViolations } from '../../composables/global-methods';
    import '../../assets/sass/components/tabs-accordian/custom-accordions.scss';
    import dBtnOutlined from '../base/d-btn-outlined.vue';

    const props = defineProps({
        contactData: {
            type: Array,
            default: []
        },
        customerId: {
            type: Number
        }
    });

    const data = ref({
        gender_id: 0,
        firstname: '',
        lastname: '',
        email: '',
        mailing: true,
        mailing_with_calligraphie: false,
        phone: '',
        mobile_phone: '',
        fax: '',
        customerId: props.customerId
    });

    const error = ref({});
    const emit = defineEmits(['addContact']);
    const addContact = async () => {
        try {
            if (props.customerId) {
                error.value = {};
                const res = await axiosInstance.post('api/createContact/' + props.customerId, data.value);
                window.showMessage('Ajout avec succées.');
                emit('addContact', true);
                window.location.reload();
            }
        } catch (e) {
            let errorMessage = e?.message ?? "Une erreur s'est produite.";

            if (e?.response?.data?.violations) {
                error.value = formatErrorViolations(e.response.data.violations);
            }

            if (e?.response?.data?.message === 'There is a contact with same user') {
                error.value = {
                    ...error.value,
                    email: 'Cette adresse e-mail est déjà utilisée. Merci d\'en choisir une autre.'
                };
                errorMessage = "Cette adresse e-mail est déjà utilisée. Merci d'en choisir une autre.";
            } else if (e?.response?.data?.message) {
                errorMessage = e.response.data.message;
            }

            window.showMessage(errorMessage, 'error');
        }
    };

    const addNewContact = () => {
        const element = document.querySelector('#toggleAccordion > div:nth-child(1) > header > section > div');

        if (element.ariaExpanded === 'false') {
            element.click();
        }

        document.getElementById('toggleAccordion').scrollIntoView();
    };

</script>
<style>

</style>
