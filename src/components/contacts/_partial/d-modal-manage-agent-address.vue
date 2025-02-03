<template>
    <div>
        <d-base-modal id="modalAgentManageAddress" title="Adresse agent" @onClose="handleClose">
            <template v-slot:modal-body>
               <d-address :intermediary="true" :addressData="props.addressData" :intermediaryId="props.intermediaryId"></d-address>
            </template>
            <template v-slot:modal-footer>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { ref, onMounted, watch } from "vue";
    import dPanelTitle from "../../common/d-panel-title.vue";
    import dBaseModal from "../../base/d-base-modal.vue";
    import dAddress from "../d-address.vue";

    const props = defineProps({
        addressData: {
            type: [Object, null],
        },
        intermediaryId: {
            type: [Number, null]
        }
    });
</script>
