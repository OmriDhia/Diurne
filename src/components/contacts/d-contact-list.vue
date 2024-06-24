<template>
    <div class="panel br-6 p-2">
        <div class="row p-2">
            <div class="col-auto">
                <button class="btn btn-custom pe-5 ps-5" @click="goToNewContact">Nouveau contact</button> 
            </div>
        </div>
        <div class="row align-items-center pe-2 ps-2 mt-3">
            <div class="col-md-6 col-sm-12 col-lg-3">
                <d-input label="Nom" v-model="filter.lastname" ></d-input>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-3">
                <d-input label="Raison sociale" v-model="filter.rs" ></d-input>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-3">
                <d-input label="Ville" v-model="filter.city" ></d-input>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-3">
                <d-languages v-model="filter.mailingLanguageId" ></d-languages>
            </div>
        </div>
        <div class="row align-items-center pe-2 ps-2">
            <div class="col-md-6 col-sm-12 col-lg-3">
                <d-input label="Prénom" v-model="filter.firstname" ></d-input>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-3">
                <d-input label="CE TVA" v-model="filter.tva_ce" ></d-input>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-3">
                <d-input label="Code postal" v-model="filter.postCode" ></d-input>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-3">
                <d-contact-mailing v-model="filter.contactMailing"></d-contact-mailing>
            </div>
        </div>
        <div class="row align-items-center pe-2 ps-2">
            <div class="col-md-6 col-sm-12 col-lg-3">
                <d-input label="Commercial" v-model="filter.commercial" ></d-input>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-3">
                <d-input label="Site web" v-model="filter.webSite" ></d-input>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-3">
                <d-countries v-model="filter.country" ></d-countries>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-3">
                <div class="row justify-content-end">
                    <div class="col-auto p-0">
                        <button v-if="filterActive" class="btn btn-outline-secondary btn-reset" @click.prevent="doReset">
                            Reset filtre </button>
                    </div>
                    <div class="col-auto p-0 me-2">
                        <button class="btn btn-custom pe-3 ps-3" @click.prevent="doSearch">Recherche</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-center pe-2 ps-2">
            <div class="col-md-6 col-sm-12 col-lg-3">
                <d-input label="Prescripteur" v-model="filter.pres" ></d-input>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-3">
                <d-customer-type v-model="filter.customerTypeId"></d-customer-type>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-3">
                <div class="row align-items-center">
                <div class="col-3 text-black">
                    {{ $t('Adresse eronée')}}:
                </div>
                <div class="col-auto pe-1 ps-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="warnig-add-yes" v-model="filter.wrongAdd" name="warningAdd" value="true"/>
                        <label class="custom-control-label text-black" for="warnig-add-yes"> {{ $t('Oui') }} </label>
                    </div>
                </div>
                <div class="col-auto pe-1 ps-1">
                    <div class="radio-success custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="warnig-add-no" v-model="filter.wrongAdd" name="warningAdd" value="false"/>
                        <label class="custom-control-label text-black" for="warnig-add-no"> {{ $t('Non') }} </label>
                    </div>
                </div>
                <div class="col-auto pe-1 ps-1">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="warnig-add-all" v-model="filter.wrongAdd" name="warningAdd" value="all"/>
                        <label class="custom-control-label text-black" for="warnig-add-all"> {{ $t('Tous') }} </label>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-3">
                <div class="row justify-content-end">
                    <div class="col-auto">
                        <button class="btn btn-outline-custom">
                            éditer list emailing
                            <vue-feather type="file-plus" size="14"></vue-feather>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row align-items-center pe-2 ps-2">
            <div class="col-md-6 col-sm-12 p-3">
                <div class="row align-items-center">
                    <div class="col-auto pe-1 ps-1">
                        <div class="radio-success custom-control custom-radio">
                            <input type="checkbox" class="custom-control-input" id="hasOnlyOneContact" v-model="filter.hasOnlyOneContact"/>
                            <label class="custom-control-label text-black" for="hasOnlyOneContact"> {{ $t('Seul un contact') }} </label>
                        </div>
                    </div>
                    <div class="col-auto pe-1 ps-2">
                        <div class="custom-control custom-radio">
                            <input type="checkbox" class="custom-control-input" id="customer-active" v-model="filter.active"/>
                            <label class="custom-control-label text-black" for="customer-active"> {{ $t('Seul client actif') }} </label>
                        </div>
                    </div>
                    <div class="col-auto pe-1 ps-1">
                        <div class="custom-control custom-radio">
                            <input type="checkbox" class="custom-control-input" id="comercial-valid" v-model="filter.hasInvalidCommercial"/>
                            <label class="custom-control-label text-black" for="comercial-valid"> {{ $t('Commercial à valider') }} </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="row align-items-center">
                    <div class="col-3 text-black">
                        {{ $t('Adresse complète')}}:
                    </div>
                    <div class="col-auto pe-1 ps-2">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="finiched-add-yes" v-model="filter.validAdd" name="finichedAdd" value="true"/>
                            <label class="custom-control-label text-black" for="finiched-add-yes"> {{ $t('Oui') }} </label>
                        </div>
                    </div>
                    <div class="col-auto pe-1 ps-1">
                        <div class="radio-success custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="finiched-add-no" v-model="filter.validAdd" name="finichedgAdd" value="false"/>
                            <label class="custom-control-label text-black" for="finiched-add-no"> {{ $t('Non') }} </label>
                        </div>
                    </div>
                    <div class="col-auto pe-1 ps-1">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="finiched-add-all" v-model="filter.validAdd" name="finichedAdd" value="all"/>
                            <label class="custom-control-label text-black" for="finiched-add-all"> {{ $t('Tous') }} </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel br-6 p-2 mt-3">
        <div class="row mt-5 ms-2 mb-5">
            <div class="vue3-datatable w-100">
                <div class="mb-2 relative">
                    <div class="btn-group custom-dropdown mb-4 me-2 btn-group-lg">
                        <button class="btn btn-outline-custom p-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Cacher / Montrer Colonnes
                        </button>
                        <ul class="dropdown-menu p-2">
                            <li v-for="col in cols" :key="col.field">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" :checked="!col.hide" :id="col.field" @change="col.hide = !$event.target.checked" :name="col.field"/>
                                    <label class="custom-control-label text-black" :for="col.field"> {{ col.title }} </label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <vue3-datatable :rows="rows" :columns="cols" :loading="loading" :isServerMode="true"
                                :totalRows="total_rows" :page="params.current_page" :pageSize="params.pagesize"
                                :pageSizeOptions="[10, 25, 50, 75, 100]" noDataContent="Aucun contact trouvé."
                                paginationInfo="Affichage de {0} à {1} sur {2} entrées" :sortable="true"
                                @change="changeServer" class="advanced-table text-nowrap">
                    <template #customer="data">
                        <div class="d-flex justify-content-between">
                            <strong>{{ data.value.customer}}</strong>
                            <router-link :to="'/contacts/manage/' + data.value.id"  v-if="$hasPermission('update contact')">
                                <vue-feather type="search"  stroke-width="1" class="cursor-pointer"></vue-feather>
                            </router-link>

                        </div>
                    </template>
                    <template #commercial="data">
                        <div class="d-flex justify-content-between">
                            <strong>{{ data.value.commercial}}</strong>
                           <div v-if="data.value.status == 'Pending' ">
                               <button type="button" class="btn btn-icon p-0" v-if="data.value.loading">
                                   <vue-feather type="loader" animation="spin"></vue-feather>
                               </button>
                               <button type="button" 
                                       class="btn btn-icon p-0"
                                       data-bs-toggle="dropdown"
                                       aria-haspopup="true"
                                       aria-expanded="false" v-if="!data.value.loading" >
                                   <vue-feather type="clipboard"></vue-feather>
                               </button>
                               <ul class="dropdown-menu p-0" style="will-change: transform" v-if="!loadingAttribution">
                                   <li class="p-2 text-uppercase" style="background-color: green">
                                       <a href="javascript:void(0);" class="dropdown-item text-white" @click.prevent="doValidation('validation',data)">Valider</a>
                                   </li>
                                   <li class="p-2 text-uppercase" style="background-color: red">
                                       <a href="javascript:void(0);" class="dropdown-item text-white"  @click.prevent="doValidation('reject',data)">Annuler</a>
                                   </li>
                               </ul>
                           </div>
                        </div>
                    </template>
                    <template #has_completed_address="data">
                        <div title="test" class="t-dot" :class="data.value.has_completed_address === 'true' ? 'bg-success' :'bg-danger'"></div>
                    </template>
                    <template #has_wrong_address="data">
                        <div title="test" class="t-dot" :class="data.value.has_wrong_address === 'true' ? 'bg-success' :'bg-danger'"></div>
                    </template>
                </vue3-datatable>
            </div>
        </div>
    </div>
