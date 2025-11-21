<template>
    <div>
        <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click.prevent="handleDelete"
                :disabled="disabled">
            <vue-feather type="x" :size="14"></vue-feather>
        </button>
    </div>
</template>

<script setup>
    import VueFeather from 'vue-feather';
    import axiosInstance from '../../config/http';

    const props = defineProps({
        message: {
            type: String,
            default: ''
        },
        api: {
            type: String
        },
        disabled: {
            type: Boolean,
            default: false
        }
    });
    const emit = defineEmits(['isDone']);
    const handleDelete = async () => {
        new window.Swal({
            title: 'Êtes-vous sûr ?',
            text: props.message ? props.message : 'Vous voulez supprimer cet élément!',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Annuler',
            confirmButtonText: 'Supprimer',
            padding: '2em'
        }).then(async result => {
            if (result.value) {
                try {
                    // mark deletion in progress and timestamp it so other components can avoid autosave loops
                    try {
                        window.__isDeleting = true;
                    } catch (e) {
                    }
                    try {
                        window.__recentlyDeleted = Date.now();
                    } catch (e) {
                    }
                    try {
                        window.__suppressAutoSaveUntil = Date.now() + 3000;
                    } catch (e) {
                    }
                    const res = await axiosInstance.delete(props.api);
                    emit('isDone');
                    window.showMessage('L\'élément a été supprimé avec succès.');
                } catch (e) {
                    console.error(e.toString());
                } finally {
                    try {
                        window.__isDeleting = false;
                    } catch (e) {
                    }
                }
            }
        });
    };

</script>
