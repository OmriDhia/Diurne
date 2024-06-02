<template>
    <div class="row">
        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    Evènement
                </div>
                <perfect-scrollbar tag="div" class="h-250 col-12"
                                   :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 300, suppressScrollX: true }">
                    <div class="table-checkable table-highlight-head table-responsive">
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
        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    Commentaire
                </div>
            <perfect-scrollbar tag="div" class="h-250 col-12"
                               :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 300, suppressScrollX: true }">
                
                    <textarea id="textarea" v-model="comment" class="form-control h-250"></textarea>
               
            </perfect-scrollbar>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted, watch} from "vue"
    import axiosInstance from "../../../config/http";
    import VueFeather from 'vue-feather';
    const props = defineProps({
        customerId: {
            type: Number
        }
    });
    
    const datas = ref([]);
    const selected = ref(null);
    const comment = ref("");
    const getEventHistories = async (customerId) => {
      try{
          const res = await axiosInstance.get(`/api/customer/${customerId}/events`);
          datas.value = res.data.response.customerEventsData;
      }catch{
          console.log("Erreur get events customer")
      }
    };
    const handleComment = async (index) => {
        selected.value = index;
        comment.value = datas.value[index].commentaire;
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
