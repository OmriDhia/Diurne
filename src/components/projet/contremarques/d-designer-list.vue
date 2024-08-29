<template>
    <div class="row align-items-center">
        <perfect-scrollbar tag="div" class="h-200-forced row pe-2 ps-2 mt-2 block-custom-border"
                           :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 200, suppressScrollX: true }">
            <div class="row align-items-start p-2 ">
                <h6 class="w-100 p-0 pb-1 title-border-bottom">Suivi de production de l'image</h6>
            </div>
            <template v-for="(designer,index) in designers">
                <div class="row align-items-center justify-content-between ps-0">
                    
                </div>
            </template>
        </perfect-scrollbar>
        <d-modal-add-designer @addDesigner="addDesigner($event)"></d-modal-add-designer>
        <div class="row ps-0 mt-2">
            <div class="col-auto ps-0">
                <button class="btn ms-0 btn-outline-custom" data-bs-toggle="modal" data-bs-target="#modalAddDesigner">
                    Ajouter
                    <vue-feather type="plus" size="14"></vue-feather>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../../config/http';
    import dModalAddDesigner from "./_Partials/d-modal-add-designer.vue";
    import VueFeather from 'vue-feather';

    export default {
        components: {
            dModalAddDesigner,
            VueFeather,
        },
        props: {
            
        },
        data() {
            return {
                designers: [],
                selectedLocation: null
            };
        },
        methods: {
            async getLocations() {
                
            },
            updateLocation(location){
                
            },
            handleClose(){

            },
            addDesigner(data){
                this.designers.push(data)
            },
            handleDelete(index){
                this.designers.splice(index, 1)
            }
        },
        mounted() {
           
        },
        watch: {
           
        }
    };
</script>
<style scoped>
    ul {
        list-style-type: none;
    }
</style>
