<template>
    <d-base-page :loading="loading">
        <template v-slot:title>
            <d-page-title title="Contremarque"></d-page-title>
        </template>
        <template v-slot:header>
          <d-panel>
              <template v-slot:panel-header>
                  <div class="row p-2 align-items-center">
                      <div class="col-md-4 col-sm-12">
                          <d-input label="Contremarque" :required="true" v-model="data.designation" :error="error.designation"></d-input>
                      </div>
                      <div class="col-md-4 col-sm-12">
                          <d-input label="Lieu distination" v-model="data.destination_location"></d-input>
                      </div>
                      <div class="col-md-4 col-sm-12">
                          <d-input type="date" label="Date cible" :required="false" v-model="data.target_date" ></d-input>
                      </div>
                      <!-- :error="error.target_date" -->
                  </div>
              </template>
          </d-panel>
        </template>
        <template v-slot:body>
            <div class="row mt-3 mb-3 pe-0">
                <div class="col-md-6 col-sm-12 pe-sm-0">
                    <d-panel>
                        <template v-slot:panel-header>
                            <d-panel-title title="Client"></d-panel-title>
                        </template>
                        <template v-slot:panel-body>
                            <div class="row pe-2 ps-0">
                                <d-customer-dropdown :showCustomer="true" :required="true" v-model="selectedCustomer" :error="error.customer_id"></d-customer-dropdown>
                            </div>
                            <div class="row pe-2 ps-0" v-if="currentCustomer.contactsData">
                                <d-base-dropdown name="Contact" label="lastname" trackBy="contact_id" :datas="currentCustomer.contactsData" v-model="contact"></d-base-dropdown>
                            </div>
                            <div class="row pe-2 ps-0" v-if="currentCustomer.contactsData">
                                <d-discount v-model="tarifId.discount_rule_id"  :error="error.customerDiscount_id"></d-discount>
                            </div>
                            <div class="row justify-content-center mt-5" v-if="contremarque_id">
                                <div class="col-auto d-flex flex-column pe-3">
                                    <button class="btn btn-custom ps-5 pe-5 text-uppercase" @click="goToDIProjet"  :disabled="disableButtonActions">Voir les di projets</button>
                                    <button class="btn btn-custom ps-5 pe-5 text-uppercase mt-2" @click="goToContremarques()"  :disabled="disableButtonActions">Voir les devis</button>
                                    <button class="btn btn-custom ps-4 pe-4 text-uppercase mt-2" :disabled="disableButtonActions">Voir les commandes</button>
                                </div>
                                <div class="col-auto d-flex flex-column">
                                    <button class="btn btn-outline-custom" @click="goToListSUiviDI()" :disabled="disableButtonActions">
                                        Suivi DI projets
                                        <vue-feather type="arrow-right" size="14"></vue-feather>
                                    </button>
                                    <button class="btn btn-outline-custom mt-2" @click="goToCreateDevis()"  :disabled="disableButtonActions">
                                        Créer Un Devis
                                        <vue-feather type="arrow-right" size="14"></vue-feather>
                                    </button>
                                </div>
                            </div>
                            <div class="row align-content-end justify-content-end p-2 pe-3">
                                <!--div class="col-auto p-1">
                                    <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle" @click="saveContremarque">
                                        <vue-feather type="save" size="14"></vue-feather>
                                    </button>
                                </div-->
                                <div class="col-auto p-1 pe-4" v-if="data.customer_id">
                                    <d-delete :api="''"></d-delete>
                                </div>
                            </div>
                        </template>
                    </d-panel>
                </div>
                <div class="col-md-6 col-sm-12 p-0 ps-sm-2 mt-md-1 mt-sm-3 mt-xl-0"  v-if="currentCustomer.customer_id">
                    <d-panel>
                        <template v-slot:panel-header>
                            <d-panel-title title="Prescripteur"></d-panel-title>
                        </template>
                        <template v-slot:panel-body>
                            <div class="row pe-2 ps-0">
                                <d-customer-dropdown :isPrescripteur="true" v-model="prescriber" :error="error.prescriber_id"></d-customer-dropdown>
                            </div>
                            <div class="row pe-2 ps-0 align-items-center">
                                <div class="col-md-6">
                                    <d-input label="Commission (%)" type="Number" v-model="data.commission" :error="null"></d-input> 
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio">
                                        <input type="checkbox" class="custom-control-input" id="warnig-add-yes" v-model="data.commission_on_deposit" name="warningAdd" value="true"/>
                                        <label class="custom-control-label text-black" for="warnig-add-yes"> {{ $t('Commission sur acompte') }} </label>
                                    </div>
                                </div>
                            </div>
                            <d-panel-title title="Commercial" className="ps-2"></d-panel-title>
                            <div class="row pe-2 ps-0">
                                <d-commertial-histories :commertialData="currentCustomer.contactCommercialHistoriesData"></d-commertial-histories>
                            </div>
                        </template>
                    </d-panel>
                </div>
            </div>
            <div class="row mt-3 pe-0">
                <div class="col-md-6 col-sm-12 ps-sm-2 pe-sm-0"  v-if="contremarque_id">
                    <d-panel>
                        <template v-slot:panel-header>
                            <d-panel-title title="Liste des emplacements de la contremarque"></d-panel-title>
                        </template>
                        <template v-slot:panel-body>
                            <d-locations @updateLocation="disableButton" :locationOptions="locationOptions" :contremarqueId="contremarque.contremarque_id"> </d-locations>
                        </template>
                    </d-panel>
                </div>
                <div class="col-md-6 col-sm-12 ps-sm-2 pe-sm-0"  v-if="contremarque_id">
                    <d-panel>
                        <template v-slot:panel-header>
                            <d-panel-title title="évènement"></d-panel-title>
                        </template>
                        <template v-slot:panel-body>
                            <div class="row pe-2 ps-0">
                                <d-event-histories :customerId="currentCustomer.customer_id" :contremarqueId="contremarque.contremarque_id"></d-event-histories>
                            </div>
                        </template>
                    </d-panel>
                </div>
            </div>
        </template>
        <template v-slot:footer>
            <div class="row p-2 justify-content-between">
                <div class="col-auto">
                    <button class="btn btn-custom pe-5 ps-5" @click="goToContremarqueList">Retour à la liste</button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-custom pe-5 ps-5" @click="saveContremarque">Enregistrer</button>
                </div>
            </div>
        </template>
    </d-base-page>
