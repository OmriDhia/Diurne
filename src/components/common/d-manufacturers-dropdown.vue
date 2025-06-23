<template>
    <div class="row align-items-center pt-2">
        <div class="col-4">
            <label class="form-label">Fabricant<span class="required" v-if="required">*</span> :</label>
        </div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                :model-value="selectedContremarque"
                :options="contremarques"
                placeholder="Contremarque"
                track-by="contremarque_id"
                label="designation"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                :disabled="disabled"
                @update:model-value="handleChange($event)"
                @search-change="handleSearch($event)"
            >
                <template v-slot:afterList>
                    <div class="row justify-content-between align-items-center p-1">
                        <div class="col-6 text-start">
                            <a href="#" @click.prevent="prevPage" class="w-100 font-size-0-9" v-if="currentPage > 1">« précédent</a>
                        </div>
                        <div class="col-6 text-end">
                            <a href="#" @click.prevent="nextPage" class="w-100 font-size-0-9"  v-if="currentPage < totalPages">suivant »</a>
                        </div>
                    </div>
                </template>
            </multiselect>
            
            <div v-if="error" class="invalid-feedback">{{ $t("Le champ fabricant est abligatoire.") }}</div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../config/http';
    import Multiselect from 'vue-multiselect'
    import 'vue-multiselect/dist/vue-multiselect.css';
    import store from "../../store/index";
    import contremarqueService from "../../Services/contremarque-service.js";
    import VueFeather from 'vue-feather';
    import workshopService from "@/Services/workshop-service.js";

    export default {
        components:{
            VueFeather,
            Multiselect
        },
        props: {
            modelValue: {
                type: [Number, null],
                required: true
            },
            customerId: {
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
            },
            disabled:{
                type: Boolean,
                default: false
            }

        },
        data() {
            return {
                selectedContremarque: null,
                contremarques: [],
                currentPage: 1,
                itemsPerPage: 100, 
                totalContrmarques: 0,
            };
        },
        computed: {
            totalPages() {
                return Math.ceil(this.totalContrmarques / this.itemsPerPage);
            },contremarqueId() {
                return this.selectedContremarque?.contremarque_id || '';
            }
        },
        methods: {
            handleChange(value) {
                this.selectedContremarque = value;
                this.$emit('update:modelValue', value ? value.contremarque_id : null);

            },
            handleSearch(searchQuery){
                this.getContremarques(searchQuery);
            },
            async getContremarques (designation = ""){
                try{
                    const data = await workshopService.getManufacturers({page: this.currentPage, itemsPerPage: this.itemsPerPage});
                    const list = data.response?.data || data.data || [];
                    manufacturers.value = list.map((m: any) => ({value: m.id, label: m.name}));
                    let url = `/api/contremarques?page=${this.currentPage}&limit=${this.itemsPerPage}&order=designation&orderWay=asc`;
                    let localString = "contremarqueList";
                    if(this.customerId){
                        url += '&customerId=' + this.customerId;
                        localString += this.customerId;
                    }
                    if(designation){
                        url += '&designation=' + designation;
                        localString += designation;
                    }
                    
                    const res = await axiosInstance.get(url);
                    this.contremarques = list.map((m) => ({value: m.id, label: m.name}));;
                    this.totalContrmarques = res.data.count;
                    await this.matchContremarqueWithModel()
                }catch(e){
                    console.error(e);
                    console.log('Erreur get contremarques list.')
                }
            },
            async matchContremarqueWithModel() {
                if (this.modelValue) {
                    const selectedContremarque = this.contremarques.find(cust => cust.id === this.modelValue);
                    if (!selectedContremarque) {
                        const missingContremarque = await contremarqueService.getContremarqueById(this.modelValue);
                        this.contremarques.push(missingContremarque);
                        this.selectedContremarque = missingContremarque
                    }
                }
            },
            async nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                    await this.getContremarques();
                }
            },
            async prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    await this.getContremarques();
                }
            }
        },
        mounted() {
            this.getContremarques();
        },
        watch: {
            modelValue(newValue) {
                this.selectedContremarque = this.contremarques.find( ad => ad.contremarque_id === newValue ) || null;
            },
            customerId(){
                this.getContremarques();
            }
        }
    };
</script>
