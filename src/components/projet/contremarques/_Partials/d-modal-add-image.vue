<template>
    <div>
        <d-base-modal id="modalAddImage" title="Ajouter Image" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input label="Référence" v-model="data.image_reference"></d-input>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <div class="row">
                                <div class="col-sm-12 col-md-4 text-black"> Commentaire: </div>
                                <div class="col-sm-12 col-md-8">
                                   <textarea v-model="data.commentaire" class="block-custom-border w-100"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <div class="row">
                                <div class="col-sm-12 col-md-4 text-black"> Image: </div>
                                <div class="col-sm-12 col-md-8">
                                    <input type="file" @change="onFileChange" accept="image/*" />
                                    <div class="pt-3" v-if="uploadProgress">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" :style="{width: uploadProgress+'%'}"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="submitFile">Ajouter</button>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { ref } from 'vue';
    import attachmentService from '../../../../Services/attachment-service';
    import dInput from "../../../../components/base/d-input.vue";
    import dBaseModal from "../../../../components/base/d-base-modal.vue";
    import axiosInstance from "../../../../config/http";

    const props = defineProps({
        carpetDesignOrderId : {
            type: Number
        }
    });

    const file = ref(null);
    const fileType = ref('image');
    const uploadProgress = ref(0);
    let uplodedImage = null;
    let createdImage = null;
    
    const data = ref({
        image_reference: "",
        carpetDesignOrderId: 0,
        isValidated: false,
        hasError: false,
        error: "",
        commentaire: "",
        validatedAt: new Date()
    });

    // Handle file selection
    const onFileChange = (event) => {
        file.value = event.target.files[0];
    };

    // Handle form submission
    const submitFile = async () => {
        if (!file.value) {
            uploadError.value = 'Please select a file to upload.';
            return;
        }
        
        uploadProgress.value = 0;

        try {
            if(!uplodedImage){
                const response = await attachmentService.uploadFile(
                    file.value,
                    fileType.value,
                    (progress) => {
                        uploadProgress.value = progress;
                    }
                );
                window.showMessage('Upload image avec succées')
                uplodedImage = response.id
            }
            
            try{
                if(!createdImage){
                    data.value.carpetDesignOrderId = parseInt(props.carpetDesignOrderId);
                    const res = await axiosInstance.post("/api/createImage", data.value);
                    createdImage = res.data.response.id
                }
                try{
                    const res = await axiosInstance.post("/api/createImageAttachment", {
                        imageId: createdImage,
                        attachmentId: uplodedImage,
                    });
                    window.showMessage('Objet image créer avec succées')
                    document.querySelector("#modalAddImage .btn-close").click();
                }catch(error){
                    window.showMessage('Erreur creation objet image', 'error')
                }
            }catch(error){
                window.showMessage('Erreur creation objet image', 'error')
            }
        } catch (error) {
            window.showMessage('Erreur upload image !!', 'error');
        }
    };

    const emit = defineEmits(['onClose']);
    
    const handleClose = () => {
        data.value = {
            image_reference: "",
            carpetDesignOrderId: 0,
            isValidated: false,
            hasError: false,
            error: "",
            commentaire: "",
            validatedAt: ""
        };
        file.value = null;
        uploadProgress.value = 0;
        uplodedImage = null;
        createdImage = null;
        emit('onClose')
    }
</script>
