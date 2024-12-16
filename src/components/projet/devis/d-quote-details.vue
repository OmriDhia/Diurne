<template>
    <div class="col-12 ps-0" style="overflow-x: auto;">
        <table class="table table-striped">
            <thead>
            <tr class="border-top text-black bg-black">
                <th  class="border-start border-end text-white">Réf. tapis</th>
                <th  class="border-end bg-gradient-dark text-white">% Prix total</th>
                <th  class="border-end bg-gradient-dark text-white">Collection</th>
                <th  class="border-end bg-gradient-dark text-white">Modèle</th>
                <th  class="border-end bg-gradient-dark text-white">m²</th>
                <th  class="border-end bg-gradient-dark text-white">sqft</th>
                <th  class="border-end bg-gradient-dark text-white">Prix(m²)</th>
                <th  class="border-end bg-gradient-dark text-white">Prix(sqft)</th>
                <th  class="border-end bg-gradient-dark text-white">Total</th>
                <th  class="border-end bg-gradient-dark text-white">Trait</th>
                <th  class="border-end bg-gradient-dark text-white">RN</th>
                <th  class="border-end bg-gradient-dark text-white">Emplacement</th>
                <th  class="border-end bg-gradient-dark text-white">Versement</th>
                <th  class="border-end bg-gradient-dark text-white">Actions</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="(row, rowIndex) in props.quoteDetails" :key="rowIndex">
                    <td class="border-start border-end text-center">{{ row.reference}}</td>
                    <td class="border-start border-end text-center">{{ row.totalPriceRate}}</td>
                    <td class="border-start border-end text-center"><d-collections-dropdown :disabled="true" :showOnlyDropdown="true" v-model="row.carpetSpecification.collection.id"></d-collections-dropdown></td>
                    <td class="border-start border-end text-center"><d-model-dropdown :disabled="true" :showOnlyDropdown="true" v-model="row.carpetSpecification.model.id"></d-model-dropdown></td>
                    <td class="border-start border-end text-center">{{ row.prices.tarif['m²'].price }}</td>
                    <td class="border-start border-end text-center">{{ row.prices.tarif.sqft.price }}</td>
                    <td class="border-start border-end text-center">{{ row.prices['prix-propose-avant-remise-complementaire']['m²'].price }}</td>
                    <td class="border-start border-end text-center">{{ row.prices['prix-propose-avant-remise-complementaire'].sqft.price  }}</td>
                    <td class="border-start border-end text-center">{{ row.prices['prix-propose-avant-remise-complementaire'].totalttc }}</td>
                    <td class="border-start border-end text-center">{{ row.isValidated }}</td>
                    <td class="border-start border-end text-center"></td>
                    <td class="border-start border-end text-center"><d-location-dropdown :showOnlyDropdown="true" :disabled="true" :contremarqueId="props.contremarque.contremarque_id" v-model="row.location.location_id"> </d-location-dropdown> </td>
                    <td class="border-start border-end text-center"></td>
                    <td class="border-start">
                        <div class="row ps-4 align-items-center">
                            <div class="col-auto p-1">
                                <d-delete :api="''"></d-delete>
                            </div>
                            <div class="col-auto p-1">
                                <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle"  @click="goToQuoteDetails(row.id)">
                                    <vue-feather type="search" size="14"></vue-feather>
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                  <td colspan="14">
                      <div class="row justify-content-end align-items-start mt-1 pe-2">
                          <div class="col-auto">
                              <button class="btn w-100 btn-custom text-uppercase" @click="goToDetails">Nouveau</button>
                          </div>
                          <div class="col-auto">
                              <button class="btn w-100 btn-custom text-uppercase">Nouveau stock</button>
                          </div>
                      </div>
                  </td>  
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import { useStore } from "vuex";
    import VueFeather from 'vue-feather';
    import dDelete from "../../common/d-delete.vue";
    import dInput from "../../base/d-input.vue";
    import contremarqueService from "../../../Services/contremarque-service";
    import dModelDropdown from "../contremarques/dropdown/d-model-dropdown.vue";
    import dCollectionsDropdown from "../contremarques/dropdown/d-collections-dropdown.vue";
    import dLocationDropdown from "../contremarques/dropdown/d-location-dropdown.vue";

    const props = defineProps({
        quoteId: {
            type: Number,
        },
        quoteDetails: {
            type: Array,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        contremarque: {
            type: Object,
        },
    });

    const store = useStore();
    
    const goToDetails = () => {
        location.href = `/projet/devis/${props.quoteId}/details`
    };
    const goToQuoteDetails = (id) => {
        location.href = `/projet/devis/${props.quoteId}/details/${id}`
    };
</script>

<style>
    .table > thead > tr > th {
        font-size: 0.6rem;
        vertical-align: middle;
        color: #000000;
    }
    .table > thead > tr > th:first-child {
        min-width: 82px;
    }
    .table > tbody > tr:last-child > td {
        --bs-table-accent-bg: unset !important;
    }
    .border-top{
        border-top: 1px solid #dee2e6 !important;
    }
    .table > thead > tr {
        border-radius-: 10px 0px 0px 10px;
    }
    .multiselect,
    .multiselect__input,
    .multiselect__single,
    input.form-control,
    textarea{
        font-size: 0.8rem !important;
        color: black;
    }
    .w-4{
        width: 4rem !important;
        text-align: center;
    }
    .multiselect{
        min-width: 115px;
    }
    /*.multiselect .multiselect__content-wrapper{
        position: relative;
        z-index: 1000;
    }*/
</style>
