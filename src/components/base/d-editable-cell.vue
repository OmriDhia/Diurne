<template>
  <td>
    <template v-if="isEditing">
      <template v-if="column.type === 'text'">
        <input type="text" v-model="row[column.key]" class="form-control" />
      </template>
      <template v-else-if="column.type === 'dropdown'">
        <select v-model="row[column.key]" class="form-select">
          <option v-for="option in column.options" :value="option.value" :key="option.value">
            {{ option.label }}
          </option>
        </select>
      </template>
      <template v-else-if="column.type === 'boolean'">
        <input 
          type="checkbox" 
          v-model="row[column.key]" 
          class="form-check-input" 
        />
      </template>
      <template v-else-if="column.type === 'number'">
        <input type="number" v-model="row[column.key]" class="form-control" />
      </template>
      <template v-else-if="column.type === 'date'">
        <input type="datetime-local" :value="formatDateForInput(row[column.key])"
          @input="row[column.key] = $event.target.value" class="form-control"
          :max="new Date().toISOString().slice(0, 16)" />
      </template>
      <template v-else-if="column.type === 'custom'">
        <component :is="getComponent(column.component)" v-model="row[column.key]" :isEditing="true"
          v-bind="column.props || {}" />
      </template>
    </template>
    <template v-else>
      <template v-if="column.type === 'boolean'">
        <vue-feather v-if="row[column.key]" type="check-circle" class="text-success" size="20"></vue-feather>
        <vue-feather v-else type="x-circle"  class="text-danger"  size="20"></vue-feather>
      </template>
      <template v-else-if="column.type === 'custom'">
        <component
          :is="getComponent(column.component)"
          :modelValue="row[column.key]"
          :isEditing="false"
          v-bind="column.props || {}"
        />
      </template>
      <template v-else-if="column.type === 'date'">
        {{ formatDateDisplay(row[column.key]) }}
      </template>
      <template v-else-if="column.formatter">
        {{ column.formatter(row[column.key]) }}
      </template>
      <template v-else>
        {{ row[column.key] }}
      </template>
    </template>
  </td>
</template>

<script setup>
import { defineAsyncComponent } from 'vue';
import VueFeather from 'vue-feather';

const props = defineProps({
  row: { type: Object, required: true },
  column: { type: Object, required: true },
  isEditing: { type: Boolean, required: true },
});

const formatDateForInput = (dateString) => {
  if (!dateString) return '';
  try {
    const date = new Date(dateString);
    const pad = num => num.toString().padStart(2, '0');
    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
  } catch (e) {
    console.error('Error formatting date for input:', e);
    return '';
  }
};

const formatDateDisplay = (dateString) => {
  if (!dateString) return '';
  try {
    const date = new Date(dateString);
    return date.toLocaleString();
  } catch (e) {
    console.error('Error formatting date for display:', e);
    return dateString;
  }
};

const getComponent = (name) => {
  return defineAsyncComponent(() => import(`../common/settings/${name}.vue`));
};

defineExpose({
  formatDateForInput,
  formatDateDisplay
});
</script>

<style scoped>
  .form-check-input[type="checkbox"]{
    position: unset;
    opacity: 1;
  }
</style>