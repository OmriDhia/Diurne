<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">Type intermediaire<span class="required" v-if="required">*</span>:</label></div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                :model-value="type"
                :options="intermediaryTypes"
                placeholder="Type intermediaire"
                track-by="intermediaryType_id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @update:model-value="handleChange($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le type de d'adresse est abligatoire.") }}</div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../config/http';
    import Multiselect from '@suadelabs/vue3-multiselect';
    import '../../assets/sass/scrollspyNav.scss';
    import '@suadelabs/vue3-multiselect/dist/vue3-multiselect.css';
    import store from "../../store/index";

    export default {
        components:{
            Multiselect
        },
        computed: {
            intermediaryTypes: {
                get() {
                    return store.getters.intermediaryTypes;
                },
                set(value) {
                    store.commit('setIntermediaryTypes', value)
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
            required: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                type: null,
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value.intermediaryType_id));
            },
            async getIntermediaryTypes() {
                if(this.intermediaryTypes.length === 0){
                    try {
                        const res = await axiosInstance.get('/api/intermediaryTypes');
                        this.intermediaryTypes = res.data.response.intermediaryTypes;
                        this.type = this.intermediaryTypes.filter(ad => ad.intermediaryType_id === this.modelValue)[0]
                    } catch (error) {
                        console.error('Failed to fetch intermediary types:', error);
                    }
                }
            }
        },
        mounted() {
            this.getIntermediaryTypes();
        },
        watch: {
            modelValue(newValue) {
                this.type = this.intermediaryTypes.filter(ad => ad.intermediaryType_id === newValue)[0]
            }
        }
    };
</script>
<style scoped>
    .invalid-feedback{
        display: flex !important;
        font-size: 10px;
    }
</style>
