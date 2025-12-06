<template>
    <div class="row align-items-center pt-2">
        <div class="col-4" v-if="!hideLabel">
            <label class="form-label">Type attachement<span class="required" v-if="required">*</span>:</label>
        </div>
        <div :class="{'col-8': !hideLabel, 'col-12': hideLabel}">
            <multiselect
                :class="{ 'is-invalid': error }"
                v-model="value"
                :options="attachmentTypes" 
                placeholder="Type attachement"
                track-by="id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
                @update:model-value="handleChange($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champs type attachement est obligatoire.") }}</div>
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
            }
        },
        data() {
            return {
                value: null
            };
        },
        computed: {
            ...mapGetters(['attachmentTypes'])
        },
        methods: {
            ...mapActions(['fetchAttachmentTypes']),
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value.id));
            },
            selectedValue(){
                if (this.modelValue) {
                    this.value = this.attachmentTypes.find(ad => ad.id === this.modelValue);
                }
            }
        },
        async mounted() {
            await this.fetchAttachmentTypes();
            this.selectedValue();
        },
        watch: {
            modelValue:'selectedValue',
            imageTypes:'selectedValue',
        }
    };
</script>
