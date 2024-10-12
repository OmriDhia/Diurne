<template>
    <div class="row align-items-center pt-2">
        <div class="col-4">
            <label class="form-label">
                <template v-if="isPrescripteur">
                    Prescripteur
                </template>
                <template v-else>
                    Client
                </template>
                <span class="required" v-if="required">*</span> :
            </label>
        </div>
        <div :class="{'col-8': !showCustomer || !customerId,'col-7': showCustomer &&  customerId}">
            <multiselect
                :class="{ 'is-invalid': error }"
                v-model="customerId"
                :options="customers"
                :multiple="multiple"
                :placeholder="isPrescripteur ? 'Prescripteur' : 'Client'"
                track-by="id"
                label="customer"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @tag="addTag"
                @update:model-value="handleChange($event)"
                @search-change="handleSearch"
            >
                <template v-slot:afterList>
                    <div class="row justify-content-between align-items-center p-1">
                        <div class="col-6 text-start">
                            <a href="#" @click="prevPage" class="w-100 font-size-0-9" v-if="currentPage > 1">« précédent</a>
                        </div>
                        <div class="col-6 text-end">
                            <a href="#" @click="nextPage" class="w-100 font-size-0-9"  v-if="currentPage < totalPages">suivant »</a> 
                        </div>
                    </div>
                </template>
            </multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t("Le champ client est obligatoire.") }}</div>
        </div>
        <div class="col-1 ps-0" v-if="showCustomer &&  customerId">
            <router-link alt="Voir contact" :to="'/contacts/manage/' + customerId.id">
                <vue-feather type="eye"  stroke-width="1" class="cursor-pointer"></vue-feather>
            </router-link>
        </div>
    </div>
</template>

<script>
    import VueFeather from 'vue-feather';
    import axiosInstance from '../../config/http';
    import Multiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';
    import contactService from "../../Services/contact-service";

    export default {
        components: {
            Multiselect,
            VueFeather
        },
        props: {
            modelValue: {
                type: [Number, Array, null],
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
            multiple: {
                type: Boolean,
                default: false
            },
            isPrescripteur: {
                type: Boolean,
                default: false
            },
            showCustomer: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                customerId: null,
                customers: [],
                currentPage: 1,
                itemsPerPage: 100, // Adjust as needed
                totalCustomers: 0, // Total number of customers from the server
            };
        },
        computed: {
            totalPages() {
                return Math.ceil(this.totalCustomers / this.itemsPerPage);
            }
        },
        methods: {
            handleChange(value) {
                if (this.multiple) {
                    this.$emit('update:modelValue', value);
                } else {
                    this.$emit('update:modelValue', value ? value.id : null);
                }
            },
            addTag(newTag){
                this.users.push(newTag);
                this.userId.push(newTag);
            },
            handleSearch(searchQuery) {
                this.getCustomers(searchQuery);
            },
            async getCustomers(customerName = "") {
                try {
                    let url = `/api/customers?page=${this.currentPage}&itemsPerPage=${this.itemsPerPage}&orderBy=customer&orderWay=asc`;

                    if (customerName) {
                        url += `&filter[customerName]=${customerName}`;
                    }
                    
                    url += `&filter[hasOnlyOneContact]=true`;

                    const res = await axiosInstance.get(url);
                    this.customers = res.data.response.customers;
                    this.totalCustomers = res.data.response.count; // Make sure this matches your API

                    this.matchCustomerWithModel();
                } catch (e) {
                    console.error('Erreur lors de la récupération de la liste des clients.', e);
                }
            },
            async matchCustomerWithModel() {
                if (this.modelValue) {
                    if (this.multiple) {
                        const missingCustomers = this.modelValue.filter(id => !this.customers.some(cust => cust.id === id));
                        if (missingCustomers.length > 0) {
                            for (const missingId of missingCustomers) {
                                const missingCustomer = await contactService.getCustomerById(missingId);
                                this.customers.push({ id: missingId, customer: missingCustomer.customerName });
                            }
                        }
                        this.customerId = this.customers.filter(cust => this.modelValue.includes(cust.id));
                    } else {
                        const selectedCustomer = this.customers.find(cust => cust.id === this.modelValue);
                        if (!selectedCustomer) {
                            const missingCustomer = await contactService.getCustomerById(this.modelValue);
                            this.customers.push({ id: this.modelValue, customer: missingCustomer.customerName });
                        }
                        this.customerId = this.customers.find(cust => cust.id === this.modelValue);
                    }
                }
            },
            nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                    this.getCustomers();
                }
            },
            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.getCustomers();
                }
            }
        },
        mounted() {
            this.getCustomers();
        },
        watch: {
            modelValue: {
                handler() {
                    this.matchCustomerWithModel();
                },
                immediate: true
            }
        }
    };
</script>
