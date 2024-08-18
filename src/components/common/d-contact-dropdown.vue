<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">Client<span class="required" v-if="required">*</span> :</label></div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                :model-value="customerId"
                :options="customers"
                placeholder="Client"
                track-by="id"
                label="customer"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @tag="addTag"
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
                customerId: null,
                customers: [],
            };
        },
        methods: {
            handleChange(value) {
                if(this.multiple){
                    this.$emit('update:modelValue', value);
                }else{
                    this.$emit('update:modelValue', parseInt(value.id));
                }
            },
            addTag(newTag){
                this.customers.push(newTag);
                this.customerId.push(newTag);
            },
            handleSearch(searchQuery){
                const se = searchQuery.split(' ');
                this.getCustomers(se[0], se[1]);
            },
            async getCustomers (firstname = "", lastname = ""){
                try{
                    let url = '/api/customers?page=1&itemsPerPage=50';

                    if(firstname){
                        url += '&filter[firstname]='+firstname;
                    }

                    if(lastname){
                        url += '&filter[lastname]='+lastname;
                    }

                    const res = await axiosInstance.get(url);
                    this.customers = res.data.response.customers;
                }catch{
                    console.log('Erreur get customers list.')
                }
            },
        },
        mounted() {
            this.getCustomers();
        },
        watch: {
            modelValue(newValue) {
                if(this.multiple){
                    this.customerId = this.customers.filter(ad =>newValue.includes(ad.id));
                }else{
                    this.customerId = this.customers.filter(ad => ad.id === newValue)[0];
                }
            }
        }
    };
</script>
