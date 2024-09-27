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
                            <div class="col-md-8">  {{data.attachment.file}} </div>
                            <div class="col-md-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click.prevent="handleDownload(data.attachment)">
                                    <vue-feather type="download" :size="14"></vue-feather>
                                </button>
                                <d-delete :api="''"></d-delete> 
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </perfect-scrollbar>
        <div class="col-md-12">
            <div class="row justify-content-center">
                <div class="col-auto">
                    <input type="file" @change="onFileChange" accept="image/*" ref="fileInput" hidden />
                    <button class="btn btn-custom pe-5 ps-5 mb-2" @click="triggerFileInput" :disabled="startUpload">
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
    import axios from 'axios';
    import axiosInstance from "../../../../config/http";
    import dDelete from "../../../../components/common/d-delete.vue";
    import btnLoadIcon from "../../../../components/common/svg/btn-load-icon.vue";
    import attachmentService from "../../../../Services/attachment-service";
    import {Helper} from "../../../../composables/global-methods";
    
    const props = defineProps({
        carpetDesignOrderId: {
            type: Number,
        },
        diId: {
            type: Number,
        }
    });
    
    const fileInput = ref(null);
    const fileType = ref('image');
    const startUpload = ref(false);
    const file = ref(null);
    const attachmentData = ref([]);
    
    const triggerFileInput = () => {
        fileInput.value.click()
    };

    const onFileChange = (event) => {
        startUpload.value = true;
        file.value = event.target.files[0];
        submitFile();
    };

    const submitFile = async () => {
        if (!file.value) {
            window.showMessage('Veuillez selectionner un fichier !','error');
            return;
        }
        
        try {

            const response = await attachmentService.uploadFile(
                file.value,
                fileType.value
            );
            const uplodedFile = response.id;
    
            if(props.carpetDesignOrderId){
                const res = await axiosInstance.post("/api/createCarpetDesignOrderAttachment", {
                    carpetDesignOrderId: parseInt(props.carpetDesignOrderId),
                    attachmentId: uplodedFile,
                }); 
            }else if(props.diId){
                const res = await axiosInstance.post("/api/createDiAttachment", {
                    diId: parseInt(props.diId),
                    attachmentId: uplodedFile,
                });
            }
            startUpload.value = false;
            getAttachments();
            window.showMessage('Upload fichier avec succées')
        } catch (error) {
            console.log(error);
            window.showMessage('Erreur upload fichier', 'error')
        }
    };
    
    const getAttachments = async () => {
        let res = {};
        if(props.carpetDesignOrderId){
            res = await axiosInstance.get(`/api/carpetDesignOrderAttachments/${props.carpetDesignOrderId}`);
        }else if(props.diId){
            res = await axiosInstance.get(`/api/projectDi/${props.diId}/attachments`);
        }
        
        if(res && res.data && res.data.response){
            attachmentData.value = res.data.response
        }
    };
    
    const handleDownload = async (attachment) => {
        await attachmentService.downloadFile(attachment);
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
