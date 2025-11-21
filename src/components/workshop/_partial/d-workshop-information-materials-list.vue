<template>
    <div class="row align-items-center">
        <div class="row align-items-start" v-if="showTitle">
            <h6 class="w-100 p-0">Matières atelier <span class="required">*</span></h6>
        </div>

        <div class="card p-0" :class="{ 'border border-danger shadow-sm': error }">
            <perfect-scrollbar tag="div" class="h-130-forced p-0"
                               :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 130, suppressScrollX: true }">
                <div class="card-body p-0 ps-2 mt-2">
                    <template v-for="(material, index) in materials" :key="material.__localKey || material.id">
                        <div class="row align-items-center justify-content-between ps-0">
                            <div class="col-5">
                                <d-materials-dropdown :disabled="disabled" :hide-label="true"
                                                      v-model="material.material_id"></d-materials-dropdown>
                            </div>

                            <div class="col-3 text-center font-size-0-7">
                                <input
                                    v-model="material.rate"
                                    type="text"
                                    inputmode="numeric"
                                    pattern="[0-9]*\.?[0-9]*"
                                    min="0"
                                    max="100"
                                    style="padding: 3px"
                                    class="form-control form-control-sm text-end"
                                    @change="onFieldChange(index)"
                                    @blur="onBlurFormat(index, 'rate')"
                                    @focus="onFocusUnformat(index, 'rate')"
                                    :disabled="disabled"
                                />
                            </div>

                            <div class="col-3 text-center font-size-0-7">
                                <input
                                    v-model="material.price"
                                    type="text"
                                    inputmode="decimal"
                                    style="padding: 3px"
                                    class="form-control form-control-sm text-end"
                                    @change="onFieldChange(index)"
                                    @blur="onBlurFormat(index, 'price')"
                                    @focus="onFocusUnformat(index, 'price')"
                                    :disabled="disabled"
                                />
                            </div>

                            <div class="col-1">
                                <!-- If the item exists on server, use the shared delete modal which will call the API then emit isDone -->
                                <d-delete
                                    v-if="material.id && workshopInfoId"
                                    :api="`/api/workshop-information-materials/${material.id}`"
                                    :disabled="disabled"
                                    @isDone="() => handleDeleteConfirmed(index)"
                                />
                                <!-- Otherwise use a local delete button for unsaved items -->
                                <button v-else :disabled="disabled" type="button"
                                        class="btn btn-dark mb-1 me-1 rounded-circle"
                                        @click.prevent="handleDelete(index)">
                                    <vue-feather type="x" :size="14"></vue-feather>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </perfect-scrollbar>
        </div>

        <d-modal-add-material
            @addMaterial="addMaterial($event)"
            @add-materials-click="handleAddMaterialClick"
        ></d-modal-add-material>

        <div class="col-12 ps-0 mt-2 d-flex align-items-center">
            <div class="col-auto ps-0">
                <button
                    :disabled="disabled"
                    class="btn ms-0 btn-outline-custom"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAddMaterials"
                >
                    Ajouter
                    <vue-feather type="plus" size="14"></vue-feather>
                </button>
            </div>
            <transition name="fade">
                <span v-if="error"
                      class="ms-3 text-danger fw-bold"> Le taux total des matières doit être égale à 100. </span>
            </transition>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '@/config/http';
    import dModalAddMaterial from '@/components/workshop/_partial/d-modal-add-material.vue';
    import VueFeather from 'vue-feather';
    import dMaterialsDropdown from '@/components/projet/contremarques/dropdown/d-materials-dropdown.vue';
    import DDelete from '@/components/common/d-delete.vue';

    export default {
        name: 'DWorkshopInformationMaterialsList',
        components: {
            dModalAddMaterial,
            VueFeather,
            dMaterialsDropdown,
            DDelete
        },
        props: {
            materialsProps: {
                type: [Array, Object],
                default: () => []
            },
            workshopInfoId: {
                type: [Number, String],
                default: null
            },
            disabled: {
                type: Boolean,
                default: false
            },
            showTitle: {
                type: Boolean,
                default: true
            },
            error: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                materials: []
            };
        },
        methods: {
            formatDataProps() {
                // materialsProps may be array or object keyed
                const src = this.materialsProps || [];
                let list = [];
                if (Array.isArray(src)) {
                    list = src.map(m => {
                        // material id can come as m.material.id, m.materialId, m.material_id or m.material (object)
                        const matId = (m && m.material && m.material.id) ? Number(m.material.id) : (m.materialId ?? m.material_id ?? m.material);
                        const material_id = (matId !== undefined && matId !== null) ? Number(matId) : null;
                        // rate/price can be on different keys depending on source
                        const rateVal = m.rate ?? m.orderSilkPercentage ?? m.percentage ?? null;
                        const priceVal = m.price ?? m.price_value ?? m.materialPrice ?? null;
                        return {
                            id: m.id ?? null,
                            material_id: material_id,
                            // format rate/price for display (two decimals)
                            rate: this.formatToTwo(rateVal),
                            price: this.formatToTwo(priceVal),
                            workshopInformationId: m.workshopInformationId ?? m.workshop_information ?? m.workshopInformation ?? null,
                            __localKey: m.id ? `id-${m.id}` : `local-${Math.random().toString(36).substr(2, 9)}`
                        };
                    });
                } else if (typeof src === 'object' && src !== null) {
                    list = Object.values(src).map(m => {
                        const matId = (m && m.material && m.material.id) ? Number(m.material.id) : (m.materialId ?? m.material_id ?? m.material);
                        const material_id = (matId !== undefined && matId !== null) ? Number(matId) : null;
                        const rateVal = m.rate ?? m.orderSilkPercentage ?? m.percentage ?? null;
                        const priceVal = m.price ?? m.price_value ?? m.materialPrice ?? null;
                        return {
                            id: m.id ?? null,
                            material_id: material_id,
                            rate: this.formatToTwo(rateVal),
                            price: this.formatToTwo(priceVal),
                            workshopInformationId: m.workshopInformationId ?? m.workshop_information ?? m.workshopInformation ?? null,
                            __localKey: m.id ? `id-${m.id}` : `local-${Math.random().toString(36).substr(2, 9)}`
                        };
                    });
                }
                this.materials = list;
                this.emitMaterialsChange();
            },
            async addMaterial(newMaterial) {
                // newMaterial: { material_id, rate, price } (rate may be null or string 2 decimals)
                // Accept materialId (from modal) or material_id (legacy) or nested material object
                const incomingMat = newMaterial.materialId ?? newMaterial.material_id ?? (newMaterial.material && newMaterial.material.id) ?? null;
                const normalizedMatId = incomingMat !== null && incomingMat !== undefined ? Number(incomingMat) : null;
                // server expects materialId (int)
                const payload = {
                    materialId: normalizedMatId,
                    // if rate explicitly null keep null, else ensure formatted string
                    rate: newMaterial.rate === null ? null : (newMaterial.rate === '' ? null : this.formatToTwo(newMaterial.rate)),
                    price: newMaterial.price ?? '',
                    workshopInformationId: this.workshopInfoId || null
                };
                if (this.workshopInfoId) {
                    try {
                        const res = await axiosInstance.post('/api/workshop-information-materials', payload);
                        const created = res.data.response || res.data;
                        // normalize returned material id (could be materialId or material_id or nested material.id)
                        const returnedMaterialId = (created.materialId ?? created.material_id ?? (created.material && created.material.id)) || null;
                        this.materials.push({
                            id: created.id ?? null,
                            material_id: returnedMaterialId ? Number(returnedMaterialId) : null,
                            rate: this.formatToTwo(created.rate),
                            price: this.formatToTwo(created.price),
                            workshopInformationId: created.workshopInformationId ?? created.workshop_information ?? this.workshopInfoId,
                            __localKey: created.id ? `id-${created.id}` : `local-${Math.random().toString(36).substr(2, 9)}`
                        });
                        window.showMessage('Matière ajoutée', 'success');
                    } catch (e) {
                        console.error('Failed to add workshop material', e);
                        handleApiErrorLocal(e, 'Erreur lors de l\'ajout de la matière');
                    }
                } else {
                    // local add
                    this.materials.push({
                        id: null,
                        material_id: payload.materialId ? Number(payload.materialId) : null,
                        rate: this.formatToTwo(payload.rate),
                        price: this.formatToTwo(payload.price),
                        workshopInformationId: payload.workshopInformationId,
                        __localKey: `local-${Math.random().toString(36).substr(2, 9)}`
                    });
                }
                this.emitMaterialsChange();
            },
            async handleDelete(index) {
                // Local removal for unsaved items or when no server deletion required
                const item = this.materials[index];
                this.materials.splice(index, 1);
                this.emitMaterialsChange();
            },
            handleDeleteConfirmed(index) {
                // Called after DDelete has performed the API deletion successfully
                this.materials.splice(index, 1);
                this.emitMaterialsChange();
            },
            async onFieldChange(index) {
                const item = this.materials[index];
                if (!item) return;
                const payload = {
                    id: item.id,
                    // server needs materialId int
                    materialId: item.material_id ? Number(item.material_id) : null,
                    rate: (item.rate === '' || item.rate === null || Number.isNaN(Number(item.rate))) ? null : this.formatToTwo(item.rate),
                    price: (item.price === '' || item.price === null || Number.isNaN(Number(item.price))) ? '' : this.formatToTwo(item.price),
                    workshopInformationId: this.workshopInfoId || item.workshopInformationId || null
                };
                if (item.id && this.workshopInfoId) {
                    // update existing
                    try {
                        await axiosInstance.put('/api/workshop-information-materials', payload);
                        window.showMessage('Matière mise à jour', 'success');
                    } catch (e) {
                        console.error('Failed to update', e);
                        handleApiErrorLocal(e, 'Erreur lors de la mise à jour');
                    }
                } else if (this.workshopInfoId) {
                    // create then replace local entry id
                    try {
                        const res = await axiosInstance.post('/api/workshop-information-materials', payload);
                        const created = res.data.response || res.data;
                        const returnedMaterialId = (created.materialId ?? created.material_id ?? (created.material && created.material.id)) || null;
                        // replace local entry
                        this.materials[index] = {
                            id: created.id ?? null,
                            material_id: returnedMaterialId ? Number(returnedMaterialId) : null,
                            rate: this.formatToTwo(created.rate),
                            price: this.formatToTwo(created.price),
                            workshopInformationId: created.workshopInformationId ?? created.workshop_information ?? this.workshopInfoId,
                            __localKey: created.id ? `id-${created.id}` : `local-${Math.random().toString(36).substr(2, 9)}`
                        };
                        window.showMessage('Matière enregistrée', 'success');
                    } catch (e) {
                        console.error('Failed to create on field change', e);
                        handleApiErrorLocal(e, 'Erreur lors de l\'enregistrement');
                    }
                } else {
                    // just local change
                }
                this.emitMaterialsChange();
            },
            handleAddMaterialClick() {
                this.$emit('add-materials-click');
            },
            emitMaterialsChange() {
                // Emit simplified materials array (material_id, rate, price)
                const simplified = this.materials.map(m => ({
                    material_id: m.material_id ? Number(m.material_id) : null,
                    // emit null for empty values to match API expectation (null|string) and ensure two decimals
                    rate: (m.rate === '' || m.rate === null) ? null : this.formatToTwo(m.rate),
                    price: this.formatToTwo(m.price),
                    id: m.id
                }));
                this.$emit('changeMaterials', simplified);
                // also update store similar to original
                if (this.$store && this.$store.commit) {
                    this.$store.commit('setMaterials', simplified);
                }
            },
            // format helpers for UI display
            formatToTwo(val) {
                if (val === null || val === undefined || val === '') return '';
                const n = Number(String(val).replace(',', '.'));
                if (Number.isNaN(n)) return '';
                return n.toFixed(2);
            },
            onBlurFormat(index, field) {
                const item = this.materials[index];
                if (!item) return;
                item[field] = this.formatToTwo(item[field]);
                this.emitMaterialsChange();
            },
            onFocusUnformat(index, field) {
                const item = this.materials[index];
                if (!item) return;
                if (item[field] === '' || item[field] === null) return;
                const n = Number(String(item[field]).replace(',', '.'));
                item[field] = Number.isNaN(n) ? '' : String(n);
            }
        },
        watch: {
            materialsProps: {
                handler() {
                    this.formatDataProps();
                },
                immediate: true,
                deep: true
            }
        }
    };

    // local helper to show API errors
    function handleApiErrorLocal(e, defaultMsg) {
        try {
            const data = e?.response?.data || {};
            const msg = data.message || data.detail || e.message || defaultMsg;
            window.showMessage(msg, 'error');
        } catch (er) {
            console.error(er);
            window.showMessage(defaultMsg, 'error');
        }
    }
</script>

<style scoped>
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.3s ease-in-out;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }
</style>
