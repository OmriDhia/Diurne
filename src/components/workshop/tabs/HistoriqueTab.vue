<script setup lang="ts">
import {onMounted, ref} from 'vue';
import SelectInput from '@/components/workshop/ui/SelectInput.vue';
import dInput from '../../../components/base/d-input.vue';
import DEvenementRnDropdown from "@/components/workshop/dropdown/d-evenement-rn-dropdown.vue";
import DEmplacementRnDropdown from "@/components/workshop/dropdown/d-emplacement-rn-dropdown.vue";
import DCustomerDropdown from "@/components/common/d-customer-dropdown.vue";
import DModalHistoriqueRn from "@/components/workshop/_partial/d-modal-historique-rn.vue";
import axiosInstance from '../../../config/http';

const props = defineProps({
    workshopOrderId: {
        type: [Number, null]
    }
});

const historicalRecords = ref([]);
const data = ref({
    formData: {
        event: 'test',
        location: '',
        startDate: '',
        endDate: '',
        customer: ''
    }
});

const getHistoriqueRn = async () => {
    const res = await axiosInstance.get(`/api/workshopRnHistories/workshopOrder/${props.workshopOrderId}`);
    historicalRecords.value = res.data.response;
}

const handleClose = async () => {
    await getHistoriqueRn()
}

onMounted(() => {
    getHistoriqueRn();
})
</script>

<template>
    <div class="historique-tab">
        <div class="my-3">
            <button class="btn btn-custom font-size-0-7 text-uppercase" data-bs-toggle="modal"
                    data-bs-target="#modalAgentManageHistoriqueRn">Nouveau déplacement
            </button>
            <d-modal-historique-rn @onClose="handleClose" :workshopOrderId="props.workshopOrderId"></d-modal-historique-rn>
        </div>
        <div class="col-12 ps-0" style="overflow-x: auto;">
            <table class="table table-striped">
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
                            <d-evenement-rn-dropdown :show-only-dropdown="true" v-model="record.event_type_id"
                                                     rootClass="pink-bg"/>
                        </div>
                    </td>
                    <td>
                        <div class="col-12 px-2">
                            <d-emplacement-rn-dropdown :show-only-dropdown="true" v-model="record.location_id"
                                                       rootClass="pink-bg" :disabled="record.location_id != 12"/>
                        </div>
                    </td>
                    <td>
                        <div class="col-12 px-2">
                            <d-input type="datetime-local"
                                     v-model="record.begin_at"/>
                        </div>
                    </td>
                    <td>
                        <div class="col-12 px-2">
                            <d-input type="datetime-local"
                                     v-model="record.end_at"/>
                        </div>
                    </td>
                    <td>
                        <div class="col-12 px-2">
                            <d-customer-dropdown :show-only-dropdown="true" :disabled="true"
                                                 v-model="record.customer_id"/>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
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
