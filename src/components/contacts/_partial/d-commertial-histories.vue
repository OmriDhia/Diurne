<template>
    <div class="table-checkable table-highlight-head table-responsive">
        <table role="table" aria-busy="false" aria-colcount="5" class="table b-table table-striped table-hover table-bordered" id="__BVID__368">
            <thead role="rowgroup" class="">
                <tr role="row">
                    <th role="columnheader" scope="col" aria-colindex="1" class="h">
                        Commercial
                    </th>
                    <th role="columnheader" scope="col" aria-colindex="2" class="">Date d'attribution</th>
                    <th role="columnheader" scope="col" aria-colindex="3" class="">Date de fin</th>
                    <th role="columnheader" scope="col" aria-colindex="4" class="">Etat</th>
                </tr>
            </thead>
            <tbody role="rowgroup">
                <tr v-for="(item, i) in datas" :key="item.commercial_id" role="row" class="">
                    <td aria-colindex="1" role="cell">
                        {{ item.firstname + " " + item.lastname }}
                    </td> 
                    <td aria-colindex="2" role="cell" class="">
                        {{ (item.from) ? $Helper.FormatDate(item.from) : ''}}
                    </td>
                    <td aria-colindex="3" role="cell" class="">
                        {{ (item.to) ? $Helper.FormatDate(item.to) : ''}}
                    </td>
                    <td aria-colindex="4" role="cell" class="">
                        <div title="test" class="t-dot" :class="item.status == 'Pending' ?  'bg-warning' : item.status == 'Accepted' ? 'bg-success' :'bg-danger'"></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
    import { ref, onMounted, watch} from "vue"
    const props = defineProps({
        commertialData: {
            type: Array
        }
    });
    
    const datas = ref([]);
    onMounted(()=>{
        datas.value = props.commertialData;
    });
    watch(
        () => props.commertialData,
        (newVal) => {
            datas.value = newVal
        }
    );
</script>

<style scoped>
    .table > thead > tr > th {
        color: #000000;
        font-weight: 700;
        font-size: 11px;
    }
</style>
