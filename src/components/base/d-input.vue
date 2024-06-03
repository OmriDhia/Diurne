<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label" :for="computedId">{{ label }}<span class="required" v-if="required">*</span>:  </label></div>
        <div class="col-8">
            <input
                :type="type"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                :class="{ 'is-invalid': error, 'form-control': true }"
                :id="computedId"
                :disabled="disabled"
            />
            <div v-if="error" class="invalid-feedback">{{ $t(error) }}</div>
        </div>
    </div>
</template>

<script>
    import {generateUniqueId} from "../../composables/global-methods";

    export default {
        name: 'InputField',
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
