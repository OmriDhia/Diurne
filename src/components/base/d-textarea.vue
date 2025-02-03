<template>
    <div class="row align-items-center pt-2">
        <div class="col-4" v-if="label">
            <label class="form-label" :for="computedId">
                {{ label }}<span class="required" v-if="required">*</span>:  
            </label>
        </div>
        <div :class="{'col-5' : button && label,'col-8': !button && label, 'col-12': !label }">
            <textarea
                :type="type"
                :value="modelValue"
                @input="updateValue"
                :class="{ 'is-invalid': error, 'form-control': true }"
                :id="computedId"
                :disabled="disabled"
                :rows="rows || 5" 
            />
            <div v-if="error" class="invalid-feedback">{{ $t(error) }}</div>
        </div>
        <div class="col-3">
            <slot name="input-button"></slot>
        </div>
    </div>
</template>

<script>
    import {generateUniqueId} from "../../composables/global-methods";

    export default {
        name: 'DTextarea',
        props: {
            modelValue: {
                type: [String,null],
                required: true
            },
            error: {
                type: String,
                default: ''
            },
            label: {
                type: String,
                required: false,
                default: ''
            },
            id: {
                type: String,
                required: false,
                default: null
            },
            disabled:{
                type: Boolean,
                required: false,
                default: false
            },
            required: {
                type: Boolean,
                required: false,
                default: false
            },
            button: {
                type: Boolean,
                default: false
            },
            rows: {
                type: Number,
                default: 5 
            }
        },
        computed: {
            computedId() {
                return this.id || generateUniqueId();
            }
        },
        methods: {
            updateValue(event) {
                const newValue = event.target.value;
                if (newValue !== this.modelValue) {
                    this.$emit("update:modelValue", newValue);
                }
            }
        }
    };
</script>

<style scoped>
    .invalid-feedback{
        display: flex !important;
        font-size: 10px;
    }
    input{
        padding: 6px 12px !important;
    }
    textarea.form-control {
        resize: vertical; /* Allow the user to resize the textarea vertically */
    }
</style>
