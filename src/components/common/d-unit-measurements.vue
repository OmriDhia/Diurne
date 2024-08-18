<template>
    <div class="d-flex">
        <div class="col-auto pe-1 ps-2 text-black">
            Unité de mesure<span class="required" v-if="required">*</span>:
        </div>
        <template v-for="item in unitOfMesurements">
            <div class="col-auto pe-1 ps-2">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" :id="'Unit-'+item.id" v-model="unit"
                           name="unitOfMesurements" :value="item.id" :disabled="disabled"/>
                    <label class="custom-control-label text-black" :for="'Unit-'+item.id">
                        {{item.abbreviation}} </label>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import axiosInstance from '../../config/http';

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
                    const res = await axiosInstance.get('/api/unitOfMeasurements');
                    this.unitOfMesurements = res.data.response.units;
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
        }
    };
</script>
