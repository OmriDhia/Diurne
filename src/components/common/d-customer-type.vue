<template>
    <div class="row align-items-center pt-2">
        <div class="col-4">
            <label for="droit" class="form-label">Type client<span class="required" v-if="required">*</span>:</label>
        </div>
        <div class="col-8">
            <select 
                id="droit"  
                :class="{ 'is-invalid': error, 'form-select': true }" 
                :value="type" 
                @input="handleChange($event.target.value)">
                <option value="0" selected disabled>Type de client</option>
                <option v-for="(prof, key) in customerTypes" :key="key" :value="prof.customerGroup_id">{{ prof.name }}</option>
                <option value="9">Autre</option>
            </select>
            <div v-if="error" class="invalid-feedback">{{ $t('Le type de client est obligatoire.') }}</div>
        </div>
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
            required:{
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                type: this.modelValue,
                customerTypes: []
            };
        },
        methods: {
            handleChange(newValue) {
                this.type = parseInt(newValue);
                this.$emit('update:modelValue', parseInt(newValue));
            },
            async getCustomerTypes() {
                try {
                    const res = await axiosInstance.get('/api/customerGroups');
                    this.customerTypes = res.data.response.customerGroup;
                } catch (error) {
                    console.error('Failed to fetch customer groups:', error);
                }
            }
        },
        mounted() {
            this.getCustomerTypes();
        },
        watch: {
            modelValue(newValue) {
                this.type = parseInt(newValue);
                console.log("customer type : " + newValue)

            }
        }
    };
</script>
<style scoped>
   
</style>
