<template>
    <div class="col-lg-12 col-md-12 col-sm-12 pe-sm-0 w-100">
        <div v-if="coherenceCheck.differences && !coherenceCheck.isCoherent"
             class="alert alert-warning d-flex align-items-center mb-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <span>Specifications differences detected!</span>
            <button @click="showCoherenceAlert(coherenceCheck.differences)"
                    class="btn btn-sm btn-outline-light ms-auto">
                View Details
            </button>
        </div>
        <div v-if="coherenceCheck.differences && coherenceCheck.isCoherent"
             class="alert alert-success d-flex align-items-center mb-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <span>Specifications is coherent!</span>
        </div>
        <!-- FLEX CONTAINER for "Attribuer + Collection" and Image -->
        <div class="d-flex justify-content-between align-items-start mb-3 w-100">
            <!-- ATTRIBUER BUTTON + COLLECTION (TOP LEFT) -->
            <div class="d-flex flex-column">
                <button @click="showPopup = true" class="btn btn-dark w-100 mt-2">ATTRIBUER</button>
                <div class="mt-4">
                    <label class="fw-bold">Collection :</label>
                    <span class="ms-2">{{ collection }}</span>
                </div>

            </div>

            <!-- IMAGE (MIDDLE FAR RIGHT) -->
            <div v-if="selectedRow" class="d-flex flex-column ms-auto">
                <img :src="getImageUrl(selectedRow.image_name)" alt="Carpet Design" class="img-thumbnail"
                     style="width: 125px; height: auto" />
                <router-link v-if="diLink" :to="diLink"
                             class="mt-3 btn btn-link p-0 align-self-start px-2 py-1 btn-primary">
                    voir di
                </router-link>
            </div>
        </div>

        <!-- DATE VALIDATION (BELOW) -->
        <div class="d-flex justify-content-between mb-3 w-100">
            <label class="fw-bold w-50">Date de validation client :</label>
            <div class="position-relative w-50">
                <input type="date" v-model="validationDate" class="form-control pe-5" />
                <i class="bi bi-calendar-date position-absolute top-50 end-0 translate-middle-y me-3 text-secondary"></i>
            </div>
        </div>
        <a href="#" @click.prevent="checkCoherence" class="d-block mb-3">
            <u>Contrôle de cohérence</u>
            <i v-if="coherenceCheck.differences && !coherenceCheck.isCoherent"
               class="bi bi-exclamation-triangle-fill text-warning ms-2"></i>
        </a>
        <!-- Popup for selecting Contremarque / CarpetDesignOrders -->
        <div v-if="showPopup" class="popup">
            <div class="popup-content">
                <h4>Choisir une Maquette</h4>
                <!-- Scrollable table container -->
                <div class="table-container">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Sélection</th>
                            <th>Nom Image</th>
                            <th>Contremarque</th>
                            <th>Num Maquette</th>
                            <th>Client</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row, index) in rows" :key="index">
                            <td>
                                <input type="radio" v-model="selectedId" :value="row.order_design_id"
                                       class="form-check-input" />
                            </td>
                            <td>
                                <img v-if="row.image_name" :src="getImageUrl(row.image_name)" alt="Carpet Image"
                                     class="img-thumbnail" style="width: 80px; height: auto" />
                                <span v-else>Aucune image</span>
                            </td>
                            <td>{{ row.contremarque }}</td>
                            <td>{{ row.diNumber }}</td>
                            <td>{{ row.customer }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Footer buttons always visible -->
                <div class="popup-footer mt-3 d-flex gap-2">
                    <button class="btn btn-primary" @click="confirmSelection">Confirmer</button>
                    <button class="btn btn-danger" @click="showPopup = false">Fermer</button>
                </div>
            </div>
        </div>
        <!-- Coherence Differences Modal -->
        <div v-if="showCoherenceModal" class="modal fade show" tabindex="-1"
             style="display: block; background: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                            Specification Differences
                        </h5>
                        <button type="button" class="btn-close" @click="showCoherenceModal = false"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Field</th>
                                <th>Design Order</th>
                                <th>Quote</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(field, name) in coherenceDifferences" :key="name"
                                :class="hasDifference(field) ? 'table-danger' : 'table-success'">
                                <td class="fw-bold">{{ formatFieldName(name) }}</td>
                                <td>
                <span v-if="name === 'dimensions'">
                  {{ formatDimensions(field.carpetDesignOrder) }}
                </span>
                                    <span v-else-if="name === 'materials'">
                  {{ formatMaterials(field.carpetDesignOrder) }}
                </span>
                                    <span v-else>
                  {{ field.carpetDesignOrder }}
                </span>
                                </td>
                                <td>
                <span v-if="name === 'dimensions'">
                  {{ formatDimensions(field.quoteDetail) }}
                </span>
                                    <span v-else-if="name === 'materials'">
                  {{ formatMaterials(field.quoteDetail) }}
                </span>
                                    <span v-else>
                  {{ field.quoteDetail }}
                </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted, watch, defineProps, computed } from 'vue';
    import axiosInstance from '../../../config/http';
    import { formatErrorViolationsComposed } from '../../../composables/global-methods';

    const props = defineProps({
        contremarqueId: { type: Number, default: 0 },
        quoteDetailId: { type: Number, default: 0 },
        image: { type: String, default: '' },
        collection: { type: String, default: '' }
    });

    const coherenceCheck = ref({
        isCoherent: true,
        differences: null
    });

    const showPopup = ref(false);
    const loading = ref(false);
    const error = ref({});
    const rows = ref([]);
    const showCoherenceModal = ref(false);
    const coherenceDifferences = ref(null);
    const selectedId = ref(null);
    const selectedRow = ref(null);
    const validationDate = ref('');
    const initialCarpetDesignOrderId = ref(null);

    const normalizeId = (value) => {
        const parsed = Number(value);
        return Number.isFinite(parsed) && parsed > 0 ? parsed : null;
    };

    const formatDateForInput = (rawDate) => {
        if (!rawDate) {
            return '';
        }

        if (/^\d{4}-\d{2}-\d{2}$/.test(rawDate)) {
            return rawDate;
        }

        const parts = rawDate.split(/[\sT:-]/).filter(Boolean);
        if (parts.length >= 3) {
            const [first, second, third] = parts;
            if (first.length === 2 && second.length === 2 && third.length === 4) {
                return `${third}-${second}-${first}`;
            }
            if (first.length === 4 && second.length === 2 && third.length === 2) {
                return `${first}-${second}-${third}`;
            }
        }

        return rawDate;
    };

    const applyValidationDate = (rawDate) => {
        validationDate.value = formatDateForInput(rawDate);
    };

    const syncSelectionById = () => {
        const targetId = normalizeId(initialCarpetDesignOrderId.value);
        if (!targetId || !Array.isArray(rows.value)) {
            return;
        }

        const match = rows.value.find((row) => normalizeId(row?.order_design_id) === targetId);
        if (match) {
            selectedId.value = match.order_design_id;
            selectedRow.value = match;
        }
    };

    const fetchQuoteDetail = async () => {
        if (!props.quoteDetailId) {
            initialCarpetDesignOrderId.value = null;
            applyValidationDate('');
            return;
        }

        try {
            const response = await axiosInstance.get(`/api/quoteDetail/${props.quoteDetailId}`);
            const detail = response.data?.quoteDetailData
                ?? response.data?.response?.quoteDetailData
                ?? response.data?.quoteDetail
                ?? response.data;

            initialCarpetDesignOrderId.value = normalizeId(
                detail?.carpetDesignOrderId ?? detail?.order_design_id ?? detail?.carpet_design_order_id
            );
            applyValidationDate(detail?.customer_validation_date);

            if (!validationDate.value && initialCarpetDesignOrderId.value) {
                await fetchCarpetDesignOrderById(initialCarpetDesignOrderId.value);
            }

            syncSelectionById();
        } catch (err) {
            console.error('Failed to fetch quote detail', err);
        }
    };

    const fetchCarpetDesignOrderById = async (orderId) => {
        const normalizedId = normalizeId(orderId);
        if (!normalizedId) {
            applyValidationDate('');
            return;
        }

        try {
            const response = await axiosInstance.get(`/api/carpet-design-orders/${normalizedId}`);
            const order = response.data?.response ?? response.data;
            applyValidationDate(order?.customerInstruction?.customerValidationDate);
        } catch (err) {
            console.error('Failed to fetch carpet design order', err);
        }
    };

    const diLink = computed(() => {
        const diId = selectedRow.value?.di_id || selectedRow.value?.id_di;
        const carpetDesignOrderId = selectedRow.value?.order_design_id;

        if (!diId || !carpetDesignOrderId) {
            return null;
        }

        return `/projet/dis/model/${diId}/update/${carpetDesignOrderId}`;
    });
    /**
     * Fetch the data from API
     */
    const getDI = async () => {
        try {
            loading.value = true;
            let url = `/api/carpetDesignOrders/all?page=1&itemsPerPage=100`;
            if (props.contremarqueId) {
                url += `&filter[contremarqueId]=${props.contremarqueId}`;
            }
            const response = await axiosInstance.get(url);
            const data = response.data.response;
            rows.value = data.carpetDesignOrders;
            // If an image is passed, set the selected row based on it
            if (props.image) {
                const image = props.image.split('/').pop();

                const preselectedRow = rows.value.find((row) => row.image_name === image);
                if (preselectedRow) {
                    selectedId.value = preselectedRow.order_design_id;
                    selectedRow.value = preselectedRow;
                }
            }

            if (!selectedRow.value && initialCarpetDesignOrderId.value) {
                syncSelectionById();
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        } finally {
            loading.value = false;
        }
    };

    const carpetDesignOrderAttachment = ref({
        quoteDetailId: 0,
        carpetDesignOrderId: 0
    });
    /**
     * Called when user clicks "Confirmer"
     */
    const confirmSelection = () => {
        const normalizedSelectedId = normalizeId(selectedId.value);
        if (!normalizedSelectedId) {
            window.showMessage('Veuillez sélectionner une maquette.', 'error');
            return;
        }

        selectedRow.value = rows.value.find(
            (row) => normalizeId(row?.order_design_id) === normalizedSelectedId
        ) ?? null;

        if (!selectedRow.value) {
            window.showMessage('La maquette sélectionnée est introuvable.', 'error');
            return;
        }

        showPopup.value = false;
        carpetDesignOrderAttachment.value.quoteDetailId = Number.parseInt(props.quoteDetailId, 10) || 0;
        carpetDesignOrderAttachment.value.carpetDesignOrderId = normalizedSelectedId;
        initialCarpetDesignOrderId.value = normalizedSelectedId;
        AttachCarpetDesignOrder();
    };

    const AttachCarpetDesignOrder = async () => {
        try {
            await axiosInstance.put(`/api/quote-details/attach-carpet-design-order`, carpetDesignOrderAttachment.value);
            window.showMessage('Mise a jour avec succées.');
            if (selectedRow.value?.order_design_id) {
                await fetchCarpetDesignOrderById(selectedRow.value.order_design_id);
            }
        } catch (e) {
            if (e?.response?.data?.violations) {
                error.value = formatErrorViolationsComposed(e.response.data.violations);
            }
            console.error('Failed to attach carpet design order', e);
            window.showMessage(e.message, 'error');
        }
    };
    const checkCoherence = async () => {
        try {
            const response = await axiosInstance.get(
                `/api/specificationCoherence?carpetDesignOrderId=${selectedRow.value?.order_design_id}&quoteDetailId=${props.quoteDetailId}`
            );
            coherenceCheck.value = response.data.response;


        } catch (error) {
            console.error('Error checking coherence:', error);
        }
    };

    const showCoherenceAlert = (differences) => {
        // Convert Proxy to plain object if needed
        coherenceDifferences.value = JSON.parse(JSON.stringify(differences));
        showCoherenceModal.value = true;
    };
    const formatFieldName = (name) => {
        const names = {
            'collection': 'Collection',
            'quality': 'Quality',
            'model': 'Model',
            'dimensions': 'Dimensions',
            'materials': 'Materials',
            'location': 'Location'
        };
        return names[name] || name;
    };

    const formatDimensions = (dimensions) => {
        if (!dimensions) return 'N/A';
        const width = dimensions.Largeur?.[0]?.value;
        const length = dimensions.Longueur?.[0]?.value;
        return width && length ? `${parseFloat(width)}x${parseFloat(length)} cm` : 'N/A';
    };

    const formatMaterials = (materials) => {
        if (!materials) return 'N/A';
        return Object.values(materials)
            .sort((a, b) => b.reference.localeCompare(a.reference))
            .map(m => `${parseFloat(m.rate)}% ${m.reference}`)
            .join(', ');
    };
    const hasDifference = (field) => {
        if (field.carpetDesignOrder === undefined || field.quoteDetail === undefined) {
            return false;
        }

        // Handle special cases for dimensions and materials
        if (typeof field.carpetDesignOrder === 'object') {
            if ('Largeur' in field.carpetDesignOrder) {
                // Dimensions comparison
                const doWidth = field.carpetDesignOrder.Largeur?.[0]?.value;
                const qWidth = field.quoteDetail.Largeur?.[0]?.value;
                const doLength = field.carpetDesignOrder.Longueur?.[0]?.value;
                const qLength = field.quoteDetail.Longueur?.[0]?.value;
                return doWidth !== qWidth || doLength !== qLength;
            } else {
                // Materials comparison
                const doMaterials = JSON.stringify(field.carpetDesignOrder);
                const qMaterials = JSON.stringify(field.quoteDetail);
                return doMaterials !== qMaterials;
            }
        }

        // Simple value comparison
        return field.carpetDesignOrder !== field.quoteDetail;
    };
    /**
     * Convert image name to a direct path
     */
    const getImageUrl = (imageName) => {
        return `https://diurne-api.webntricks.com/uploads/attachments/${imageName}`;
    };

    onMounted(() => {
        fetchQuoteDetail();
        getDI();
    });

    watch(
        () => props.contremarqueId,
        () => {
            getDI();
        }
    );

    watch(
        () => props.quoteDetailId,
        () => {
            fetchQuoteDetail();
        }
    );
</script>

<style scoped>
    .w-100 {
        width: 90% !important;
    }

    .popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        background: #fff;
        padding: 20px;
        width: 50%;
        max-width: 90vw;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    /* Make popup-content a flex container with vertical layout */
    .popup-content {
        display: flex;
        flex-direction: column;
        max-height: 70vh;
    }

    /* Table container takes available space and scrolls if needed */
    .table-container {
        flex: 1;
        overflow-y: auto;
    }

    /* Footer area always visible */
    .popup-footer {
        /* You can add styling to ensure the footer stays at the bottom */
    }
</style>
