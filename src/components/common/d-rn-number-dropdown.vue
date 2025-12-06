<template>
    <div class="row align-items-center pt-2">
        <div class="col-4">
            <label class="form-label">RN<span class="required" v-if="required">*</span> :</label>
        </div>
        <div class="col-8 d-flex align-items-center">
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
            <button class="btn btn-add m-2" @click="selectRnChoix" v-if="showActionRn && selectedRn">
                <vue-feather type="plus" class="cursor-pointer"></vue-feather>
            </button>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../config/http';
    import VueFeather from 'vue-feather';
    import Multiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';

    export default {
        components: {
            Multiselect,
            VueFeather
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
            },
            showActionRn: {
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
            async selectRnChoix() {
                if (!this.selectedRn) return;
                // ensure parent v-model (string RN) is updated with selected RN number
                this.$emit('update:modelValue', this.selectedRn ? this.selectedRn.rnNumber : null);
                const res = await axiosInstance.get(`/api/carpets/rn/${this.selectedRn.id}`);
                // Emit both camelCase and kebab-case events to support different listener styles
                this.$emit('dataOfRn', res);
                this.$emit('data-of-rn', res);
                // Also emit a simplified payload (parsed response body) for convenience
                const parsed = res?.data?.response || res?.data || null;
                this.$emit('rnData', parsed);
                this.$emit('rn-data', parsed);
            },
            handleSearch(searchQuery) {
                this.getRn(searchQuery);
            },
            async getRn() {
                try {
                    const res = await axiosInstance.get('api/carpets');
                    const data = res.data.response || res.data || [];
                    // Normalize RN property so multiselect label='rnNumber' always works
                    this.rns = (Array.isArray(data) ? data : Object.values(data)).map((item) => {
                        const rnVal = item.rn ?? item.carpetRnNumber ?? item.rnNumber ?? item.reference ?? item.name ?? '';
                        return {
                            ...item,
                            rnNumber: rnVal
                        };
                    });
                    if (this.modelValue) {
                        // modelValue may be an RN string or an id
                        const byRn = this.rns.find((r) => r.rnNumber === String(this.modelValue));
                        const byId = this.rns.find((r) => String(r.id) === String(this.modelValue));
                        this.selectedRn = byRn || byId || null;
                    }
                } catch (e) {
                    console.error('Erreur get RN list.', e);
                }
            }
        },
        mounted() {
            this.getRn();
        },
        watch: {
            modelValue(newValue) {
                if (newValue) {
                    // If modelValue changes externally, try to sync selectedRn
                    const byRn = this.rns.find((r) => r.rnNumber === String(newValue));
                    const byId = this.rns.find((r) => String(r.id) === String(newValue));
                    this.selectedRn = byRn || byId || null;
                } else {
                    this.selectedRn = null;
                }
            }
        }
    };
</script>
