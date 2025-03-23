<template>
  <td>
    <template v-if="isEditing">
      <template v-if="column.type === 'text'">
        <input type="text" v-model="row[column.key]" class="form-control" />
      </template>
      <template v-else-if="column.type === 'dropdown'">
        <select v-model="row[column.key]" class="form-select">
          <option v-for="option in column.options" :value="option" :key="option">
            {{ option }}
          </option>
        </select>
      </template>
      <template v-else-if="column.type === 'boolean'">
        <input type="checkbox" v-model="row[column.key]" class="form-check-input" />
      </template>
      <template v-else-if="column.type === 'number'">
        <input type="number" v-model="row[column.key]" class="form-control" />
      </template>
    </template>
    <template v-else>
      <span v-if="column.type === 'boolean'">
        <i :class="row[column.key] ? 'bi bi-check-circle text-success' : 'bi bi-x-circle text-danger'"></i>
      </span>
      <span v-else> {{ row[column.key] }} </span>
    </template>
  </td>
</template>

<script setup>
  const props = defineProps({
    row: { type: Object, required: true },
    column: { type: Object, required: true },
    isEditing: { type: Boolean, required: true },
  });
</script>