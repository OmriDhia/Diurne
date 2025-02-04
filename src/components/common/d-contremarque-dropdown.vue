<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">Contremarque<span class="required" v-if="required">*</span> :</label></div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                :model-value="selectedContremarque"
                :options="contremarques"
                placeholder="Contremarque"
                track-by="contremarque_id"
                label="designation"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
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
            },
            disabled:{
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                selectedContremarque: null,
                contremarques: [],
            };
        },
        methods: {
            handleChange(value) {
                // this.$emit('update:modelValue', parseInt(value.contremarque_id));
                this.selectedContremarque = value;
                this.$emit('update:modelValue', value ? value.contremarque_id : null);

            },
            handleSearch(searchQuery){
                this.getContremarques(searchQuery);
            },
            async getContremarques (designation = ""){
                try{
                    let url = '/api/contremarques?page=1&limit=200&order=designation&orderWay=asc';
                    let localString = "contremarqueList";
                    if(this.customerId){
                        url += '&customerId=' + this.customerId;
                        localString += this.customerId;
                    }
                    if(designation){
                        url += '&designation=' + designation;
                        localString += designation;
                    }

                    if(!localStorage.getItem(localString)){
                        const res = await axiosInstance.get(url);
                        localStorage.setItem(localString,JSON.stringify(res.data.contremarques))
                    }
                    
                    this.contremarques = JSON.parse(localStorage.getItem(localString));
                    // Select the contremarque from the URL if available
                    if (this.modelValue) {
                        this.selectedContremarque = this.contremarques.find(  ad => ad.contremarque_id === this.modelValue ) || null;
                    }
                }catch(e){
                    console.error(e);
                    console.log('Erreur get contremarques list.')
                }
            }
        },
        mounted() {
            this.getContremarques();
        },
        watch: {
            modelValue(newValue) {
                this.selectedContremarque = this.contremarques.find( ad => ad.contremarque_id === newValue ) || null;
                console.log("Selection mise à jour :", this.selectedContremarque);
            },
            // modelValue(newValue) {
            //     this.contremarqueId = this.contremarques.filter(ad => ad.contremarque_id === newValue)[0];
            // },
            customerId(){
                this.getContremarques();
            }
        }
    };
</script>
