<template>
    <d-base-page :loading="loading">
        <template #title>
            <d-page-title title="Progress Report" />
        </template>
        <template #body>
            <d-panel>
                <template #panel-header>
                    <d-panel-title title="Create progress report" />
                </template>
                <template #panel-body>
                    <div class="row">
                        <div class="col-md-6">
                            <d-input type="date" label="Date PR" v-model="form.datePr" />
                        </div>
                        <div class="col-md-6">
                            <d-users-dropdown label="auteur" v-model="form.authorId" :required="true" />
                        </div>
                        <div class="col-md-6">
                            <d-input type="text" label="RN" v-model="form.rn" />
                        </div>
                        <div class="col-md-6">
                            <d-base-dropdown name="État de la commande" v-model="form.statusId" :datas="statuses"
                                             label="status"
                                             trackBy="id" />
                        </div>

                    </div>
                    <!--                    <div class="row mt-3">
                                            <div class="col-md-4">
                                                <d-input type="date" label="Date évènement" v-model="form.dateEvent" />
                                            </div>
                                            <div class="col-md-4">
                                                <d-input type="date" label="Date atelier" v-model="form.dateWorkshop" />
                                            </div>
                    
                                        </div>-->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <d-contremarque-dropdown v-model="form.contremarque" :disabled="true" />
                            <d-input type="date" label="date évènement" v-model="form.dateEvent" />
                            <d-input type="date" label="date atelier cible" v-model="form.dateWorkshop" />
                            <d-input label="Tissage" v-model="form.tissage" v-if="form.statusId?.id === 2"/>
                        </div>
                        <div class="col-md-6">
                            <d-textarea label="Comment" v-model="form.comment" />
                        </div>
                    </div>
                    <div class="row mt-4 justify-content-end">
                        <div class="col-auto">
                            <button class="btn btn-custom" @click="save">Enregistrer</button>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-custom" @click="save">Nouveau PR</button>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-custom pe-5 ps-5" @click="goToWorkshop">
                                Retour à la workShop
                            </button>
                        </div>
                    </div>
                </template>
            </d-panel>
        </template>
    </d-base-page>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import dBasePage from '@/components/base/d-base-page.vue';
    import dInput from '@/components/base/d-input.vue';
    import dTextarea from '@/components/base/d-textarea.vue';
    import dPanel from '@/components/common/d-panel.vue';
    import dPanelTitle from '@/components/common/d-panel-title.vue';
    import dPageTitle from '@/components/common/d-page-title.vue';
    import dBaseDropdown from '@/components/base/d-base-dropdown.vue';
    import dUsersDropdown from '@/components/common/d-users-dropdown.vue';
    import progressReportService from '@/Services/progress-report-service';
    import {useRoute, useRouter} from "vue-router";
    import axiosInstance from "@/config/http.js";
    import DContremarqueDropdown from "@/components/common/d-contremarque-dropdown.vue";

    const route = useRoute();
    const router = useRouter();
    const loading = ref(false);
    const provisionalCalendar = ref({});
    const form = ref({
        authorId: [],
        datePr: '',
        rn: '',
        contremarque: '',
        comment: '',
        dateEvent: '',
        dateWorkshop: '',
        tissage: '',
        statusId: null,
        provisionalCalendarId: parseInt(route.params.provisionalCalendarId),
    });
    const statuses = ref([]);
    const loadStatuses = async () => {
        try {
            const res = await progressReportService.getStatuses();
            statuses.value = res.progressReportStatuses || res.progress_report_statuses || res;
        } catch (e) {
            window.showMessage(e.message, 'error');
        }
    };
    const getCalndar = async () => {
        try {
            const res = await axiosInstance.get(`/api/provisionalCalendar/${form.value.provisionalCalendarId}`);
            provisionalCalendar.value = res.data?.response;
            form.value.rn = provisionalCalendar.value.workshopOrder.workshopInformation.rn
            form.value.contremarque = provisionalCalendar.value.workshopOrder.imageCommand.carpetDesignOrder.location.contremarque_id
        } catch (e) {
            window.showMessage(e.message, 'error');
        }
    };

    onMounted(() => {
        loadStatuses()
        getCalndar()
    });

    const save = async () => {
        try {
            loading.value = true;
            await progressReportService.create({
                authorId: form.value.authorId[0],
                datePr: form.value.datePr,
                comment: form.value.comment,
                dateEvent: form.value.dateEvent,
                dateWorkshop: form.value.dateWorkshop,
                tissage: form.value.tissage,
                statusId: form.value.statusId?.id,
                provisionalCalendarId: form.value.provisionalCalendarId
            });
            window.showMessage('Ajout avec succées.');
        } catch (e) {
            window.showMessage(e.message, 'error');
        } finally {
            loading.value = false;
        }
    };
    const goToWorkshop = () => {
        router.back();
    };
</script>

<style scoped></style>

