<template>
    <div class="row align-items-center pt-2">
        <div class="col-4"><label class="form-label">{{ label }}<span class="required" v-if="required">*</span>
            :</label></div>
        <div class="col-8">
            <multiselect
                :class="{ 'is-invalid': error}"
                :multiple="true"
                v-model="userId"
                :options="users"
                :placeholder="label"
                track-by="id"
                label="name"
                :searchable="true"
                selected-label=""
                select-label=""
                deselect-label=""
                @tag="addTag"
                @update:model-value="handleChange($event)"
                @search-change="handleSearch($event)"
            ></multiselect>
            <div v-if="error" class="invalid-feedback">{{ $t('Le champ client est abligatoire.') }}</div>
        </div>
    </div>
</template>

<script>
    import axiosInstance from '../../config/http';
    import Multiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.css';
    import store from '../../store/index';

    export default {
        components: {
            Multiselect
        },
        computed: {},
        props: {
            modelValue: {
                type: [Array, null],
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
            label: {
                type: String,
                default: 'Contact diurne'
            }
        },
        data() {
            return {
                userId: [],
                users: []
            };
        },
        methods: {
            handleChange(value) {
                this.$emit('update:modelValue', value.map(e => {
                    return parseInt(e.id);
                }));
            },
            addTag(newTag) {
                this.users.push(newTag);
                this.userId.push(newTag);
            },
            handleSearch(searchQuery) {
                const se = searchQuery.split(' ');
                this.getUsers(se[0], se[1]);
            },
            async getUsers(firstname = '', lastname = '') {
                try {
                    let url = 'api/users?page=1&itemPerPage=100';

                    if (firstname) {
                        url += '&filter[firstname]=' + firstname;
                    }

                    if (lastname) {
                        url += '&filter[lastname]=' + lastname;
                    }

                    url += '&filter[profiles]=Super admin,Commercial,Commercial manager,Designer,Designer manager,ADV,Assistant commercial&filter[isActive]=true';

                    const res = await axiosInstance.get(url);
                    this.users = res.data.response.users.map(e => {
                        return {
                            id: e.id,
                            name: e.firstname + ' ' + e.lastname
                        };
                    });

                    if (this.modelValue) {
                        this.userId = this.users.filter(f => this.modelValue.indexOf(f.id) > -1);
                    }
                } catch {
                    console.log('Erreur get users list.');
                }
            }
        },
        mounted() {
            this.getUsers();
        },
        watch: {
            modelValue(newValue) {
                if (newValue) {
                    this.userId = this.users.filter(f => newValue.indexOf(f.id) > -1);
                }
            }
        }
    };
</script>
