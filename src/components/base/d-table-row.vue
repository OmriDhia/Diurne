<template>
  <tr>
    <EditableCell v-for="column in columns" :key="column.key" :row="row" :column="column" :isEditing="isEditing" />
    <td>
      <template v-if="isEditing">
        <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="saveEdit(row)">
          <vue-feather type="save" :size="14"></vue-feather>
        </button>
        <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="cancelEdit()">
          <vue-feather type="slash" :size="14"></vue-feather>
        </button>
      </template>
      <template v-else>
        <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="startEdit(row)">
          <vue-feather type="edit" :size="14"></vue-feather>
        </button>
        <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="deleteRow(row)">
          <vue-feather type="x" :size="14"></vue-feather>
        </button>
      </template>
    </td>
  </tr>
</template>

<script setup>
  import EditableCell from './d-editable-cell.vue';
  import VueFeather from 'vue-feather';

  defineProps({
    row: { type: Object, required: true },
    columns: { type: Array, required: true },
    isEditing: { type: Boolean, required: true },
  });

  const emit = defineEmits(['edit', 'save', 'cancel', 'delete']);

  const startEdit = (row) => {
    emit('edit', row);
  };

  const saveEdit = (row) => {
    emit('save', row);
  };

  const cancelEdit = () => {
    emit('cancel');
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

