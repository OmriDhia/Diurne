<template>
    <div>
        <div class="row align-items-center pt-2">
            <div class="col-4"><label class="form-label">Modèle<span class="required" v-if="required">*</span>:</label></div>
            <div class="col-8">
                <div class="row">
                    <multiselect
                        :class="{ 'is-invalid': error}"
                        :model-value="value"
                        :options="data"
                        placeholder="Modèle"
                        track-by="id"
                        label="reference"
                        :searchable="true"
                        selected-label=""
                        select-label=""
                        deselect-label=""
                        @update:model-value="handleChange($event)"
                    ></multiselect>
                    <div v-if="error" class="invalid-feedback">{{ $t("Le champs modèle est abligatoire.") }}</div>
                </div>
            </div>
        </div>
        <div class="row align-items-center justify-content-end">
            <div class="col-8 pe-0">
                <button class="btn btn-custom pe-2 ps-2 font-size-0-7 w-100" @click="goToSettings">Céer une modèle</button>
            </div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../../../config/http';
    import Multiselect from '@suadelabs/vue3-multiselect'
    import '@suadelabs/vue3-multiselect/dist/vue3-multiselect.css';
    import store from "../../../../store/index";

    export default {
        components:{
            Multiselect
        },
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
        },
        data() {
            return {
                value: null,
                data: []
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value.id));
            },
            async getData() {
                    try {
                        const res = await axiosInstance.get('/api/collections');
                        this.data= res.data.response;
                    } catch (error) {
                        console.error('Failed to fetch address types:', error);
                    }
            },
            goToSettings(){

            }
        },
        mounted() {
            this.getData();
        },
        watch: {
            modelValue(newValue) {
                this.value = this.data.filter(ad => ad.id === newValue)[0]
            }
        }
    };
</script>
