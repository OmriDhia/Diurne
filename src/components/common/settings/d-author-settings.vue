<template>
    <div>
        <template v-if="isEditing">
            <d-users-dropdown
                v-model="dropdownValue"
                :label="''"
                :required="false"
            />
        </template>
        <template v-else>
            {{ displayValue }}
        </template>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import dUsersDropdown from '../d-users-dropdown.vue';

const props = defineProps({
    modelValue: {
        type: [Number, Object, String, null],
        default: null,
    },
    isEditing: {
        type: Boolean,
        default: false,
    },
    authors: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['update:modelValue']);

const dropdownValue = computed({
    get() {
        const id = extractId(props.modelValue);
        return (id || id === 0) ? [id] : [];
    },
    set(value) {
        const [selectedId] = Array.isArray(value) ? value : [];
        if (selectedId === undefined || selectedId === null) {
            emit('update:modelValue', null);
            return;
        }

        const author = findAuthor(selectedId);
        if (author) {
            emit('update:modelValue', { id: author.id, name: author.name });
        } else if (typeof props.modelValue === 'object' && props.modelValue) {
            const current = props.modelValue.__v_raw || props.modelValue;
            emit('update:modelValue', { id: selectedId, name: current.name });
        } else {
            emit('update:modelValue', { id: selectedId });
        }
    }
});

const displayValue = computed(() => {
    if (!props.modelValue) {
        return 'Aucun auteur sélectionné';
    }

    if (typeof props.modelValue === 'object') {
        const rawValue = props.modelValue.__v_raw || props.modelValue;
        if (rawValue.name && rawValue.name.trim()) {
            return rawValue.name.trim();
        }
        const firstname = rawValue.firstname ?? rawValue.first_name;
        const lastname = rawValue.lastname ?? rawValue.last_name;
        const combined = [firstname, lastname].filter(Boolean).join(' ').trim();
        if (combined) return combined;
        if (rawValue.id || rawValue.author_id || rawValue.authorId) {
            const id = rawValue.id ?? rawValue.author_id ?? rawValue.authorId;
            const author = findAuthor(id);
            if (author?.name) return author.name;
            return `Auteur #${id}`;
        }
    }

    const id = extractId(props.modelValue);
    if (id || id === 0) {
        const author = findAuthor(id);
        if (author?.name) return author.name;
        return `Auteur #${id}`;
    }

    if (typeof props.modelValue === 'string' && props.modelValue.trim()) {
        return props.modelValue.trim();
    }

    return 'Aucun auteur sélectionné';
});

function extractId(value) {
    if (value === null || value === undefined) return null;
    if (typeof value === 'object') {
        const raw = value.__v_raw || value;
        return raw.id ?? raw.author_id ?? raw.authorId ?? raw.user_id ?? null;
    }
    if (typeof value === 'string' && value.trim() && Number.isNaN(Number(value))) {
        return null;
    }
    const numeric = Number(value);
    return Number.isNaN(numeric) ? value : numeric;
}

function findAuthor(id) {
    if (id === null || id === undefined) return null;
    return props.authors.find(author => Number(author.id) === Number(id)) || null;
}
</script>

<style scoped></style>
