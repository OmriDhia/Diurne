<template>
    <div class="row align-items-center pt-2">
        <div class="col-4">
            <label class="form-label">RN<span class="required" v-if="required">*</span> :</label>
        </div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error }"
                v-model="selectedRn"
                :options="rns"
                placeholder="RN"
                track-by="rnNumber"
                label="rnNumber"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
                @update:model-value="handleChange"
                @search-change="handleSearch"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t('Le champ RN est obligatoire.') }}</div>
        </div>
    </div>
</template>

<script>
import axiosInstance from '../../config/http';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';

export default {
    components: {
        Multiselect
    },
    props: {
        modelValue: {
            type: [String, Number, null],
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
        }
    },
    data() {
        return {
            selectedRn: null,
            rns: []
        };
    },
    methods: {
        handleChange(value) {
            this.selectedRn = value;
            this.$emit('update:modelValue', value ? value.rnNumber : null);
        },
        handleSearch(searchQuery) {
            this.getRn(searchQuery);
        },
        async getRn(rnNumber) {
            if (!rnNumber) {
                this.rns = [];
                return;
            }
            try {
                const res = await axiosInstance.get(`/api/carpets/rn/${rnNumber}`);
                const data = res.data.response || res.data;
                this.rns = Array.isArray(data) ? data : [data];
                if (this.modelValue) {
                    this.selectedRn = this.rns.find(r => r.rnNumber === this.modelValue) || null;
                }
            } catch (e) {
                console.error('Erreur get RN list.', e);
            }
        }
    },
    mounted() {
        if (this.modelValue) {
            this.getRn(this.modelValue);
        }
    },
    watch: {
        modelValue(newValue) {
            if (newValue) {
                this.getRn(newValue);
            } else {
                this.selectedRn = null;
            }
        }
    }
};
</script>

