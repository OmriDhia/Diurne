<script setup lang="ts">
    import { ref } from 'vue';
    import SelectInput from '@/components/workshop/ui/SelectInput.vue';
    import dInput from '../../../components/base/d-input.vue';

    const historicalRecords = ref([
        { id: 1, date: '20-10-2021', action: 'Création', user: 'Jean Dupont' },
        { id: 2, date: '22-10-2021', action: 'Modification', user: 'Marie Martin' },
        { id: 3, date: '24-10-2021', action: 'Validation', user: 'Pierre Durand' }
    ]);
    const data = ref({
        formData: {
            event: 'test',
            location: '',
            startDate: '',
            endDate: '',
            customer: ''
        }
    });
</script>

<template>
    <div class="historique-tab">
        <div class="my-3">
            <button class="btn btn-custom font-size-0-7 text-uppercase">Nouveau déplacement</button>
          
        </div>

        <table class="history-table">
            <thead>
            <tr>
                <th>Evènement</th>
                <th>Emplacement</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Client</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="record in historicalRecords" :key="record.id">
                <td>
                    <div class="col-12 px-2">
                        <SelectInput v-model="data.formData.event" rootClass="pink-bg" />
                    </div>

                </td>
                <td>
                    <div class="col-12 px-2">
                        <SelectInput v-model="data.formData.event" rootClass="pink-bg" />
                    </div>
                </td>
                <td>
                    <div class="col-12 px-2">
                        <d-input type="date"
                                 v-model="data.formData.startDate" />
                    </div>

                </td>
                <td>
                    <div class="col-12 px-2">
                        <d-input type="date"
                                 v-model="data.formData.endDate" />
                    </div>

                </td>
                <td>
                    <div class="col-12 px-2">
                        <SelectInput type="date"
                                     v-model="data.formData.customer" />
                    </div>

                </td>
            </tr>
            </tbody>
        </table>

        <div class="no-records" v-if="historicalRecords.length === 0">
            Aucun historique disponible
        </div>
    </div>
</template>

<style scoped lang="scss">
    .historique-tab {
        padding: 20px;
    }

    h3 {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 18px;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f5f5f5;
            font-weight: 600;
        }

        tr:hover td {
            background-color: #f9f9f9;
        }
    }

    .no-records {
        padding: 20px;
        text-align: center;
        color: #999;
        font-style: italic;
    }
</style>
