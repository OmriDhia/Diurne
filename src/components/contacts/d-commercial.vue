<template>
    <div class="row align-items-center mb-2 pe-0">
        <div class="col-md-3 col-sm-12"><label class="form-label">Commertial:</label></div>
        <div class="col-md-9 col-sm-12">
            <multiselect 
                :class="{ 'is-invalid': error}"
                v-model="commertial"
                :options="commertials"
                placeholder="commertial"
                track-by="user_id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @search-change="handleSearch($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le type de d'adresse est abligatoire.") }}</div>
        </div>
    </div>
    <div class="row">
        <d-date-start-end :dates="dates" @update:dates="updateDates($event)"></d-date-start-end>
    </div>
    <div class="row justify-content-end mt-3">
        <div class="col-auto p-0">
            <button class="btn btn-custom pe-3 ps-3" @click="addAttribution">Demande d'attribution</button>
        </div>
    </div>
    <div class="row justify-content-end mt-3 pe-0">
        <perfect-scrollbar tag="div" class="h-100"
                           :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 300, suppressScrollX: true }">
            <d-commertial-histories :commertialData="commercialData"></d-commertial-histories>
        </perfect-scrollbar>
    </div>

</template>

<script setup>
    import { ref , onMounted, watch} from "vue";
    import dDateStartEnd from "../common/d-date-start-end.vue";
    import Multiselect from '@suadelabs/vue3-multiselect';
    import axiosInstance from "../../config/http";
    import dCommertialHistories from "./d-commertial-histories.vue";
    import perfectScroll from "../plugins/perfect-scrollbar1.vue";
    
    const props = defineProps({
        customerId: {
            type: Number,
        },
        commertialData: {
            type: Array
        }
    });

    const commertials = ref([]);
    const commertial = ref('');
    const dates = ref({startDate: null, endDate: null});
    const commercialData = ref([]);

    const addAttribution = async () => {
        try{
            let url = 'api/AssignCommercialToCustomer';
            const res = await axiosInstance.post(url,{
                commercialId: commertial.value.user_id,
                customerId: props.customerId,
                isValidated: false,
                fromDate: dates.value.startDate,
                toDate: dates.value.endDate
            });
            
            const name = commertial.value.name.split(' ');
            commercialData.value.push({
                commercial_id: commertial.value.user_id,
                customer_id: props.customerId,
                firstname: name[0],
                lastname: name[1],
                from: dates.value.startDate,
                to: dates.value.endDate,
                is_validated: false,
            });
            window.showMessage("Commercial ajouté avec succès.");
        }catch (e){
            window.showMessage("Veuillez vérifier les dates et les commerciaux choisis.","error");
        }
    };
    
    const handleSearch = (searchQuery) => {
        const se = searchQuery.split(' ');
        getCommertials(se[0], se[1]);
    };
    
    const updateDates = (date) => {
        dates.value = date
    };
    
    const getCommertials = async (firstname = "", lastname = "") => {
        try{
            let url = 'api/commercials?page=1&itemPerPage=100';
            
            if(firstname){
                url += '&filter[firstname]='+firstname;   
            }

            if(lastname){
                url += '&filter[lastname]='+lastname;
            }
            
            const res = await axiosInstance.get(url);
            commertials.value = res.data.response.commercials.map(com => {
                return {
                    name: com.firstname + ' ' + com.lastname,
                    user_id: com.user_id
                }
            });
        }catch{
            console.log('Erreur get commertials list.')
        }
    };
    
    onMounted(()=>{
        getCommertials()
        commercialData.value = props.commertialData;
    });
    
    watch(
        () => props.commertialData,
        (newVal) => {
            commercialData.value = newVal
        }
    );
</script>
