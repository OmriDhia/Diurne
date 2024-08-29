<template>
    <div class="row">
        <div class="col-md-12">
            <div class="row align-items-center pt-2">
                <div class="col-md-4"><label class="form-label">Collection<span class="required" v-if="required">*</span>:</label>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <multiselect
                            :class="{ 'is-invalid': error}"
                            :model-value="value"
                            :options="data"
                            placeholder="Collections"
                            track-by="id"
                            label="reference"
                            :searchable="true"
                            selected-label=""
                            select-label=""
                            deselect-label=""
                            @update:model-value="handleChange($event)"
                        ></multiselect>
                        <div v-if="error" class="invalid-feedback">{{ $t("Le champs collection est abligatoire.") }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-end">
                <div class="col-md-8 pe-0">
                    <button class="btn btn-custom pe-2 ps-2 font-size-0-7 w-100" @click="goToSettings">Céer une
                        collection
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../../../config/http';
    import Multiselect from '@suadelabs/vue3-multiselect'
    import '@suadelabs/vue3-multiselect/dist/vue3-multiselect.css';

    export default {
        components: {
            Multiselect
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
                value: null,
                data: []
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value.id));
            },
            async getData() {
                try {
                    const res = await axiosInstance.get('/api/collections');
                    this.data = res.data.response;
                } catch (error) {
                    console.error('Failed to fetch address types:', error);
                }
            },
            goToSettings() {

            }
        },
        mounted() {
            this.getData();
        },
        watch: {
            modelValue(newValue) {
                this.value = this.data.filter(ad => ad.id === newValue)[0]
            }
        }
    };
</script>
