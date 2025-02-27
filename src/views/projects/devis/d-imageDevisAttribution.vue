<template>
  <div class="col-lg-4 col-md-6 col-sm-12 pe-sm-0">
      <!-- Title -->
      <d-panel-title title="Tapis de projet" className="ps-2"></d-panel-title>

      <!-- Main Content Row -->
      <div class="row pe-2 ps-0 align-items-center">
          <!-- Left side: Placeholder content -->
          <div class="mb-3">
              <label class="fw-bold">Collection :</label>
              <span class="ms-2">Hors Collection 10.05</span>
          </div>

          <!-- Button to open popup -->
          <div class="mb-3">
              <button @click="showPopup = true" class="btn btn-dark">ATTRIBUER</button>
          </div>

          <!-- Date validation + optional link -->
          <div class="mb-3">
              <label class="fw-bold">Date de validation client :</label>
              <span class="ms-2">22-02-2022</span>
              <a href="#" class="ms-3">Contrôle de cohérence</a>
          </div>

          <!-- Show selected image (if any) -->
          <div v-if="selectedRow" class="mt-2">
              <img :src="getImageUrl(selectedRow.image_name)" alt="Carpet Design" class="img-thumbnail" style="width: 100px; height: auto" />
              <p class="mt-2"><strong>DI Number:</strong> {{ selectedRow.diNumber }}</p>
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
      contremarqueId: {
          type: Number,
          default: 0,
      },
      quoteDetailId:{
        type: Number,
        default: 0,
      }
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
      } catch (error) {
          console.error('Error fetching data:', error);
      } finally {
          loading.value = false;
      }
  };

  /**
   * Called when user clicks "Confirmer"
   */
  const confirmSelection = async () => {
      selectedRow.value = rows.value.find((r) => r.order_design_id === selectedId.value);
      if (!selectedRow.value) {
          console.error('No selection made');
          return;
      }

      try {
          const response = await axiosInstance.put("/api/quote-details/attach-carpet-design-order", {
              quoteDetailId: parseInt(props.quoteDetailId),
              carpetDesignOrderId: parseInt(selectedId.value),
          });
          console.log('API Response:', response.data);
          showPopup.value = false;
      } catch (error) {
          console.error('Error attaching carpet design order:', error);
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

  .popup-content {
      display: flex;
      flex-direction: column;
      max-height: 70vh;
  }

  .table-container {
      flex: 1;
      overflow-y: auto;
  }
</style>
