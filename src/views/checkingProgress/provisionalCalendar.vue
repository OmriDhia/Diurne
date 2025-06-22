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
                            <d-input type="number" label="Workshop order" v-model="form.workshopOrderId" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <d-input type="number" label="Deadline preparation" v-model="form.deadlinPreparation" />
                        </div>
                        <div class="col-md-4">
                            <d-input label="Event preparation" v-model="form.eventPreparation" />
                        </div>
                        <div class="col-md-4">
                            <d-input label="Stop preparation" v-model="form.stopPreparation" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <d-input type="number" label="Deadline weave" v-model="form.deadlinWeave" />
                        </div>
                        <div class="col-md-4">
                            <d-input label="Event weave" v-model="form.eventWeave" />
                        </div>
                        <div class="col-md-4">
                            <d-input label="Stop weave" v-model="form.stopWeave" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <d-input type="number" label="Deadline finition" v-model="form.deadlinFinition" />
                        </div>
                        <div class="col-md-4">
                            <d-input label="Event finition" v-model="form.eventFinition" />
                        </div>
                        <div class="col-md-4">
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
    workshopOrderId: '',
    deadlinPreparation: '',
    deadlinWeave: '',
    deadlinFinition: '',
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

