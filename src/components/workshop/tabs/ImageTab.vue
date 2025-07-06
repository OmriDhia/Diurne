<template>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Image Preview</h5>
                        <div class="image-placeholder">
                            <img v-if="data.imageUrl" :src="data.imageUrl" alt="Uploaded image" class="img-fluid">
                            <div v-else class="placeholder-content">
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">No image available</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-row ">
                    <d-input label="Nom du fichier" rootClass="pink-bg" class="w-100"
                             v-model="data.formData.nomFichier" />
                </div>

                <div class="form-row ">
                    <d-input label="Date de création" rootClass="pink-bg" type="date" class="w-100"
                             v-model="data.formData.dateCreation" />
                </div>

                <div class="form-row ">
                    <d-input label="Format" class="w-100" v-model="data.formData.format" />
                </div>
                
                <d-location-dropdown :disabled="true" :contremarque-id="props.imageCommande?.carpetDesignOrder?.location?.contremarque_id" v-model="data.formData.emplacement" :required="true"></d-location-dropdown>
                <d-image-type-dropdown v-model="data.formData.type" :required="true"></d-image-type-dropdown>
                <d-upload-file @file-selected="selectedFile($event)"></d-upload-file>
                <div class="row mt-2 justify-content-end">
                    <d-btn-outlined icon="plus" label="Ajouter" @clickBtn="addNewImage"></d-btn-outlined>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <perfect-scrollbar tag="div" class="h-400-forced col-12 pt-2"
                               :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 200, suppressScrollX: true }">
                <div class="row g-4">
                    <div class="col-lg-3 col-md-4 col-sm-6" v-for="(item, index) in images" :key="index">
                        <div class="card border-0">
                            <!-- Checkbox for selecting image for deletion -->
                            <input type="checkbox" class="position-absolute top-0 end-0 m-2"
                                   :id="'delete-checkbox-t' + index" v-model="selectedImages" :value="item.id"
                                   :disabled="props.disabled" />
                            <img :src="$Helper.getImagePath(item.attachment)" class="card-img-top cursor-pointer"
                                 @click="downloadImage(item.attachment)" alt="Image Preview" />
                            <div class="card-body p-0 mt-2">
                                <div class="meta-info">
                                    <h6 class="card-title text-center p-1">{{ item.file_name }}</h6>
                                    <d-image-type-dropdown :disabled="true" v-model="item.id_image_type"
                                                           :hideLabel="true" @imageTypeSelected="updateImageTypes(index, $event)"
                                    ></d-image-type-dropdown>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </perfect-scrollbar>
        </div>
    </div>
</template>
<script setup>
import {onMounted, ref, watch} from 'vue';
import dInput from '../../../components/base/d-input.vue';
import DImageTypeDropdown from "@/components/projet/contremarques/dropdown/d-image-type-dropdown.vue";
import DLocationDropdown from "@/components/projet/contremarques/dropdown/d-location-dropdown.vue";
import axiosInstance from "@/config/http.js";
import DUploadFile from "@/components/common/d-upload-file.vue";
import DBtnOutlined from "@/components/base/d-btn-outlined.vue";
import {useStore} from "vuex";

    const props = defineProps({
        workshopOrderId: {
            type: Number,
            required: false
        },
        imageCommandId: {
            type: Number,
            required: true
        },
        imageCommande: {
            type: Object,
            required: false
        },
    });

    const store = useStore();
    const images = ref([]);
    const data = ref({
        formData: {
            nomFichier: '',
            dateCreation: '',
            format: '',
            emplacement: '',
            type: '',
            file: '',
        },
        imageUrl: '/assets/images/No-Image-Placeholder.svg'
    });
    
    const getImages = async () => {
        const res = await axiosInstance.get(`/api/workshopImages/workshopOrder/${props.workshopOrderId}`);
        images.value = res.data.response;
    }

    const addNewImage = async () => {
        const formData = new FormData();
        formData.append('fileName', data.value.formData.nomFichier);
        formData.append('idImageType', data.value.formData.type);
        formData.append('format', data.value.formData.format);
        formData.append('locationId', data.value.formData.emplacement);
        formData.append('workshopOrderId', props.workshopOrderId);
        formData.append('file', data.value.formData.file);  // binary file here!
        formData.append('idImageType', store.getters.defaultTypeImageId);  // binary file here!
    
        try{
            const res = await axiosInstance.post(`/api/workshopImages`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            window.showMessage("Ajout d'un image avec succées.")
            await getImages();
        } catch (error) {
            
        }
        
    };
    
    onMounted(() => {
        getImages();
        data.value.formData.emplacement = props.imageCommande?.carpetDesignOrder?.location?.location_id;
    })

    let previousObjectUrl = null;
    const selectedFile = (event) => {
        data.value.formData.file = event;
        if (event instanceof File) {
            if (previousObjectUrl) {
                URL.revokeObjectURL(previousObjectUrl);
            }
            previousObjectUrl = URL.createObjectURL(event);
            data.value.imageUrl = previousObjectUrl;
        }
    };

    const updateImageTypes = (index, name) => {
        console.log(name);
    };
    
    watch(
        () => props.imageCommande,
        () => {
            console.log(props.imageCommande?.carpetDesignOrder?.location?.location_id);
        },
        { deep: true }
    );
    
</script>
