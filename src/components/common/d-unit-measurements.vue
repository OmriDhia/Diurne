<template>
    <div class="row">
        <div class="col-auto pe-1 ps-2 text-black">
            Unité de mesure<span class="required" v-if="required">*</span>:
        </div>
        <template v-for="item in unitOfMesurements">
            <div class="col-auto pe-1 ps-2">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" :id="computedId+'-'+item.id" v-model="unit"
                           :name="'unitOfMesurements-'+computedId" :value="item.id" :disabled="disabled"/>
                    <label class="custom-control-label text-black" :for="computedId+'-'+item.id">
                        {{item.abbreviation}} </label>
                </div>
            </div>
        </template>
        <div class="col-12" v-if="error">
            <div  class="invalid-feedback">{{ $t("Le champ unité de mesure est abligatoire.") }}</div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../config/http';
    import {generateUniqueId} from "../../composables/global-methods";

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
                unitOfMesurements: null,
                unit: null
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', parseInt(value));
            },
            async getUnitOfMesurements() {
                try {
                    const url = '/api/unitOfMeasurements?feetInchCombinated=1';
                    const res = await axiosInstance.get(url);
                    
                    this.unitOfMesurements = res.data.response.units;
                    if(this.modelValue){
                        this.unit = this.modelValue;
                    }
                } catch (error) {
                    console.error('Failed to fetch address types:', error);
                }
            }
        },
        mounted() {
            this.getUnitOfMesurements();
        },
        watch: {
            unit(unit) {
                this.handleChange(unit);
            },
            modelValue(unit) {
                this.unit = unit;
            }
        },
        computed: {
            computedId() {
                return generateUniqueId();
            }
        }
    };
</script>
