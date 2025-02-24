<template>
    <div class="row align-items-center">
        <div class="col-4"><label for="gender" class="form-label">Civilité<span class="required" v-if="required">*</span>:</label></div>
        <div class="col-8">
            <select id="gender"  :class="{ 'is-invalid': error, 'form-select': true }" :value="gender" @input="handleChange($event.target.value)">
                <option value="-1" selected="selected">Selectionnez une civilité</option>
                <option v-for="(civ, key) in genders" :key="key" :value="civ.gender_id">{{ civ.name }}</option>
            </select>
            <div v-if="error" class="invalid-feedback">{{ $t('La civilité est obligatoire.') }}</div>
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
            required: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                gender: this.modelValue,
                genders: []
            };
        },
        methods: {
            handleChange(newValue) {
                this.gender = parseInt(newValue);
                this.$emit('update:modelValue', parseInt(newValue));
            },
            async getGenders() {
                try {
                    const res = await axiosInstance.get('/api/genders');
                    this.genders = res.data.response.genders;
                } catch (error) {
                    console.error('Failed to fetch gender:', error);
                }
            }
        },
        mounted() {
            this.getGenders();
        },
        watch: {
            modelValue(newValue) {
                this.gender = parseInt(newValue);
            }
        }
    };
</script>
<style scoped>
    .invalid-feedback{
        display: flex !important;
        font-size: 10px;
    }
</style>
