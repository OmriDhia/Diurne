<template>
    <div>
        <d-base-modal id="modalLocationManage" title="Emplacement contremarque" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-carpet-dropdown required="true" v-model="data.carpetTypeId"
                                :error="error.carpetTypeId"></d-carpet-dropdown>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input label="Description" v-model="data.description"
                                :error="error.description"></d-input>
                        </div>
                    </div>
                    <!--div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input required="true" label="Prix min" v-model="data.price_min" :error="error.price_min"></d-input>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input required="true" label="Prix max" v-model="data.price_max" :error="error.price_max"></d-input>
                        </div>
                    </div-->
                    <!--div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-6">
                            <d-input type="datetime-local" label="Date devis" v-model="data.quote_processing_date" :error="error.quote_processing_date"></d-input>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="custom-control custom-radio">
                                <input type="checkbox" class="custom-control-input" id="quote_processed" v-model="data.quote_processed" name="quote_processed" value="true"/>
                                <label class="custom-control-label text-black" for="quote_processed"> {{ $t('Devis traité') }} </label>
                            </div>
                        </div>
                    </div-->
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="saveLocation">Enregistrer</button>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
import VueFeather from 'vue-feather';
import { ref, onMounted, watch } from "vue";
import axiosInstance from "../../../../config/http";
import dInput from "../../../../components/base/d-input.vue";
import dBaseModal from "../../../../components/base/d-base-modal.vue";
import dPanelTitle from "../../../../components/common/d-panel-title.vue";
import { formatErrorViolations, Helper } from "../../../../composables/global-methods";
import dCarpetDropdown from "../../../../components/common/d-carpet-dropdown.vue";

const props = defineProps({
    locationData: {
        type: Object,
    },
    contremarqueId: {
        type: Number
    },
    options: {
        type: Object,
        default: {
            min_price: 0,
            max_price: 0,
            last_quote_date: ''
        }
    },
    saveAndStay: {
        type: Boolean,
        default: true
    }
});

const data = ref({
    contremarqueId: 0,
    carpetTypeId: 0,
    description: "",
    quote_processed: true,
    quote_processing_date: "",
    price_min: 0,
    price_max: 0,
    createdAt: "",
});
const error = ref({});

onMounted(() => {
    if (props.locationData) {
        affectData(props.locationData);
    } else {
        initData();
    }
    setOptions();
});

const saveLocation = async () => {
    try {
        data.value.price_min = parseFloat(data.value.price_min);
        data.value.price_max = parseFloat(data.value.price_max);
        data.value.quote_processing_date = "";
        data.value.createdAt = data.value.createdAt ? Helper.FormatDate(data.value.createdAt, "YYYY-MM-DD HH:mm:ss") : Helper.FormatDate(new Date(), "YYYY-MM-DD HH:mm:ss");
        data.value.contremarqueId = props.contremarqueId;
        let res;
        if (data.value.location_id) {
            res = await axiosInstance.put("/api/updateLocation/" + data.value.location_id, { ...data.value, quoteProcessed: data.value.quote_processed });
            window.showMessage("Mise à jour avec succées.");
        } else {
            res = await axiosInstance.post("/api/createLocation", data.value);
            window.showMessage("Ajout avec succées.");
        }
        if (props.saveAndStay && !data.value.location_id) {
            affectData(res.data.response);
        } else {
            document.querySelector("#modalLocationManage .btn-close").click();
            initData();
        }
    } catch (e) {
        if (e.response.data.violations) {
            error.value = formatErrorViolations(e.response.data.violations);
        }
        window.showMessage(e.message, 'error')
    }
};

const initData = () => {
    data.value.contremarqueId = 0;
    data.value.carpetTypeId = 0;
    data.value.description = "";
    data.value.quote_processed = true;
    data.value.quote_processing_date = "";
    data.value.price_min = 0;
    data.value.price_max = 0;
    data.value.createdAt = "";
    error.value = {};
    data.value.location_id = 0;

    setOptions();
};

const affectData = (loc) => {
    if (loc) {
        data.value = {
            contremarqueId: loc.contremarque_id,
            carpetTypeId: loc.carpetType_id,
            description: loc.description,
            location_id: loc.id ? loc.id : loc.location_id ? loc.location_id : 0,
            quote_processed: loc.quote_processed,
            quote_processing_date: "",
            price_min: Helper.FormatNumber(loc.price_min),
            price_max: Helper.FormatNumber(loc.price_max),
            createdAt: loc.created_at.date ? new Date(loc.created_at.date) : new Date(),
        };
    }
};

const setOptions = () => {
    if (props.options) {
        data.value.price_min = Helper.FormatNumber(props.options?.min_price);
        data.value.price_max = Helper.FormatNumber(props.options?.max_price);
        data.value.createdAt = props.options?.last_quote_date ? new Date(props.options?.last_quote_date) : "";
    }
};
watch(
    () => props.locationData,
    (loc) => {
        if (loc)
            affectData(loc)
        else
            initData();
    }
);
watch(
    () => props.options,
    (ops) => {
        setOptions();
    },
    { deep: true }
);
const emit = defineEmits(['onClose']);

const handleClose = () => {
    initData();
    error.value = {};
    emit('onClose')
}

defineExpose({
    initData,
});
</script>
