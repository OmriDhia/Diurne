<template>
    <div class="mt-3">
        <d-panel>
            <template v-slot:panel-header>
                <d-panel-title title="Progress report"></d-panel-title>
            </template>
            <template v-slot:panel-body>
                <div class="row pe-2 ps-0">
                    <div id="toggleAccordionEvent" class="accordion ps-1">
                        <template v-for="(item, index) in data">
                            <div class="card mb-1">
                                <header class="card-header" role="tab">
                                    <section class="mb-0 mt-0">
                                        <div class="collapsed" role="menu" data-bs-toggle="collapse" :data-bs-target="'#event'+index" aria-expanded="false" :aria-controls="'address'+index">
                                            Evenement {{ index + 1 }}
                                            <div class="icons">
                                                <vue-feather type="chevron-down" size="14"></vue-feather>
                                            </div>
                                        </div>
                                    </section>
                                </header>
                                <div :id="'event'+index" class="collapse" :aria-labelledby="'event'+index" data-bs-parent="#toggleAccordionEvent">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row"><d-input label="Evenement" v-model="item.event"></d-input></div>
                                                <div class="row"><d-input label="Date" v-model="item.date"></d-input></div>
                                                <div class="row"><d-input label="Date prev" v-model="item.dateprev"></d-input></div>
                                            </div>
                                            <div class="col-6">
                                                <div class="row"><textarea v-model="item.comment"></textarea></div>
                                                <div class="row"><vue-feather type="eye" :size="14"></vue-feather></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </d-panel>
        <div class="row mt-2 justify-content-end">
            <d-btn-outlined icon="plus" label="Ajouter" @click.prevent="addNewProgressReport"></d-btn-outlined>
        </div>
    </div>
</template>

<script setup>
import {defineProps, ref} from 'vue';
import VueFeather from 'vue-feather';
import dInput from "../../../components/base/d-input.vue";
import '../../../assets/sass/components/tabs-accordian/custom-accordions.scss';
import dBtnOutlined from "../../base/d-btn-outlined.vue"
import DPanel from "@/components/common/d-panel.vue";
import DPanelTitle from "@/components/common/d-panel-title.vue";
import checkingListService from "@/Services/checkingList-service.js";
import axiosInstance from "@/config/http.js";
import {useRouter} from "vue-router";

const props = defineProps({
    workshopOrderId: {
        type: Number,
        required: false
    },
});

const data = ref([{
    event: "",
    date: "",
    dateprev: "",
    comment: ""
},{
    event: "",
    date: "",
    dateprev: "",
    comment: ""
},{
    event: "",
    date: "",
    dateprev: "",
    comment: ""
}]);
const error = ref({});
const router = useRouter();

const addNewProgressReport = async () => {
    if (props.workshopOrderId) {
        const res = await axiosInstance.get(`/api/provisionalCalendar/workshopOrder/${props.workshopOrderId}`);
        const provisionalCalendar = res.data?.response;
        if (provisionalCalendar.length > 0) {
            router.push({name: "progressReport", params :{provisionalCalendarId: provisionalCalendar[0].id}})
        } else {
            router.push({name: "provisionalCalendarView", params :{workshopOrderId: props.workshopOrderId}})
        }
    } else {
        window.showMessage("Identifiant commande atelier introuvable",'error');
    }
   
}
</script>
<style>

</style>
