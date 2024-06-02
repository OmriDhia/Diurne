<template>
    <div>
        <button type="button" class="btn btn-icon p-0"  data-bs-toggle="modal" :data-bs-target="'#Modal_'+props.customerId">
            <vue-feather type="file-text"></vue-feather>
        </button>
        <div class="modal animated fadeInDown" :id="'Modal_'+props.customerId" tabindex="-1" role="dialog" aria-labelledby="fadeinModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fadeinModalLabel">Historique évènement</h5>
                        <button type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <d-event-histories :customerId="props.customerId"></d-event-histories>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import dEventHistories from "./d-event-histories.vue";
    import VueFeather from 'vue-feather';
    
    const props = defineProps({
        customerId : {
            type: Number
        }
    });
</script>
