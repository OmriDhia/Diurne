<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title :title="'Demande Image'"></d-page-title>

        <div class="row layout-top-spacing mt-3 p-2">
            <div class="panel br-6 p-2">
                <div class="row p-2">
                    <div class="table-checkable table-highlight-head table-responsive">
                        <table role="table" aria-busy="false" aria-colcount="5"
                               class="w-50 histories-event-table table b-table table-striped">
                            <thead>
                            <tr>
                                <th>Numéro di</th>
                                <th>Date création</th>
                                <th>Date transmis</th>
                                <th>transmis</th>
                            </tr>
                            </thead>
                            <tbody role="rowgroup">
                            <tr v-for="(item, i) in datas" :key="item.project_di"
                                :class="{'selectedDi': i === selected}" @click="handleSelected(i)">
                                <td aria-colindex="1" role="cell">
                                    <div class="row align-items-center" v-if="i === selected">
                                        <div class="col-auto pe-0">
                                            <vue-feather type="chevron-right" class="pt-1"></vue-feather>
                                        </div>
                                        <div class="col-auto ps-0">{{ item.demande_number }}</div>
                                    </div>
                                    <div v-else>
                                        {{ item.demande_number }}
                                    </div>
                                </td>
                                <td aria-colindex="2" role="cell" class="">
                                    {{ $Helper.FormatDate(item.createdAt.date, 'DD/MM/YYYY') }}
                                </td>
                                <td aria-colindex="2" role="cell" class="">
                                    {{ (item.transmition_date && item.transmition_date.date && item.transmitted_to_studio) ? $Helper.FormatDate(item.transmition_date.date, 'DD/MM/YYYY') : ''
                                    }}
                                </td>
                                <td aria-colindex="2" role="cell" class="">
                                    <div title="test" class="t-dot"
                                         :class="item.transmitted_to_studio ? 'bg-success' :'bg-danger'"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel br-6 p-2 mt-3">
                <div class="row ms-2 mt-2 mb-2">
                    <div class="col-xl-auto col-md-12 d-flex">
                        <div class="col-auto pe-1 ps-2 text-black">
                            Format:
                        </div>
                        <div class="col-auto pe-1 ps-2">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="formatA3"
                                       v-model="selectedData.format" name="format" value="A3" disabled />
                                <label class="custom-control-label text-black" for="formatA3"> {{ $t('A3') }} </label>
                            </div>
                        </div>
                        <div class="col-auto pe-1 ps-1">
                            <div class="radio-success custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="formatA4"
                                       v-model="selectedData.format" name="warningAdd" value="A4" disabled />
                                <label class="custom-control-label text-black" for="formatA4"> {{ $t('A4') }} </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-auto col-md-12">
                        <d-unit-measurements v-model="unitOfMesurements.id" :disabled="true"></d-unit-measurements>
                    </div>
                    <div class="col-xl-3 col-md-4 col xs-12">

                    </div>
                    <div class="col-xl-3 col-md-4 col xs-12">

                    </div>
                </div>
            </div>
            <div class="panel br-6 p-2 mt-3">
                <div class="row ms-2 mt-2 mb-2">
                    <!-- Inputs Column -->
                    <div class="col-xl-4 col-md-12">
                        <d-input :disabled="true" v-model="customer.customerName" label="Client"></d-input>
                        <d-input :disabled="true" v-model="contremarque.designation" label="Contremarque"></d-input>
                        <d-input :disabled="true" v-model="selectedData.demande_number"
                                 label="N° de la demande"></d-input>
                        <d-input :disabled="true" v-model="transDate" label="Date transmission"></d-input>
                        <d-input :disabled="true" v-model="deadline" label="Deadline"></d-input>
                        <d-input :disabled="true" v-model="commercial" label="Commercial"></d-input>
                    </div>

                    <div class="col-auto d-flex align-self-start flex-column">
                        <router-link alt="Voir contact" :to="'/contacts/manage/'+ customer.customer_id">
                            <vue-feather style="padding: 11px 0px 1px 0px;" type="eye" stroke-width="1"
                                         class="cursor-pointer"></vue-feather>
                        </router-link>
                        <router-link alt="Voir contact" :to="'/projet/contremarques/manage/'+ contremarque_id">
                            <vue-feather style="padding: 11px 0px 1px 0px;" type="eye" stroke-width="1"
                                         class="cursor-pointer"></vue-feather>
                        </router-link>
                        <p></p>
                        <vue-feather @click="copyDemandeNumber" style="padding: 11px 0px 1px 0px;" type="clipboard"
                                     stroke-width="1"
                                     class="cursor-pointer"></vue-feather>
                    </div>


                    <!-- Attachments Column -->
                    <div class="col-xl-7 col-md-12 pe-2">
                        <d-attachment :diId="selectedData.project_di"></d-attachment>
                    </div>
                </div>
                <div class="row p-2 mt-3">
                    <div class="col-md-8">
                        Veuillez trouver ci-joint les images de la DI n° {{ selectedData.demande_number }}, pour le
                        projet {{ contremarque.designation }} du client {{ customer.customerName }} <span
                        v-if="contremarque.prescriber">, prescrit par {{ contremarque.prescriber.customerName }}</span>.
                    </div>
                </div>
                <div class="row p-2">
                    <div class="table-checkable table-highlight-head table-responsive w-75">
                        <table role="table" aria-busy="false" aria-colcount="5" class="table b-table table-striped">
                            <thead>
                            <tr>
                                <th>Emplacement</th>
                                <th>Image</th>
                                <th>Etat tapis</th>
                                <th>Collection</th>
                                <th>Variation</th>
                                <th>Modèle</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody role="rowgroup">
                            <tr v-for="(item, i) in carpetDesign" :key="item.id">
                                <td aria-colindex="1" role="cell">
                                    {{ (item.location && item.location.description) ? item.location.description : '' }}
                                </td>
                                <td aria-colindex="2" role="cell" class="">
                                    <img :src="$Helper.getImagePathNew(item.vignette_resized)" width="50">
                                </td>
                                <td aria-colindex="2" role="cell" class="">
                                    {{ (item.status && item.status.name) ? item.status.name : '' }}
                                </td>
                                <td aria-colindex="4" role="cell" class="">
                                    {{ (item.carpetSpecification && item.carpetSpecification.collection) ? item.carpetSpecification.collection.reference : ''
                                    }}
                                </td>
                                <td aria-colindex="5" role="cell" class="">
                                    {{ (item.variation) ? item.variation : '' }}
                                </td>
                                <td aria-colindex="5" role="cell" class="">
                                    {{ (item.carpetSpecification && item.carpetSpecification.model) ? item.carpetSpecification.model.code : ''
                                    }}
                                </td>
                                <td aria-colindex="6" role="cell" class="p-0">
                                    <div class="row ps-4 align-items-center">
                                        <div class="col-auto p-1">
                                            <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle"
                                                    title="Mise à jour de l'image"
                                                    @click="goToUpdateOrder(item.id)"
                                                    :disabled="item.status && item.status.id === 2">
                                                <vue-feather type="search" size="14"></vue-feather>
                                            </button>
                                        </div>
                                        <div class="col-auto p-1">
                                            <button type="button" class="btn btn-dark mb-1 me-1 rounded-circle"
                                                    :title="'Dupliquer cette image (crée une nouvelle image avec les mêmes infos tant que la DI n\'est pas transmise)'"
                                                    @click="CopieImage(item.id)"
                                                    :disabled="item.status && item.status.id === 2">
                                                <vue-feather type="clipboard" size="14"></vue-feather>
                                            </button>
                                        </div>
                                        <div class="col-auto p-1">
                                            <d-delete
                                                :api="`/api/carpet-design-orders/${item.id}`"
                                                message="Voulez-vous vraiment supprimer cette commande de design de tapis?"
                                                @isDone="handleDeleteSuccess"
                                                title="Supprimer image"
                                                :disabled="item.status && item.status.id === 2"
                                            >
                                            </d-delete>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="w-25">
                        <div class="row">
                            <div class="col-auto">
                                <button class="btn btn-custom pe-4 ps-4" @click="goTocreateOrder">Nouveau</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gap-3 ps-2 mt-4">
                    <div class="col-auto">
                        <d-btn-outlined data-bs-toggle="modal" data-bs-target="#modalDIManage"
                                        label="Editer la demande de l'image projet " icon="arrow-right"
                                        buttonClass="ps-4" @click="selectDiId"></d-btn-outlined>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-custom pe-5 ps-5" data-bs-toggle="modal" data-bs-target="#modalDIManage">
                            NOUVELLE DI
                        </button>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-custom pe-5 ps-5" @click.prevent="CopieDI"
                                :title="'Dupliquer la DI (copie tous les éléments sauf la Dead Line)'"
                        >
                            Copier DI
                        </button>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-custom pe-5 ps-5" :disabled="selectedData.transmitted_to_studio"
                                @click="TransStudio"
                                title="Transmettre toutes les demandes de cette DI au studio">
                            Transmettre au studio
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <d-modal-manage-di :diId="selectedDiId" :contremarqueId="contremarque_id" @onClose="getDIS"></d-modal-manage-di>
    </div>
