<template>
    <div>
        <d-base-modal id="modalAddAttachment" title="Ajouter un attachement" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-attachment-type-dropdown v-model="attachmentTypeId"></d-attachment-type-dropdown>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input label="Url distant" v-model="distantFilePath"></d-input>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center" v-if="attachmentTypeId === defaultTypeImageId">
                        <div class="col-sm-12 col-md-8">
                            <div class="row">
                                <div class="col-sm-12 col-md-4 text-black"> Image: </div>
                                <div class="col-sm-12 col-md-8">
                                    <input type="file" class="form-control" @change="onFileChange" accept="image/*" />
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
    import { ref, computed } from 'vue';
    import attachmentService from '../../../../Services/attachment-service';
    import dInput from "../../../../components/base/d-input.vue";
    import dBaseModal from "../../../../components/base/d-base-modal.vue";
    import axiosInstance from "../../../../config/http";
    import dImageTypeDropdown from "../dropdown/d-image-type-dropdown.vue";
    import dAttachmentTypeDropdown from "../dropdown/d-attachment-type-dropdown.vue";
    import { useStore } from 'vuex';
    
    const props = defineProps({
        carpetDesignOrderId : {
            type: Number
        },
        diId: {
            type: Number,
        }
    });

    const store = useStore();
    
    const file = ref(null);
    const attachmentTypeId = ref(0);
    const distantFilePath = ref(null);
    const uploadProgress = ref(0);
    let uplodedImage = null;
    let createdImage = null;
    const defaultTypeImageId = computed(() => store.getters.defaultTypeImageId)
    
    const data = ref({
        image_reference: "",
        carpetDesignOrderId: 0,
        imageTypeId: 0,
        isValidated: false,
        hasError: false,
        error: "",
        commentaire: "",
        validatedAt: new Date()
    });

    // Handle file selection
    const onFileChange = (event) => {
        uploadProgress.value = 0;
        file.value = event.target.files[0];
    };
    
    const submitFile = async () => {
        if (!file.value) {
            window.showMessage('Veuillez selectionner un fichier !','error');
            return;
        }

        try {
            uploadProgress.value = 0;
            const response = await attachmentService.uploadFile(
                file.value,
                attachmentTypeId.value,
                distantFilePath.value,
                (progress) => {
                    uploadProgress.value = progress;
                }
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
            window.showMessage('Upload fichier avec succées')
            document.querySelector("#modalAddAttachment .btn-close").click();
        } catch (error) {
            console.log(error);
            window.showMessage('Erreur upload fichier', 'error')
        }
    };

    const emit = defineEmits(['onClose']);
    
    const handleClose = () => {
        file.value = null;
        attachmentTypeId.value = 0;
        distantFilePath.value = null;
        uploadProgress.value = 0;
        emit('onClose')
    }
</script>
