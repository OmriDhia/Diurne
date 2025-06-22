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
                    <div v-if="route.params.id && calendar">
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>RN:</strong> {{ calendar.rn }}</div>
                            <div class="col-md-4"><strong>Deadline preparation:</strong> {{ calendar.deadlinPreparation
                                }}
                            </div>
                            <div class="col-md-4"><strong>Date end preparation:</strong> {{ calendar.dateEndPreparation
                                }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Deadline weave:</strong> {{ calendar.deadlinWeave }}</div>
                            <div class="col-md-4"><strong>Date end weave:</strong> {{ calendar.dateEndWeave }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Deadline finition:</strong> {{ calendar.deadlinFinition }}
                            </div>
                            <div class="col-md-4"><strong>Date end finition:</strong> {{ calendar.dateEndFinition }}
                            </div>
                        </div>
                    </div>
                    <div v-else>
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
                                <button class="btn btn-custom" @click="save">Enregistrer</button>
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

    const loading = ref(false);
    const route = useRoute();
    const router = useRouter();
    const calendar = ref(null);

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
    console.log('test');
    const loadCalendar = async (id) => {
        try {
            loading.value = true;
            calendar.value = await provisionalCalendarService.getById(id);
        } catch (e) {
            window.showMessage(e.message, 'error');
        } finally {
            loading.value = false;
        }
    };

    const save = async () => {
        try {
            loading.value = true;
            const res = await provisionalCalendarService.create({ ...form.value });
            window.showMessage('Ajout avec succées.');
            router.push({ name: 'provisionalCalendarView', params: { id: res.id } });
        } catch (e) {
            window.showMessage(e.message, 'error');
        } finally {
            loading.value = false;
        }
    };

    onMounted(() => {
        if (route.params.id) {
            loadCalendar(route.params.id);
        }
    });
</script>

<style scoped></style>

