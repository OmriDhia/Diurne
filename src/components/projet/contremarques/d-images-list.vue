<template>
    <div class="row mt-5 p-0 block-custom-border">
        <div class="col-md-12 bg-theme text-center p-2">
            Chargement de la nouvelle image
        </div>
        <perfect-scrollbar tag="div" class="h-400-forced col-12 pt-2"
                           :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 200, suppressScrollX: true }">
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="col" v-for="(item, index) in images" :key="index">
                    <div class="card border-0">
                        <img :src="$Helper.getImagePath(item.attachment)" class="card-img-top cursor-pointer" @click="downloadImage(item.attachment)" alt="Image Preview">
                        <div class="card-body p-0 mt-2">
                            <div class="meta-info">
                                <d-image-type-dropdown v-model="item.imageType.id" :hideLabel="true"></d-image-type-dropdown>
                                <h6 class="card-title">{{ item.image_reference}}</h6>
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
                    <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle"  data-bs-toggle="modal" data-bs-target="#modalAddImage">
                        <vue-feather type="save" size="14"></vue-feather>
                    </button>
                </div>
                <div class="col-auto p-1">
                    <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle">
                        <vue-feather type="x" size="14"></vue-feather>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import VueFeather from 'vue-feather';
    import { ref, onMounted } from 'vue';
    import contremarqueService from "../../../Services/contremarque-service";
    import attachmentService from "../../../Services/attachment-service";
    import dModalAddImage from "./_Partials/d-modal-add-image.vue";
    import axios from 'axios';
    import dImageTypeDropdown from "./dropdown/d-image-type-dropdown.vue";
    
    const props = defineProps({
        carpetDesignOrderId : {
            type: Number
        }
    });
    
    const images = ref([]);
    
    const getImages = async () => {
        try {
            images.value = await contremarqueService.getCarpetDesignImages(props.carpetDesignOrderId);
           
        } catch (e) {
            console.error(e.message);
        }
    };
    const handleClose = async () => {
            await getImages()
    };
    const downloadImage = async (attachment) => {
        await attachmentService.downloadFile(attachment);
    };

    onMounted(async () => {
        await getImages();
    });
    
</script>
<style scoped>
    .card-img-top {
        object-fit: cover;
    }
    .card-body {
        padding-top: 10px;
    }
</style>

