<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">Suivi mailing:</label></div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                :model-value="language"
                :options="languages"
                placeholder="Language"
                track-by="language_id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @update:model-value="handleChange($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("La langue est abligatoire.") }}</div>
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
            languages: {
                get() {
                    return store.getters.languages;
                },
                set(value) {
                    store.commit('setLanguages', value)
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
                language: null,
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value.language_id));
            },
            async getLanguages() {
                if(this.languages.length === 0){
                    try {
                        const res = await axiosInstance.get('/api/languages');
                        this.languages = res.data.response.languages;
                        this.language = this.languages.filter(ad => ad.language_id === this.modelValue)[0];
                        if(this.language){
                            this.$emit('update:modelValue', parseInt(this.language.language_id));  
                        }else{
                            this.language = null 
                        }
                    } catch (error) {
                        console.error('Failed to fetch address types:', error);
                    }
                }
            }
        },
        mounted() {
            this.getLanguages();
        },
        watch: {
            modelValue(newValue) {
                this.language = this.languages.filter(ad => ad.language_id === newValue)[0]
            }
        }
    };
</script>
