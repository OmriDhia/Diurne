<template>
    <div class="col-sm-12 col-md-6" style="margin-top: 20px">
        <div class="row pe-2">
            <div id="toggleAccordion" class="accordion">
                <div class="card mb-1">
                    <div id="headingOne1" class="collapse show" aria-labelledby="headingOne1" data-bs-parent="#toggleAccordion">
                        <div class="card-body">
                            <div class="row p-1 align-items-center">
                                <div class="col-sm-12 col-md-6">
                                    <d-gender :required="true" v-model="data.gender_id" :error="error.gender_id"></d-gender>
                                </div>
                            </div>
                            <div class="row p-1 align-items-center">
                                <div class="col-sm-12 col-md-6">
                                    <d-input label="Email" v-model="data.email" :error="error.email"></d-input>
                                </div>
                            </div>
                            <div class="row p-1 align-items-center">
                                <div class="col-sm-12 col-md-6">
                                    <d-input label="Tel. portable" v-model="data.mobile_phone" :error="error.mobile_phone"></d-input>
                                </div>
                            </div>
                            <div class="row p-1 align-items-center">
                                <div class="col-sm-12 col-md-6">
                                    <d-input label="Tél. fixe" v-model="data.phone" :error="error.phone"></d-input>
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
    import { defineProps, ref, watch, watchEffect, onMounted, computed } from 'vue';
    import axiosInstance from '../../config/http';
    import VueFeather from 'vue-feather';
    import dContactFormParticulier from './_partial/d-contact-form-particulier.vue';
    import dGender from '../../components/common/d-gender.vue';
    import dInput from '../../components/base/d-input.vue';
    import { formatErrorViolations } from '../../composables/global-methods';
    import '../../assets/sass/components/tabs-accordian/custom-accordions.scss';
    import dBtnOutlined from '../base/d-btn-outlined.vue';
    import { useRouter } from 'vue-router';
    const router = useRouter();
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
    });
    const emit = defineEmits(['updateFormData']);
    const error = ref({});

    // Initialize `data` with `contactData` if available, else use `formData`
    const data = ref({
        gender_id: props.formData.gender_id || 0,
        email: props.formData.email || "",
        phone: props.formData.phone || null,
        mobile_phone: props.formData.mobile_phone || null,
    });
    // If `contactData` is provided, update `data`
    watch(() => props.contactData, (newData) => {
        if (newData.length > 0) {
            data.value = { ...newData[0] };
        }
    }, { deep: true, immediate: true });
    

    // Watch for changes in data and emit updates to parent
    watch(data, (newValue) => {
        emit("updateFormData", newValue);
    }, { deep: true });



    onMounted(() => {
        console.log('particulier : ', props.isParticular);
    });
</script>
<style></style>
