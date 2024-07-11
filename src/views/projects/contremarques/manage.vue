<template>
    <d-base-page>
        <template v-slot:title>
            <d-page-title title="Contremarque"></d-page-title>
        </template>
        <template v-slot:header>
          <d-panel>
              <template v-slot:panel-header>
                  <div class="row p-2 align-items-center">
                      <div class="col-md-4 col-sm-12">
                          <d-input label="Contremarque" v-model="data.designation"></d-input>
                      </div>
                      <div class="col-md-4 col-sm-12">
                          <d-input label="Lieu distination"  v-model="data.destination_location"></d-input>
                      </div>
                      <div class="col-md-4 col-sm-12">
                          <d-input type="date" label="Date cible"  v-model="data.target_date"></d-input>
                      </div>
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
                                <d-customer-dropdown v-model="selectedCustomer"></d-customer-dropdown>
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
                              
                            </div>
                            <d-panel-title title="Commercial" className="ps-2"></d-panel-title>
                            <div class="row pe-2 ps-0">
                                <d-commertial-histories :commertialData="currentCustomer.contactCommercialHistoriesData"></d-commertial-histories>
                            </div>
                        </template>
                    </d-panel>
                </div>
            </div>
            <div class="row mt-3 mb-3 pe-0" v-if="currentCustomer.customer_id">
                <div class="col-md-6 col-sm-12 ps-sm-2 pe-sm-0">
                    <d-panel>
                        <template v-slot:panel-header>
                            <d-panel-title title="Liste des enplacements de la contremarque"></d-panel-title>
                        </template>
                        <template v-slot:panel-body>
                            <div class="row pe-2 ps-0">
                                <div class="col-auto">
                                    <button class="btn btn-custom pe-5 ps-5" @click="goToNewContremarque">Nouveau</button>
                                </div>
                            </div>
                        </template>
                    </d-panel>
                </div>
                <div class="col-md-6 col-sm-12 ps-sm-2 pe-sm-0">
                    <d-panel>
                        <template v-slot:panel-header>
                            <d-panel-title title="évènement"></d-panel-title>
                        </template>
                        <template v-slot:panel-body>
                            <div class="row pe-2 ps-0">
                                <d-event-histories :customerId="currentCustomer.customer_id"></d-event-histories>
                            </div>
                        </template>
                    </d-panel>
                </div>
            </div>
        </template>
        <template v-slot:footer>
            <div class="row p-2">
                <div class="col-auto">
                    <button class="btn btn-custom pe-5 ps-5" @click="goToContremarqueList">Retour à la liste</button>
                </div>
            </div>
        </template>
    </d-base-page>
</template>

<script setup>
    import {ref, onMounted, watch} from 'vue';
    import {useMeta} from '/src/composables/use-meta';
    import axiosInstance from "../../../config/http";
    import dBasePage from "../../../components/base/d-base-page.vue"
    import dInput from "../../../components/base/d-input.vue"
    import dPageTitle from "../../../components/common/d-page-title.vue"
    import dPanelTitle from "../../../components/common/d-panel-title.vue";
    import dPanel from "../../../components/common/d-panel.vue";
    import dCommertialHistories from "../../../components/contacts/_partial/d-commertial-histories.vue"
    import { useRoute } from 'vue-router';
    import dCustomerDropdown from "../../../components/common/d-customer-dropdown.vue";

    useMeta({ title: 'Contacts' });

    const route = useRoute();
    const customer_id = route.params.id;
    const selectedCustomer = ref({});
    
    const data = ref({
        project_number: "",
        designation: "",
        destination_location: "",
        target_date: "",
        customer_id: 0,
        customerDiscount_id: 0,
        prescriber_id: 0,
        commission: 0,
        commission_on_deposit: true
    })
    const currentCustomer = ref({});
    
    watch(selectedCustomer, (customer) => {
        getCustomer(customer.id)
    });
    
    const getCustomer = async (customer_id) => {
        try{
            if(customer_id){
                const res = await axiosInstance.get("api/customer/" + customer_id);
                currentCustomer.value = res.data.response.customerData;
                console.log(currentCustomer.value);
            }
        }catch(e){
            const msg = "Un client d'id " + customer_id + " n'existe pas";
            window.showMessage(msg,'error');
        }
    };
    onMounted(() => {
       
    });

    const goToContremarqueList = () => {
        location.href = '/projet/contremarques'
    }
    
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
