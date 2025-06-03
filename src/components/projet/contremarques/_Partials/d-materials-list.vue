<template>
    <div class="row align-items-center">
        <div class="row align-items-start" v-if="showTitle">
            <h6 class="w-100 p-0">Matière demandés <span class="required">*</span></h6>
        </div>
        <div class="card p-0" :class="{ 'border border-danger shadow-sm': error }">
            <perfect-scrollbar tag="div" class="h-130-forced p-0" :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 130, suppressScrollX: true }">
                <div class="card-body p-0 ps-2 mt-2">
                    <template v-for="(material, index) in materials">
                        <div class="row align-items-center justify-content-between ps-0">
                            <div class="col-6">
                                <d-materials-dropdown :disabled="disabled" :hideLabel="true" v-model="material.material_id"></d-materials-dropdown>
                            </div>
                            <div class="col-4 text-center font-size-0-7">
                                <input
                                    v-model.number="material.rate"
                                    type="number"
                                    min="0"
                                    max="100"
                                    style="padding: 3px"
                                    class="form-control form-control-sm"
                                    @input="updateMaterialsInStore()"
                                    :disabled="disabled"
                                />
                            </div>
                            <div class="col-2">
                                <button :disabled="disabled" type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click.prevent="handleDelete(index)">
                                    <vue-feather type="x" :size="14"></vue-feather>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </perfect-scrollbar>
            <!-- <div v-if="error" class="text-danger">
                Le taux total des matières doit être au moins 100.
            </div> -->
            <!-- Error Message -->
        </div>
        <d-modal-add-material @addMaterial="addMaterial($event)"></d-modal-add-material>
        <div class="col-12 ps-0 mt-2 d-flex align-items-center">
            <div class="col-auto ps-0">
                <button :disabled="disabled" class="btn ms-0 btn-outline-custom" data-bs-toggle="modal" data-bs-target="#modalAddMaterials">
                    Ajouter
                    <vue-feather type="plus" size="14"></vue-feather>
                </button>
            </div>
            <!-- Error Message (Appears Next to Button) -->
            <transition name="fade">
                <span v-if="error" class="ms-3 text-danger fw-bold"> Le taux total des matières doit être égale à 100. </span>
            </transition>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../../../config/http';
    import dModalAddMaterial from '../_Partials/d-modal-add-material.vue';
    import dDelete from '../../../common/d-delete.vue';
    import VueFeather from 'vue-feather';
    import DPanelTitle from '../../../common/d-panel-title.vue';
    import DMaterialsDropdown from '../dropdown/d-materials-dropdown.vue';

    export default {
        components: {
            DMaterialsDropdown,
            DPanelTitle,
            dModalAddMaterial,
            VueFeather,
            dDelete,
        },
        props: {
            materialsProps: {
                type: Array,
            },
            firstLoad: {
                type: Boolean,
            },
            disabled: {
                type: Boolean,
                default: false,
            },
            showTitle: {
                type: Boolean,
                default: true,
            },
            error: {
                type: Boolean,
                default: false,
            },
        },
        data() {
            return {
                materials: [],
                selectedLocation: null,
            };
        },
        mounted() {
            // console.log("yassine : ", materialsProps);
        },
        methods: {
            formatDataProps() {
                if (Array.isArray(this.materialsProps)) {
                    this.materials = this.materialsProps.map((m) => ({
                        material_id: m.material_id,
                        rate: parseFloat(m.rate),
                    }));
                } else if (typeof this.materialsProps === 'object' && this.materialsProps !== null) {
                    // Convert object to an array with the object values
                    this.materials = Object.values(this.materialsProps).map((m) => ({
                        material_id: m.material_id,
                        rate: parseFloat(m.rate),
                    }));
                } else {
                    console.error('Unexpected materialsProps format:', this.materialsProps);
                    this.materials = [];
                    // this.materials = this.materialsProps.map((m) => ({
                        // material_id: m.material_id,
                        // rate: parseFloat(m.rate),
                    // }));
                }
                this.updateMaterialsInStore();
                // this.materials = this.materialsProps.map((m) => ({
                //     material_id: m.material_id,
                //     rate: parseFloat(m.rate),
                // }));
                // this.updateMaterialsInStore();
            },
            addMaterial(newMaterial) {
                newMaterial.rate = parseFloat(newMaterial.rate);
                this.materials.push(newMaterial);
                this.updateMaterialsInStore();
                this.$emit('changeMaterials', this.materials);
            },
            handleDelete(index) {
                this.materials.splice(index, 1);
                this.updateMaterialsInStore();
                this.$emit('changeMaterials', this.materials);
            },
            updateMaterialsInStore() {
                this.$store.commit('setMaterials', this.materials);
            },
        },
        watch: {
            materialsProps() {
                this.formatDataProps();
                if (!this.firstLoad) {
                    this.$emit('changeMaterials', this.materials);
                }
            },
        },
    };
</script>
<style scoped>
    ul {
        list-style-type: none;
    }
    /* Fade effect for error message */
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.3s ease-in-out;
    }
    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }
</style>
