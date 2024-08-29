<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">Préscripteur<span class="required" v-if="required">*</span> :</label></div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                v-model="presId"
                :options="presDatas"
                :multiple="multiple"
                placeholder="Préscipteur"
                track-by="id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @update:model-value="handleChange($event)"
                @search-change="handleSearch($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champ client est abligatoire.") }}</div>
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
                type: [Object,Array, null],
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
            multiple:{
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                presId: [],
                presDatas: [],
                firstOne: true,
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value.id));
            },
            handleSearch(searchQuery){
                const se = searchQuery.split(' ');
                this.getCustomers(se[0], se[1]);
            },
            addTag(newTag){
                this.presDatas.push(newTag);
                this.presId.push(newTag);
            },
            async getCustomers (firstname = "", lastname = "", socialReason =""){
                try{
                    let url = '/api/prescripters?page=1&itemPerPage=100';

                    if(firstname){
                        url += '&filter[firstname]='+firstname;
                    }
                    
                    if(socialReason){
                        url += '&filter[socialReason]='+socialReason;
                    }

                    if(lastname){
                        url += '&filter[lastname]='+lastname;
                    }

                    const res = await axiosInstance.get(url);
                    const data = res.data.response.prescripters;
                    this.presDatas = data.map(d => {
                        return {id: d.id, name: d.gender + ' ' + d.firstname}
                    })
                    if(this.modelValue){
                        this.presId = this.presDatas.filter(d => d.id === this.modelValue)[0];
                    }
                }catch{
                    console.log('Erreur get prescripteur list.')
                }
            },
        },
        mounted() {
            this.getCustomers();
        },
        watch: {
            modelValue(newValue) {
                this.presId = this.presDatas.filter(d => d.id === newValue)[0];
                /*if(newValue !== null && typeof newValue === "object" && this.firstOne){
                    this.firstOne = false;
                    this.getCustomers("","",newValue.socialReason);
                }*/
            }
        }
    };
</script>
