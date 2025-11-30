<template>
    <div class="row align-items-center">
        <div class="row align-items-start" v-if="showTitle">
            <h6 class="w-100 p-0">Matière demandés <span class="required">*</span></h6>
        </div>
        <div class="card p-0" :class="{ 'border border-danger shadow-sm': error }">
            <perfect-scrollbar tag="div" class="h-130-forced p-0"
                               :options="{ wheelSpeed: 0.5, swipeEasing: !0, minScrollbarLength: 40, maxScrollbarLength: 130, suppressScrollX: true }">
                <div class="card-body p-0 ps-2 mt-2">
                    <template v-for="(material, index) in materials" :key="material.id || index">
                        <div class="row align-items-center justify-content-between ps-0">
                            <div class="col-6">
                                <d-materials-dropdown :disabled="disabled" :hideLabel="true"
                                                      v-model="material.material_id"
                                                      @update:model-value="() => { updateMaterialsInStore(); scheduleAutoUpdate(index); }"></d-materials-dropdown>
                            </div>
                            <div class="col-4 text-center font-size-0-7">
                                <input
                                    v-model.number="material.rate"
                                    type="number"
                                    min="0"
                                    max="100"
                                    style="padding: 3px"
                                    class="form-control form-control-sm"
                                    @blur="() => { updateMaterialsInStore(); scheduleAutoUpdate(index); }"
                                    :disabled="disabled"
                                />
                            </div>
                            <div class="col-2">
                                <!-- Use shared delete modal for server-backed items (provides confirmation + API delete) -->
                                <d-delete v-if="getDeleteApi(material)"
                                          :api="getDeleteApi(material)"
                                          :disabled="disabled"
                                          @isDone="() => handleServerDelete(index)"
                                />
                                <!-- Local deletion for unsaved items -->
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
            <!-- <div v-if="error" class="text-danger">
                Le taux total des matières doit être au moins 100.
            </div> -->
            <!-- Error Message -->
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
            <!-- Error Message (Appears Next to Button) -->
            <transition name="fade">
                <span v-if="error"
                      class="ms-3 text-danger fw-bold"> Le taux total des matières doit être égale à 100. </span>
            </transition>
        </div>
    </div>
</template>

