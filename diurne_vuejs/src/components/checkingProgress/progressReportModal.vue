<template>
    <div>
        <d-base-modal v-if="visible" ref="modalRef" id="modalProgressReport" title="Créer un progress report"
                      @onClose="handleClose">
            <template #modal-body>
                <d-panel>
                    <template #panel-header>
                        <d-panel-title :title="`Progress Report`" />
                    </template>
                    <template #panel-body>
                        <div class="row">
                            <div class="col-md-6">
                                <d-input type="date" label="Date PR" v-model="form.datePr" />
                            </div>
                            <div class="col-md-6">
                                <d-users-dropdown label="Auteur" v-model="form.authorId" :required="true" disabled />
                            </div>
                            <div class="col-md-6">
                                <d-input type="text" label="RN" v-model="form.rn" />
                            </div>
                            <div class="col-md-6">
                                <d-base-dropdown name="État de la commande" v-model="form.statusId" :datas="statuses"
                                                 label="status" trackBy="id" />
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="row mt-2" v-for="(process,index) in processes" :key="index">
                                    <div class="col-md-3">
                                        <d-progress-process-dropdown disabled
                                                                     v-model="process.processId"></d-progress-process-dropdown>
                                    </div>
                                    <div class="col-md-3">
                                        <d-input type="date" label="debut" v-model="process.debut"></d-input>
                                    </div>
                                    <div class="col-md-3">
                                        <d-input type="date" label="fin" v-model="process.fin"></d-input>
                                    </div>
                                    <div class="col-md-3">
                                        <d-input v-model="process.comment"></d-input>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <d-contremarque-dropdown v-model="form.contremarque" disabled />
                                <d-input type="date" label="date évènement" v-model="form.dateEvent" />
                                <d-input type="date" label="date atelier cible" v-model="form.dateWorkshop" />
                                <div class="row align-items-center" v-if="form.statusId?.id === 2">
                                    <div class="col-9">
                                        <d-input label="Tissage" v-model="form.tissage" />
                                    </div>
                                    <div class="col-3 d-flex align-items-center mt-2 p-0">
                                        <div v-if="form.orderedHeigh !== '' && form.orderedHeigh !== null">
                                            <span class="text-muted">/ ({{ Helper.FormatNumber(form.orderedHeigh)
                                                }})</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <d-textarea label="Comment" v-model="form.comment" />
                            </div>
                        </div>

                        <div class="row mt-4 justify-content-end">
                            <div class="col-auto">
                                <button class="btn btn-custom" @click="save">Enregistrer</button>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-secondary" @click="closeModal">Fermer</button>
                            </div>
                        </div>
                    </template>
                </d-panel>
            </template>
        </d-base-modal>
    </div>
</template>

