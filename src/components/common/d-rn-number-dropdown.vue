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
            VueFeather,
        },
        props: {
            modelValue: {
                type: [String, Number, null],
                required: true,
            },
            error: {
                type: String,
                default: '',
            },
            required: {
                type: Boolean,
                default: false,
            },
            disabled: {
                type: Boolean,
                default: false,
            },
            showActionRn: {
                type: Boolean,
                default: false,
            },
        },
        data() {
            return {
                selectedRn: null,
                rns: [],
            };
        },
        methods: {
            handleChange(value) {
                this.selectedRn = value;
                this.$emit('update:modelValue', value ? value.rnNumber : null);
            },
            async selectRnChoix() {
                const res = await axiosInstance.get(`/api/carpets/rn/${this.selectedRn.id}`);
                this.$emit('dataOfRn', res);
            },
            handleSearch(searchQuery) {
                this.getRn(searchQuery);
            },
            async getRn() {
                // if (!rnNumber) {
                //     this.rns = [];
                //     return;
                // }
                try {
                    // const res = await axiosInstance.get(`/api/carpets/rn/${rnNumber}`);
                    const res = await axiosInstance.get('api/carpets');
                    const data = res.data.response || res.data;
                    this.rns = data;
                    // if (this.modelValue) {
                    //     this.selectedRn = this.rns.find((r) => r.rnNumber === this.modelValue) || null;
                    // }
                } catch (e) {
                    console.error('Erreur get RN list.', e);
                }
            },
        },
        mounted() {
            this.getRn();
        },
        watch: {
            modelValue(newValue) {
                if (newValue) {
                    return true;
                } else {
                    this.selectedRn = null;
                }
            },
        },
    };
</script>
