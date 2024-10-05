<template>
    <div class="row align-items-center">
        <div class="row align-items-start">
            <h6 class="w-100 p-0">Matière demandés</h6>
        </div>
        <div class="card p-0">
            <perfect-scrollbar tag="div" class="h-130-forced p-0"
                               :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 130, suppressScrollX: true }">

                <div class="card-body p-0 ps-2 mt-2">
                    <template v-for="(material,index) in materials">
                        <div class="row align-items-center justify-content-between ps-0">
                            <div class="col-6">
                                <d-materials-dropdown :hideLabel="true" v-model="material.material_id"></d-materials-dropdown>
                            </div>
                            <div class="col-3 text-end font-size-0-7">
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
        <d-modal-add-material @addMaterial="addMaterial($event)"></d-modal-add-material>
        <div class="col-12 ps-0 mt-2">
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
            materialsProps:{
                type: Array
            },
            firstLoad : {
                type: Boolean
            }  
        },
        data() {
            return {
                materials: [],
                selectedLocation: null
            };
        },
        methods: {
            formatDataProps() {
                this.materials = this.materialsProps.map(m => ({
                    material_id: m.material_id,
                    rate: parseFloat(m.rate)
                }));
                this.updateMaterialsInStore();
            },
            addMaterial(data) {
                this.materials.push(data);
                this.updateMaterialsInStore();
                this.$emit('changeMaterials', this.materials);
            },
            handleDelete(index) {
                this.materials.splice(index, 1);
                this.updateMaterialsInStore();
                this.$emit('changeMaterials', this.materials);
            },
            updateMaterialsInStore() {
                this.$store.commit("setMaterials", this.materials);
            }
        },
        watch: {
            materialsProps() {
                this.formatDataProps();
                if(!this.firstLoad){
                    this.$emit('changeMaterials', this.materials);
                }
            }
        }
    };
</script>
<style scoped>
    ul {
        list-style-type: none;
    }
</style>
