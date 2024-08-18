<template>
    <div class="row">
        <div class="col-lg-12 col-xl-6">
            <div class="row">
                <div class="col-12">
                    Evènement
                </div>
                <perfect-scrollbar tag="div" class="h-200 pe-1 col-12"
                                   :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 200, suppressScrollX: true }">
                    <div class="table-checkable table-highlight-head table-responsive block-custom-border">
                        <table role="table" aria-busy="false" aria-colcount="5" class="histories-event-table table b-table table-striped table-hover table-bordered">
                            <tbody role="rowgroup">
                            <tr v-for="(item, i) in datas" :key="item.event_id" :class="{'selected': i === selected}" @click="handleComment(i)">
                                <td aria-colindex="1" role="cell">
                                    <div class="row align-items-center" v-if="i === selected">
                                        <div class="col-1"><vue-feather type="play"></vue-feather></div>
                                        <div class="col-10">{{ item.nomenclature.subject }}</div>
                                    </div>
                                    <div v-else>
                                        {{ item.nomenclature.subject }}
                                    </div>
                                </td>
                                <td aria-colindex="2" role="cell" class="">
                                    {{ $Helper.FormatDate(item.event_date.date,"DD/MM/YYYY")}}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </perfect-scrollbar>
            </div>
        </div>
        <div class="col-xl-6 col-lg-12">
            <div class="row">
                <div class="col-12">
                    Commentaire
                </div>
            <perfect-scrollbar tag="div" class="h-200-forced col-12 block-custom-border"
                               :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 200, suppressScrollX: true }">
                
                    <textarea id="textarea" v-model="comment" class="form-control h-200-forced"></textarea>
                <div class="position-absolute d-flex bottom-0">
                    <div class="col-auto p-1">
                        <d-delete :api="''"></d-delete>
                    </div>
                    <div class="col-auto p-1 pe-4">
                        <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" data-bs-toggle="modal" data-bs-target="#modalEventManage">
                            <vue-feather type="search" size="14"></vue-feather>
                        </button>
                    </div>
                </div>
            </perfect-scrollbar>
            </div>
        </div>
        <d-modal-manage-event :eventData="selectedEvent" ></d-modal-manage-event>
    </div>
</template>

<script setup>
    import { ref, onMounted, watch} from "vue"
    import axiosInstance from "../../../config/http";
    import VueFeather from 'vue-feather';
    import dDelete from "../../common/d-delete.vue";
    import dModalManageEvent from "../../contacts/d-modal-manage-event.vue";
    const props = defineProps({
        customerId: {
            type: Number
        }
    });
    
    const datas = ref([]);
    const selected = ref(null);
    const selectedEvent = ref(null);
    const comment = ref("");
    const getEventHistories = async (customerId) => {
      try{
          const res = await axiosInstance.get(`/api/customer/${customerId}/events`);
          datas.value = res.data.response.customerEventsData;
          await handleComment(0);
      }catch{
          console.log("Erreur get events customer")
      }
    };
    const handleComment = async (index) => {
        selected.value = index;
        comment.value = datas.value[index].commentaire;
        selectedEvent.value = datas.value[index];
    };
    onMounted(()=>{
        getEventHistories(props.customerId);
    });
    watch(
        () => props.customerId,
        (newVal) => {
            getEventHistories(newVal)
        }
    );
</script>

<style scoped>
    .table > thead > tr > th {
        color: #000000;
        font-weight: 700;
        font-size: 11px;
    }
    .table > tbody > tr > td {
        font-size: 0.8rem;
    }
</style>
