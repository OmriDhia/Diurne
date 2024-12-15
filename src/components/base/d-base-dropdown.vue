<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">{{ name }}<span class="required" v-if="required">*</span> :</label></div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                :model-value="data"
                :options="datas"
                :placeholder="name"
                :track-by="trackBy"
                :label="label"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
                @update:model-value="handleChange($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champ " + name + " est abligatoire.") }}</div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../config/http';
    import Multiselect from 'vue-multiselect'
    import 'vue-multiselect/dist/vue-multiselect.css';
    import store from "../../store/index";

    export default {
        components:{
            Multiselect
        },
        computed: {
            
        },
        props: {
            modelValue: {
                type: [Object, null],
                required: true
            },
            error: {
                type: String,
                default: ''
            },
            required:{
                type: Boolean,
                default: false
            },
            disabled:{
                type: Boolean,
                default: false
            },
            name:{
                type: String,
                required: true 
            },
            datas:{
                type: Array,
                required: true
            },
            trackBy:{
                type: String,
                required: true
            },
            label:{
                type: String,
                required: true
            }
        },
        data() {
            return {
                data: null,
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', value);
            },
        },
        mounted() {
            this.data = this.modelValue;
        },
        watch: {
            modelValue(newValue) {
                console.log(newValue);
                this.data = newValue
            }
        }
    };
</script>
