<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label text-capitalize">évènement:</label></div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                :model-value="nomenclature"
                :options="nomenclatures"
                placeholder="Evenement"
                track-by="nomenclature_id"
                label="subject"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @update:model-value="handleChange($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le sujet évènement est abligatoire.") }}</div>
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
            nomenclatures: {
                get() {
                    return store.getters.nomenclatures;
                },
                set(value) {
                    store.commit('setNomenclatures', value)
                }
            }
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
        },
        data() {
            return {
                nomenclature: null,
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value.nomenclature_id));
            },
            async getNomenclatures() {
                if(this.nomenclatures.length === 0){
                    try {
                        const res = await axiosInstance.get('/api/nomenclatures');
                        this.nomenclatures = res.data.response.nomenclatures;
                        this.nomenclature = this.nomenclatures.filter(ad => ad.nomenclature_id === this.modelValue)[0];
                        if(this.nomenclature){
                            this.$emit('update:modelValue', parseInt(this.nomenclature.nomenclature_id));  
                        }else{
                            this.nomenclature = null 
                        }
                    } catch (error) {
                        console.error('Failed to fetch address types:', error);
                    }
                }
            }
        },
        mounted() {
            this.getNomenclatures();
        },
        watch: {
            modelValue(newValue) {
                this.nomenclature = this.nomenclatures.filter(ad => ad.nomenclature_id === newValue)[0]
            }
        }
    };
</script>
