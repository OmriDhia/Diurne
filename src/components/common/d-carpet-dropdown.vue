<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">Client<span class="required" v-if="required">*</span> :</label></div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                :model-value="carpertTypeId"
                :options="carpetTypes"
                placeholder="Type de tapis"
                track-by="id"
                label="name"
                selected-label=""
                select-label=""
                deselect-label=""
                @update:model-value="handleChange($event)"
                @search-change="handleSearch($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champ type de tapis est abligatoire.") }}</div>
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
                carpertTypeId: null,
                carpetTypes: [
                    {id: 1, name: "tapis"},
                    {id: 2, name: "échantillon"}
                ],
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value.id));
            },
            async getCarpetTypes (){
                try{
                    let url = '/api/carpet-types';
                    const res = await axiosInstance.get(url);
                    this.carpetTypes = res.data.response.carpet_types;
                }catch{
                    console.log('Erreur get carpet type list.')
                }
            },
        },
        mounted() {
            this.getCarpetTypes();
        },
        watch: {
            modelValue(newValue) {
                this.carpertTypeId = this.carpetTypes.filter(ad => ad.id === newValue)[0];
            }
        }
    };
</script>
