<template>
    <d-base-page>
        <template v-slot:title>
            <d-page-title title="Contacts"></d-page-title>
        </template>
        <template v-slot:header>
          <d-panel>
              <template v-slot:panel-header>
                  <d-panel-title title="Contact en cours"></d-panel-title>
              </template>
              <template v-slot:panel-body>
                  <div class="row">
                      <d-customer :customerData="currentCustomer" ></d-customer>
                      <d-contact-top  v-if="currentCustomer.customer_id" 
                      :contactData="currentCustomer.contactsData" 
                      :customerId="currentCustomer.customer_id"
                      ></d-contact-top>
                  </div>
              </template>
          </d-panel>
        </template>
        <template v-slot:body>
            <div class="row mt-3 mb-3 pe-0" v-if="currentCustomer.customer_id">
                <div class="col-md-6 col-sm-12 pe-sm-0">
                    <d-panel>
                        <template v-slot:panel-header>
                            <d-panel-title title="Adresse"></d-panel-title>
                        </template>
                        <template v-slot:panel-body>
                            <div class="row pe-2 ps-0">
                                <d-address :disableOptions="!currentCustomer.already_has_postal_address" :addressData="currentCustomer.addressesData" :customerId="currentCustomer.customer_id"></d-address>
                            </div>
                        </template>
                    </d-panel>
                </div>
                <div class="col-md-6 col-sm-12 p-0 ps-sm-2 mt-md-1 mt-sm-3 mt-xl-0">
                    <d-panel>
                        <template v-slot:panel-header>
                            <d-panel-title title="Agent"></d-panel-title>
                        </template>
                        <template v-slot:panel-body>
                            <div class="row pe-2 ps-0">
                                <d-agent :customerId="currentCustomer.customer_id" :agents="currentCustomer.customerIntermediaryHistoriesData.agents"></d-agent>
                            </div>
                            <d-panel-title title="Commercial"></d-panel-title>
                            <div class="row pe-2 ps-0">
                                <d-commercial :customerId="currentCustomer.customer_id" :commertialData="currentCustomer.contactCommercialHistoriesData"></d-commercial>
                            </div>
                        </template>
                    </d-panel>
                </div>
            </div>
            <div class="row mt-3 mb-3 pe-0" v-if="currentCustomer.customer_id">
                <div class="col-md-6 col-sm-12 ps-sm-2 pe-sm-0">
                    <d-panel>
                        <template v-slot:panel-header>
                            <d-panel-title title="Contremarque"></d-panel-title>
                        </template>
                        <template v-slot:panel-body>
                            <div class="row pe-2 ps-0">
                                <d-contremarque-histories :customerId="currentCustomer.customer_id"></d-contremarque-histories>
                                <d-contremarque-prescriptor-histories :customerId="currentCustomer.customer_id"></d-contremarque-prescriptor-histories>
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
                    <button class="btn btn-custom pe-5 ps-5" @click="goToListCustomers">Retour à la liste</button>
                </div>
            </div>
        </template>
    </d-base-page>
</template>

<script setup>
    import {ref, onMounted, watch} from 'vue';
    import {useMeta} from '/src/composables/use-meta';
    import axiosInstance from "../../config/http";
    import dBasePage from "../../components/base/d-base-page.vue";
    import dPageTitle from "../../components/common/d-page-title.vue";
    import dPanelTitle from "../../components/common/d-panel-title.vue";
    import dPanel from "../../components/common/d-panel.vue";
    import dCustomer from "../../components/contacts/d-customer.vue";
    import dContactTop from "../../components/contacts/d-contact-top.vue";
    import dAddress from "../../components/contacts/d-address.vue";
    import dAgent from "../../components/contacts/d-agent.vue";
    import dCommercial from "../../components/contacts/d-commercial.vue";
    import dEventHistories from "../../components/contacts/_partial/d-event-histories.vue";
    import dContremarqueHistories from "../../components/contacts/_partial/d-contremarque-histories.vue";
    import dContremarquePrescriptorHistories from "../../components/contacts/_partial/d-contremarque-prescriptor-histories.vue";
    import { useRoute } from 'vue-router';

    useMeta({ title: 'Contacts' });

    const route = useRoute();
    const customer_id = route.params.id;
    
    const currentCustomer = ref({});
    const loading = ref(false);

    const getCustomer = async () => {
        try{
            loading.value = true;
            if(customer_id){
                const res = await axiosInstance.get("api/customer/" + customer_id);
                currentCustomer.value = res.data.response.customerData;
            }
        }catch(e){
            let msg = "";
            if(e.response.data && e.response.data.detail.includes("failed: not_found")){
                msg = "Un client d'id " + customer_id + " n'existe pas";
            }else{
                msg = e.message;
            }
            window.showMessage(msg,'error');
        }finally {
            loading.value = false;
        }
    };
    onMounted(() => {
        getCustomer();
    });

    const goToListCustomers = () => {
        router.push({name: 'contactsList'});
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
