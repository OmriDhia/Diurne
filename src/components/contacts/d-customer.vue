<template>
    <div class="col-sm-12 col-md-6">
        <div class="row p-2">
            <d-customer-type :required="true" :error="error.customerGroupId" v-model="customerGroup" />
        </div>
        <div class="row p-2" v-if="!isParticular">
            <d-input :required="true" label="Raison social" :error="error.social_reason"
                     v-model="data.social_reason"></d-input>
        </div>
        <div class="row p-2" v-if="isParticular">
            <d-input required="true" label="Nom" :error="error.lastname" v-model="data.lastname"></d-input>
        </div>
        <div class="row p-2" v-if="isParticular">
            <d-input label="Prénom" :error="error.firstname" v-model="data.firstname"></d-input>
        </div>
        <!-- <div class="row p-2">
            <d-input  :button="data.customer_id === 0" required="true" label="Code contact" v-model="data.code" :error="error.code">
                <template v-slot:input-button>
                    <button class="btn btn-success" @click.prevent="incrimentSuffix" v-if="data.customer_id === 0">Générer</button>
                </template>
            </d-input>
        </div> -->
        <div class="row p-2" v-if="!isParticular">
            <d-input label="CE TVA" v-model="data.tva_ce" :error="error.tva_ce"></d-input>
        </div>
        <div class="row p-2">
            <d-discount required="true" :error="error.discountTypeId" v-model="data.discountTypeId"></d-discount>
        </div>
        <div class="row p-2" v-if="!isParticular">
            <d-input label="Site web" :error="error.website" v-model="data.website"></d-input>
        </div>
        <div class="row p-2" v-if="!isParticular">
            <d-languages :error="error.website" v-model="data.mailingLanguageId"></d-languages>
        </div>
        <div class="row p-2">
            <d-customer-origin :required="true" :error="errorContactOrigin" v-model="data" />
        </div>
        <div class="row p-2" v-if="isAutreSelectedOriginType">
            <d-textarea label="Commentaire" v-model="data.commentaire" :error="errorCommentaire" :required="true"
                        :rows="5" type="textarea" class="custom-textarea" />
        </div>

        <div class="row p-2">
            <div class="col-md-auto">
                <div class="checkbox-primary custom-control custom-checkbox text-color rounded">
                    <input type="checkbox" v-model="data.is_agent" class="custom-control-input" id="isAgent" />
                    <label class="custom-control-label" for="isAgent"> Agent </label>
                </div>
            </div>
        </div>
        <div class="row align-content-end justify-content-end p-2 pe-3 mt-auto" v-if="data.customerGroupId !== 1">
            <div class="col-auto p-1">
                <button class="btn btn-custom pe-5 ps-5" @click="createCustomer" v-if="!props.customerData.customer_id">
                    Ajouter Client
                </button>
                <button class="btn btn-custom pe-5 ps-5" @click="createCustomer" v-if="props.customerData.customer_id">
                    Modifier Client
                </button>
            </div>
            <div class="col-auto p-1 pe-4" v-if="data.customer_id">
                <d-delete :api="`/api/customer/${data.customer_id}/delete`"></d-delete>
            </div>
        </div>
    </div>

    <!-- <div class="col-sm-12 col-md-6 d-flex flex-column" v-if="data.customerGroupId !== 1">
        <div class="row p-2">
            <d-customer-origin :required="true" :error="errorContactOrigin" v-model="data"  />
        </div>
        <div class="row p-2" v-if="isAutreSelectedOriginType">
            <d-textarea
                label="Commentaire"
                v-model="data.commentaire"
                :error="errorCommentaire"
                :required="true"
                :rows="5"
                type="textarea"
                class="custom-textarea"
            />
        </div>
        <div class="row align-content-end justify-content-end p-2 pe-3 mt-auto">
            <div class="col-auto p-1">
                <button class="btn btn-custom pe-5 ps-5" @click="createCustomer">Ajouter Client</button> 
            </div>
            <div class="col-auto p-1 pe-4" v-if="data.customer_id">
                <d-delete :api="`/api/customer/${data.customer_id}/delete`"></d-delete>
            </div>
        </div>
    </div> -->

    <d-contact-client-particulier
        v-if="data.customerGroupId === 1"
        :contactData="customerData.contactsData"
        :customerId="customerData.customer_id || null"
        :isParticular="isParticular"
        :required="true"
        :formData="localContactData"
        :error="validationContactErrors"
        @updateFormData="updateLocalContactData"
    />
    <div class="col-sm-12 col-md-12 d-flex flex-column" v-if="data.customerGroupId === 1">
        <div class="row align-content-end justify-content-end p-2 pe-3 mt-auto">
            <div class="col-auto p-1">
                <button class="btn btn-custom pe-5 ps-5" @click="createCustomer" v-if="!props.customerData.customer_id">
                    Ajouter Client
                </button>
                <button class="btn btn-custom pe-5 ps-5" @click="createCustomer" v-if="props.customerData.customer_id">
                    Modifier Client
                </button>
            </div>
            <div class="col-auto p-1 pe-4" v-if="data.customer_id">
                <d-delete :api="`/api/customer/${data.customer_id}/delete`"></d-delete>
            </div>
        </div>
    </div>
