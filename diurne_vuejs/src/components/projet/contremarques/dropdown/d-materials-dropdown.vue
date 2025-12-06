<template>
    <div class="row align-items-center pt-2">
        <div class="col-4" v-if="!hideLabel">
            <label class="form-label">Matière<span class="required" v-if="required">*</span>:</label>
        </div>
        <div :class="{'col-8': !hideLabel, 'col-12': hideLabel}">
            <multiselect
                :class="{ 'is-invalid': error }"
                v-model="value" 
                :options="materialsData"
                placeholder="Matière"
                track-by="id"
                label="reference"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
                @update:model-value="handleChange"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champs matière est obligatoire.") }}</div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import Multiselect from 'vue-multiselect';
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
            hideLabel: {
                type: Boolean,
                default: false
            },
            disabled: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                value: null
            };
        },
        computed: {
            ...mapGetters(['materialsData'])
        },
        methods: {
            ...mapActions(['fetchMaterials']),
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value.id));
            },
            selectedValue() {
                if (this.modelValue) {
                    this.value = this.materialsData.find(mat => mat.id === this.modelValue) || null;
                }
            }
        },
        async mounted() {
            await this.fetchMaterials(); 
            this.selectedValue(); 
        },
        watch: {
            modelValue: 'selectedValue', 
            materialsData: 'selectedValue' 
        }
    };
</script>

<style scoped>
    /* Ajoutez vos styles ici si nécessaire */
</style>
