<template>
    <div class="row align-items-center">
        <template v-for="item in designerStatus">
            <div class="col-auto pe-1 ps-2">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" :id="computedId+'-'+item.id" v-model="status"
                           :name="'designerStatus-'+computedId" :value="item.id" :disabled="disabled || item.id < 3 ? true : false" @change="handleChange(item.id)" @click="handleClick(item.id)"/>
                    <label class="custom-control-label text-black" :for="computedId+'-'+item.id">
                        {{item.label}} </label>
                </div>
            </div>
        </template>
        <div class="col-12" v-if="error">
            <div  class="invalid-feedback">{{ $t("Le champ unité de mesure est abligatoire.") }}</div>
        </div>
    </div>
</template>

<script>
    import { generateUniqueId } from "../../../../composables/global-methods";
    import { designerStatusConst } from "../../../../composables/constants";
    
    export default {
        props: {
            modelValue: {
                type: [Number, null],
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
            disabled: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                designerStatus: designerStatusConst,
                status: null
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value));
            },
            handleClick(value) {
                this.$emit('handleClick', parseInt(value));
            }
        },
        mounted() {
            if(this.modelValue){
                this.status = this.modelValue;
            }
        },
        watch: {
            unit(status) {
                this.handleChange(status);
            },
            modelValue(status) {
                this.status = status;
            }
        },
        computed: {
            computedId() {
                return generateUniqueId();
            }
        }
    };
</script>
