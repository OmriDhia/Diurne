<template>
    <div class="row align-items-center p-0 pt-2 mt-3">
        <div class="row align-items-start">
            <h6 class="w-100 p-0">Matières de l'image</h6>
        </div>
        <div class="card p-0">
            <perfect-scrollbar tag="div" class="h-200-forced p-0"
                               :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 200, suppressScrollX: true }">

                <div class="card-body p-0 ps-2 mt-2">
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
                </div>
            </perfect-scrollbar>
        </div>
        <d-modal-add-designer-composition :carpetSpecificationId="carpetSpecificationId" @addDesignerComposition="addDesignerComposition($event)"></d-modal-add-designer-composition>
        <div class="row ps-0 mt-2">
            <div class="col-auto">
                <button class="btn ms-0 btn-outline-custom" data-bs-toggle="modal" data-bs-target="#modalAddDesignerComposition">
                    Ajouter
                    <vue-feather type="plus" size="14"></vue-feather>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from "../../../config/http";
    import dModalAddDesignerComposition from "./_Partials/d-modal-add-designer-composition.vue";
    import dDelete from "../../common/d-delete.vue";
    import VueFeather from 'vue-feather';
    import DPanelTitle from "../../common/d-panel-title.vue";
    import DMaterialsDropdown from "./dropdown/d-materials-dropdown.vue";

    export default {
        components: {
            DMaterialsDropdown,
            DPanelTitle,
            VueFeather,
            dDelete,
            dModalAddDesignerComposition
        },
        props: {
            designerComposition:{
                type: Array
            },
            carpetSpecificationId: {
                type: Number
            }
        },
        data() {
            return {
                materials: [],
                selectedLocation: null
            };
        },
        methods: {
            formatDataProps(){
                if(this.designerComposition){
                    this.materials = this.designerComposition.map(m => {
                        m.rate = parseFloat(m.rate);
                        return m;
                    })  
                };
            },
            addDesignerComposition(data){
                this.materials.push(data);
            },
            handleDelete(index){
                this.materials.splice(index, 1);
            }
        },
        mounted() {
            this.formatDataProps()
        },
        watch: {
            designerComposition(){
                this.formatDataProps();
            }
        }
    };
</script>
<style scoped>
    ul {
        list-style-type: none;
    }
</style>
