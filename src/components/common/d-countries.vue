<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">Pays<span class="required" v-if="required">*</span>:</label></div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                :model-value="country"
                :options="countries"
                placeholder="Pays"
                track-by="country_id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @update:model-value="handleChange($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le pays est abligatoire.") }}</div>
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
            countries: {
                get() {
                    return store.getters.countries;
                },
                set(value) {
                    store.commit('setCountries', value)
                }
            }
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
                country: null,
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value.country_id));
            },
            async getCountries() {
                if(this.countries.length === 0){
                    try {
                        const res = await axiosInstance.get('/api/countries');
                        this.countries = res.data.response.countries;
                        this.country = this.countries.filter(ad => ad.country_id === this.modelValue || ad.name === this.modelValue)[0];
                        if(this.country){
                            this.$emit('update:modelValue', parseInt(this.country.country_id));  
                        }else{
                            this.country = null 
                        }
                    } catch (error) {
                        console.error('Failed to fetch address types:', error);
                    }
                }
            }
        },
        mounted() {
            this.getCountries();
        },
        watch: {
            modelValue(newValue) {
                this.country = this.countries.filter(ad => ad.country_id === newValue || ad.name === newValue)[0]
            }
        }
    };
</script>
