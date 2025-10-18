<template>
    <div class="col-sm-12 col-md-6" style="margin-top: 20px">
        <div class="row pe-2">
            <div id="toggleAccordion" class="accordion">
                <div class="card mb-1">
                    <div id="headingOne1" class="collapse show" aria-labelledby="headingOne1" data-bs-parent="#toggleAccordion">
                        <div class="card-body">
                            <div class="row p-1 align-items-center">
                                <div class="col-sm-12 col-md-6">
                                    <d-gender :required="true" v-model="data.gender_id" :error="props.error?.gender_id"></d-gender>
                                </div>
                            </div>
                            <div class="row p-1 align-items-center">
                                <div class="col-sm-12 col-md-6">
                                    <d-input label="Email" v-model="data.email" :error="props.error?.email"></d-input>
                                </div>
                            </div>
                            <div class="row p-1 align-items-center">
                                <div class="col-sm-12 col-md-6">
                                    <d-input label="Tel. portable" v-model="data.mobile_phone" :error="props.error?.mobile_phone"></d-input>
                                </div>
                            </div>
                            <div class="row p-1 align-items-center">
                                <div class="col-sm-12 col-md-6">
                                    <d-input label="Tél. fixe" v-model="data.phone" :error="props.error?.phone"></d-input>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { defineProps, ref, watch } from 'vue';
    import axiosInstance from '../../config/http';
    import VueFeather from 'vue-feather';
    import dContactFormParticulier from './_partial/d-contact-form-particulier.vue';
    import dGender from '../../components/common/d-gender.vue';
    import dInput from '../../components/base/d-input.vue';
    import { formatErrorViolations } from '../../composables/global-methods';
    import '../../assets/sass/components/tabs-accordian/custom-accordions.scss';
    import dBtnOutlined from '../base/d-btn-outlined.vue';
    const props = defineProps({
        contactData: {
            type: Array,
            default: [],
        },
        isParticular: {
            type: Boolean,
        },
        required: {
            type: Boolean,
            default: true,
        },
        formData: {
            // New prop for form data
            type: Object,
            required: true,
        },
        error: {
            type: Object,
            default: () => ({}),
        },
    });
    const emit = defineEmits(['updateFormData']);
    // const error = ref({});

    const DEFAULT_CONTACT = Object.freeze({
        gender_id: 0,
        email: '',
        phone: null,
        mobile_phone: null,
    });
    const CONTACT_FIELDS = Object.keys(DEFAULT_CONTACT);

    const data = ref({ ...DEFAULT_CONTACT, ...props.formData });

    const assignFromSource = (source, { replace = false } = {}) => {
        if (!source) {
            return;
        }

        const target = replace ? { ...DEFAULT_CONTACT } : { ...data.value };

        CONTACT_FIELDS.forEach((field) => {
            if (Object.prototype.hasOwnProperty.call(source, field)) {
                target[field] = source[field];
            } else if (replace && !Object.prototype.hasOwnProperty.call(target, field)) {
                target[field] = DEFAULT_CONTACT[field];
            }
        });

        data.value = target;
    };

    // Prefer the contact payload coming from the API when it is available.
    watch(
        () => props.contactData,
        (newData) => {
            const hasContacts = Array.isArray(newData) && newData.length > 0;

            if (!hasContacts) {
                data.value = { ...DEFAULT_CONTACT };
                return;
            }

            assignFromSource(newData[0], { replace: true });
        },
        { deep: true, immediate: true }
    );

    // React to parent form updates (for example when the parent resets the form
    // or when a user edit is reflected back down) without clearing fields that
    // are not part of the payload.
    watch(
        () => props.formData,
        (newFormData) => {
            if (!newFormData) {
                return;
            }

            const containsRelevantField = CONTACT_FIELDS.some((field) =>
                Object.prototype.hasOwnProperty.call(newFormData, field)
            );

            if (!containsRelevantField) {
                return;
            }

            assignFromSource(newFormData);
        },
        { deep: true }
    );

    // Watch for changes in data and emit updates to parent
    watch(
        data,
        (newValue) => {
            emit('updateFormData', newValue);
        },
        { deep: true }
    );

    
</script>
<style></style>
