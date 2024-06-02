<template>
    <div class="row align-items-center mb-2 pe-0">
        <div class="col-md-3 col-sm-12"><label class="form-label">Commertial:</label></div>
        <div class="col-md-9 col-sm-12">
            <multiselect
                :class="{ 'is-invalid': error}"
                v-model="agent"
                :options="agents"
                placeholder="Agent"
                track-by="id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @search-change="handleSearch($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("L'agent est abligatoire.") }}</div>
        </div>
    </div>
    <div class="row">
        <d-date-start-end :dates="dates" @update:dates="updateDates($event)"></d-date-start-end>
    </div>
    <div class="row justify-content-end mt-3">
        <div class="col-auto p-0">
            <button class="btn btn-outline-custom ps-2" @click="addAttribution">
                Ajouter
                <vue-feather type="plus" size="14"></vue-feather>
            </button>
        </div>
    </div>
    <div class="row justify-content-end mt-3 pe-0">
        <perfect-scrollbar tag="div" class="h-100"
                           :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 300, suppressScrollX: true }">
            <d-agent-histories :agents="agentsData"></d-agent-histories>
        </perfect-scrollbar>
    </div>

</template>

<script setup>
    import { ref , onMounted, watch} from "vue";
    import dDateStartEnd from "../common/d-date-start-end.vue";
    import Multiselect from '@suadelabs/vue3-multiselect';
    import axiosInstance from "../../config/http";
    import VueFeather from 'vue-feather';
    import dAgentHistories from "./_partial/d-agent-histories.vue";
    import perfectScroll from "../plugins/perfect-scrollbar1.vue";

    const props = defineProps({
        customerId: {
            type: Number,
        },
        agents: {
            type: Array
        }
    });

    const agents = ref([]);
    const agent = ref('');
    const dates = ref({startDate: null, endDate: null});
    const agentsData = ref([]);

    const addAttribution = async () => {
        try{
            let url = 'api/AssignIntermediaryToCustomer';
            console.log(agent.value);
            const res = await axiosInstance.post(url,{
                intermediaryId: agent.value.id,
                customerId: props.customerId,
                fromDate: dates.value.startDate,
                toDate: dates.value.endDate
            });

            const name = agent.value.name.split(" ");
            agentsData.value.push({
                intermediary_id: agent.value.id,
                customer_id: props.customerId,
                firstname: name[0],
                lastname: name[1],
                from: dates.value.startDate,
                to: dates.value.endDate,
            });
            window.showMessage("Agent attributé avec succès.");
        }catch (e){
            window.showMessage("Veuillez vérifier les dates et l'agent choisis.","error");
        }
    };

    const handleSearch = (searchQuery) => {
        const se = searchQuery.split(' ');
        getAgents(se[0], se[1]);
    };

    const updateDates = (date) => {
        dates.value = date
    };

    const getAgents = async (firstname = "", lastname = "") => {
        try{
            let url = 'api/agents?page=1&itemPerPage=30';

            if(firstname){
                url += '&filter[firstname]='+firstname;
            }

            if(lastname){
                url += '&filter[lastname]='+lastname;
            }

            const res = await axiosInstance.get(url);
            agents.value = res.data.response.agents.map(com => {
                return {
                    name: com.firstname + ' ' + com.lastname,
                    id: com.id
                }
            });
        }catch{
            console.log('Erreur get agents list.')
        }
    };

    onMounted(()=>{
        getAgents();
        agentsData.value = props.agents;
    });

    watch(
        () => props.agents,
        (newVal) => {
            agentsData.value = newVal;
        }
    );
</script>
