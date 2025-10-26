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
import { computed, ref, onMounted, watch } from 'vue';
import axiosInstance from '../../../config/http';
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

const fetchedAuthors = ref([]);

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

const normalizedProvidedAuthors = computed(() => normalizeAuthorList(props.authors));

const authorsMap = computed(() => {
    const map = new Map();

    normalizedProvidedAuthors.value.forEach(author => {
        const id = Number(author.id);
        if (!Number.isNaN(id)) {
            map.set(id, author);
        }
    });

    fetchedAuthors.value.forEach(author => {
        const normalized = normalizeAuthorEntry(author);
        if (!normalized) return;

        const id = Number(normalized.id);
        if (Number.isNaN(id)) return;

        const existing = map.get(id);
        if (!existing || isPlaceholderName(existing.name)) {
            map.set(id, normalized);
        }
    });

    return map;
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

    if (typeof props.modelValue === 'string') {
        const trimmed = props.modelValue.trim();
        if (!trimmed) {
            return 'Aucun auteur sélectionné';
        }

        const placeholderId = extractPlaceholderId(trimmed);
        if (placeholderId !== null) {
            const author = findAuthor(placeholderId);
            if (author?.name && !isPlaceholderName(author.name)) {
                return author.name;
            }
        }

        return trimmed;
    }

    return 'Aucun auteur sélectionné';
});

function extractId(value) {
    if (value === null || value === undefined) return null;
    if (typeof value === 'object') {
        const raw = value.__v_raw || value;
        return raw.id ?? raw.author_id ?? raw.authorId ?? raw.user_id ?? null;
    }
    if (typeof value === 'string') {
        const trimmed = value.trim();
        if (!trimmed) return null;

        const placeholderId = extractPlaceholderId(trimmed);
        if (placeholderId !== null) {
            return placeholderId;
        }

        const numeric = Number(trimmed);
        return Number.isNaN(numeric) ? value : numeric;
    }
    const numeric = Number(value);
    return Number.isNaN(numeric) ? value : numeric;
}

function findAuthor(id) {
    if (id === null || id === undefined) return null;
    const numericId = Number(id);
    if (Number.isNaN(numericId)) return null;
    return authorsMap.value.get(numericId) || null;
}

function normalizeAuthorList(list) {
    if (!Array.isArray(list)) return [];
    return list
        .map(normalizeAuthorEntry)
        .filter(Boolean);
}

function normalizeAuthorEntry(author) {
    if (!author) return null;

    const raw = author.__v_raw || author;
    const id = raw.id ?? raw.author_id ?? raw.authorId ?? raw.user_id;
    if (id === null || id === undefined) return null;

    const firstname = raw.firstname ?? raw.first_name ?? null;
    const lastname = raw.lastname ?? raw.last_name ?? null;
    const nameFromFields = [firstname, lastname].filter(Boolean).join(' ').trim();
    const name = (raw.name && raw.name.trim()) || nameFromFields;

    return {
        id,
        name: name || `Auteur #${id}`,
        firstname,
        lastname,
    };
}

function isPlaceholderName(name) {
    if (!name) return true;
    const trimmed = name.trim();
    return trimmed === '' || /^Auteur\s*#\d+$/i.test(trimmed);
}

function extractPlaceholderId(label) {
    const match = /^Auteur\s*#(\d+)$/i.exec(label.trim());
    if (!match) return null;
    const numeric = Number(match[1]);
    return Number.isNaN(numeric) ? null : numeric;
}

async function fetchAuthors(firstname = '', lastname = '') {
    try {
        let url = 'api/users?page=1&itemPerPage=100';

        if (firstname) {
            url += `&filter[firstname]=${encodeURIComponent(firstname)}`;
        }

        if (lastname) {
            url += `&filter[lastname]=${encodeURIComponent(lastname)}`;
        }

        url += '&filter[profiles]=Super admin,Commercial,Commercial manager,Designer,Designer manager,ADV,Assistant commercial&filter[isActive]=true';

        const { data } = await axiosInstance.get(url);
        const users = data?.response?.users || [];
        fetchedAuthors.value = users.map(user => ({
            id: user.id,
            firstname: user.firstname ?? null,
            lastname: user.lastname ?? null,
            name: [user.firstname, user.lastname].filter(Boolean).join(' ').trim() || user.name || `Auteur #${user.id}`,
        }));
    } catch (error) {
        console.error('Erreur lors de la récupération des auteurs :', error);
        fetchedAuthors.value = [];
    }
}

onMounted(() => {
    fetchAuthors();
});

watch(
    () => props.modelValue,
    (newValue) => {
        const id = extractId(newValue);
        if ((id || id === 0) && !findAuthor(id)) {
            const raw = newValue && typeof newValue === 'object' ? (newValue.__v_raw || newValue) : null;
            const firstname = raw?.firstname ?? raw?.first_name ?? '';
            const lastname = raw?.lastname ?? raw?.last_name ?? '';
            if (firstname || lastname) {
                fetchAuthors(firstname, lastname);
            } else {
                fetchAuthors();
            }
        }
    },
    { immediate: false }
);
</script>

<style scoped></style>
