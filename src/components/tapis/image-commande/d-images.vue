<template>
    <div class="p-2">
        <div class="row align-items-start">
            <div class="col-auto pe-1 ps-2 text-black"><h6 class="w-100 p-0">Image de:</h6></div>
            <div class="col-auto pe-1 ps-2">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="ImagesContremarque" v-model="images" name="images" value="contremarque"/>
                    <label class="custom-control-label text-black font-size-0-6" for="ImagesContremarque"> {{ $t('contremarque') }} </label>
                </div>
            </div>
            <div class="col-auto pe-1 ps-1">
                <div class="radio-success custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="ImagesDI" v-model="images" name="images" value="DI"/>
                    <label class="custom-control-label text-black font-size-0-6" for="ImagesDI"> {{ $t('DI') }} </label>
                </div>
            </div>
            <div class="col-auto pe-1 ps-1">
                <div class="radio-success custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="ImagesLocation" v-model="images" name="images" value="location"/>
                    <label class="custom-control-label text-black font-size-0-6" for="ImagesLocation"> {{ $t('Emplacement') }} </label>
                </div>
            </div>
        </div>
        <div class="card p-0">
            <perfect-scrollbar tag="div" class="h-200-forced p-0" :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 200, suppressScrollX: true }">
                <div class="card-body p-0 mt-2">
                    <div class="row row-cols-1 p-2">
                        <div class="col mb-2" v-for="(image, index) in imagesArray" :key="index">
                            <div class="card">
                                <div class="row align-items-center justify-content-between p-2">
                                    <div class="col-xl-1 col-md-1">
                                        <input type="checkbox" v-model="image.image_reference" disabled/>
                                    </div>
                                    <div class="col-xl-4 col-md-4">
                                        <input v-model="image.image_reference" disabled/>
                                    </div>
                                    <div class="col-xl-7 col-md-7">
                                        <input v-model="image.commentaire " disabled/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </perfect-scrollbar>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../../config/http';
    import VueFeather from 'vue-feather';

    export default {
        components: {
            VueFeather,
        },
        props: {
            contremarqueId: {
                type: Number,
                default: null,
            },
            diId: {
                type: Number,
                default: null,
            },
            locationId: {
                type: Number,
                default: null,
            },
            disabled: {
                type: Boolean,
                default: false,
            },
        },
        data() {
            return {
                imagesArray: [],
                images: null,
                error: {},
            };
        },
        computed: {
            canAddDesigner() {
                return (this.$store.getters.isDesignerManager || this.$store.getters.isSuperAdmin) && !this.$store.getters.isFinStatus;
            },
        },
        methods: {
            async getImages() {
                let url = `/api/images?`;
                switch (this.images) {
                    case 'contremarque':
                        url += 'filter[id_contremarque]='+ this.contremarqueId;
                        break;
                    case 'DI':
                        url += 'filter[id_di]=' + this.diId;
                        break;
                    case 'location':
                        url += 'filter[id_location]=' + this.locationId;
                        break;
                } 
                
                const res = await axiosInstance.get(url);
                this.imagesArray = res.data.response.images;
                console.log(res.data.response);
            }
        },
        mounted() {
          
        },
        unmounted() {
            
        },
        watch: {
            async images() {
               await this.getImages();
            },
        },
    };
</script>
<style scoped>
    ul {
        list-style-type: none;
    }
</style>
