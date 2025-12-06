<template>
    <div class="row align-items-center pt-2">
        <div class="col-4" v-if="label"><label class="form-label" :for="computedId">{{ label }}<span class="required" v-if="required">*</span>:  </label></div>
        <div :class="{'col-5' : button && label,'col-8': !button && label, 'col-12': !label }">
            <input
                :type="type"
                :value="modelValue"
                :min="min"
                :max="max"
                @input="$emit('update:modelValue', $event.target.value)"
                @blur="$emit('changeValue', true)"
                :class="{ 'is-invalid': error, 'form-control': true }"
                :id="computedId"
                :disabled="disabled"
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
        name: 'InputField',
        props: {
            modelValue: {
                type: [String, Number, null],
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
            type: {
                type: String,
                default: 'text'
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
            min: {
                type: String,
                default: null
            },
            max: {
                type: String,
                default: null
            }
        },
        computed: {
            computedId() {
                return this.id || generateUniqueId();
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
</style>
