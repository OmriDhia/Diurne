<template>
    <div class="row align-items-center pt-2">
        <div class="col-4" v-if="!hideLabel">
            <label class="form-label">Type image<span class="required" v-if="required">*</span>:</label>
        </div>
        <div :class="{'col-8': !hideLabel, 'col-12': hideLabel}">
            <multiselect
                :class="{ 'is-invalid': error }"
                v-model="value"
                :options="imageTypes" 
                placeholder="Type image"
                track-by="id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @update:model-value="handleChange($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champs image type est obligatoire.") }}</div>
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
            }
        },
        data() {
            return {
                value: null
            };
        },
        computed: {
            ...mapGetters(['imageTypes'])
        },
        methods: {
            ...mapActions(['fetchImageTypes']),
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value.id));
            },
            selectedValue(){
                if (this.modelValue) {
                    this.value = this.imageTypes.find(ad => ad.id === this.modelValue);
                }
            }
        },
        async mounted() {
            await this.fetchImageTypes();
            this.selectedValue();
        },
        watch: {
            modelValue:'selectedValue',
            imageTypes:'selectedValue',
        }
    };
</script>
