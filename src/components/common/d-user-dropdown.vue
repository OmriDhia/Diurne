<template>
    <div class="row align-items-center pt-2">
        <div class="col-4" v-if="!hideLabel">
            <label class="form-label">Auteur<span class="required" v-if="required">*</span> :</label>
        </div>
        <div :class="{ 'col-8': !hideLabel, 'col-12': hideLabel }">
            <Multiselect
                :class="{ 'is-invalid': error }"
                :multiple="isMultiple"
                v-model="selectedUser"
                :options="users"
                placeholder="Auteur"
                track-by="id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
                @update:model-value="handleChange"
                @search-change="handleSearch"
            />
            <div v-if="error" class="invalid-feedback">{{ $t('Le champ auteur est obligatoire.') }}</div>
        </div>
    </div>
</template>

<script setup>
import { ref, watchEffect } from 'vue';
import axiosInstance from '../../config/http';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';

const props = defineProps({
    modelValue: {
        type: [Array, String, Number, null],
        required: true,
    },
    error: {
        type: String,
        default: '',
    },
    required: {
        type: Boolean,
        default: false,
    },
    hideLabel: {
        type: Boolean,
        default: false,
    },
    isMultiple: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue']);

const selectedUser = ref(props.isMultiple ? [] : null);
const users = ref([]);

const handleChange = (value) => {
    emit('update:modelValue', props.isMultiple ? value.map(e => e.id) : value?.id);
};

const handleSearch = (searchQuery) => {
    const [firstname = '', lastname = ''] = searchQuery.split(' ');
    getUsers(firstname, lastname);
};

const getUsers = async (firstname = '', lastname = '') => {
    try {
        let url = 'api/users?page=1&itemPerPage=100';
        if (firstname) url += `&filter[firstname]=${firstname}`;
        if (lastname) url += `&filter[lastname]=${lastname}`;
        const res = await axiosInstance.get(url);
        const data = res.data.response?.users || res.data.response || res.data.users || res.data;
        users.value = data.map(u => ({ id: u.id, name: `${u.firstname} ${u.lastname}` }));
        syncSelectedUser();
    } catch (e) {
        console.error('Erreur get users list.', e);
    }
};

const syncSelectedUser = () => {
    if (props.modelValue) {
        if (props.isMultiple) {
            selectedUser.value = users.value.filter(u => Array.isArray(props.modelValue) && props.modelValue.includes(u.id));
        } else {
            selectedUser.value = users.value.find(u => u.id === props.modelValue) || null;
        }
    }
};

watchEffect(() => {
    syncSelectedUser();
});

getUsers();
</script>

<style scoped></style>
