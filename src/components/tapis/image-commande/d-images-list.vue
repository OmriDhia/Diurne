<template>
    <div class="row mt-5 p-0 block-custom-border">
        <div class="col-md-12 bg-theme text-center p-2">Chargement de la nouvelle image</div>
        <perfect-scrollbar tag="div" class="h-400-forced col-12 pt-2"
            :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 200, suppressScrollX: true }">
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="col" v-for="(item, index) in images" :key="index">
                    <div class="card border-0">
                        <!-- Checkbox for selecting image for deletion -->
                        <input type="checkbox" class="position-absolute top-0 end-0 m-2"
                            :id="'delete-checkbox-t' + index" v-model="selectedImages" :value="item.id"
                            :disabled="props.disabled" />
                        <img :src="$Helper.getImagePath(item.attachment)" class="card-img-top cursor-pointer"
                            @click="downloadImage(item.attachment)" alt="Image Preview" />
                        <div class="card-body p-0 mt-2">
                            <div class="meta-info">
                                <d-image-type-dropdown :disabled="props.disabled" v-model="item.imageType.id"
                                    :hideLabel="true" @imageTypeSelected="updateImageTypes(index, $event)"
                                    @imageTypeUpdateSelected="UpdateImageReference(item.id, item.imageType.id)"></d-image-type-dropdown>
                                <h6 class="card-title">{{ item.image_reference }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </perfect-scrollbar>
        <d-modal-add-image :carpetDesignOrderId="props.carpetDesignOrderId" @onClose="handleClose"></d-modal-add-image>
        <div class="col-md-12">
            <div class="row justify-content-end pe-2">
                <div class="col-auto p-1">
                    <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" :disabled="props.disabled"
                        data-bs-toggle="modal" data-bs-target="#modalAddImage">
                        <vue-feather type="save" size="14"></vue-feather>
                    </button>
                </div>
                <div class="col-auto p-1">
                    <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" :disabled="props.disabled"
                        @click="deleteSelectedImages">
                        <vue-feather type="x" size="14"></vue-feather>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import VueFeather from 'vue-feather';
import { ref, onMounted, watch } from 'vue';
import contremarqueService from '../../../Services/contremarque-service';
import attachmentService from '../../../Services/attachment-service';
import dModalAddImage from '../../tapis/image-commande/_Partials/d-modal-add-image.vue';
import axios from 'axios';
import dImageTypeDropdown from '../../projet/contremarques/dropdown/d-image-type-dropdown.vue';
import axiosInstance from '../../../config/http';

const props = defineProps({
    imageCommandeId: {
        type: Number,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    status: {
        type: Number,
        default: 0,
    },
});
watch(
    () => props.status,
    (newStatus) => {
    },
    { deep: true }
);

const images = ref([]);
const selectedImages = ref([]); // Array to hold the selected image IDs
const emit = defineEmits(['imageTypesUpdated', 'imageLists']); // 🔥 Define emit correctly

const getImages = async () => {
    try {
        images.value = await contremarqueService.getCarpetDesignImages(props.carpetDesignOrderId);
        emitSelectedImageTypes(); // Emit the image types when images are fetched
    } catch (e) {
        console.error(e.message);
    }
};
const handleClose = async () => {
    await getImages();
};
const downloadImage = async (attachment) => {
    await attachmentService.downloadFile(attachment);
};

onMounted(async () => {
    await getImages();
});

const selectedImageTypes = ref([]); // Stores selected image type names

// Watch for any changes in the images list and emit selected image types
watch(images, () => {
    emitSelectedImageTypes();
});
const updateImageTypes = (index, name) => {
    selectedImageTypes.value[index] = name;
    // console.log(name);
    emitSelectedImageTypes();
};
const UpdateImageReference = async (imageId, imageTypeId) => {
    // console.log(imageId);
    try {
        const response = await axiosInstance.put('/api/image/update-type', {
            imageId: imageId,
            imageTypeId: imageTypeId,
        });

        // Optionally, handle success
        window.showMessage('Image type updated successfully.', 'success');
    } catch (error) {
        console.error('Error updating image type:', error);
        window.showMessage('An error occurred while updating the image type.', 'error');
    }
};

const emitSelectedImageTypes = () => {
    const uniqueTypes = [...new Set(selectedImageTypes.value)]; // Remove duplicates
    emit('imageTypesUpdated', uniqueTypes);
};

// Method to delete selected images
const deleteSelectedImages = async () => {
    try {
        if (selectedImages.value.length === 0) {
            window.showMessage('Please select at least one image to delete.', 'error');
            return;
        }
        const response = await axiosInstance.delete(`/api/image/delete`, {
            data: {
                imageIds: selectedImages.value, // Pass imageIds inside the 'data' property
            },
        });
        selectedImages.value = [];
        // Refresh the image list after deletion
        await getImages();
        window.showMessage('Selected images deleted successfully.');
    } catch (e) {
        console.error('Error deleting images:', e.message);
        window.showMessage(e.message, 'error');
    }
};
</script>
<style scoped>
.card-img-top {
    object-fit: cover;
}

.card-body {
    padding-top: 10px;
}
</style>
