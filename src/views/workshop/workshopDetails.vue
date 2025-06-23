<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title icon="carpet" :title="`Tapis (${workshopOrder?.workshopInformation?.rn})`"></d-page-title>
        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-2">
               
            </div>
        </div>
    </div>
</template>
<script setup>
import dInput from '@/components/base/d-input.vue';
import {onMounted, ref} from 'vue';
import {useRoute} from "vue-router";
import workshopService from "@/Services/workshop-service.js";
import DPageTitle from "@/components/common/d-page-title.vue";

const route = useRoute();
const workshopOrderId = parseInt(route.params.workshopOrderId);
const workshopOrder = ref({})
const loading = ref(false);

const getWorkshopOrder = async () => {
    loading.value = true;
    workshopOrder.value = await workshopService.getWorkshopOrder(workshopOrderId);
    loading.value = false;
    console.log(workshopOrder.value);
}

onMounted(getWorkshopOrder)

</script>
