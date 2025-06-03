<template>
  <div class="br-6 p-2 mt-3">
    <h1 class="my-4">{{ title }}</h1>
    <div class="bh-table-responsive">
      <table class="bh-table-striped bh-table-hover">
        <thead>
          <tr>
            <th v-for="column in columns" :key="column.key">
              {{ column.label }}
            </th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="isLoading">
            <td :colspan="columns.length + 1" class="text-center">
              Chargement...
            </td>
          </tr>
          <TableRow v-for="row in rows" :key="row[rowKey]" :row="row" :columns="columns" :showViewButton="showViewButton"
            :isEditing="isEditing === row[rowKey]" @edit="startEdit" @save="saveEdit" @delete="deleteRow"  @view="startView" 
            @cancel="cancelEdit" />
          <tr>
            <EditableCell v-if="!disableAddNew" v-for="column in columns" :key="column.key" :row="newRow"
              :column="column" :isEditing="true" />
            <td>
              <button v-if="!disableAddNew" class="btn btn-dark ps-2" @click="addRow">
                <span class="me-2">Ajouter</span>
                <vue-feather type="plus" size="14"></vue-feather>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <Pagination v-if="showPagination" :currentPage="pagination.currentPage" :totalPages="pagination.totalPages"
      :totalItems="pagination.totalItems" :itemsPerPage="pagination.itemsPerPage" @page-change="changePage"
      @page-size-change="changePageSize" />

  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from "vue";
import VueFeather from 'vue-feather';
import Pagination from './Pagination/d-pagination.vue';
import TableRow from './d-table-row.vue';
import EditableCell from './d-editable-cell.vue';

const props = defineProps({
  fetchData: { type: Function, required: true },
  saveData: { type: Function, required: true },
  deleteData: { type: Function, required: true },
  addData: { type: Function, required: true },
  columns: { type: Array, required: true },
  rowKey: { type: String, required: true },
  title: { type: String, required: false },
  disableAddNew: { type: Boolean, default: false },
  showViewButton: { type: Boolean, default: false }
});

const emit = defineEmits(["updated", "added","delete", "view"]);

const rows = ref([]);
const isEditing = ref(null);
const newRow = reactive({});
const isLoading = ref(false);

const pagination = ref({
  currentPage: 1,
  totalPages: 1,
  totalItems: 100,
  itemsPerPage: 10,
});

const initializeNewRow = () => {
  props.columns.forEach(column => {
    newRow[column.key] = column.type === 'boolean' ? false : '';
  });
};

const saveEdit = async (row) => {
  try {
    await props.saveData(row);
    isEditing.value = null;
    emit("updated", row);
    window.showMessage("La modification a été effectuée avec succès.");
  } catch (error) {
    window.showMessage("La modification n'a pas pu être effectuée.", "error");
    console.error("Erreur lors de la sauvegarde des données :", error);
  }
};

const deleteRow = async (row) => {
  try {
    await props.deleteData(row);
    rows.value = rows.value.filter(item => item[props.rowKey] !== row[props.rowKey]);
    emit("delete", row);
    window.showMessage("La suppression a été effectuée avec succès.");
  } catch (error) {
    window.showMessage("Erreur lors de la suppression des données.", "error");
    console.error("Erreur lors de la suppression des données :", error);
  }
};

const cancelEdit = () => {
  isEditing.value = null;
};

const startEdit = (row) => {
  isEditing.value = row[props.rowKey];
};

const startView = (row) => {
  emit('view', row);
};

const addRow = async () => {
  try {
    const addedRow = await props.addData(newRow);
    rows.value.push(addedRow);
    emit("added", addedRow);
    initializeNewRow();
    window.showMessage("L'ajout a été effectué avec succès.");
  } catch (error) {
    console.error("Erreur inattendue lors de l'ajout d'une nouvelle ligne :", error);
  }
};

const fetchData = async () => {
  const { currentPage, itemsPerPage } = pagination.value;
  try {
    isLoading.value = true;
    const data = await props.fetchData({ page: currentPage, itemsPerPage });

    if (data && data.response) {

      rows.value = data.response?.data ?? data.response ?? null;

      if (data.response?.pagination || data.response?.meta || data.response.data?.pagination) {
        pagination.value.totalPages =
          data.response.pagination?.totalPages ||
          Math.ceil(data.response.meta?.total_items / itemsPerPage) ||
          1;

        pagination.value.totalItems =
          data.response.pagination?.totalItems ||
          data.response.meta?.total_items ||
          data.response.meta?.totalItems ||
          0;

      } else {
        console.warn("Pagination data is missing in the response, using defaults:", data.response);
        pagination.value.totalPages = 1;
        pagination.value.totalItems = rows.value?.length || 0;
      }
    } else {
      console.error("Invalid data structure:", data);
      window.showMessage("Received invalid data structure from server.", "error");
    }
  } catch (error) {
    console.error('Erreur lors de la récupération des données:', error);
    window.showMessage("Erreur lors de la récupération des données.", "error");
  } finally {
    isLoading.value = false;
  }
};

const changePage = (page) => {
  pagination.value.currentPage = page;
  fetchData();
};

const changePageSize = (newSize) => {
  pagination.value.itemsPerPage = newSize;
  pagination.value.currentPage = 1;
  fetchData();
};

const showPagination = computed(() => {
  return pagination.value.totalPages > 1;
});

onMounted(() => {
  fetchData();
});
</script>

<style scoped>
.bh-table-responsive table thead tr,
.bh-table-responsive table tfoot tr,
.bh-table-responsive table thead tr th.bh-sticky,
.bh-table-responsive table tbody tr td.bh-sticky {
  background: #eff5ff !important;
}
</style>