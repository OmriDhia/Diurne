<template>
    <d-base-page :loading="loading">
        <template #title>
            <d-page-title title="Provisional Calendar" />
        </template>
        <template #body>
            <d-panel>
                <template #panel-header>
                    <d-panel-title :title="route.params.id ? 'Provisional Calendar' : 'Create provisional calendar'" />
                </template>
                <template #panel-body>
                    <div>
                        <div class="row">
                            <div class="col-md-4">
                                <d-input type="text" label="RN" :disabled="true" v-model="form.rn" />
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
                                <d-input type="number" label="Deadline finition"
                                         v-model.number="form.deadlinFinition" />
                                <d-input label="Date de fin" type="date" v-model="form.dateFinFinition" />
                            </div>
                            <div class="col-md-6">
                                <d-input label="Event finition" v-model="form.eventFinition" />
                                <d-input label="Stop finition" v-model="form.stopFinition" />

                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-auto">
                                <button class="btn btn-custom" @click="save">
                                    {{ route.params.id ? 'Update' : 'Enregistrer' }}
                                </button>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-custom" @click="goToWorkshop">
                                    Retour à la workshop
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </d-panel>
        </template>
    </d-base-page>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import dBasePage from '@/components/base/d-base-page.vue';
    import dInput from '@/components/base/d-input.vue';
    import dPanel from '@/components/common/d-panel.vue';
    import dPanelTitle from '@/components/common/d-panel-title.vue';
    import dPageTitle from '@/components/common/d-page-title.vue';
    import provisionalCalendarService from '@/Services/provisional-calendar-service';
    import workshopService from '@/Services/workshop-service.js';

    const loading = ref(false);
    const route = useRoute();
    const router = useRouter();
    const calendar = ref(null);

    const workshopOrderId = route.params.workshopOrderId;

    const form = ref({
        rn: '',
        workshopOrderId: parseInt(workshopOrderId),
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

    const formatDateForInput = (dateString) => {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toISOString().split('T')[0];
    };
    const getworkshopOrder = async () => {
        if (workshopOrderId) {
            const res = await workshopService.getWorkshopOrder(workshopOrderId);
            // Prefer RN from nested workshopOrder.workshopInformation if available, fallback to top-level
            const rnFromResponse = res?.workshopOrder?.workshopInformation?.rn || res?.workshopInformation?.rn || '';
            form.value.rn = rnFromResponse;
        }
    };

    const loadCalendar = async (id) => {
        try {
            loading.value = true;
            const data = await provisionalCalendarService.getById(id);
            calendar.value = data;

            form.value = {
                // Prefer RN from calendar data; if not present, keep existing RN (from workshop order)
                rn: data.rn || form.value.rn,
                workshopOrderId: data.workshopOrderId,
                deadlinPreparation: data.deadlinPreparation,
                dateFinPreparation: formatDateForInput(data.dateEndPreparation),
                deadlinWeave: data.deadlinWeave,
                dateFinWeave: formatDateForInput(data.dateEndWeave),
                deadlinFinition: data.deadlinFinition,
                dateFinFinition: formatDateForInput(data.dateEndFinition),
                eventPreparation: data.eventPreparation,
                stopPreparation: data.stopPreparation,
                eventWeave: data.eventWeave,
                stopWeave: data.stopWeave,
                eventFinition: data.eventFinition,
                stopFinition: data.stopFinition
            };
        } catch (e) {
            window.showMessage(e.message, 'error');
        } finally {
            loading.value = false;
        }
    };

    const save = async () => {
        try {
            loading.value = true;
            if (route.params.id) {
                await provisionalCalendarService.update(route.params.id, {
                    ...form.value,
                    deadlinPreparation: parseInt(form.value.deadlinPreparation),
                    deadlinWeave: parseInt(form.value.deadlinWeave),
                    deadlinFinition: parseInt(form.value.deadlinFinition)
                });
                window.showMessage('Mise à jour avec succès.');

                // Force reload the data
                await loadCalendar(route.params.id);
            } else {
                const res = await provisionalCalendarService.create({
                    ...form.value,
                    deadlinPreparation: parseInt(form.value.deadlinPreparation),
                    deadlinWeave: parseInt(form.value.deadlinWeave),
                    deadlinFinition: parseInt(form.value.deadlinFinition)
                });
                window.showMessage('Ajout avec succès.');
                router.push({ name: 'provisionalCalendarView', params: { workshopOrderId, id: res.id } });
            }
        } catch (e) {
            window.showMessage(e.message, 'error');
        } finally {
            loading.value = false;
        }
    };

    const goToWorkshop = () => {
        if (workshopOrderId) {
            router.push({ name: 'updateCarpetWorkshop', params: { workshopOrderId } });
        }
    };

    onMounted(() => {
        getworkshopOrder();
        if (route.params.id) {
            loadCalendar(route.params.id);
        }
    });
</script>

<style scoped></style>
