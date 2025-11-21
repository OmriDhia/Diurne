<template>
    <div>
        <d-base-modal id="modalAddMaterials" title="Ajout matière" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-materials-dropdown v-model="data.material_id"></d-materials-dropdown>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input label="Taux" type="number" v-model="data.rate"></d-input>
                        </div>
                    </div>
                    <div class="row p-1 align-items-center">
                        <div class="col-sm-12 col-md-8">
                            <d-input label="Prix" type="text" v-model="data.price"></d-input>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="addMaterials">Ajouter</button>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { nextTick, ref } from 'vue';
    import dInput from '@/components/base/d-input.vue';
    import dBaseModal from '@/components/base/d-base-modal.vue';
    import dMaterialsDropdown from '@/components/projet/contremarques/dropdown/d-materials-dropdown.vue';

    const data = ref({
        material_id: 0,
        rate: '',
        price: ''
    });
    const emit = defineEmits(['onClose', 'addMaterial', 'add-materials-click']);
    const addMaterials = async () => {
        // require a material selection
        const materialId = data.value.material_id ? Number(data.value.material_id) : 0;
        if (!materialId || Number.isNaN(materialId) || materialId <= 0) {
            window.showMessage('Veuillez choisir une matière', 'error');
            return;
        }
        // normalize rate to null or string with two decimals if provided
        const rateVal = data.value.rate === '' || data.value.rate === null ? null : (Number.isNaN(Number(data.value.rate)) ? null : Number(data.value.rate).toFixed(2));
        const priceVal = data.value.price === '' || data.value.price === null ? '' : (Number.isNaN(Number(data.value.price)) ? '' : Number(data.value.price).toFixed(2));
        // emit materialId (int) to satisfy server validation
        emit('addMaterial', { materialId: materialId, rate: rateVal, price: priceVal });
        await nextTick();
        emit('add-materials-click');
        // close modal
        const btn = document.querySelector('#modalAddMaterials .btn-close');
        if (btn) btn.click();
    };
    const handleClose = () => {
        data.value = { material_id: 0, rate: '', price: '' };
        emit('onClose');
    };
</script>
