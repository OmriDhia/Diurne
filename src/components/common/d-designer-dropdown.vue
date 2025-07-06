<template>
    <div class="row align-items-center pt-2">
        <div class="col-4" v-if="!hideLabel">
            <label class="form-label">Designeur<span class="required" v-if="required">*</span> :</label>
        </div>
        <div :class="{ 'col-8': !hideLabel, 'col-12': hideLabel }">
            <Multiselect
                :class="{ 'is-invalid': error }"
                :multiple="isMultiple"
                v-model="userId"
                :options="users"
                placeholder="Designeur"
                track-by="id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
                @tag="addTag"
                @update:model-value="handleChange"
                @search-change="handleSearch"
            />
            <div v-if="error" class="invalid-feedback">{{ $t("Le champ designeur est obligatoire.") }}</div>
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
        type: [Array, String, null],
        required: true
    },
    error: {
        type: String,
        default: ''
    },
    required: {
        type: Boolean,
        default: false
    },
    hideLabel: {
        type: Boolean,
        default: false
    },
    isMultiple: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue']);

const userId = ref([]);
const users = ref([]);

const handleChange = (value) => {
    emit('update:modelValue', props.isMultiple ? value.map(e => e.id) : value?.id);
};

const addTag = (newTag) => {
    users.value.push(newTag);
    userId.value.push(newTag);
};

const handleSearch = (searchQuery) => {
    const [firstname = '', lastname = ''] = searchQuery.split(' ');
    getUsers(firstname, lastname);
};

const getUsers = async (firstname = "", lastname = "") => {
    try {
        let url = 'api/users?page=1&itemPerPage=100';
        if (firstname) url += `&filter[firstname]=${firstname}`;
        if (lastname) url += `&filter[lastname]=${lastname}`;
        url += "&filter[profiles]=Designer,Designer manager";

        const res = await axiosInstance.get(url);
        console.log("designer", res.data.response.users);
        users.value = res.data.response.users.map(e => ({
            id: e.id,
            name: `${e.firstname} ${e.lastname}`
        }));

        syncUserId();
    } catch {
        console.error('Erreur get users list.');
    }
};

const syncUserId = () => {
    if (props.modelValue) {
        if (props.isMultiple) {
            userId.value = users.value.filter(f => props.modelValue.includes(f.id));
        } else {
            userId.value = users.value.find(f => f.id === props.modelValue) || null;
        }
    }
};

watchEffect(() => {
    syncUserId();
});

getUsers();
</script>
