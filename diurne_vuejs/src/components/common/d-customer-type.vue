<template>
    <div class="row align-items-center pt-2">
        <div class="col-4">
            <label for="droit" class="form-label">Type client<span class="required" v-if="required">*</span>:</label>
        </div>
        <div class="col-8">
            <select 
                id="droit"  
                :class="{ 'is-invalid': error, 'form-select': true }"
                v-model="selectedCustomerGroupType"
                @change="updateCustomerGroup">
                <option value="0" selected disabled>Type de client</option>
                <option v-for="(prof, key) in customerTypes" :key="key" :value="prof.customerGroup_id">{{ prof.name }}</option>
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
                selectedCustomerGroupType: this.modelValue.customerGroupId || 0,
                customerTypes: []
            };
        },
        methods: {
            async getCustomerTypes() {
                try {
                    const res = await axiosInstance.get('/api/customerGroups');
                    this.customerTypes = res.data?.response?.customerGroup || [];
                    this.setCustomerType(); // Initialize selected value after fetching
                } catch (error) {
                    console.error('Failed to fetch customer groups:', error);
                    this.customerTypes = [];
                }
            },
            updateCustomerGroup(event) {
                const selectedOption = this.customerTypes.find(opt => opt.customerGroup_id === parseInt(this.selectedCustomerGroupType));
                if (selectedOption) {
                    const newValue = {
                        customerGroupId: selectedOption.customerGroup_id,
                        customerType: selectedOption.name
                    };
                    // Emit only if the value has changed to prevent infinite loops
                    if (JSON.stringify(newValue) !== JSON.stringify(this.modelValue)) {
                        this.$emit('update:modelValue', newValue);
                    }
                }
            },
            setCustomerType() {
                const matchingOption = this.customerTypes.find(opt => opt.customerGroup_id === this.modelValue.customerGroupId);
                if (matchingOption) {
                    this.selectedCustomerGroupType = matchingOption.customerGroup_id;
                }
            }
        },
        mounted() {
            this.getCustomerTypes();
        },
        watch: {
            modelValue: {
                handler(newValue) {
                    if (newValue && newValue.customerGroupId !== this.selectedCustomerGroupType) {
                        this.selectedCustomerGroupType = newValue.customerGroupId;
                    }
                },
                deep: true,
            }
        }
    };
</script>
<style scoped>
   
</style>
