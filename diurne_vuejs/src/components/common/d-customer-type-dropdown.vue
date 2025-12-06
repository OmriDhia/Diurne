<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">Type de client<span class="required" v-if="required">*</span> :</label></div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                v-model="customerId"
                :options="customers"
                :multiple="true"
                placeholder="Type client"
                track-by="customerGroup_id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @update:model-value="handleChange($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champ client est abligatoire.") }}</div>
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
                type: [Array, null],
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
                customerId: [],
                customers: [],
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', this.customerId);
            },
            addTag(newTag){
                this.customers.push(newTag);
                this.customerId.push(newTag);
            },
            async getCustomers (){
                try{
                    const res = await axiosInstance.get('/api/customerGroups');
                    this.customers = res.data.response.customerGroup;
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
                this.customerId = newValue
            }
        }
    };
</script>
