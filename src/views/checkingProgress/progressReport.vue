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
                            <d-users-dropdown label="Auteur" v-model="form.authorId" :required="true" />
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
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="row mt-2" v-for="(process,index) in processes" :key="index">
                                <div class="col-md-3">
                                    <d-progress-process-dropdown :disabled="true" v-model="process.processId"></d-progress-process-dropdown>
                                </div>
                                <div class="col-md-3">
                                    <d-input type="date" label="fin"  v-model="process.debut"></d-input>
                                </div>
                                <div class="col-md-3">
                                    <d-input type="date" label="fin" v-model="process.fin"></d-input>
                                </div>
                                <div class="col-md-3">
                                    <d-input v-model="process.comment"></d-input>
                                </div>
                            </div>
                        </div>
                    </div>
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
import {ref, onMounted, watch} from 'vue';
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
import {async} from "vue3-number-spinner";
import DProgressProcessDropdown from "@/components/workshop/dropdown/d-progress-process-dropdown.vue";
import userService from "@/Services/user-service.js";

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
    const processes = ref([])
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
            const userData = userService.getUserInfo()
            form.value.authorId = [parseInt(userData?.id)];
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
            const res = await progressReportService.create({
                authorId: form.value.authorId[0],
                datePr: form.value.datePr,
                comment: form.value.comment,
                dateEvent: form.value.dateEvent,
                dateWorkshop: form.value.dateWorkshop,
                tissage: form.value.tissage,
                statusId: form.value.statusId?.id,
                provisionalCalendarId: form.value.provisionalCalendarId
            });
            for (const process of processes.value){
                try {
                    const response = await axiosInstance.post('/api/processDeadline', {
                        progressReportId: res.id,
                        processId:  process.processId,
                        dateStart:  process.debut,
                        dateEnd:  process.fin,
                        comment: process.comment
                    });
                } catch (error) {
                    throw error;
                }
            }
            
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
    
    const switchProcessByStatusId = async () => {
        processes.value = [];
        const statusId = form.value.statusId?.id;
        switch (statusId) {
            case 1 || 2:
                processes.value.push({
                    processId: 1,
                    debut: "",
                    fin: "",
                    comment: ""
                });
                if(statusId === 1){
                    processes.value.push({
                        processId: 2,
                        debut: "",
                        fin: "",
                        comment: ""
                    });
                }
                break;
            case 3:
                processes.value.push({
                    processId: 3,
                    debut: "",
                    fin: "",
                    comment: ""
                });
                processes.value.push({
                    processId: 4,
                    debut: "",
                    fin: "",
                    comment: ""
                });
                break;
        }
    };
    watch(() => form.value.statusId, switchProcessByStatusId);
</script>

<style scoped></style>