</template>

<script setup>
  import { ref , reactive, onMounted} from 'vue';
  import dInput from '../base/d-input.vue';
  import dCountries from "../common/d-countries.vue";
  import dCustomerType from "../common/d-customer-type.vue";
  import dContactMailing from "../common/d-contact-mailing.vue";
  import dLanguages from "../common/d-langages.vue";
  import VueFeather from 'vue-feather';
  import Vue3Datatable from '@bhplugin/vue3-datatable';
  import axiosInstance from '../../config/http';
  import { filterContact } from "../../composables/constants";

  const loading = ref(true);
  const loadingAttribution = ref(false);
  const total_rows = ref(0);

  const params = reactive({
      current_page: 1,
      pagesize: 50,
      orderBy: 'customer',
      orderWay: 'asc'
  });
  
  const filter = ref(filterContact);
  const rows = ref(null);
  const filterActive = ref(false);

  const cols = ref([
      { field: 'customer', title: 'Nom client' },
      { field: 'contact', title: 'Contact' },
      { field: 'commercial', title: 'Commercial' },
      { field: 'phone', title: 'Tél. fixe'},
      { field: 'mobile_phone', title: 'Tél. portable' },
      { field: 'email', title: 'Email', sort: false },
      { field: 'has_completed_address', title: 'Adr. Ok', sort: false  },
      { field: 'has_wrong_address', title: 'Adr. Err', sort: false },
  ]) || [];

  onMounted(() => {
      getCustomers();
  });
  const getCustomers = async () => {
      try {
          loading.value = true;
          let url_customers = `/api/customers?page=${params.current_page}&itemsPerPage=${params.pagesize}&orderBy=${params.orderBy}&orderWay=${params.orderWay}`;
          url_customers += getFilterParams();
          const response = await axiosInstance.get(url_customers);
          const data = await response.data.response;
          total_rows.value = data.count;
          rows.value = data.customers;
      } catch { }

      loading.value = false;
  };
  const changeServer = (data) => {
      params.current_page = data.current_page;
      params.pagesize = data.pagesize;
      params.orderBy = data.sort_column;
      params.orderWay = data.sort_direction;

      getCustomers();
  };
  const doSearch = () => {
      filterActive.value = true
      getCustomers();
  };
  const getFilterParams = () => {
      
      let param = "";
      if (filter.value.firstname) {
          param += "&filter[firstname]=" + filter.value.firstname
      }
      if (filter.value.lastname) {
          param += "&filter[lastname]=" + filter.value.lastname
      }
      if (filter.value.rs) {
          param += "&filter[socialReason]=" + filter.value.rs
      }
      if (filter.value.city) {
          param += "&filter[city]=" + filter.value.city
      }
      if (filter.value.postCode) {
          param += "&filter[zip_code]=" + filter.value.postCode
      }
      if (filter.value.country) {
          param += "&filter[country]=" + filter.value.country
      }
      if (filter.value.mailingLanguageId) {
          param += "&filter[mailingLanguageId]=" + filter.value.mailingLanguageId
      }
      if (filter.value.customerTypeId) {
          console.log(filter.value.customerTypeId)
          param += "&filter[customerGroupId]=" + filter.value.customerTypeId
      }
      if (filter.value.contactMailing) {
          param += "&filter[contactMailing]=" + filter.value.contactMailing
      }
      if (filter.value.wrongAdd && filter.value.wrongAdd !== 'all') {
          param += "&filter[hasWrongAddress]=" + filter.value.wrongAdd
      }
      if (filter.value.validAdd && filter.value.validAdd !== 'all') {
          param += "&filter[hasValidAddress]=" + filter.value.wrongAdd
      }
      if (filter.value.active && filter.value.active !== 'all') {
          param += "&filter[active]=" + filter.value.active
      }
      if (filter.value.hasInvalidCommercial) {
          param += "&filter[hasInvalidCommercial]=" + filter.value.hasInvalidCommercial
      }
      if (filter.value.tva_ce) {
          param += "&filter[tva_ce]=" + filter.value.tva_ce
      }
      if (filter.value.commercial) {
          param += "&filter[commercial]=" + filter.value.commercial
      }
      if (filter.value.webSite) {
          param += "&filter[website]=" + filter.value.webSite
      }
      if (filter.value.pres) {
          param += "&filter[prescripteur]=" + filter.value.pres
      }
      if (filter.value.hasOnlyOneContact) {
          param += "&filter[hasOnlyOneContact]=" + filter.value.hasOnlyOneContact
      }
      return param;
  };
  const doReset = () => {
      filterActive.value = false;
      filter.value = {
          lastname: null,
          postCode: null,
          rs: null,
          city: null,
          firstname: null,
          tva_ce: null,
          commercial: null,
          webSite: null,
          country: null,
          pres: null,
          customerTypeId: null,
          wrongAdd: null,
          validAdd: null,
          hasInvalidCommercial: null,
          active: null,
          hasOnlyOneContact: null,
          mailingLanguageId: null,
          contactMailing: null,
      };
      getCustomers();
  }
  const doValidation = async (action, attribution) => {
      try{
          attribution.value.loading = true;
          if(action === "validation"){
              const res = await axiosInstance.post('/api/CommercialAttributionValidation/' + attribution.value.attribution_id);
              window.showMessage("La demande d'attribution est bien validée.");
          }else{
              const res = await axiosInstance.post('/api/CommercialAttributionAnnulation/' + attribution.value.attribution_id);
              window.showMessage("La demande d'attribution est rejetée avec succès.");
          }
          // attribution.value.loading = false;
          getCustomers();
      }catch (e){
          
      }
  }
  const goToNewContact = () => {
      location.href = '/contacts/manage'
  }
</script>

<style>
    .advanced-table .progress-bar {
        width: 80%;
        min-width: 120px;
        height: 8px;
        background-color: #ebedf2;
        border-radius: 9999px;
        display: flex;
    }

    .advanced-table .progress-line {
        height: 8px;
        border-radius: 9999px;
    }
    
    .btn-reset{
        box-shadow: none !important;
        margin-right: 5px;
    }
    .dropdown-item:active, .dropdown-item:hover{
        background: none !important;
        
    }
</style>

