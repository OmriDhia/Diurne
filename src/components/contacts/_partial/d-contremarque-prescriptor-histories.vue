<template>
        <div class="col-lg-12 col-xl-6">
            <div v-if="datas[0]">
                <perfect-scrollbar tag="div" class="h-200-forced p-0 col-12 block-custom-border"
                                   :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 200, suppressScrollX: true }">
                    <div class="table-checkable table-highlight-head">
                        <table role="table" aria-busy="false" aria-colcount="5" class="histories-event-table table b-table table-striped">
                            <tbody role="rowgroup">
                            <tr v-for="(item, i) in datas" :key="i">
                                <td aria-colindex="1" role="cell">
                                    {{ item.designation }}
                                </td>
                                <td aria-colindex="2" role="cell" class="" width="80">
                                    <div class="row justify-content-center align-items-center">
                                        <div class="col-auto p-0">
                                            <d-delete></d-delete>
                                        </div>
                                        <div class="col-auto p-0">
                                            <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click.prevent="gotToContremarque(item.contremarque_id)">
                                                <vue-feather type="eye" :size="14"></vue-feather>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </perfect-scrollbar>
            </div>
            <div v-else>
                <div class="col-12">
                    Ce client ne prescrit aucune contremarque.
                </div>
            </div>
        </div>
</template>

<script setup>
    import { ref, onMounted, watch} from "vue"
    import axiosInstance from "../../../config/http";
    import VueFeather from 'vue-feather';
    import dBtnOutlined from '../../base/d-btn-outlined.vue'
    import dDelete from "../../common/d-delete.vue";

    const props = defineProps({
        customerId: {
            type: Number
        }
    });
    
    const datas = ref([]);
    const getContremarqueHistories = async (customerId) => {
      try{
          if(customerId){
              let url = `/api/contremarques/prescriber/${customerId}`;
              const res = await axiosInstance.get(url);
              datas.value = res.data.response;
          }
      }catch{
          console.log("Erreur get contremarque customer")
      }
    };
    onMounted(()=>{
        getContremarqueHistories(props.customerId);
    });
    watch(
        () => props.customerId,
        (newVal) => {
            getContremarqueHistories(newVal)
        }
    );
    const gotToContremarque = (id) => {
        location.href = "/projet/contremarques/manage/" + id;
    };
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
    .table-striped>tbody>tr:nth-of-type(odd)>* {
        --bs-table-accent-bg: var(--bs-table-bg);
    }
    .table>:not(caption)>*>*{
        --bs-table-accent-bg: #EDF9FD;
    }
</style>