</template>

<script setup>
    import dInput from '../../../components/base/d-input.vue';
    import dPageTitle from '../../../components/common/d-page-title.vue';
    import dUnitMeasurements from '../../../components/common/d-unit-measurements.vue';
    import dBtnOutlined from '../../../components/base/d-btn-outlined.vue';
    import dDelete from '../../../components/common/d-delete.vue';
    import dModalManageDi from '../../../components/projet/contremarques/_Partials/d-modal-manage-di.vue';
    import VueFeather from 'vue-feather';
    import axiosInstance from '../../../config/http';
    import { useRoute, useRouter } from 'vue-router';
    import { ref, onMounted, watch } from 'vue';
    import contremarqueService from '../../../Services/contremarque-service';
    import dAttachment from '../../../components/projet/contremarques/_Partials/d-attachments.vue';

    import Store from '../../../store/index';

    import { useMeta } from '/src/composables/use-meta';
    import { Helper } from '../../../composables/global-methods';

    useMeta({ title: 'Demande Image' });

    const router = useRouter();
    const route = useRoute();
    const contremarque_id = route.params.id;
    const datas = ref([]);
    const selected = ref(null);
    const selectedData = ref({});
    const comment = ref('');
    const format = ref('');
    const unitOfMesurements = ref('');
    const contremarque = ref({});
    const customer = ref({});
    const transDate = ref('');
    const carpetDesign = ref([]);
    const deadline = ref(null);
    const commercial = ref(null);
    const selectedDiId = ref(null);
    const getDIS = async () => {
        try {
            const res = await axiosInstance.get(`/api/contremarque/${contremarque_id}/projectDis`);
            datas.value = res.data.response.projectDis;
            selectedDiId.value = null;
            await handleSelected(0);
        } catch (e) {
            console.log('Erreur get data di');
        }
    };
    const getProjectDIS = async () => {
        try {
            getDIS();
            contremarque.value = await contremarqueService.getContremarqueById(contremarque_id);
            commercial.value = (contremarque.value.commercials) ? contremarque.value.commercials[0].firstname + ' ' + contremarque.value.commercials[0].lastname : '';
            customer.value = contremarque.value.customer;
        } catch (e) {
            console.log(e);
            console.log('Erreur get events customer');
        }
    };

    const CopieImage = async (carpetDesignOrderId) => {
        try {
            const res = await axiosInstance.post(`/api/cloneCarpetDesignOrders/${carpetDesignOrderId}`);
            window.showMessage(`Image d'id ${carpetDesignOrderId} a été dupliqué avec succées`);
            carpetDesign.value = await contremarqueService.getcarpetDesign(contremarque_id, selectedData.value.project_di);
        } catch (e) {
            console.log(e);
            console.log('Erreur get events customer');
        }
    };

    const CopieDI = async () => {
        try {
            const res = await axiosInstance.post(`/api/cloneProjectDi/${selectedData.value.project_di}`);
            window.showMessage(`Une demande d'image d'id ${selectedData.value.project_di} a été dupliqué avec succées`);
            getDIS();
        } catch (e) {
            console.log(e);
            console.log('Erreur get events customer');
        }
    };

    const TransStudio = async () => {
        try {
            const d = {
                transmitted_to_studio: true,
                transmition_date: new Date()
            };
            const res = await axiosInstance.put(`/api/projectDi/${selectedData.value.project_di}/update`, d);
            getDIS();
            window.showMessage('Demande d\'image est transmis avec succées.');
        } catch (e) {
            console.log(e);
            console.log('Erreur get events customer');
        }
    };
    const handleSelected = async (index) => {
        selected.value = index;
        comment.value = datas.value[index].commentaire;
        selectedData.value = datas.value[index];
        unitOfMesurements.value = selectedData.value.unit;
        transDate.value = (selectedData.value.transmition_date && selectedData.value.transmitted_to_studio) ? Helper.FormatDate(selectedData.value.transmition_date.date) : '';
        carpetDesign.value = await contremarqueService.getcarpetDesign(contremarque_id, selectedData.value.project_di);
        deadline.value = (selectedData.value.deadline) ? Helper.FormatDate(selectedData.value.deadline.date) : '';
    };
    const selectDiId = () => {
        selectedDiId.value = selectedData.value.project_di;
    };
    const goTocreateOrder = () => {
        if (selectedData.value.project_di) {
            router.push({ name: 'di_orderDesigner_create', params: { id_di: selectedData.value.project_di } });
        } else {
            window.showMessage('Auccun demande d\'image selectionner', 'error');
        }

    };
    const goToUpdateOrder = (id) => {
        if (selectedData.value.project_di) {
            router.push({
                name: 'di_orderDesigner_update',
                params: { id_di: selectedData.value.project_di, carpetDesignOrderId: id }
            });
        } else {
            window.showMessage('Auccun demande d\'image selectionner', 'error');
        }
    };
    const handleDeleteSuccess = () => {

        contremarqueService.getcarpetDesign(contremarque_id, selectedData.value.project_di)
            .then(data => {
                carpetDesign.value = data;
            })
            .catch(error => {
                console.error('Error refreshing carpet designs:', error);
            });
    };
    const copyDemandeNumber = () => {
        if (selectedData.value.demande_number) {
            navigator.clipboard.writeText(selectedData.value.demande_number);
            window.showMessage && window.showMessage('Numéro de demande copié !', 'success');
        }
    };
    onMounted(() => {
        getProjectDIS();
    });
    /*watch(
        () => props.customerId,
        (newVal) => {
            console.log(newVal);
            getEventHistories(newVal)
        }
    );*/