<script>
    import dModalAddMaterial from '@/components/projet/contremarques/_Partials/d-modal-add-material.vue';
    import dDelete from '../../../common/d-delete.vue';
    import VueFeather from 'vue-feather';
    import DPanelTitle from '../../../common/d-panel-title.vue';
    import DMaterialsDropdown from '../dropdown/d-materials-dropdown.vue';
    import axiosInstance from '@/config/http';

    export default {
        components: {
            DMaterialsDropdown,
            DPanelTitle,
            dModalAddMaterial,
            VueFeather,
            dDelete
        },
        props: {
            materialsProps: {
                type: Array
            },
            quoteDetailId: {
                type: [Number, String],
                default: null
            },
            firstLoad: {
                type: Boolean
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
                materials: [],
                selectedLocation: null,
                // timers for debounced auto-update per row index
                updateTimers: {}
            };
        },
        mounted() {
            console.debug('[DMaterialsList] mounted, quoteDetailId=', this.quoteDetailId);
        },
        methods: {
            formatDataProps() {
                let mappedArray = [];
                if (Array.isArray(this.materialsProps)) {
                    mappedArray = this.materialsProps.map((m) => {
                        const detectedId = this.getItemId(m) || null;
                        const detectedMaterialId = m.material_id ?? (m.material && (m.material.id ?? null)) ?? (m.material && m.material.id) ?? null;
                        return {
                            id: detectedId,
                            material_id: detectedMaterialId,
                            rate: parseFloat(m.rate),
                            __raw: m
                        };
                    });
                } else if (typeof this.materialsProps === 'object' && this.materialsProps !== null) {
                    mappedArray = Object.values(this.materialsProps).map((m) => {
                        const detectedId = this.getItemId(m) || null;
                        const detectedMaterialId = m.material_id ?? (m.material && (m.material.id ?? null)) ?? null;
                        return {
                            id: detectedId,
                            material_id: detectedMaterialId,
                            rate: parseFloat(m.rate),
                            __raw: m
                        };
                    });
                } else {
                    console.error('Unexpected materialsProps format:', this.materialsProps);
                }

                // Smart merge to preserve local state (IDs and focus)
                if (this.materials.length === mappedArray.length) {
                    this.materials.forEach((item, index) => {
                        const newItem = mappedArray[index];
                        // Preserve local ID if prop ID is missing (e.g. after local creation before parent refresh)
                        if (!newItem.id && item.id) {
                            newItem.id = item.id;
                        }
                        
                        // Update properties
                        item.id = newItem.id;
                        item.material_id = newItem.material_id;
                        item.__raw = newItem.__raw;
                        
                        // Only update rate if significantly different to avoid input fighting
                        if (Math.abs(item.rate - newItem.rate) > 0.001) {
                            item.rate = newItem.rate;
                        }
                    });
                } else {
                    // Length changed, full replace
                    this.materials = mappedArray;
                }

                this.updateMaterialsInStore();
                console.debug('[DMaterialsList] mapped materials:', this.materials);
            },
            addMaterial(newMaterial) {
                console.debug('[DMaterialsList] addMaterial', newMaterial);
                newMaterial.rate = parseFloat(newMaterial.rate);
                this.materials.push(newMaterial);
                this.updateMaterialsInStore();
                this.$emit('changeMaterials', this.materials);
            },
            scheduleAutoUpdate(index) {
                console.debug('[DMaterialsList] scheduleAutoUpdate for index', index);
                // debounce per index
                if (this.updateTimers[index]) clearTimeout(this.updateTimers[index]);
                this.updateTimers[index] = setTimeout(() => {
                    console.debug('[DMaterialsList] debounce fired for index', index);
                    this.autoUpdateMaterial(index);
                    delete this.updateTimers[index];
                }, 600);
            },
            async autoUpdateMaterial(index) {
                console.debug('[DMaterialsList] autoUpdateMaterial start for index', index);
                const item = this.materials[index];
                if (!item) {
                    console.debug('[DMaterialsList] autoUpdateMaterial: no item at index', index);
                    return;
                }
                // build payload: include rate and/or materialId if available
                const payload = {};
                if (item.rate !== '' && item.rate !== null && item.rate !== undefined) {
                    // ensure string with two decimals
                    const n = Number(String(item.rate).replace(',', '.'));
                    payload.rate = Number.isNaN(n) ? null : n.toFixed(2);
                }
                if (item.material_id !== undefined && item.material_id !== null) {
                    payload.materialId = Number(item.material_id);
                }


                // if we have a server id and quoteDetailId, call PATCH endpoint
                const serverId = this.getItemId(item);
                console.debug('[DMaterialsList] autoUpdate: serverId=', serverId, 'quoteDetailId=', this.quoteDetailId, 'item raw=', item.__raw ?? item);
                if (serverId && this.quoteDetailId) {
                    const prev = { rate: item.rate, material_id: item.material_id };
                    try {
                        const url = `/api/QuoteDetail/${this.quoteDetailId}/CarpetMaterial/${serverId}`;
                        // server controller expects a PUT with the rate and optionally materialId
                        const putPayload = { rate: payload.rate };
                        if (payload.materialId) {
                            putPayload.materialId = payload.materialId;
                        }
                        console.debug('[DMaterialsList] PUT', url, putPayload);
                        const res = await axiosInstance.put(url, putPayload);
                        console.debug('[DMaterialsList] PUT response', res && res.data ? res.data : res);
                        // success -> update store/emit
                        this.updateMaterialsInStore();
                        this.$emit('changeMaterials', this.materials);
                        window.showMessage('Matière mise à jour avec succès');
                    } catch (e) {
                        console.error('[DMaterialsList] Failed to auto-update material', e);
                        // revert to previous value
                        item.rate = prev.rate;
                        item.material_id = prev.material_id;
                        this.updateMaterialsInStore();
                        handleApiErrorLocal(e, 'Erreur lors de la mise à jour de la matière');
                    }
                } else {
                    // no server id: if we have quoteDetailId, try to create it on server (POST), otherwise just update local
                    if (this.quoteDetailId) {
                        try {
                            const createUrl = `/api/QuoteDetail/${this.quoteDetailId}/CarpetMaterial`;
                            console.debug('[DMaterialsList] POST create', createUrl, payload);
                            const createRes = await axiosInstance.post(createUrl, payload);
                            console.debug('[DMaterialsList] POST response', createRes && createRes.data ? createRes.data : createRes);
                            // attempt to extract returned id
                            const returned = (createRes?.data?.response) ?? createRes?.data ?? createRes;
                            const newId = returned?.id ?? returned?.carpetMaterialId ?? returned?.carpet_material_id ?? (returned && returned.carpetMaterial && returned.carpetMaterial.id) ?? null;
                            if (newId) {
                                // set the item id so future edits PATCH
                                this.$set ? this.$set(item, 'id', newId) : (item.id = newId);
                                console.debug('[DMaterialsList] created material id=', newId);
                            }
                            // reconcile other returned fields (rate might be in response)
                            const newRate = returned?.rate ?? returned?.taux ?? returned?.orderSilkPercentage ?? null;
                            if (newRate !== undefined && newRate !== null) {
                                item.rate = Number(newRate);
                            }
                            this.updateMaterialsInStore();
                            this.$emit('changeMaterials', this.materials);
                        } catch (e) {
                            console.error('[DMaterialsList] Failed to create material on server', e);
                            handleApiErrorLocal(e, 'Erreur lors de la création de la matière');
                            // fallback to local update
                            this.updateMaterialsInStore();
                            this.$emit('changeMaterials', this.materials);
                        }
                    } else {
                        // no server id and no quote context: just update store/local
                        this.updateMaterialsInStore();
                        this.$emit('changeMaterials', this.materials);
                    }
                }
                console.debug('[DMaterialsList] autoUpdateMaterial done for index', index);
            },
            getItemId(item) {
                if (!item) return null;
                // prefer normalized top-level ids
                const top = item.id ?? item.carpetMaterialId ?? item.carpet_material_id ?? null;
                if (top) return top;
                // try to inspect raw object if present
                const raw = item.__raw ?? item;
                // helper: shallow recursive search for numeric id-like props
                const seen = new Set();

                function findId(obj, depth = 0) {
                    if (!obj || typeof obj !== 'object' || depth > 4) return null;
                    if (seen.has(obj)) return null;
                    seen.add(obj);
                    for (const k of Object.keys(obj)) {
                        try {
                            const v = obj[k];
                            if (v == null) continue;
                            const key = String(k).toLowerCase();
                            if ((key === 'id' || key.endsWith('id') || key.includes('carpet') && key.includes('id')) && (typeof v === 'number' || (typeof v === 'string' && !Number.isNaN(Number(v))))) {
                                const n = Number(v);
                                if (Number.isFinite(n) && n > 0) return n;
                            }
                            if (typeof v === 'object') {
                                const nested = findId(v, depth + 1);
                                if (nested) return nested;
                            }
                        } catch (e) {
                            // continue
                        }
                    }
                    return null;
                }

                const found = findId(raw, 0);
                if (found) {
                    console.debug('[DMaterialsList] getItemId found id in raw:', found, 'raw=', raw);
                    return found;
                }
                return null;
            },
            getDeleteApi(item) {
                const serverId = this.getItemId(item);
                if (serverId && this.quoteDetailId) {
                    return `/api/QuoteDetail/${this.quoteDetailId}/CarpetMaterial/${serverId}`;
                }
                if (serverId) {
                    return `/api/workshop-information-materials/${serverId}`;
                }
                return '';
            },
            handleDelete(index) {
                console.debug('[DMaterialsList] local delete index', index);
                // local-only removal for unsaved materials
                this.materials.splice(index, 1);
                this.updateMaterialsInStore();
                this.$emit('changeMaterials', this.materials);
            },
            handleServerDelete(index) {
                console.debug('[DMaterialsList] server delete confirmed index', index);
                // Called after d-delete performed the API deletion successfully
                this.materials.splice(index, 1);
                this.updateMaterialsInStore();
                this.$emit('changeMaterials', this.materials);
            },
            updateMaterialsInStore() {
                this.$store.commit('setMaterials', this.materials);
            },
            handleAddMaterialClick() {
                this.$emit('add-materials-click');
            }
        },
        watch: {
            materialsProps: {
                handler() {
                    console.debug('[DMaterialsList] materialsProps changed or initial', this.materialsProps);
                    this.formatDataProps();
                    if (!this.firstLoad) {
                        this.$emit('changeMaterials', this.materials);
                    }
                },
                immediate: true,
                deep: true
            }
        }
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
