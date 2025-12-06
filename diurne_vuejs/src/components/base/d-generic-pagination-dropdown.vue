<template>
    <div class="row align-items-center pt-2">
        <div class="col-4">
            <label class="form-label">
                <slot name="label">{{ label }}</slot>
                <span class="required" v-if="required">*</span> :
            </label>
        </div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error }"
                v-model="selectedItems"
                :options="items"
                :multiple="multiple"
                :placeholder="placeholder"
                track-by="trackBy"
                label="label"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @input="handleChange"
                @search-change="handleSearch"
            >
                <template v-slot:afterList>
                    <!-- Pagination Controls -->
                    <div class="pagination-controls">
                        <div class="btn-group" role="group" aria-label="Pagination buttons">
                            <button type="button" @click="prevPage" class="btn btn-outline-light w-50 font-size-0-6" :disabled="currentPage === 1">« précédent</button>
                            <button type="button" @click="nextPage" class="btn btn-outline-light w-50 font-size-0-6" :disabled="currentPage === totalPages">suivant »</button>
                        </div>
                    </div>
                </template>
            </multiselect>
            <div v-if="error" class="invalid-feedback">{{ error }}</div>
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
            label: {
                type: String,
                default: 'Select Item'
            },
            apiEndpoint: {
                type: String,
                required: true // The API endpoint to fetch items
            },
            modelValue: {
                type: [Number, Array, null],
                required: true
            },
            trackBy: {
                type: String,
                default: 'id' // Field to track uniqueness in options
            },
            labelField: {
                type: String,
                default: 'name' // Field to display as label in the list
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
            placeholder: {
                type: String,
                default: 'Select an option'
            },
            itemsPerPage: {
                type: Number,
                default: 100
            }
        },
        data() {
            return {
                selectedItems: null,
                items: [],
                currentPage: 1,
                totalItems: 0, // Total number of items returned by the server
            };
        },
        computed: {
            totalPages() {
                return Math.ceil(this.totalItems / this.itemsPerPage);
            }
        },
        methods: {
            handleChange(value) {
                if (this.multiple) {
                    this.$emit('update:modelValue', value);
                } else {
                    this.$emit('update:modelValue', value ? value[this.trackBy] : null);
                }
            },
            handleSearch(searchQuery) {
                this.getItems(searchQuery);
            },
            async getItems(searchQuery = "") {
                try {
                    let url = `${this.apiEndpoint}?page=${this.currentPage}&itemsPerPage=${this.itemsPerPage}`;

                    if (searchQuery) {
                        url += `&filter[${this.labelField}]=${searchQuery}`;
                    }

                    const res = await axiosInstance.get(url);
                    this.items = res.data.response.items;
                    this.totalItems = res.data.response.totalItems; // Make sure this matches your API

                    this.matchModelWithItems();
                } catch (e) {
                    console.error('Error fetching the list of items.', e);
                }
            },
            async matchModelWithItems() {
                if (this.modelValue) {
                    if (this.multiple) {
                        const missingItems = this.modelValue.filter(id => !this.items.some(item => item[this.trackBy] === id));
                        if (missingItems.length > 0) {
                            for (const missingId of missingItems) {
                                const missingItem = await this.fetchItemById(missingId);
                                this.items.push({ [this.trackBy]: missingId, [this.labelField]: missingItem[this.labelField] });
                            }
                        }
                        this.selectedItems = this.items.filter(item => this.modelValue.includes(item[this.trackBy]));
                    } else {
                        const selectedItem = this.items.find(item => item[this.trackBy] === this.modelValue);
                        if (!selectedItem) {
                            const missingItem = await this.fetchItemById(this.modelValue);
                            this.items.push({ [this.trackBy]: this.modelValue, [this.labelField]: missingItem[this.labelField] });
                        }
                        this.selectedItems = this.items.find(item => item[this.trackBy] === this.modelValue);
                    }
                }
            },
            async fetchItemById(id) {
                // Replace with the actual API call to fetch a single item by its ID
                const res = await axiosInstance.get(`${this.apiEndpoint}/${id}`);
                return res.data;
            },
            nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                    this.getItems();
                }
            },
            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.getItems();
                }
            }
        },
        mounted() {
            this.getItems();
        },
        watch: {
            modelValue: {
                handler() {
                    this.matchModelWithItems();
                },
                immediate: true
            }
        }
    };
</script>
