<template>
  <tr>
    <EditableCell v-for="column in columns" :key="column.key" :row="row" :column="column" :isEditing="isEditing" />
    <td class="align-reglement">
      <template v-if="isEditing">
        <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="saveEdit(row)">
          <vue-feather type="save" :size="14"></vue-feather>
        </button>
        <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="cancelEdit()">
          <vue-feather type="slash" :size="14"></vue-feather>
        </button>
        <button v-if="showViewButton" type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="startView(row)">
          <vue-feather type="eye" :size="14"></vue-feather>
        </button>
      </template>
      <template v-else>
        <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="startEdit(row)">
          <vue-feather type="edit" :size="14"></vue-feather>
        </button>
        <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="deleteRow(row)">
          <vue-feather type="x" :size="14"></vue-feather>
        </button>
        <button v-if="showViewButton" type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="startView(row)">
          <vue-feather type="eye" :size="14"></vue-feather>
        </button>
        <button v-if="showRattacherButton" type="button" class="btn btn-dark mb-1 me-1" @click="startRattacher(row)">
          Attribuer
        </button>
      </template>
      <button v-if="showViewButton" type="button" class="btn btn-dark mb-1 me-2" @click="alertCommercial(row)">
          Alerte commercial
      </button>
      <div v-if="showViewButton" class="t-dot reglement" :class="hasOrderPaymentDetails ? 'bg-success' : 'bg-danger'"></div>
    </td>
  </tr>
</template>

<script setup>
import { computed } from 'vue';
import EditableCell from './d-editable-cell.vue';
import VueFeather from 'vue-feather';

const props = defineProps({
  row: { type: Object, required: true },
  columns: { type: Array, required: true },
  isEditing: { type: Boolean, required: true },
  showViewButton: { type: Boolean, default: false },
  showRattacherButton: { type: Boolean, default: false }
});

const emit = defineEmits(['edit', 'save', 'cancel', 'delete','view','rattacher']);

const hasOrderPaymentDetails = computed(() => {
  const details = props.row?.orderPaymentDetails;

  if (Array.isArray(details)) {
    return details.length > 0;
  }

  if (typeof details === 'string') {
    try {
      const parsed = JSON.parse(details);
      return Array.isArray(parsed) ? parsed.length > 0 : Boolean(parsed);
    } catch (error) {
      return details.trim().length > 0;
    }
  }

  if (details && typeof details === 'object') {
    return Object.keys(details).length > 0;
  }

  return false;
});

const startEdit = (row) => {
  emit('edit', row);
};

const saveEdit = (row) => {
  emit('save', row);
};

const cancelEdit = () => {
  emit('cancel');
};

const startView = (row) => {
  emit('view', row);
};

const startRattacher = (row) => {
  emit('rattacher', row);
};

const alertCommercial = (row) => {
  /*to do */
};

const deleteRow = (row) => {
  Swal.fire({
    title: "Êtes-vous sûr ?",
    text: "Cette action est irréversible !",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Oui, supprimer !",
    cancelButtonText: "Annuler"
  }).then((result) => {
    if (result.isConfirmed) {
      emit('delete', row);
    }
  });
};
</script>

<style lang="css" scoped>

.align-reglement{
    display: flex;
    align-items: center;
}

.reglement.t-dot {
    height: 20px;
    width: 20px;
    border-radius: 50%;
    cursor: pointer;
    margin: 0 auto;
}

</style>