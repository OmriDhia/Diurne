<template>
    <div class="row space-x-6">
        <div v-if="canceldAttribution" class="row align-items-center">
            <div class="col-4">
                <d-input :disabled="true" :model-value="`${getRnNumber(canceldAttribution.carpet)}`"></d-input>
            </div>
            <div class="col-4">
                <div class="text-gray-700">Annulée le {{ $Helper.FormatDate(canceldAttribution.canceledAt, 'DD/MM/YYYY')
                    }}
                </div>
            </div>
        </div>
    </div>

    <div class="row align-items-center pt-2">
        <div :class="{ 'col-md-4': !showOnlyDropdown, 'col-md-12': showOnlyDropdown }">
            <Multiselect
                :class="{ 'multiselect--error': !!error }"
                v-model="selectedValue"
                :options="data"
                placeholder="RNs"
                track-by="id"
                label="rnNumber"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
            />
            <div v-if="error" class="invalid-feedback">
                {{ $t('Le champ collection est obligatoire.') }}
            </div>
        </div>
        <div class="col-md-4" v-if="!showOnlyDropdown">
            <a href="#" class="text-black underline text-sm mt-1">
                <u>Voir RN</u>
            </a>
        </div>
        <div class="col-4">
            <button class="px-6 py-2 bg-black text-white rounded" @click="cancelRnAttribution"
                    v-if="!currentAttribution">ANNULÉ RN
            </button>
            <button class="px-6 py-2 bg-black text-white rounded" @click="createRnAttribution"
                    v-if="currentAttribution">Associer à un RN
            </button>
        </div>
    </div>

    <div class="row align-items-center justify-content-end" v-if="!showOnlyDropdown && !hideBtn">
        <div class="col-md-8">
            <button class="btn btn-custom pe-2 ps-2 font-size-0-7 w-100" @click="goToSettings">Créer une collection
            </button>
        </div>
    </div>
</template>

<script setup>
    import { ref, watch, onMounted, computed } from 'vue';
    import axiosInstance from '../../../../config/http';
    import Multiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';
    import dInput from '../../../base/d-input.vue';
    // Props
    const props = defineProps({
        modelValue: {
            type: [Number, String, null],
            required: true
        },
        error: {
            type: [String, Boolean],
            default: ''
        },
        required: {
            type: Boolean,
            default: false
        },
        disabled: {
            type: Boolean,
            default: false
        },
        showOnlyDropdown: {
            type: Boolean,
            default: false
        },
        hideBtn: {
            type: Boolean,
            default: false
        },
        carpetOrderDetailsId: {
            type: Number
        }
    });

    const emit = defineEmits(['update:modelValue', 'rn-attribution-created']);

    const data = ref([]);
    const selectedValue = ref(null);
    const isLoading = ref(false);
    const currentAttribution = ref([]);
    const canceldAttribution = ref(null);
    // Méthodes
    console.log(canceldAttribution.value);
    console.log(currentAttribution.value);
    // Separate function to fetch attribution
    const fetchAttribution = async () => {
        console.log('fetchAttribution');
        console.log(props.carpetOrderDetailsId);
        if (!props.carpetOrderDetailsId) return;

        try {
            const response = await axiosInstance.get(`/api/rnAttributions/${props.carpetOrderDetailsId}`);
            if (response.data.response.active && !response.data.response.active.canceledAt) {
                console.log('currentAttribution', response.data.response.active);
                currentAttribution.value = response.data.response.active;
                // Set selected RN if attribution exists
                selectedValue.value = data.value.find((item) => item.id === currentAttribution.value.carpet) || null;
            }
            if (response.data.response.lastCanceled) {
                console.log(response.data.response.lastCanceled, 'lastCanceled');
                canceldAttribution.value = response.data.response.lastCanceled;
            } else {
                currentAttribution.value = null;
            }
        } catch (error) {
            console.error('Error fetching attribution:', error);
            currentAttribution.value = null;
        }
        console.log(currentAttribution.value);
    };

    // Modified fetchData to only get RN options
    const fetchData = async () => {
        try {
            const res = await axiosInstance.get('/api/carpets');
            data.value = res.data.response;

            // Set initial selected value from modelValue if no attribution exists
            if (props.modelValue && !currentAttribution.value) {
                selectedValue.value = data.value.find((item) => item.id === props.modelValue) || null;
            }
        } catch (error) {
            console.error('Failed to fetch collections:', error);
        }
    };

    const createRnAttribution = async () => {
        console.log('clicked');
        console.log('props.carpetOrderDetailsId', props.carpetOrderDetailsId);
        console.log('props.carpetOrderDetailsId', selectedValue.value?.id);
        console.log((!selectedValue.value?.id || !props.carpetOrderDetailsId));
        if (!selectedValue.value?.id || !props.carpetOrderDetailsId) return;
        isLoading.value = true;
        try {
            const payload = {
                carpetOrderDetailId: props.carpetOrderDetailsId,
                carpetId: selectedValue.value.id,
                canceledAt: null
            };

            const response = await axiosInstance.post('/api/rnAttributions', payload);
            currentAttribution.value = response.data.response;
            emit('rn-attribution-created', response.data);
            emit('update:modelValue', selectedValue.value.id);
            alert('RN attribution créée avec succès!');
        } catch (error) {
            console.error('Error creating RN attribution:', error);
            alert('Erreur lors de la création de l\'attribution RN');
        } finally {
            isLoading.value = false;
        }
    };

    const cancelRnAttribution = async () => {
        console.log('clicked', currentAttribution.value.id);
        if (!currentAttribution.value) return;

        isLoading.value = true;
        try {
            const payload = {};
            const response = await axiosInstance.put(`/api/rnAttributions/${currentAttribution.value.id}`, payload);
            canceldAttribution.value = response.data.response;
            currentAttribution.value = null;
            selectedValue.value = null;
            emit('rn-attribution-canceled', response.data);
            alert('RN annulé avec succès!');
        } catch (error) {
            console.error('Error canceling RN attribution:', error);
            alert(`Erreur: ${error.response?.data?.message || 'Erreur inconnue'}`);
        } finally {
            isLoading.value = false;
        }
    };
    const goToSettings = () => {
        console.log('Redirection vers la création d\'une collection...');
    };
    const getRnNumber = (carpetId) => {
        const carpet = data.value.find((item) => item.id === carpetId);
        return carpet ? carpet.rnNumber : 'N/A';
    };
    // Watchers
    watch(selectedValue, (newValue) => {
        emit('update:modelValue', newValue ? parseInt(newValue.id) : null);
    });

    watch(
        () => props.modelValue,
        (newValue) => {
            selectedValue.value = data.value.find((item) => item.id === newValue) || null;
        }
    );
    // Lifecycle
    watch(
        () => props.carpetOrderDetailsId,
        (newId) => {
            if (newId) {
                fetchAttribution();
            } else {
                currentAttribution.value = null;
            }
        }
    );
    onMounted(async () => {
        await fetchData();
        if (props.carpetOrderDetailsId) {
            await fetchAttribution();
        }
    });
</script>

<style>
    .multiselect--error .multiselect__tags {
        border: 1px solid red !important;
    }
</style>
