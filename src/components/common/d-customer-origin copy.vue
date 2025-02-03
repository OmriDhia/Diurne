<template>
    <div class="row align-items-center pt-2">
        <div class="col-4">
            <label for="droit" class="form-label">Origine du Contact<span class="required" v-if="required">*</span>:</label>
        </div>
        <div class="col-8">
            <select id="droit"  :class="{ 'is-invalid': error, 'form-select': true }" :value="type" @input="handleChange($event.target.value)">
                <option value="0" selected disabled data>Type de d'origine</option>
                <option v-for="(prof, key) in contactOriginTypes" :key="key" :value="prof.id">{{ prof.label }}</option>
            </select>
            <div v-if="error" class="invalid-feedback">{{ error }}</div>
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
                contactOriginTypes: []
            };
        },
        methods: {
            handleChange(newValue) {
                this.type = parseInt(newValue);
                this.$emit('update:modelValue', parseInt(newValue));
            },
            async getcontactOriginTypes() {
                try {
                    const res = await axiosInstance.get('/api/contact-origins');
                    this.contactOriginTypes = res.data.response.contactOrigin;
                } catch (error) {
                    console.error('Failed to fetch customer groups:', error);
                }
            }
        },
        mounted() {
            this.getcontactOriginTypes();
        },
        watch: {
            modelValue(newValue) {
                this.type = parseInt(newValue);
                console.log("origin type : " + newValue)
            }
        }
    };
</script>
<style scoped>
   
</style>
