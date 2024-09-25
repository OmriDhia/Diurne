<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">
            <tempalte v-if="isPrescripteur">
                Prescripteur
            </tempalte>
            <tempalte v-else>
                Client
            </tempalte>
            <span class="required" v-if="required">*</span> :</label></div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                v-model="customerId"
                :options="customers"
                :multiple="multiple"
                :placeholder=" isPrescripteur ? 'Prescripteur' : 'Client'"
                track-by="id"
                label="customer"
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
    import Multiselect from 'vue-multiselect'
    import 'vue-multiselect/dist/vue-multiselect.css';

    export default {
        components:{
            Multiselect
        },
        computed: {
            
        },
        props: {
            modelValue: {
                type: [Number,Array, null],
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
            },
            isPrescripteur:{
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                customerId: [],
                customers: [],
                firstOne: true,
            };
        },
        methods: {
            handleChange(value) {
                if(this.multiple){
                    this.$emit('update:modelValue', this.customerId);
                }else{
                    this.$emit('update:modelValue', parseInt(value.id));  
                }
            },
            handleSearch(searchQuery){
                this.getCustomers(searchQuery);
            },
            addTag(newTag){
                this.customers.push(newTag);
                this.customerId.push(newTag);
            },
            async getCustomers (customerName = "",){
                try{
                    let url = '/api/customers?page=1&itemsPerPage=100&orderBy=customer&orderWay=asc';

                    if(customerName){
                        url += '&filter[customerName]='+customerName;
                    }

                    const res = await axiosInstance.get(url);
                    this.customers = res.data.response.customers;
                    if(this.modelValue){
                        this.customerId = this.customers.filter(e => e.id === this.modelValue);
                    }
                }catch{
                    console.log('Erreur get customers list.')
                }
            },
        },
        mounted() {
            this.getCustomers();
            console.log("mounted");
        },
        watch: {
            modelValue(newValue) {
                if(this.multiple){
                    this.customerId = newValue;
                }else{
                    this.customerId = this.customers.filter(e => e.id === newValue);
                }
            }
        }
    };
</script>
