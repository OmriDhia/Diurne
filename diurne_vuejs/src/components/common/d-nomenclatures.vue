<template>
    <div class="row align-items-center pt-2">
        <div class="col-4">
            <label class="form-label text-capitalize">évènement
                <span class="required" v-if="required">*</span>:
            </label>
        </div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error }"
                v-model="nomenclature"
                :options="filteredNomenclatures"
                placeholder="Evenement"
                track-by="nomenclature_id"
                label="subject"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @select="handleChange"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t('Le sujet évènement est abligatoire.') }}</div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../config/http';
    import Multiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';
    import store from '../../store/index';

    export default {
        components: {
            Multiselect
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
            excludeAutomatic: {
                type: Boolean,
                default: true
            }
        },
        data() {
            return {
                nomenclature: null  // To bind to the dropdown
            };
        },
        computed: {
            nomenclatures: {
                get() {
                    return store.getters.nomenclatures || [];
                },
                set(value) {
                    store.commit('setNomenclatures', value);
                }
            },
            filteredNomenclatures() {
                return this.excludeAutomatic
                    ? this.nomenclatures.filter((nomenclature) => !nomenclature.is_automatic)
                    : this.nomenclatures;
            }
        },
        methods: {
            handleChange(value) {
                if (value) {
                    this.$emit('update:modelValue', parseInt(value.nomenclature_id));
                    this.$emit('changeNomenclature', value);
                } else {
                    this.$emit('update:modelValue', null);  // Reset if no selection
                }
            },
            async getNomenclatures() {
                if (this.nomenclatures.length === 0) {
                    try {
                        const res = await axiosInstance.get('/api/nomenclatures');
                        this.nomenclatures = res.data.response.nomenclatures;
                        this.affectModalValue();
                    } catch (error) {
                        console.error('Failed to fetch nomenclatures:', error);
                    }
                } else {
                    this.affectModalValue();
                }
            },
            affectModalValue() {
                // Find and set the selected nomenclature based on modelValue
                if (this.modelValue) {
                    this.nomenclature = this.filteredNomenclatures.find(
                        (nomenclature) => nomenclature.nomenclature_id === this.modelValue
                    );
                } else {
                    this.nomenclature = null;
                }
            }
        },
        mounted() {
            this.getNomenclatures();  // Fetch nomenclatures on mount
        },
        watch: {
            modelValue(newValue) {
                this.affectModalValue();  // Update the dropdown if modelValue changes
            }
        }
    };
</script>
