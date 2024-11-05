<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">Couleur <span v-if="index > 0"> Fil N° {{index}}</span> <span class="required" v-if="required">*</span>:</label>
        </div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error }"
                :model-value="value"
                :options="data"
                placeholder="Couleur"
                track-by="id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
                :custom-label="customLabel"
                @update:model-value="handleChange($event)"
                @open="adjustDropdownPosition('open')"
                @close="adjustDropdownPosition"
            >
                <template #option="{ option }">
                    <div v-html="customLabel(option)"></div>
                </template>
                <template #singleLabel="{ option }">
                    <div v-html="customLabel(option)"></div>
                </template>
            </multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champs Couleur est obligatoire.") }}</div>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import axiosInstance from "../../../../config/http";

    export default {
        components: { Multiselect },
        props: {
            modelValue: {
                type: [Number, String, null],
                required: true
            },
            error: {
                type: String,
                default: ''
            },
            required: {
                type: Boolean,
                default: false
            },
            index: {
                type: Number,
            },
            disabled: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                value: null,
                data: []
            };
        },
        methods: {
            customLabel(color) {
                return `<div class="d-flex align-items-center">
                            <span class="label-multiselect-color" style="background-color:${color.hexCode};"></span>
                            <span>${color.name}</span>
                        </div>`;
            },
            adjustDropdownPosition(c) {
                const multiselectElement = document.querySelector('#modalCompositionThread .modal-body');
                const multiselectElementNew = document.querySelector('#modalNewComposition .modal-body');

                if (c === 'open') {
                    if (multiselectElement) {
                        multiselectElement.style.minHeight = "90vh";
                    }
                    if (multiselectElementNew) {
                        multiselectElementNew.style.minHeight = "90vh";
                    }
                } else {
                    if (multiselectElement) {
                        multiselectElement.removeAttribute('style');
                    }
                    if (multiselectElementNew) {
                        multiselectElementNew.removeAttribute('style');
                    }
                }
            },
            handleChange(value) {
                this.$emit('update:modelValue', value);
            },
            async getData() {
                try {
                    const res = await axiosInstance.get('/api/dominant-colors');
                    this.data = res.data.response;
                } catch (error) {
                    console.error('Failed to fetch dominant colors:', error);
                }
            },
        },
        mounted() {
            this.getData();
        },
        watch: {
            modelValue(newValue) {
                this.value = newValue;
            }
        }
    };
</script>

<style>
    .label-multiselect-color{
        width: 40px; 
        height: 20px;
        margin-right: 5px;
        display: inline-block;
    }
</style>
