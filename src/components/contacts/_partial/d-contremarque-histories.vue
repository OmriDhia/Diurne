<template>
    <div class="row">
        <div class="col-lg-12 col-xl-6">
            <div class="row" v-if="datas[0]">
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
            <div class="row" v-else>
                <div class="col-12">
                    Ce client ne possède pas des contremarques.
                </div>
            </div>
            <div class="row justify-content-end mt-2">
                <d-btn-outlined class="pe-0" label="Ajouter" icon="plus" @click="goToCreateContremarque"></d-btn-outlined>
            </div>
        </div>
        <div class="col-xl-6 col-lg-12">
            <div class="row">
                
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
              let url = '/api/contremarques?page=1&limit=100&order=contremarque_id&orderWay=desc`';
              url += "&customerId=" + customerId;
              const res = await axiosInstance.get(url);
              datas.value = res.data.contremarques;
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
    const goToCreateContremarque = () => {
        location.href = "/projet/contremarques/manage?customer_id=" + props.customerId;
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
