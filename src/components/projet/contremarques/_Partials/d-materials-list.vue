<template>
    <div class="row align-items-center">
        <perfect-scrollbar tag="div" class="h-130-forced row pe-2 ps-2 mt-2 block-custom-border"
                           :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 130, suppressScrollX: true }">
            <div class="row align-items-start p-2 ">
                <h6 class="w-100 p-0 pb-1 title-border-bottom">Matière demandés</h6>
            </div>
            <template v-for="(material,index) in materials">
                <div class="row align-items-center justify-content-between ps-0">
                    <div class="col-6">
                        <d-materials-dropdown :hideLabel="true" v-model="material.material_id"></d-materials-dropdown>
                    </div>
                    <div class="col-3 text-center">
                        {{ material.rate }}
                    </div> 
                    <div class="col-3">
                        <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click.prevent="handleDelete(index)">
                            <vue-feather type="x" :size="14"></vue-feather>
                        </button>  
                    </div>
                </div>
            </template>
        </perfect-scrollbar>
        <d-modal-add-material @addMaterial="addMaterial($event)"></d-modal-add-material>
        <div class="row ps-0 mt-2">
            <div class="col-auto ps-0">
                <button class="btn ms-0 btn-outline-custom" data-bs-toggle="modal" data-bs-target="#modalAddMaterials">
                    Ajouter
                    <vue-feather type="plus" size="14"></vue-feather>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../../../config/http';
    import dModalAddMaterial from "../_Partials/d-modal-add-material.vue";
    import dDelete from "../../../common/d-delete.vue";
    import VueFeather from 'vue-feather';
    import DPanelTitle from "../../../common/d-panel-title.vue";
    import DMaterialsDropdown from "../dropdown/d-materials-dropdown.vue";

    export default {
        components: {
            DMaterialsDropdown,
            DPanelTitle,
            dModalAddMaterial,
            VueFeather,
            dDelete
        },
        props: {
            
        },
        data() {
            return {
                materials: [],
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
            addMaterial(data){
               this.materials.push(data)
            },
            handleDelete(index){
                this.materials.splice(index, 1)
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
