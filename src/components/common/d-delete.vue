<template>
    <div>
        <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click.prevent="handleDelete">
            <vue-feather type="x" :size="14"></vue-feather>
        </button>
    </div>
</template>

<script setup>
    import VueFeather from 'vue-feather';
    import axiosInstance from "../../config/http";
    
    const props = defineProps({
        message: {
            type: String,
            default: ""
        },
        api: {
            type: String
        },
    });
    const emit = defineEmits(['isDone']);
    const handleDelete = async () => {
        new window.Swal({
            title: 'Êtes-vous sûr ?',
            text: props.message ? props.message : "Vous voulez supprimer cet élément!",
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Annuler',
            confirmButtonText: 'Supprimer',
            padding: '2em'
        }).then(result => {
            if (result.value) {
                try{
                    const res = axiosInstance.delete(props.api);
                    emit('isDone');
                    window.showMessage("L'élément a été supprimé avec succès.")
                }catch(e){
                    console.error(e.toString())
                }
            }
        });
    }
    
</script>
