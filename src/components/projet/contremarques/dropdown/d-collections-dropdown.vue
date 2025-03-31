<template>
    <div class="row align-items-center pt-2">
        <div class="col-md-4" v-if="!showOnlyDropdown">
            <label class="form-label">
                Collection<span class="required" v-if="required">*</span>:
            </label>
        </div>

        <div :class="{ 'col-md-8': !showOnlyDropdown, 'col-md-12': showOnlyDropdown }">
            <Multiselect
                :class="{ 'multiselect--error': !!error }"
                v-model="selectedValue"
                :options="data"
                placeholder="Collections"
                track-by="id"
                label="reference"
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
    </div>

    <div class="row align-items-center justify-content-end" v-if="!showOnlyDropdown && !hideBtn">
        <div class="col-md-8">
            <button class="btn btn-custom pe-2 ps-2 font-size-0-7 w-100" @click="goToSettings">
                Créer une collection
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import axiosInstance from "../../../../config/http";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.css";

// Props
const props = defineProps({
    modelValue: {
        type: [Number, String, null],
        required: true,
    },
    error: {
        type: [String, Boolean],
        default: "",
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    showOnlyDropdown: {
        type: Boolean,
        default: false,
    },
    hideBtn: {
        type: Boolean,
        default: false,
    },
});

// Événements
const emit = defineEmits(["update:modelValue"]);

// Références
const data = ref([]);
const selectedValue = ref(null);

// Méthodes
const fetchData = async () => {
    try {
        const res = await axiosInstance.get("/api/collections");
        data.value = res.data.response.data;

        if (props.modelValue) {
            selectedValue.value = data.value.find((item) => item.id === props.modelValue) || null;
        }
    } catch (error) {
        console.error("Failed to fetch collections:", error);
    }
};

const goToSettings = () => {
    console.log("Redirection vers la création d'une collection...");
};

// Watchers
watch(selectedValue, (newValue) => {
    emit("update:modelValue", newValue ? parseInt(newValue.id) : null);
});

watch(() => props.modelValue, (newValue) => {
    selectedValue.value = data.value.find((item) => item.id === newValue) || null;
});

// Lifecycle
onMounted(fetchData);
</script>

<style>
.multiselect--error .multiselect__tags {
    border: 1px solid red !important;
}
</style>
