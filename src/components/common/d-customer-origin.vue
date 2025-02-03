<template>
    <div class="row align-items-center pt-2">
        <div class="col-4">
            <label for="droit" class="form-label">
                Origine du Contact<span class="required" v-if="required">*</span>:
            </label>
        </div>
        <div class="col-8">
            <select 
                id="droit"  
                :class="{ 'is-invalid': error, 'form-select': true }" 
                :value="localOriginContact.OriginContactId" 
                @change="updateOriginContact"
            >
                <option value="0" selected disabled>Type d'origine</option>
                <option v-for="prof in contactOriginTypes" :key="prof.id" :value="prof.id">
                    {{ prof.label }}
                </option>
            </select>
            <div v-if="error" class="invalid-feedback">{{ error }}</div>
        </div>
    </div>
</template>

<script>
import { ref, defineProps, defineEmits, watch, onMounted } from 'vue';
import axiosInstance from '../../config/http';

export default {
    props: {
        modelValue: {
            type: Object,
            required: true
        },
        error: {
            type: String,
            default: ''
        },
        required: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            contactOriginTypes: [],
            localOriginContact: { ...this.modelValue }
        };
    },
    methods: {
        async getcontactOriginTypes() {
            try {
                const res = await axiosInstance.get('/api/contact-origins');
                this.contactOriginTypes = res.data.response.contactOrigin;
            } catch (error) {
                console.error('Failed to fetch contact origins:', error);
            }
        },
        updateOriginContact(event) {
            const selectedOption = this.contactOriginTypes.find(opt => opt.id === parseInt(event.target.value));

            if (selectedOption) {
                const updatedValue = {
                    ...this.modelValue,
                    originContactLabel: selectedOption.label,
                    OriginContactId: selectedOption.id
                };

                // console.log("Emitting from Grandchild:", updatedValue); // ✅ Debugging

                this.$emit('update:modelValue', updatedValue);
            }
        }
    },
    watch: {
        modelValue: {
            handler(newValue, oldValue) {
                if (JSON.stringify(newValue) !== JSON.stringify(oldValue)) {
                    this.localOriginContact = { ...newValue };
                }
            },
            deep: true,
            immediate: true
        }
    },
    mounted() {
        this.getcontactOriginTypes();
    }
};
</script>
