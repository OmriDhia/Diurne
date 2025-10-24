<template>
    <d-data-grid
        v-if="isCollectionGroupsLoaded"
        :fetchData="fetchData"
        :saveData="saveData"
        :addData="addData"
        :deleteData="deleteData"
        :columns="processedColumns"
        :rows="rows"
        title="Collection de tapis"
        rowKey="id"
    />
</template>

<script setup>
    import dDataGrid from '../base/d-data-grid.vue';
    import { ref, computed, onMounted } from 'vue';
    import axiosInstance from '../../config/http';

    const columns = [
        { key: 'reference', label: 'reference', type: 'text' },
        { key: 'code', label: 'Code', type: 'text' },
        { key: 'show_grid', label: 'Display grid', type: 'boolean' },
        {
            key: 'collection_group_id',
            label: 'Groupe de collecte',
            type: 'custom',
            component: 'd-collection-group-settings',
            idKey: 'id',
            nameKey: 'name'
        },
        {
            key: 'author',
            label: 'Auteur',
            type: 'custom',
            component: 'd-author-settings'
        }
    ];

    const rows = ref([]);
    const collectionGroups = ref([]);
    const authors = ref([]);
    const isCollectionGroupsLoaded = ref(false);

    async function fetchCollectionGroup() {
        try {
            const res = await axiosInstance.get('/api/collection-groups');
            collectionGroups.value = res.data.response;
        } catch (error) {
            console.error('Failed to fetch collection-groups:', error);
        }
    }

    async function fetchAuthors() {
        try {
            const res = await axiosInstance.get('api/users?page=1&itemPerPage=100');
            const data = res.data.response?.users || res.data.response || res.data.users || res.data;
            authors.value = Array.isArray(data)
                ? data.map(user => ({
                    id: user.id,
                    name: [user.firstname, user.lastname].filter(Boolean).join(' ').trim() || user.name || `Auteur #${user.id}`
                }))
                : [];
        } catch (error) {
            console.error('Failed to fetch authors:', error);
            authors.value = [];
        }
    }

    onMounted(async () => {
        try {
            await Promise.all([fetchCollectionGroup(), fetchAuthors()]);
        } finally {
            isCollectionGroupsLoaded.value = true;
        }
    });

    const getAuthorLabel = (id) => {
        if (!id && id !== 0) return '';
        const author = authors.value.find(authorItem => Number(authorItem.id) === Number(id));
        return author?.name || '';
    };

    const normalizeAuthor = (item) => {
        if (!item) return null;

        const possibleAuthor = item.author ?? item.auteur ?? item.user ?? null;
        const authorId = item.author_id ?? item.authorId ?? item.user_id ?? null;

        if (possibleAuthor && typeof possibleAuthor === 'object') {
            const rawAuthor = possibleAuthor.__v_raw || possibleAuthor;
            const id = rawAuthor.id ?? rawAuthor.author_id ?? rawAuthor.authorId ?? rawAuthor.user_id ?? authorId;
            if (!id) return null;
            const name = rawAuthor.name
                || [rawAuthor.firstname ?? rawAuthor.first_name, rawAuthor.lastname ?? rawAuthor.last_name]
                    .filter(Boolean)
                    .join(' ')
                    .trim();

            return {
                id,
                name: name || getAuthorLabel(id) || `Auteur #${id}`
            };
        }

        if (typeof possibleAuthor === 'string' && possibleAuthor.trim() && Number.isNaN(Number(possibleAuthor))) {
            return {
                id: authorId ?? null,
                name: possibleAuthor.trim()
            };
        }

        const id = authorId ?? (typeof possibleAuthor === 'number' ? possibleAuthor : null);
        if (!id) return null;

        const name = item.author_name
            ?? item.authorName
            ?? getAuthorLabel(id);

        return {
            id,
            name: (typeof name === 'string' && name.trim()) ? name.trim() : `Auteur #${id}`
        };
    };

    const ensureAuthorCached = (author) => {
        if (!author || !author.id) return;
        const exists = authors.value.some(existing => Number(existing.id) === Number(author.id));
        if (!exists) {
            authors.value.push({
                id: author.id,
                name: author.name || `Auteur #${author.id}`
            });
        }
    };

    const processedColumns = computed(() => {
        if (!isCollectionGroupsLoaded.value) return [];

        return columns.map(col => {
            if (col.component === 'd-collection-group-settings') {
                return {
                    ...col,
                    props: {
                        collectionGroups: collectionGroups.value
                    }
                };
            }
            if (col.component === 'd-author-settings') {
                return {
                    ...col,
                    props: {
                        authors: authors.value
                    }
                };
            }
            return col;
        });
    });

    const fetchData = async ({ page, itemsPerPage }) => {
        try {
            const { data } = await axiosInstance.get('/api/collections', {
                params: { page, itemsPerPage }
            });

            const transformedData = data.response.data.map(item => {
                const collectionGroup = collectionGroups.value.find(group => group.id === item.collection_group_id);
                const author = normalizeAuthor(item);
                ensureAuthorCached(author);

                return {
                    ...item,
                    collection_group_id: collectionGroup || null,
                    author,
                    show_grid: item.show_grid || false
                };
            });

            return {
                ...data,
                response: {
                    ...data.response,
                    data: transformedData
                }
            };

        } catch (error) {
            console.error('Erreur lors de la récupération des données:', error);
            throw new Error('Failed to fetch data');
        }
    };


    const addData = async (row) => {
        try {
            const payload = {
                reference: row.reference,
                code: row.code,
                show_grid: row.show_grid || false,
                collection_group_id: typeof row.collection_group_id === 'object'
                    ? row.collection_group_id.id
                    : row.collection_group_id,
                author_id: row.author
                    ? (typeof row.author === 'object'
                        ? row.author.id
                        : row.author)
                    : null,
                languages: row.languages && row.languages.length > 0
                    ? row.languages
                    : [{ description: 'Default Description', languageId: 1 }]
            };

            const { data } = await axiosInstance.post('/api/carpet-collections', payload);

            const newRow = {
                ...data.response,
                collection_group_id: collectionGroups.value.find(g => g.id === data.response.collection_group_id),
                author: normalizeAuthor(data.response)
            };
            ensureAuthorCached(newRow.author);
            rows.value.push(newRow);
            return newRow;
        } catch (error) {
            console.error('Erreur lors de l\'ajout des données:', error);
            throw new Error('Failed to add data');
        }
    };

    const saveData = async (row) => {
        try {
            const payload = {
                reference: row.reference,
                code: row.code,
                show_grid: row.show_grid || false,
                collection_group_id: typeof row.collection_group_id === 'object'
                    ? row.collection_group_id.id
                    : row.collection_group_id,
                author_id: row.author
                    ? (typeof row.author === 'object'
                        ? row.author.id
                        : row.author)
                    : null,
                languages: row.languages && row.languages.length > 0
                    ? row.languages
                    : [{ description: 'Default Description', languageId: 1 }]
            };

            const { data } = await axiosInstance.put(`/api/carpet-collections/${row.id}`, payload);

            const updatedRow = {
                ...data.response,
                collection_group_id: collectionGroups.value.find(g => g.id === data.response.collection_group_id),
                author: normalizeAuthor(data.response),
                languages: row.languages && row.languages.length > 0
                    ? row.languages
                    : [{ description: 'Default Description', languageId: 1 }]
            };
            ensureAuthorCached(updatedRow.author);

            const index = rows.value.findIndex(item => item.id === row.id);
            if (index !== -1) {
                rows.value[index] = updatedRow;
            }
            return updatedRow;
        } catch (error) {
            console.error('Erreur lors de la mise à jour des données:', error);
            throw new Error('Failed to save data');
        }
    };

    const deleteData = async (row) => {
        try {
            await axiosInstance.delete(`/api/collections/${row.id}`);
            rows.value = rows.value.filter(item => item.id !== row.id);
        } catch (error) {
            console.error('Erreur lors de la suppression des données:', error);
            throw new Error('Failed to delete data');
        }
    };
</script>
