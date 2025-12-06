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
       
        <button @click.prevent="checkCoherence" class="coherence-btn btn btn-custom text-uppercase w-100 py-2">
            <u>Contrôle de cohérence</u>
            <i v-if="coherenceCheck.differences && !coherenceCheck.isCoherent"
               class="bi bi-exclamation-triangle-fill text-warning ms-2"></i>
        </button>
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
                                       class="form-check-input"/>
                            </td>
                            <td>
                                <img v-if="row.image_name" :src="getImageUrl(row.image_name)" alt="Carpet Image"
                                     class="img-thumbnail" style="width: 80px; height: auto"/>
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
                                <th>Image commande</th>
                                <th>Commande atelier</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(field, name) in coherenceDifferences" :key="name"
                                :class="hasDifference(field) ? 'table-danger' : 'table-success'">
                                <td class="fw-bold">{{ formatFieldName(name) }}</td>
                                <td>
                <span v-if="name === 'dimensions'">
                  {{ formatDimensions(field.imageCommand) }}
                </span>
                                    <span v-else-if="name === 'materials'">
                  {{ formatMaterials(field.imageCommand) }}
                </span>
                                    <span v-else>
                  {{ field.imageCommand }}
                </span>
                                </td>
                                <td>
                <span v-if="name === 'dimensions'">
                  {{ formatDimensions(field.workshopOrder) }}
                </span>
                                    <span v-else-if="name === 'materials'">
                  {{ formatMaterials(field.workshopOrder) }}
                </span>
                                    <span v-else>
                  {{ field.workshopOrder }}
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
import {ref, onMounted, watch, defineProps} from 'vue';
import axiosInstance from '../../../config/http'; // Adjust your axios import as needed

const props = defineProps({
    workshopOrderId: {type: Number, default: 0},
    imageCommandId: {type: Number, default: 0},
});
const coherenceCheck = ref({
    isCoherent: true,
    differences: null
});

const showPopup = ref(false);
const rows = ref([]);
const showCoherenceModal = ref(false);
const coherenceDifferences = ref(null);

const checkCoherence = async () => {
    try {
        const response = await axiosInstance.get(
            `/api/workshopImageCommandCoherence/${props.workshopOrderId}/${props.imageCommandId}`
        );
        coherenceCheck.value = response.data.response;
        console.log(coherenceCheck.value)
    } catch (error) {
        console.error('Error checking coherence:', error);
    }
};

const showCoherenceAlert = (differences) => {
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
    const width = dimensions.Largeur?.[0]?.value ? dimensions.Largeur?.[0]?.value : dimensions.width;
    const length = dimensions.Longueur?.[0]?.value ? dimensions.Longueur?.[0]?.value : dimensions.height;
    return width && length ? `${parseFloat(width)}x${parseFloat(length)} cm` : 'N/A';
};

const formatMaterials = (materials) => {
    if (!materials) return 'N/A';
    return Object.values(materials)
        .sort((a, b) => {
            const refA = a.reference || '';
            const refB = b.reference || '';
            return refB.localeCompare(refA);
        })
        .map(m => `${m.name}`) 
        .join(', ');
    //${parseFloat(m.rate)}%
};
const hasDifference = (field) => {
    if (field.workshopOrder === undefined || field.imageCommand === undefined) {
        return false;
    }

    // Handle special cases for dimensions and materials
    if (typeof field.imageCommand === 'object') {
        if ('Largeur' in field.imageCommand) {
            // Dimensions comparison
            const doWidth = field.workshopOrder.width;
            const qWidth = field.imageCommand.Largeur?.[0]?.value;
            const doLength = field.workshopOrder.height;
            const qLength = field.imageCommand.Longueur?.[0]?.value;
            return doWidth !== qWidth || doLength !== qLength;
        } else {
            // Materials comparison
            const doMaterials = JSON.stringify(field.workshopOrder);
            const qMaterials = JSON.stringify(field.imageCommand);
            return doMaterials !== qMaterials;
        }
    }

    // Simple value comparison
    return field.workshopOrder !== field.imageCommand;
};
</script>
<style scoped>

</style>
