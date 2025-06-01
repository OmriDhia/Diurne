<template>
    <div class="row align-items-center p-0 pt-2">
        <div class="row align-items-start">
            <h6 class="w-100 p-0">Suivi de production de l'image</h6>
        </div>
        <div class="card p-0">
            <perfect-scrollbar tag="div" class="h-200-forced p-0" :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 200, suppressScrollX: true }">
                <div class="card-body p-0 mt-2">
                    <div class="row row-cols-1 p-2">
                        <div class="col mb-2" v-for="(designer, index) in designers" :key="index">
                            <div class="card">
                                <div class="row align-items-center justify-content-between p-2">
                                    <div class="col-xl-8 col-md-12">
                                        <d-designer-dropdown
                                            :disabled="disabled"
                                            class="font-size-0-7"
                                            v-model="designer.designer"
                                            :hideLabel="true"
                                            @change="handleChange(index)"
                                        ></d-designer-dropdown>
                                    </div>
                                    <div class="col-xl-4 col-md-12 font-size-0-7">
                                        {{ $Helper.FormatDate(designer.date_from) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </perfect-scrollbar>
        </div>
        <d-modal-add-designer :carpetDesignOrderId="carpetOrderId" @addDesigner="addDesigner($event)"></d-modal-add-designer>
        <div class="row ps-0 mt-2">
            <div class="col-auto">
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
    import dModalAddDesigner from './_Partials/d-modal-add-designer.vue';
    import VueFeather from 'vue-feather';
    import dDesignerDropdown from '../../common/d-designer-dropdown.vue';
    import { designerStatusConst, carpetStatus } from '../../../composables/constants';
    import userService from '../../../Services/user-service';
    import { useRoute, useRouter } from 'vue-router';
    import store from '../../../store';

    export default {
        components: {
            dDesignerDropdown,
            dModalAddDesigner,
            VueFeather,
        },
        props: {
            designersProps: {
                default: [],
            },
            carpetDesignOrderId: {
                type: Number,
            },
            disabled: {
                type: Boolean,
                default: false,
            },
        },
        data() {
            return {
                designers: [],
                selectedLocation: null,
                carpetOrderId: this.carpetDesignOrderId,
                error: {},
            };
        },
        computed: {
            canAddDesigner() {
                return (this.$store.getters.isDesignerManager || this.$store.getters.isSuperAdmin) && !this.$store.getters.isFinStatus;
            },
        },
        methods: {
            async getLocations() {},
            updateLocation(location) {},
            getDesigners(designers) {
                return designers?.map((d) => {
                    const inProgress = d.in_progress || false;
                    const stopped = d.stopped || false;
                    const done = d.done || false;

                    return {
                        ...d,
                        status: inProgress ? designerStatusConst[0].id : stopped ? designerStatusConst[1].id : done ? designerStatusConst[2].id : 0,
                    };
                });
            },
            addDesigner(data) {
                const newDesigner = {
                    ...data,
                    designer: data.designerId,
                    status: data.inProgress ? designerStatusConst[0].id : data.stopped ? designerStatusConst[1].id : data.done ? designerStatusConst[2].id : 0,
                };
                this.designers.push(newDesigner);
                // Convert both values to integers for comparison
                const userId = parseInt(userService.getUserInfo().id, 10);
                const designerId = parseInt(newDesigner.designer, 10);
                // Compare the two values and emit based on the comparison
                if (userId === designerId) {
                    this.$emit('designerAdded', 4); // Emit 'En cours' if they are equal
                } else {
                    this.$emit('designerAdded', 3); // Emit 'Attribué' if they are not equal
                }
            },
            handleDelete(index) {
                this.designers.splice(index, 1);
            },
            async handleChange(index) {
                const designer = this.designers[index];
                if (!designer) {
                    window.showMessage("Ce designeur n'existe pas !!");
                    return;
                }
                const data = {
                    dateFrom: designer.date_from,
                    dateTo: designer.date_to,
                    inProgress: designer.status === designerStatusConst[0].id,
                    stopped: designer.status === designerStatusConst[1].id,
                    done: designer.status === designerStatusConst[2].id,
                };
                try {
                    const res = axiosInstance.put(`/api/designerAssignments/${designer.id}`, data);
                    window.showMessage('Mise à jour avec succées');
                } catch {
                    window.showMessage('Erreur mise a jour');
                }
            },
            updateDesignerStatus(status) {
                const user = userService.getUserInfo();
                const userId = parseInt(user.id);
                if (userId) {
                    const designer = this.designers.find((d) => d.designer === userId);
                    const indexDesigner = this.designers.findIndex((d) => d.designer === userId);
                    if (designer && !designer.done) {
                        this.designers[indexDesigner].inProgress = status === 'inProgress';
                        this.designers[indexDesigner].stopped = status === 'stopped';
                        const data = {
                            dateFrom: designer.date_from,
                            dateTo: designer.date_to,
                            inProgress: status === 'inProgress',
                            stopped: status === 'stopped',
                            done: false,
                        };
                        const carpetStatusid = (status === 'inProgress') ? carpetStatus.enCoursId :  status === 'stopped' ? carpetStatus.enPauseId : carpetStatus.attribuId ;
                        try {
                            const res = axiosInstance.put(`/api/designerAssignments/${designer.id}`, data);
                            this.$emit('endCarpetDesignOrder', carpetStatusid);
                            console.log('Mise à jour avec succées');
                        } catch (e) {
                            console.log('Erreur mise a jour', e);
                        }
                    }
                }
            },
        },
        mounted() {
            if (this.designersProps && this.designersProps.length > 0) {
                this.designers = this.getDesigners(this.designersProps);
                this.updateDesignerStatus('inProgress');
            }
        },
        unmounted() {
            this.updateDesignerStatus('stopped');
        },
        watch: {
            designersProps(newDesigners) {
                // console.log('newDesigners', newDesigners);
                // if (userId === designer.id ){
                // console.log("userId === designer.id", userId);
                // }
                if (newDesigners && newDesigners.length > 0) {
                    this.designers = this.getDesigners(newDesigners);
                    this.updateDesignerStatus('inProgress');
                }
            },
        },
    };
</script>
<style scoped>
    ul {
        list-style-type: none;
    }
</style>
