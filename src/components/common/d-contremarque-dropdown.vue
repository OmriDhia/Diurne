<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">Contremarque<span class="required" v-if="required">*</span> :</label></div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                :model-value="contremarqueId"
                :options="contremarques"
                placeholder="Contremarque"
                track-by="contremarque_id"
                label="designation"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @update:model-value="handleChange($event)"
                @search-change="handleSearch($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champ contremarque est abligatoire.") }}</div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../config/http';
    import Multiselect from 'vue-multiselect'
    import 'vue-multiselect/dist/vue-multiselect.css';
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
            customerId: {
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
                contremarqueId: null,
                contremarques: [],
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value.contremarque_id));
            },
            handleSearch(searchQuery){
                this.getContremarques(searchQuery);
            },
            async getContremarques (designation = ""){
                try{
                    let url = '/api/contremarques?page=1&limit=200&order=designation&orderWay=asc';
                    
                    if(this.customerId){
                        url += '&customerId=' + this.customerId;
                    }
                    if(designation){
                        url += '&designation=' + designation;
                    }

                    const res = await axiosInstance.get(url);
                    this.contremarques = res.data.contremarques;
                    if(this.modelValue){
                        this.contremarqueId = this.contremarques.filter(ad => ad.contremarque_id === this.modelValue)[0];
                    }
                }catch(e){
                    console.error(e);
                    console.log('Erreur get contremarques list.')
                }
            },
        },
        mounted() {
            this.getContremarques();
        },
        watch: {
            modelValue(newValue) {
                this.contremarqueId = this.contremarques.filter(ad => ad.contremarque_id === newValue)[0];
            },
            customerId(){
                this.getContremarques();
            }
        }
    };
</script>