</script>

<style scoped>
    .table > thead > tr > th {
        background-color: #D4EFF8;
        color: black;
        font-weight: 700;
        font-size: 12px;
    }

    .table > thead > tr > th:first-child {
        border-radius: 20px 0px 0px 20px;
    }

    .table > thead > tr > th:last-child {
        border-radius: 0px 20px 20px 0px;
    }

    .table > tbody > tr > td:last-child {
        border-radius: 0px 10px 10px 0px;
    }

    .table > tbody > tr > td:first-child {
        border-radius: 10px 0px 0px 10px;
    }

    .table > tbody > tr > td {
        font-size: 0.8rem;
        border: none;
    }

    .table > tbody > tr > td:not(:last-child) {
        border-right: 1px solid #D4EFF8;
    }

    .table-striped > tbody > tr:nth-of-type(odd) > * {
        --bs-table-accent-bg: var(--bs-table-bg);
    }

    .table > :not(caption) > * > * {
        --bs-table-accent-bg: #EDF9FD;
    }

    .input-group {
        display: flex;
        align-items: stretch;
    }

    .input-group .btn-custom {
        height: 100%;
        min-width: 30px;
        width: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50% !important;
        background-color: #4260EB !important;
        border-color: #4260EB !important;
        box-shadow: none !important;
        cursor: pointer;
    }

    .input-group .btn-custom svg {
        color: #fff !important;
    }

</style>
