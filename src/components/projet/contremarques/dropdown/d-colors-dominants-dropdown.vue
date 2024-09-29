<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">Couleur<span class="required" v-if="required">*</span>:</label>
        </div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                :model-value="value"
                :options="data"
                placeholder="Couleur"
                track-by="id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @update:model-value="handleChange($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champs Couleur est abligatoire.") }}</div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../../../config/http';
    import Multiselect from 'vue-multiselect'
    import 'vue-multiselect/dist/vue-multiselect.css';

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
                this.$emit('update:modelValue', value);
            },
            async getData() {
                try {
                    const res = await axiosInstance.get('/api/dominant-colors');
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
                this.value = newValue
            }
        }
    };
</script>