<script setup>
    import { ref, watch, nextTick, onMounted, onUnmounted } from 'vue';
    import axiosInstance from '@/config/http.js';
    import progressReportService from '@/Services/progress-report-service';
    import userService from '@/Services/user-service.js';
    import { Helper } from '@/composables/global-methods';

    import dBaseModal from '@/components/base/d-base-modal.vue';
    import dPanel from '@/components/common/d-panel.vue';
    import dPanelTitle from '@/components/common/d-panel-title.vue';
    import dInput from '@/components/base/d-input.vue';
    import dTextarea from '@/components/base/d-textarea.vue';
    import dBaseDropdown from '@/components/base/d-base-dropdown.vue';
    import dUsersDropdown from '@/components/common/d-users-dropdown.vue';
    import DProgressProcessDropdown from '@/components/workshop/dropdown/d-progress-process-dropdown.vue';
    import DContremarqueDropdown from '@/components/common/d-contremarque-dropdown.vue';

    const props = defineProps({
        provisionalCalendarId: {
            type: [Number, String],
            default: null
        }
    });
    const emit = defineEmits(['onClose', 'saved']);

    const loading = ref(false);
    const visible = ref(false);
    const modalRef = ref(null);
    const form = ref({
        authorId: [],
        datePr: Helper.FormatDate(new Date(), 'YYYY-MM-DD'),
        rn: '',
        contremarque: '',
        comment: '',
        dateEvent: '',
        dateWorkshop: '',
        tissage: '',
        orderedHeigh: '',
        statusId: null,
        provisionalCalendarId: null
    });
    const processes = ref([]);
    const statuses = ref([]);

    const loadStatuses = async () => {
        try {
            const res = await progressReportService.getStatuses();
            statuses.value = res.progressReportStatuses || res.progress_report_statuses || res;
        } catch (e) {
            console.error(e);
        }
    };

    // populate processes array according to selected status
    const switchProcessByStatusId = () => {
        processes.value = [];
        const statusId = form.value.statusId?.id;
        if (!statusId) return;
        // if status is 1 or 2, include process 1; if exactly 1 include process 2 as well
        if (statusId === 1 || statusId === 2) {
            processes.value.push({ processId: 1, debut: '', fin: '', comment: '' });
            if (statusId === 1) {
                processes.value.push({ processId: 2, debut: '', fin: '', comment: '' });
            }
        } else if (statusId === 3) {
            processes.value.push({ processId: 3, debut: '', fin: '', comment: '' });
            processes.value.push({ processId: 4, debut: '', fin: '', comment: '' });
        }
    };

    watch(() => form.value.statusId, switchProcessByStatusId);

    const getCalendar = async (id) => {
        try {
            if (!id) return;
            loading.value = true;
            const res = await axiosInstance.get(`/api/provisionalCalendar/${id}`);
            const provisionalCalendar = res.data?.response;
            if (provisionalCalendar) {
                form.value.provisionalCalendarId = id;
                form.value.rn = provisionalCalendar.workshopOrder.workshopInformation.rn;
                form.value.orderedHeigh = provisionalCalendar.workshopOrder.workshopInformation.orderedHeigh ?? '';
                form.value.dateWorkshop = provisionalCalendar.workshopOrder.workshopInformation.dateEndAtelierPrev ?? form.value.dateWorkshop ?? '';
                const userData = userService.getUserInfo();
                form.value.authorId = [parseInt(userData?.id)];
                form.value.contremarque = provisionalCalendar.workshopOrder.imageCommand.carpetDesignOrder.location.contremarque_id;
                // Load existing progress report if any (optional) - kept empty for creation
            }
        } catch (e) {
            console.error(e);
        } finally {
            loading.value = false;
        }
    };

    // store bootstrap modal instance and handler so we can remove listeners
    let _bsModalInstance = null;
    let _bsHiddenHandler = null;

    const removeModalBackdrop = () => {
        // remove all backdrops and the modal-open class on body
        const backdrops = Array.from(document.querySelectorAll('.modal-backdrop'));
        backdrops.forEach(b => b.parentNode && b.parentNode.removeChild(b));
        document.body.classList.remove('modal-open');
    };

    const setupBootstrapForModal = (modalEl) => {
        if (!modalEl) return;
        // cleanup any leftover state first
        try {
            if (_bsHiddenHandler) {
                try {
                    modalEl.removeEventListener('hidden.bs.modal', _bsHiddenHandler);
                } catch (e) { /* ignore */
                }
                _bsHiddenHandler = null;
            }
            if (_bsModalInstance) {
                try {
                    _bsModalInstance.dispose();
                } catch (e) { /* ignore */
                }
                _bsModalInstance = null;
            }
            removeModalBackdrop();
        } catch (e) { /* ignore */
        }

        if (window.bootstrap && typeof window.bootstrap.Modal === 'function') {
            // create new bootstrap modal instance and show it
            _bsModalInstance = new window.bootstrap.Modal(modalEl, {});
            _bsHiddenHandler = () => {
                // when bootstrap hides the modal, ensure we remove backdrop and update visible flag
                removeModalBackdrop();
                visible.value = false;
                // also emit onClose to inform parent
                emit('onClose');
            };
            modalEl.addEventListener('hidden.bs.modal', _bsHiddenHandler);
            _bsModalInstance.show();
        } else {
            // fallback: manual show
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.setAttribute('aria-modal', 'true');
            modalEl.setAttribute('role', 'dialog');
            document.body.classList.add('modal-open');
            if (!document.querySelector('.modal-backdrop')) {
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(backdrop);
            }
        }
    };

    const openIfNeeded = async (nv) => {
        if (!nv) return;
        await loadStatuses();
        await getCalendar(nv);
        // make modal visible and then setup bootstrap instance
        visible.value = true;
        await nextTick();
        try {
            const modalComp = modalRef.value;
            const modalEl = modalComp?.$el || modalComp;
            setupBootstrapForModal(modalEl);
        } catch (e) {
            console.error('Modal open error', e);
        }
    };

    // run when prop changes, and immediately on mount if prop is set
    watch(() => props.provisionalCalendarId, (nv) => {
        openIfNeeded(nv);
    }, { immediate: true });

    onMounted(() => {
        // in case immediate watch didn't open (safety), try again
        if (props.provisionalCalendarId) {
            openIfNeeded(props.provisionalCalendarId);
        }
    });

    onUnmounted(() => {
        // ensure no backdrop or body classes remain when component is destroyed
        try {
            const modalComp = modalRef.value;
            const modalEl = modalComp?.$el || modalComp;
            if (modalEl && _bsHiddenHandler) {
                try {
                    modalEl.removeEventListener('hidden.bs.modal', _bsHiddenHandler);
                } catch (e) { /* ignore */
                }
                _bsHiddenHandler = null;
            }
            if (_bsModalInstance) {
                try {
                    _bsModalInstance.dispose();
                } catch (e) { /* ignore */
                }
                _bsModalInstance = null;
            }
            removeModalBackdrop();
        } catch (e) {
            // ignore
        }
    });

    const handleClose = () => {
        // clean up and emit onClose
        try {
            closeModal();
        } catch (e) {
            // ignore
        }
        emit('onClose');
    };

    const closeModal = () => {
        const modalComp = modalRef.value;
        const modalEl = modalComp?.$el || modalComp;
        if (!modalEl) {
            visible.value = false;
            removeModalBackdrop();
            return;
        }
        try {
            if (window.bootstrap && typeof window.bootstrap.Modal === 'function') {
                const bs = window.bootstrap.Modal.getInstance(modalEl) || _bsModalInstance;
                if (bs) {
                    bs.hide();
                    try {
                        bs.dispose();
                    } catch (e) { /* ignore */
                    }
                }
                if (_bsHiddenHandler) {
                    try {
                        modalEl.removeEventListener('hidden.bs.modal', _bsHiddenHandler);
                    } catch (e) { /* ignore */
                    }
                    _bsHiddenHandler = null;
                }
                _bsModalInstance = null;
                removeModalBackdrop();
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.removeAttribute('aria-modal');
                modalEl.removeAttribute('role');
            } else {
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.removeAttribute('aria-modal');
                modalEl.removeAttribute('role');
                removeModalBackdrop();
            }
        } catch (e) {
            // fallback cleanup
            const closeBtn = modalEl.querySelector && modalEl.querySelector('.btn-close');
            if (closeBtn) closeBtn.click();
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
            if (_bsHiddenHandler) {
                try {
                    modalEl.removeEventListener('hidden.bs.modal', _bsHiddenHandler);
                } catch (e) { /* ignore */
                }
                _bsHiddenHandler = null;
            }
            _bsModalInstance = null;
            removeModalBackdrop();
        } finally {
            visible.value = false;
        }
    };

    const save = async () => {
        try {
            loading.value = true;
            const res = await progressReportService.create({
                authorId: form.value.authorId[0],
                datePr: form.value.datePr,
                comment: form.value.comment,
                dateEvent: form.value.dateEvent,
                dateWorkshop: form.value.dateWorkshop,
                tissage: form.value.tissage,
                orderedHeigh: form.value.orderedHeigh,
                statusId: form.value.statusId?.id,
                provisionalCalendarId: form.value.provisionalCalendarId
            });
            for (const process of processes.value) {
                try {
                    await axiosInstance.post('/api/processDeadline', {
                        progressReportId: res.id,
                        processId: process.processId,
                        dateStart: process.debut,
                        dateEnd: process.fin,
                        comment: process.comment
                    });
                } catch (error) {
                    console.error('Process save error', error);
                }
            }
            window.showMessage('Ajout avec succées.');
            emit('saved', res.id);
            // close modal after save
            closeModal();
        } catch (e) {
            console.error(e);
            window.showMessage(e.message || 'Erreur lors de l\'ajout', 'error');
        } finally {
            loading.value = false;
        }
    };

</script>

<style scoped>
    /* Make modal contents scroll nicely on small screens */
    :deep(#modalProgressReport .modal-dialog) {
        max-width: 1200px !important; /* xl */
    }

    @media (max-width: 768px) {
        :deep(#modalProgressReport .modal-dialog) {
            max-width: 95% !important;
            margin: 1.75rem auto;
        }
    }
</style>
