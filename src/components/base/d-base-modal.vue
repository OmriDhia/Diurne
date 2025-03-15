<template>
    <div :ref="props.ref" class="modal animated fadeInDown" :key="`_Key_${props.id}`" :id="props.id" tabindex="-1" role="dialog" aria-labelledby="fadeinModalLabel">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="fadeinModalLabel">{{ props.title }}</h5>
                    <button type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"
                        class="btn-close" @click.prevent="handleClose"></button>
                </div>
                <div class="modal-body">
                    <slot name="modal-body"></slot>
                </div>
                <div class="modal-footer">
                    <slot name="modal-footer"></slot>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    const props = defineProps({
        title : {
            type: String,
            default: ""
        },
        id : {
            type: String,
            default: "idModal"
        },
        ref : {
            type: String,
            default: ""
        }
    });
    
    const emit = defineEmits(['onClose']);

    const handleClose = () => {
        emit('onClose')
    }
</script>
