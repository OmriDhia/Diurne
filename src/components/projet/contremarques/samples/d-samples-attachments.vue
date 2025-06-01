<template>
    <div class="row m-2 block-custom-border">
        <div class="col-md-12 bg-theme text-center p-2">
            Document joints à la DI :
        </div>
        <perfect-scrollbar tag="div" class="h-200-forced col-12 p-0"
                           :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 200, suppressScrollX: true }">
            <ul class="list-group task-list-group">
                <li class="list-group-item list-group-item-action border-0 border-bottom-1" v-for="(data,index) in attachmentData" :key="index">
                    <div class="checkbox-primary custom-control">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-8">  {{ data.attachment.fromDistantServer ? data.attachment.path : data.attachment.file }} </div>
                            <div class="col-md-4 d-flex justify-content-end" v-if="!disable">
                                <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click.prevent="handleDownload(data.attachment)" v-if="!data.attachment.fromDistantServer">
                                    <vue-feather type="download" :size="14"></vue-feather>
                                </button>
                                <d-delete :api="`/api/attachment/${data.attachment?.id}`" @isDone="getAttachments"></d-delete> 
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </perfect-scrollbar>
        <div class="col-md-12" v-if="!props.disable">
            <div class="row justify-content-center">
                <div class="col-auto">
                    <d-modal-add-attachment :carpetDesignOrderId="props.carpetDesignOrderId" :diId="props.diId" @onClose="handleClose"></d-modal-add-attachment>
                    <button class="btn btn-custom pe-5 ps-5 mb-2" data-bs-toggle="modal" data-bs-target="#modalAddAttachment" :disabled="startUpload">
                        Parcourir
                        <btn-load-icon v-if="startUpload" class="ms-2"></btn-load-icon>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import { ref, onMounted, watch } from "vue";
    import VueFeather from 'vue-feather';
    import axiosInstance from "../../../../config/http";
    import dDelete from "../../../../components/common/d-delete.vue";
    import btnLoadIcon from "../../../../components/common/svg/btn-load-icon.vue";
    import attachmentService from "../../../../Services/attachment-service";
    import {Helper} from "../../../../composables/global-methods";
    import dModalAddAttachment from "../_Partials/d-modal-add-attachment.vue";
    
    const props = defineProps({
        carpetDesignOrderId: {
            type: Number,
        },
        diId: {
            type: Number,
        },
        disable: {
            type: Boolean,
            default: false
        }
    });
    
    const startUpload = ref(false);
    const attachmentData = ref([]);
    
    const getAttachments = async () => {
        startUpload.value = true;
        let res = {};
        if(props.carpetDesignOrderId){
            res = await axiosInstance.get(`/api/carpetDesignOrderAttachments/${props.carpetDesignOrderId}`);
        }else if(props.diId){
            res = await axiosInstance.get(`/api/projectDi/${props.diId}/attachments`);
        }
        
        if(res && res.data && res.data.response){
            attachmentData.value = res.data.response
        }
        startUpload.value = false;
    };
    
    const handleDownload = async (attachment) => {
        await attachmentService.downloadFile(attachment);
    };

    const handleClose = async () => {
        await getAttachments();
    };
    
    watch(
        () => props.carpetDesignOrderId,
        (newVal) => {
            getAttachments();
        }
    );
    
    watch(
        () => props.diId,
        (newVal) => {
            getAttachments();
        }
    );
    
    onMounted(async () => {
        await getAttachments();
    });
    
</script>
