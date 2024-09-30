<template>
    <div class="row align-items-center pt-2">
        <div class="col-12">
            <select id="droit"  placeholder="Contact mailing" :class="{ 'is-invalid': error, 'form-select': true }" :value="type" @input="handleChange($event.target.value)">
                <option value="" disabled selected>Contact mailing</option>
                <option v-for="(prof, key) in mailings" :key="key" :value="prof.id">{{ prof.name }}</option>
            </select>
            <div v-if="error" class="invalid-feedback">{{ $t('Le type de client est abligatoire.') }}</div>
        </div>
    </div>
</template>

<script>
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
        },
        data() {
            return {
                type: this.modelValue,
                customerTypes: [],
                mailings: [
                    {id: 1, name:"Contacts mailing"},
                    {id: 2, name:"Uniquement avec colligraphie"},
                    {id: 3, name:"Uniquement sans colligraphie"},
                    {id: 4, name:"Tous les contacts"},
                ]
            };
        },
        methods: {
            handleChange(newValue) {
                this.type = parseInt(newValue);
                this.$emit('update:modelValue', parseInt(newValue));
            },
        },
        watch: {
            modelValue(newValue) {
                this.type = parseInt(newValue);
            }
        }
    };
</script>
<style scoped>
   
</style>
