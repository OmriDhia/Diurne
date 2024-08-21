<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">Contact client:<span class="required" v-if="required">*</span> :</label></div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                :multiple="true"
                v-model="contact"
                :options="contacts"
                placeholder="Contact client"
                track-by="id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @tag="addTag"
                @update:model-value="handleChange($event)"
                @search-change="handleSearch($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champ contact client est abligatoire.") }}</div>
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
            },
            customerId:{
                type: Number,
                required: true
            }
        },
        data() {
            return {
                contact: [],
                contacts: [],
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', this.contact);
            },
            addTag(newTag){
                this.contacts.push(newTag);
                this.contact.push(newTag);  
            },
            async getContacts (){
                try{
                    if(this.customerId){
                        let url = `/api/customer/${this.customerId}/contacts`;

                        const res = await axiosInstance.get(url);
                        this.contacts = res.data.response.contacts.map(e => {
                            return {
                                id : e.contact_id,
                                name: e.firstname + " " + e.lastname
                            }
                        });  
                    }
                }catch{
                    console.log('Erreur get contact customer list.')
                }
            },
        },
        mounted() {
            this.getContacts();
        },
        watch: {
            modelValue(newValue) {
                this.contact = newValue;
            },
            customerId(newValue) {
                this.getContacts();
            }
        }
    };
</script>
