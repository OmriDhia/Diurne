<template>
    <d-base-page :loading="loading">
        <template #title>
            <d-page-title title="Provisional Calendar" />
        </template>
        <template #body>
            <d-panel>
                <template #panel-header>
                    <d-panel-title title="Create provisional calendar" />
                </template>
                <template #panel-body>
                    <div class="row">
                        <div class="col-md-4">
                            <d-input type="number" label="RN" v-model="form.rn" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <d-input type="number" label="Deadline preparation"
                                     v-model.number="form.deadlinPreparation" />
                            <d-input label="Date de fin" type="date" v-model="form.dateFinPreparation" />
                        </div>
                        <div class="col-md-6">
                            <d-input label="Event preparation" v-model="form.eventPreparation" />
                            <d-input label="Stop preparation" v-model="form.stopPreparation" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <d-input type="number" label="Deadline tissage" v-model.number="form.deadlinWeave" />
                            <d-input label="Date de fin" type="date" v-model="form.dateFinWeave" />
                        </div>
                        <div class="col-md-6">
                            <d-input label="Event tissage" v-model="form.eventWeave" />
                            <d-input label="Stop tissage" v-model="form.stopWeave" />
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <d-input type="number" label="Deadline finition" v-model.number="form.deadlinFinition" />
                            <d-input label="Date de fin" type="date" v-model="form.dateFinFinition" />
                        </div>
                        <div class="col-md-6">
                            <d-input label="Event finition" v-model="form.eventFinition" />
                            <d-input label="Stop finition" v-model="form.stopFinition" />

                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-auto">
                            <button class="btn btn-custom" @click="save">Enregistrer</button>
                        </div>
                    </div>
                </template>
            </d-panel>
        </template>
    </d-base-page>
</template>

<script setup>
    import { ref } from 'vue';
    import dBasePage from '@/components/base/d-base-page.vue';
    import dInput from '@/components/base/d-input.vue';
    import dPanel from '@/components/common/d-panel.vue';
    import dPanelTitle from '@/components/common/d-panel-title.vue';
    import dPageTitle from '@/components/common/d-page-title.vue';
    import provisionalCalendarService from '@/Services/provisional-calendar-service';

    const loading = ref(false);
    const form = ref({
        rn: '',
        workshopOrderId: 189,
        deadlinPreparation: '',
        dateFinPreparation: '',
        deadlinWeave: '',
        dateFinWeave: '',
        deadlinFinition: '',
        dateFinFinition: '',
        eventPreparation: '',
        stopPreparation: '',
        eventWeave: '',
        stopWeave: '',
        eventFinition: '',
        stopFinition: ''


    });

    const save = async () => {
        try {
            loading.value = true;
            await provisionalCalendarService.create({ ...form.value });
            window.showMessage('Ajout avec succées.');
        } catch (e) {
            window.showMessage(e.message, 'error');
        } finally {
            loading.value = false;
        }
    };
</script>

<style scoped></style>