</template>

<script setup>
    import VueFeather from 'vue-feather';
    import { useRoute, useRouter } from 'vue-router';
    import {ref, onMounted, watch} from 'vue';
    import {useMeta} from '/src/composables/use-meta';
    import { Helper, formatErrorViolations } from "../../../composables/global-methods";
    import axiosInstance from "../../../config/http";
    import contremarqueService from "../../../Services/contremarque-service"
    import dPrescripteurDropdown from "../../../components/common/d-prescripteur-dropdown.vue"
    import dBaseDropdown from "../../../components/base/d-base-dropdown.vue";
    import dDiscount from "../../../components/common/d-discount.vue";
    import dBasePage from "../../../components/base/d-base-page.vue"
    import dInput from "../../../components/base/d-input.vue"
    import dPageTitle from "../../../components/common/d-page-title.vue"
    import dPanelTitle from "../../../components/common/d-panel-title.vue";
    import dPanel from "../../../components/common/d-panel.vue";
    import dCommertialHistories from "../../../components/contacts/_partial/d-commertial-histories.vue"
    import dCustomerDropdown from "../../../components/common/d-customer-dropdown.vue";
    import dEventHistories from "../../../components/contacts/_partial/d-event-histories.vue";
    import dLocations from "../../../components/projet/contremarques/d-locations.vue";
    
    useMeta({ title: 'Gestion Contremarque' });

    const route = useRoute();
    const router = useRouter();
    const contremarque_id = route.params.id;
    const selectedCustomer = ref(0);
    const selectedContact = ref({});
    const contremarque = ref({});
    const locationOptions = ref({});
    const tarifId = ref(0);
    const contact = ref({});
    const prescriber = ref(0);
    const disableButtonActions = ref(true);
    const error = ref({});
    const loading = ref(false);
    
    const data = ref({
        project_number: "",
        designation: "",
        destination_location: "",
        target_date: "",
        customer_id: 0,
        customerDiscount_id: 0,
        prescriber_id: 0,
        commission: 0,
        commission_on_deposit: false
    });
    const currentCustomer = ref({});
    
    watch(selectedCustomer, (customerId) => {
        getCustomer(customerId)
    });
    
    const getCustomer = async (customer_id) => {
        try{
            if(customer_id){
                currentCustomer.value =  await contremarqueService.getCustomerById(customer_id);
                tarifId.value = currentCustomer.value.discountRule;
                contact.value = currentCustomer.value.contactsData[0];
            }
        }catch(e){
            const msg = "Un client d'id " + customer_id + " n'existe pas";
            window.showMessage(msg,'error');
        }finally {
            loading.value = false;
        }
    };
    const disableButton = async (locations) => {
        console.log("Locations count: ",locations)
        disableButtonActions.value = locations === 0;
    };

    const saveContremarque = async () => {
        try{
            data.value.customer_id = parseInt(selectedCustomer.value);
            data.value.prescriber_id = (prescriber.value) ? prescriber.value: 0;
            data.value.customerDiscount_id = (tarifId.value.discount_rule_id) ? tarifId.value.discount_rule_id : 0;
            data.value.commission = parseFloat(data.value.commission);
            error.value = {};
            if(contremarque_id){
                const res = await axiosInstance.put("/api/updateContremarque/" + contremarque_id,data.value);
                window.showMessage("Mise a jour avec succées.")
                setTimeout(()=>{
                    goToContremarqueList();
                }, 2000);
            }else{
                const respn = await axiosInstance.post("/api/createContremarque",data.value);
                window.showMessage("Ajout avec succées.");
                const ctm = respn.data.response;
                setTimeout(()=>{
                    goToContremarqueEdit(ctm.contremarque_id);
                }, 2000);
            }
            
        }catch(e){
            if(e.response.data.violations){
                error.value = formatErrorViolations(e.response.data.violations)
            }
            window.showMessage(e.message,'error')
        }
    };

    const getContremarque = async () => {
        try{
            if(contremarque_id){
                loading.value = true;
                contremarque.value = await contremarqueService.getContremarqueById(contremarque_id);
                selectedCustomer.value = contremarque.value.customer.customer_id;
                prescriber.value = contremarque.value.prescriber.customer_id;
                locationOptions.value = {
                    min_price: contremarque.value.min_price ?? 0,
                    max_price: contremarque.value.max_price ?? 0,
                    last_quote_date: contremarque.value.last_quote_date ?? ''
                };
                data.value = {
                    project_number: contremarque.value.projectNumber,
                    designation: contremarque.value.designation,
                    destination_location: contremarque.value.destination_location,
                    target_date: Helper.FormatDate(contremarque.value.target_date?.date,"YYYY-MM-DD"),
                    customer_id: contremarque.value.customer.customer_id,
                    customerDiscount_id: contremarque.value.discount_rule_id,
                    prescriber_id: prescriber.value,
                    commission: contremarque.value.commission,
                    commission_on_deposit: contremarque.value.commission_on_deposit
                };
            }
        }catch(e){
            console.log(e);
            const msg = "Une contremarque d'id " + contremarque_id + " n'existe pas";
            window.showMessage(msg,'error');
        }finally {
            //loading.value = false;
        }
    };
    onMounted(() => {
       if(contremarque_id){
           getContremarque();
       }
       if(!contremarque_id && route.query.customer_id){
           selectedCustomer.value = route.query.customer_id; 
       }
    });

    const goToContremarqueList = () => {
        router.push({name: 'projectsList'})
    };
    const goToContremarqueEdit = (id) => {
        router.push({name: 'projectsListManage', params:{ id: id }})
    };
    const goToDIProjet = () => {
        router.push({name: 'projectDIS', params:{ id: contremarque_id }})
    };
    const goToContremarques = () => {
        router.push({ name: 'devisList', query: { contremarqueId: contremarque_id } });
    };
    const goToListSUiviDI = () => {
        router.push({name: 'di_list' , query: { contremarqueId: contremarque_id } });
    };
    
    const goToCreateDevis = () => {
        router.push({ name: 'devisManage', query: { contremarqueId: contremarque_id } });
    };
</script>
<style scoped>
    .row {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display:         flex;
    }
    .row > [class*='col-'] {
        display: flex;
        flex-direction: column;
    }
</style>
