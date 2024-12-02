<template>
    <div class="container">
        <h1 class="my-4">{{ props.title}}</h1>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th v-for="column in columns" :key="column.key">{{ column.label }}</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="isLoading">
                <td :colspan="columns.length + 1" class="text-center">Chargement...</td>
            </tr>
            <tr v-for="(row, index) in gridData" :key="row[props.rowKey]">
                <td v-for="column in columns" :key="column.key">
                    <template v-if="isEditing === row[props.rowKey]">
                        <template v-if="column.type === 'text'">
                            <input
                                type="text"
                                v-model="row[column.key]"
                                class="form-control"
                            />
                        </template>
                        <template v-else-if="column.type === 'dropdown'">
                            <select v-model="row[column.key]" class="form-select">
                                <option v-for="option in column.options" :value="option" :key="option">
                                    {{ option }}
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
                    </template>
                    <template v-else>
              <span v-if="column.type === 'boolean'">
                <i
                    :class="row[column.key] ? 'bi bi-check-circle text-success' : 'bi bi-x-circle text-danger'"
                ></i>
              </span>
                        <span v-else>
                {{ row[column.key] }}
              </span>
                    </template>
                </td>
                <td>
                    <template v-if="isEditing === row[props.rowKey]">
                        <button class="btn btn-success btn-sm" @click="saveEdit(row)">
                            Sauvegarder
                        </button>
                        <button class="btn btn-secondary btn-sm" @click="cancelEdit">
                            Annuler
                        </button>
                    </template>
                    <template v-else>
                        <button class="btn btn-primary btn-sm" @click="isEditing = row[props.rowKey]">
                            Modifier
                        </button>
                    </template>
                </td>
            </tr>
            <tr>
                <td v-for="column in columns" :key="column.key">
                    <template v-if="column.type === 'text'">
                        <input
                            type="text"
                            v-model="newRow[column.key]"
                            class="form-control"
                            :placeholder="`Ajouter ${column.label}`"
                        />
                    </template>
                    <template v-else-if="column.type === 'dropdown'">
                        <select v-model="newRow[column.key]" class="form-select">
                            <option
                                v-for="option in column.options"
                                :value="option"
                                :key="option"
                            >
                                {{ option }}
                            </option>
                        </select>
                    </template>
                    <template v-else-if="column.type === 'boolean'">
                        <input
                            type="checkbox"
                            v-model="newRow[column.key]"
                            class="form-check-input"
                        />
                    </template>
                </td>
                <td>
                    <button class="btn btn-success btn-sm" @click="addRow">Ajouter</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
    import { ref, reactive, onMounted } from "vue";

    const props = defineProps({
        fetchData: {
            type: Function,
            required: true,
        },
        saveData: {
            type: Function,
            required: true,
        },
        addData: {
            type: Function,
            required: true,
        },
        columns: {
            type: Array,
            required: true, // [{ key: 'name', label: 'Nom', type: 'text' | 'dropdown' | 'boolean', options?: [...] }]
        },
        rowKey: {
            type: String,
            required: true,
        },
        title: {
            type: String,
            required: false,
        },
    });

    const emit = defineEmits(["updated", "added"]);

    const gridData = ref([]);
    const isEditing = ref(false);
    const newRow = reactive({});
    const isLoading = ref(false);

    // Charger les données
    const loadGridData = async () => {
        isLoading.value = true;
        try {
            gridData.value = await props.fetchData();
        } catch (error) {
            console.error("Erreur lors du chargement :", error);
        } finally {
            isLoading.value = false;
        }
    };

    // Sauvegarder
    const saveEdit = async (row) => {
        try {
            await props.saveData(row);
            isEditing.value = false;
            emit("updated", row);
        } catch (error) {
            console.error("Erreur de sauvegarde :", error);
        }
    };

    // Annuler
    const cancelEdit = () => {
        isEditing.value = false;
    };

    // Ajouter une ligne
    const addRow = async () => {
        try {
            const addedRow = await props.addData(newRow);
            gridData.value.push(addedRow);
            emit("added", addedRow);
            Object.keys(newRow).forEach((key) => (newRow[key] = ""));
        } catch (error) {
            console.error("Erreur d'ajout :", error);
        }
    };

    onMounted(() => {
        loadGridData();
    });
</script>
