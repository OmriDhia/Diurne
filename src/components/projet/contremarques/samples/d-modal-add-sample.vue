<template>
    <div>
        <d-base-modal id="modalAddSample" title="Maquette échantillon" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="row align-items-center">
                    <div class="col-6">
                        <div class="row p-1 align-items-center">
                            <div class="col-sm-12">
                                <d-location-dropdown :contremarqueId="props.contremarqueId" v-model="data.locationId" :error="error.locationId"></d-location-dropdown>
                            </div>
                            <div class="col-sm-12">
                                <d-input label="Largeur" v-model="data.dimension.height"  :error="error.height"></d-input>
                            </div>
                            <div class="col-sm-12">
                                <d-input label="longueur" v-model="data.dimension.width" :error="error.width"></d-input>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row p-1 align-items-center">
                            <div class="col-12">
                                <d-attachments></d-attachments>
                            </div>
                            <div class="col-12">
                                                    <textarea
                                                        v-model="data.customerComment"
                                                        :class="{ 'is-invalid': error?.customerComment}"
                                                        class="w-100 h-130-forced block-custom-border"
                                                    ></textarea>
                                <div v-if="error?.customerComment" class="invalid-feedback">La description est obligatoire.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="submitFile">Enregistrer</button>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { ref, watch } from 'vue';
    import attachmentService from '../../../../Services/attachment-service.js';
    import dInput from '../../../base/d-input.vue';
    import dBaseModal from '../../../base/d-base-modal.vue';
    import axiosInstance from '../../../../config/http.js';
    import dImageTypeDropdown from '../dropdown/d-image-type-dropdown.vue';
    import dAttachmentTypeDropdown from '../dropdown/d-attachment-type-dropdown.vue';
    import dUploadFile from '../../../common/d-upload-file.vue';
    import { useStore } from 'vuex';
    import DLocationDropdown from "../dropdown/d-location-dropdown.vue";
    import DAttachments from "../_Partials/d-attachments.vue";

    const props = defineProps({
        carpetDesignOrderId: {
            type: Number,
        },
        contremarqueId: {
            type: Number,
        },
    });

    const store = useStore();
    const file = ref(null);
    const attachmentTypeId = ref(1);
    const distantFilePath = ref(null);
    const uploadProgress = ref(0);
    const error = ref({})
    let uplodedImage = null;
    let createdImage = null;

    const data = ref({
        carpetDesignOrderId: null,
        locationId: null,
        collectionId: 1,
        modelId: 1,
        qualityId: 1,
        diCommandNumber: "",
        dimension: {
            width: "",
            height: ""
        },
        rn: "",
        customerComment: "",
        imageIds: [],
        attachmentIds: []
    });

    // Handle file selection
    const slectedFile = (event) => {
        file.value = event;
    };
    watch(
        () => data.value.imageTypeId,
        (newVal) => {
            console.log('Updated imageTypeId in Parent:', newVal);
        },
        { deep: true },
        { immediate: true }
    );

    // Handle form submission
    const submitFile = async () => {
        if (!file.value) {
            uploadError.value = 'Please select a file to upload.';
            return;
        }

        uploadProgress.value = 0;
        console.log(data.value.imageTypeId);
        try {
            if (!uplodedImage) {
                const response = await attachmentService.uploadFile(
                    file.value, 
                    store.getters.defaultTypeImageId, 
                    distantFilePath.value, 
                    (progress) => {
                    uploadProgress.value = progress;
                });
                window.showMessage('Upload image avec succées');
                uplodedImage = response.id;
            }

            try {
                if (!createdImage) {
                    data.value.carpetDesignOrderId = parseInt(props.carpetDesignOrderId);
                    const res = await axiosInstance.post('/api/createImage', data.value);
                    createdImage = res.data.response.id;
                }
                try {
                    const res = await axiosInstance.post('/api/createImageAttachment', {
                        imageId: createdImage,
                        attachmentId: uplodedImage,
                    });
                    window.showMessage('Objet image créer avec succées');
                    document.querySelector('#modalAddImage .btn-close').click();
                } catch (error) {
                    window.showMessage('Erreur creation objet image', 'error');
                }
            } catch (error) {
                window.showMessage('Erreur creation objet image', 'error');
            }
        } catch (error) {
            window.showMessage('Erreur upload image !!', 'error');
        }
    };

    const emit = defineEmits(['onClose']);

    const handleClose = () => {
        data.value = {
            image_reference: '',
            carpetDesignOrderId: 0,
            isValidated: false,
            hasError: false,
            error: '',
            commentaire: '',
            validatedAt: '',
        };
        file.value = null;
        uploadProgress.value = 0;
        uplodedImage = null;
        createdImage = null;
        emit('onClose');
    };
</script>