</template>
<script setup>
    import { ref, defineProps, watch, watchEffect } from 'vue';
    import axiosInstance from '../../config/http';
    import VueFeather from 'vue-feather';
    import dCustomerType from '../../components/common/d-customer-type.vue';
    import dCustomerOrigin from '../../components/common/d-customer-origin.vue';
    import dDiscount from '../../components/common/d-discount.vue';
    import dLanguages from '../../components/common/d-langages.vue';
    import dInput from '../../components/base/d-input.vue';
    import dDelete from '../common/d-delete.vue';
    import { formatErrorViolations } from '../../composables/global-methods';
    import { particularCustomerGroupId, publicDiscountTypeId } from '../../composables/constants';
    import dTextarea from '../../components/base/d-textarea.vue';
    import dContactClientParticulier from './d-contact-client-particulier.vue';

    import { useRouter } from 'vue-router';

    const props = defineProps({
        customerData: {
            type: Object,
            default: {}
        }
    });
    const DEFAULT_CONTACT = Object.freeze({
        gender_id: 0,
        email: '',
        phone: null,
        mobile_phone: null
    });

    const localContactData = ref({ ...DEFAULT_CONTACT });

    const syncLocalContactData = (customerPayload) => {
        if (
            !customerPayload ||
            !Array.isArray(customerPayload.contactsData) ||
            customerPayload.contactsData.length === 0
        ) {
            localContactData.value = { ...DEFAULT_CONTACT };
            return;
        }

        const primaryContact = customerPayload.contactsData[0];

        localContactData.value = {
            gender_id: primaryContact?.gender_id ?? DEFAULT_CONTACT.gender_id,
            email: primaryContact?.email ?? DEFAULT_CONTACT.email,
            phone: primaryContact?.phone ?? DEFAULT_CONTACT.phone,
            mobile_phone: primaryContact?.mobile_phone ?? DEFAULT_CONTACT.mobile_phone
        };
    };

    const updateLocalContactData = (newData) => {
        localContactData.value = { ...DEFAULT_CONTACT, ...newData };
    };

    const router = useRouter();
    const data = ref({
        customer_id: 0,
        customerGroupId: 0,
        social_reason: '',
        tva_ce: '',
        firstname: '',
        lastname: '',
        website: '',
        // phone: '',
        // mobile_phone: '',
        fax: '',
        discountTypeId: 0,
        mailingLanguageId: 0,
        is_agent: false,
        contact_origin_label: '',
        contact_origin_id: 0, // Updated from OriginContactId
        commentaire: '',
        email: '',
        gender_id: 0,
        phone: null,
        mobile_phone: null
    });
    const customerGroup = ref({
        customerGroupId: 0,
        customerType: ''
    });
    watch(
        () => customerGroup.value,
        (newValue) => {
            console.log('Updated customerGroup:', newValue);
            data.value.customerGroupId = newValue.customerGroupId;
        },
        { deep: true }
    );

    const errorCommentaire = ref('');
    const errorContactOrigin = ref('');
    const error = ref({});
    const isParticular = ref(false);
    watchEffect(() => {
        isParticular.value = !!props.customerData.customer_id;
    });
    const isAutreSelectedOriginType = ref(false);
    let codeSuffix = ref(1);
    const DEFAULT_CONTACT_ERRORS = Object.freeze({
        gender_id: '',
        email: ''
    });
    const DUPLICATE_EMAIL_MESSAGE = "Cette adresse e-mail est déjà utilisée. Merci d'en choisir une autre.";

    const validationContactErrors = ref({ ...DEFAULT_CONTACT_ERRORS });

    watch(
        () => localContactData.value.email,
        () => {
            if (validationContactErrors.value.email) {
                validationContactErrors.value = {
                    ...validationContactErrors.value,
                    email: ''
                };
            }
        }
    );
    // Validation function
    const clearContactErrors = () => {
        validationContactErrors.value = { ...DEFAULT_CONTACT_ERRORS };
    };

    const validateContactData = () => {
        validationContactErrors.value = {
            ...validationContactErrors.value,
            gender_id: localContactData.value.gender_id !== 0 ? '' : 'Le genre est requis'
        };
    };
    const createCustomer = async () => {
        if (data.value.contact_origin_label === '') {
            errorContactOrigin.value = 'Le type d\'origine est obligatoire.';
            return;
        }
        if (data.value.contact_origin_label === 'Autre' && data.value.commentaire === '') {
            errorCommentaire.value = 'Commentaire est Obligatoire Pour Autre.';
            return;
        } else {
            errorCommentaire.value = '';
        }
        validateContactData(); // Validate before proceeding
        if (customerGroup.value.customerType === 'Particulier (Client)') {
            // Check if there are any errors
            if (Object.values(validationContactErrors.value).some((error) => error !== '')) {
                console.log('There are validation errors.', validationContactErrors.value);
                return; // Stop further execution if there are errors
            }
        }

        try {
            clearContactErrors();
            if (props.customerData.customer_id) {
                error.value = {};
                console.log('data for updating customer:', data.value);
                const customerId = props.customerData.customer_id;
                data.value.email = localContactData.value.email;
                data.value.phone = localContactData.value.phone;
                data.value.mobile_phone = localContactData.value.mobile_phone;
                data.value.gender_id = localContactData.value.gender_id;
                const res = await axiosInstance.put(`api/updateCustomer/${customerId}`, data.value);
                window.showMessage('Mise à jour avec succès.');
            } else {
                data.value.email = localContactData.value.email;
                data.value.phone = localContactData.value.phone;
                data.value.mobile_phone = localContactData.value.mobile_phone;
                data.value.gender_id = localContactData.value.gender_id;
                const res = await axiosInstance.post('/api/createCustomer', data.value);
                window.showMessage('Client créé avec succès.');
                router.push({ name: 'addContact', params: { id: res.data.response.customer_id } });
            }
        } catch (e) {
            // Prefer a specific branch for duplicate
            if (e.response) {
                const { status, data } = e.response;
                const backendMessage = e?.response?.data?.message ?? e?.response?.data?.detail;
                // Option 1: HTTP status based
                if (status === 409) {
                    window.showMessage('Il existe déjà un contact avec le même utilisateur.', 'error');
                    validationContactErrors.value = {
                        ...validationContactErrors.value,
                        email: DUPLICATE_EMAIL_MESSAGE
                    };
                    return;
                }

                // Option 2: message text based (fallback if status isn't 409)
                if (backendMessage === 'There is a contact with same user') {
                    window.showMessage('Cette adresse e-mail est déjà utilisée. Merci d\'en choisir une autre.', 'error');
                    validationContactErrors.value = {
                        ...validationContactErrors.value,
                        email: DUPLICATE_EMAIL_MESSAGE
                    };
                    return;
                }

                // Your existing handlers
                if (data?.violations) {
                    error.value = formatErrorViolations(data.violations);
                } else if (data?.message) {
                    window.showMessage(data.message, 'error');
                } else {
                    window.showMessage('Erreur inconnue', 'error');
                }
            } else {
                window.showMessage('Erreur de réseau ou serveur', 'error');
                console.log(e);
            }
        }
    };
    const affectData = (newVal) => {
        if (!newVal) {
            return;
        }

        data.value.customer_id = newVal.customer_id ?? 0;
        data.value.customerGroupId = newVal?.customerGroup?.customer_group_id ?? 0;
        data.value.discountTypeId = newVal?.discountRule?.discount_rule_id ?? 0;
        data.value.social_reason = newVal?.socialReason ?? '';
        data.value.website = newVal?.website ?? '';
        data.value.code = newVal?.code ?? '';
        data.value.tva_ce = newVal?.tva_ce ?? '';
        data.value.mailingLanguageId = newVal?.mailingLanguage ?? 0;
        data.value.is_agent = newVal?.is_agent ?? false;
        data.value.firstname = newVal?.firstname ?? '';
        data.value.lastname = newVal?.lastname ?? '';
        data.value.contact_origin_id = newVal?.contact_origin_id ?? null;
        data.value.commentaire = newVal?.commentaire ?? '';
        data.value.contact_origin_label = newVal?.contact_origin_label ?? '';
        if (newVal?.contactsData?.[0]) {
            data.value.email = newVal.contactsData[0]?.email ?? '';
        }
        customerGroup.value.customerGroupId = newVal?.customerGroup?.customer_group_id ?? 0;
    };

    const changeCode = (Rs) => {
        if (Rs) {
            data.value.code = Rs.substr(0, 4) + codeSuffix.value.toString().padStart(2, '0');
        } else {
            data.value.code = '';
        }
    };
    const incrimentSuffix = (Rs) => {
        codeSuffix.value++;
        changeCode(data.value.social_reason);
    };
    watch(
        () => props.customerData,
        (newVal) => {
            if (newVal) {
                affectData(newVal); // Update all customer fields
                syncLocalContactData(newVal);
            }
        },
        { immediate: true, deep: true }
    );
    watch(
        () => data.value.social_reason,
        (newVal) => {
            if (data.value.customer_id === 0) {
                changeCode(newVal);
            }
        }
    );
    watch(
        () => data.value.customerGroupId,
        (groupId) => {
            console.log('Updated customerGroupId:', groupId);
            if (groupId === publicDiscountTypeId) {
                isParticular.value = true;
                data.value.discountTypeId = particularCustomerGroupId;
            } else {
                isParticular.value = false;
                //data.value.discountTypeId = 0;
            }
        },
        { deep: true }
    );
    watch(
        () => data.value.contact_origin_label,
        (newLabel, oldLabel) => {
            if (newLabel !== oldLabel) {
                // Prevents unnecessary updates
                console.log('New label detected:', newLabel);
                errorContactOrigin.value = '';
                isAutreSelectedOriginType.value = newLabel === 'Autre';

                // Reset commentaire only if the label is different
                if (!isAutreSelectedOriginType.value) {
                    data.value.commentaire = '';
                }
            }
        }
    );
    watch(
        () => data.value.commentaire,
        (newCommentaire, oldCommentaire) => {
            if (newCommentaire !== oldCommentaire) {
                console.log('Commentaire updated:', oldCommentaire);
                console.log('Commentaire updated:', newCommentaire);
            }
        }
    );
</script>
<style>
    .custom-textarea {
        align-items: baseline !important;
    }
</style>
