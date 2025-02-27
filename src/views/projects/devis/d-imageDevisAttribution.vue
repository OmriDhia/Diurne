<template>
    <div class="col-lg-12 col-md-12 col-sm-12 pe-sm-0 w-100">
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
                <img :src="getImageUrl(selectedRow.image_name)" alt="Carpet Design" class="img-thumbnail" style="width: 125px; height: auto" />
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
                                    <input type="radio" v-model="selectedId" :value="row.order_design_id" class="form-check-input" />
                                </td>
                                <td>
                                    <img v-if="row.image_name" :src="getImageUrl(row.image_name)" alt="Carpet Image" class="img-thumbnail" style="width: 80px; height: auto" />
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
    </div>
</template>

<script setup>
    import { ref, onMounted, watch, defineProps } from 'vue';
    import axiosInstance from '../../../config/http'; // Adjust your axios import as needed

    const props = defineProps({
        contremarqueId: { type: Number, default: 0 },
        quoteDetailId: { type: Number, default: 0 },
        image: { type: String, default: '' },
        collection: { type: String, default: '' },
        customerDate: { type: String, default: null } // New prop to receive customer date

    });

    // Reactive References
    const showPopup = ref(false);
    const loading = ref(false);
    const rows = ref([]);
    const total_rows = ref(0);

    // ID of the selected row (using order_design_id for selection)
    const selectedId = ref(null);

    // The entire row object of the selected item
    const selectedRow = ref(null);
    const validationDate = ref(props.customerDate || ''); // Initialize with customerDate if not null
// Watch for changes to the customerDate prop
watch(() => props.customerDate, (newDate) => {
    validationDate.value = newDate || ''; // If it's null, set to empty
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
            total_rows.value = data.count;
            // If an image is passed, set the selected row based on it
            if (props.image) {
                const image = props.image.split('/').pop();
                console.log('props.image', props.image, image);

                const preselectedRow = rows.value.find((row) => row.image_name === image);
                if (preselectedRow) {
                    selectedId.value = preselectedRow.order_design_id;
                    selectedRow.value = preselectedRow;
                }
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        } finally {
            loading.value = false;
        }
    };

    const carpetDesignOrderAttachment = ref({
        quoteDetailId: 0,
        carpetDesignOrderId: 0,
    });
    /**
     * Called when user clicks "Confirmer"
     */
    const confirmSelection = () => {
        selectedRow.value = rows.value.find((r) => r.order_design_id === selectedId.value);
        showPopup.value = false;
        carpetDesignOrderAttachment.value.quoteDetailId = parseInt(props.quoteDetailId);
        carpetDesignOrderAttachment.value.carpetDesignOrderId = selectedRow.value.order_design_id;
        console.log(carpetDesignOrderAttachment.value);
        AttachCarpetDesignOrder();
    };

    const AttachCarpetDesignOrder = async () => {
        try {
            const res = await axiosInstance.put(`/api/quote-details/attach-carpet-design-order`, carpetDesignOrderAttachment.value);
            window.showMessage('Mise a jour avec succées.');
        } catch (e) {
            if (e.response.data.violations) {
                error.value = formatErrorViolationsComposed(e.response.data.violations);
                console.log(error.value);
            }
            window.showMessage(e.message, 'error');
        }
    };

    /**
     * Convert image name to a direct path
     */
    const getImageUrl = (imageName) => {
        return `https://diurne-api.webntricks.com/uploads/attachments/${imageName}`;
    };

    onMounted(() => {
        getDI();
    });

    watch(
        () => props.contremarqueId,
        () => {
            getDI();
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
