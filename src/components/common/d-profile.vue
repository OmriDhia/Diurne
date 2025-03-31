<template>
    <div class="row align-items-center">
        <div class="col-3">
            <label for="droit" class="form-label">Droit <span class="required" v-if="required">*</span>: </label>
        </div>
        <div class="col-9">
            <select id="droit" :class="{ 'is-invalid': error, 'form-select': true }" :value="profile" @input="handleChange($event.target.value)">
                <option v-for="(prof, key) in profiles" :key="key" :value="prof.profile_id">{{ prof.name }}</option>
            </select>
        </div>
        <div v-if="error" class="invalid-feedback">{{ $t('Le champs droit est obligatoire.') }}</div>
    </div>
</template>

<script>
import axiosInstance from '../../config/http';

export default {
    props: {
        modelValue: {
            type: [String, null],
            required: true
        },
        error: {
            type: String,
            default: '',
        },
        required: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            profile: this.modelValue,
            profiles: []
        };
    },
    methods: {
        handleChange(newValue) {
            this.profile = newValue;
            this.$emit('update:modelValue', newValue);
        },
        async getProfiles() {
            try {
                const res = await axiosInstance.get('/api/profiles');
                this.profiles = res.data.response.profiles;
            } catch (error) {
                console.error('Failed to fetch profiles:', error);
            }
        }
    },
    mounted() {
        this.getProfiles();
    },
    watch: {
        modelValue(newValue) {
            this.profile = newValue;
        }
    }
};
</script>
