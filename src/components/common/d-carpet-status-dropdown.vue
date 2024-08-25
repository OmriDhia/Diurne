<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">Etat du Tapis<span class="required" v-if="required">*</span> :</label></div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                :model-value="carpertStatusId"
                :options="carpetStatus"
                placeholder="Etat du Tapis"
                track-by="id"
                label="name"
                selected-label=""
                select-label=""
                deselect-label=""
                @update:model-value="handleChange($event)"
                @search-change="handleSearch($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champ etat du tapis est abligatoire.") }}</div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../config/http';
    import Multiselect from '@suadelabs/vue3-multiselect'
    import '@suadelabs/vue3-multiselect/dist/vue3-multiselect.css';
    import store from "../../store/index";

    export default {
        components:{
            Multiselect
        },
        computed: {
            
        },
        props: {
            modelValue: {
                type: [Number, null],
                required: true
            },
            error: {
                type: String,
                default: ''
            },
            required:{
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                carpertStatusId: null,
                carpetStatus: [],
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value.id));
            },
            async getCarpetStatus (){
                try{
                    let url = '/api/contremarque/carpetDesignOrder/statuses';
                    const res = await axiosInstance.get(url);
                    this.carpetStatus = res.data.response.statuses;
                }catch{
                    console.log('Erreur get carpet status list.')
                }
            },
        },
        mounted() {
            this.getCarpetStatus();
        },
        watch: {
            modelValue(newValue) {
                this.carpertStatusId = this.carpetStatus.filter(ad => ad.id === newValue)[0];
            }
        }
    };
</script>
