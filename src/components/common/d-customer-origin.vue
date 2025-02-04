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
                v-model="selectedOriginId"
                @change="updateOriginContact"
            >
                <option value="0" disabled>Type d'origine</option>
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
            selectedOriginId: 0, // This will bind with the dropdown
        };
    },
    // computed: {
    //     selectedOriginId: {
    //         get() {
    //             return this.modelValue.contact_origin_id || 0; // Default to 0 if not set
    //         },
    //         set(newId) {
    //             const selectedOption = this.contactOriginTypes.find(opt => opt.id === newId);
    //             if (selectedOption) {
    //                 this.$emit('update:modelValue', {
    //                     ...this.modelValue,
    //                     contact_origin_label: selectedOption.label,
    //                     contact_origin_id: newId
    //                 });
    //             }
    //         }
    //     }
    // },
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
                this.$emit('update:modelValue', {
                    ...this.modelValue,
                    contact_origin_label: selectedOption.label,
                    contact_origin_id: selectedOption.id
                });
            }
        },
        setContactOrigin() {
            const { contact_origin_label } = this.modelValue;
            console.log ( "testtttt" , contact_origin_label);
            const matchingOption = this.contactOriginTypes.find(
                opt => opt.label === contact_origin_label
            );
            
            if (matchingOption) {
                this.selectedOriginId = matchingOption.id;
            }
        }
    },
    watch: {
        // Watch for changes in the parent-provided modelValue prop
        modelValue: {
            handler(newValue) {
                this.setContactOrigin(); // Recalculate selected origin when modelValue changes
            },
            deep: true,
        },
        contactOriginTypes(newContactOriginTypes) {
            this.setContactOrigin(); // Re-run selection logic when the dropdown options are updated
        }
    },
    mounted() {
        this.getcontactOriginTypes();
    },
};
</script>
