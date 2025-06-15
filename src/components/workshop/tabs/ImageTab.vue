<template>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Image Preview</h5>
                        <div class="image-placeholder">
                            <img v-if="imageUrl" :src="imageUrl" alt="Uploaded image" class="img-fluid">
                            <div v-else class="placeholder-content">
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">No image available</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-row ">
                    <d-input label="Nom du fichier" rootClass="pink-bg" class="w-100"
                             v-model="data.formData.nomFichier" />
                </div>

                <div class="form-row ">
                    <d-input label="Date de création" rootClass="pink-bg" type="date" class="w-100"
                             v-model="data.formData.dateCreation" />
                </div>

                <div class="form-row ">
                    <d-input label="Format" class="w-100" v-model="data.formData.format" />
                </div>

                <div class="form-row ">
                    <d-input label="Emplacement" class="w-100" v-model="data.formData.emplacement" />

                </div>

                <div class="form-row ">
                    <div class="row w-100 pt-2">
                        <div class="col-4"><label class="form-label" :for="computedId"> Année grille tarif<span
                            class="required" v-if="required">*</span>: </label></div>
                        <div class="col-8 px-2">
                            <SelectInput v-model="data.formData.type" rootClass="pink-bg" />
                        </div>
                    </div>

                </div>

            </div>


        </div>
    </div>
</template>
<script setup>
    import dInput from '../../../components/base/d-input.vue';
    import { ref } from 'vue';
    import SelectInput from '@/components/workshop/ui/SelectInput.vue';

    const data = ref({
        formData: {
            nomFichier: 'test',
            dateCreation: '',
            format: '',
            emplacement: '',
            type: ''
        },
        imageUrl: null
    });
</script>
