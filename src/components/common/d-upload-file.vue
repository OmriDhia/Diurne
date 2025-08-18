<script setup>
    import { ref } from 'vue';
    import VueFeather from 'vue-feather';

    const emit = defineEmits(['file-selected']);
    const file = ref(null);

    const props = defineProps({
        fileName: {
            type: String,
            default: '',
        },
    });

    const handleDrop = (event) => {
        event.preventDefault();
        if (event.dataTransfer.files.length > 0) {
            selectFile(event.dataTransfer.files[0]);
        }
    };

    const handleFileSelect = (event) => {
        if (event.target.files.length > 0) {
            selectFile(event.target.files[0]);
        }
    };

    const selectFile = (selectedFile) => {
        file.value = selectedFile;
        emit('file-selected', selectedFile);
    };

    const removeFile = () => {
        file.value = null;
        emit('file-selected', null);
    };
</script>

<template>
    <div>
        <div
            class="dropzone border border-primary rounded p-4 text-center"
            @dragover.prevent
            @drop="handleDrop"
            @click="$refs.fileInput.click()"
        >
            <input type="file" ref="fileInput" class="d-none" @change="handleFileSelect" accept="image/*" />

            <div v-if="!file">
                <VueFeather type="upload-cloud" size="48" class="text-primary mb-2" />
                <p class="mb-0">Cliquez ou glissez un fichier ici</p>
            </div>

            <div v-if="file" class="d-flex justify-content-between align-items-center mt-4 mb-4">
                <p class="mb-0"><strong>Fichier sélectionné :</strong> {{ fileName || file.name }}</p>
                <VueFeather type="x-circle" size="24" class="text-danger" @click.stop="removeFile" style="cursor: pointer;" />
            </div>
        </div>
    </div>
</template>

<style scoped>
    .dropzone {
        background: #f8f9fa;
        cursor: pointer;
        transition: background 0.3s;
    }
    .dropzone:hover {
        background: #e9ecef;
    }
</style>
