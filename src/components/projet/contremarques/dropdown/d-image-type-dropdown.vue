<template>
    <div class="row align-items-center pt-2">
        <div class="col-4" v-if="!hideLabel">
            <label class="form-label">Type image<span class="required" v-if="required">*</span>:</label>
        </div>
        <div :class="{ 'col-8': !hideLabel, 'col-12': hideLabel }">
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
                :disabled="disabled"
                @update:model-value="handleChange($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t('Le champs image type est obligatoire.') }}</div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import Multiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';

    export default {
        components: {
            Multiselect,
        },
        props: {
            modelValue: {
                type: [Number, String, null],
                required: true,
            },
            error: {
                type: String,
                default: '',
            },
            required: {
                type: Boolean,
                default: false,
            },
            hideLabel: {
                type: Boolean,
                default: false,
            },
            disabled: {
                type: Boolean,
                default: false,
            },
        },
        data() {
            return {
                value: null,
            };
        },
        computed: {
            // ...mapGetters(['imageTypes']),
            imageTypes() {
                return this.$store.getters.imageTypes;
            },
        },
        methods: {
            ...mapActions(['fetchImageTypes']),
            handleChange(value) {
                console.log("value attachment type id : ", value.id);
                this.$emit('update:modelValue', parseInt(value.id));
                this.$emit('imageTypeSelected', value.name); // Emit name to parent
                this.$emit('imageTypeUpdateSelected', value.id); // Emit name to parent
            },
            selectedValue() {
                if (this.modelValue) {
                    this.value = this.imageTypes.find((ad) => ad.id === this.modelValue);
                    this.$emit('update:modelValue', parseInt(this.value.id));
                    this.$emit('imageTypeSelected', this.value.name); // Emit name to parent
                }
            },
        },
        async mounted() {
            await this.fetchImageTypes();
            this.selectedValue();
        },
        watch: {
            modelValue: 'selectedValue',
            imageTypes: 'selectedValue',
        },
    };
</script>
