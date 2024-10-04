<template>
    <div class="row align-items-start p-2 bg-white" id="fullscreen">
        <div class="col-12 mb-2 mt-3 p-0">
            <d-composition-thread v-if="carpetCompositionId" :threadCount="dynamicColumns.length" :layerCount="rows.length" :carpetCompositionId="carpetCompositionId" :carpetSpecificationId="props.carpetSpecificationId" @addThread="addColumn($event)"></d-composition-thread>
            <d-composition-thread-new v-else :carpetSpecificationId="props.carpetSpecificationId" @newCarpetComposition="newCarpetComposition" @addThreads="addThreads"></d-composition-thread-new>
        </div>
        <div class="col-12 ps-0" v-if="dynamicColumns.length" style="overflow-x: auto;">
            <table class="table table-striped">
                <thead>
                <tr class="border-top text-black bg-black">
                    <th class="border-start border-end text-white">N° couche</th>
                    <template v-for="(col, index) in dynamicColumns" :key="index">
                        <th :style="{backgroundColor: col.hexCode}"> Col. N° {{index+1}} </th>
                        <th :style="{backgroundColor: col.hexCode}"> Matière {{index+1}}A </th>
                        <th  class="border-end-1" :style="{backgroundColor: col.hexCode}"> %{{index+1}}A </th>
                    </template>
                    <th  class="border-end bg-gradient-dark text-white">Remarque</th>
                    <!--th  class="border-end bg-gradient-dark text-white">Actions</th-->
                </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, rowIndex) in rows" :key="rowIndex">
                        <td class="border-start border-end text-center">{{ row.layerNumber }}</td>
                        <template v-for="(detail, detailIndex) in row.layer_details" :key="detailIndex">
                            <td><d-colors-dropdown :hideLabel="true" v-model="detail.color_id"></d-colors-dropdown></td>
                            <td><d-materials-dropdown :hideLabel="true" v-model="detail.material_id"> </d-materials-dropdown> </td>
                            <td class="border-end"> <input class="form-control w-4" type="text" v-model="detail.pourcentage"></td>
                        </template>
                        <td  class="border-end"><textarea class="form-control w-auto" v-model="row.remarque"></textarea></td>
                        <!--td width="50"  class="border-end"></td-->
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-12" v-if="dynamicColumns.length">
            <div class="row justify-content-end">
                <div class="col-auto">
                    <button class="btn ms-0 btn-outline-custom" @click.prevent="addRow">
                        Ajouter
                        <vue-feather :type="'plus'" size="14"></vue-feather>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import VueFeather from 'vue-feather';
    import dCompositionThread from "./_Partials/d-composition-thread.vue";
    import dCompositionThreadNew from "./_Partials/d-composition-thread-new.vue";
    import dBtnOutlined from "../../base/d-btn-outlined.vue";
    import dMaterialsDropdown from "./dropdown/d-materials-dropdown.vue";
    import dColorsDropdown from "./dropdown/d-colors-dropdown.vue";
    import dDelete from "../../common/d-delete.vue";
    import contremarqueService from "../../../Services/contremarque-service";

    const props = defineProps({
        carpetSpecificationId: {
            type: Number,
        },
        compositionData: {
            type: Array,
        },
    });
    
    const rows = ref([]);
    const dynamicColumns = ref([]);
    const carpetCompositionId = ref(null);
    
    const addColumn = async ($event) => {
        const newColumnIndex = dynamicColumns.value.length + 1;
        dynamicColumns.value.push($event);
        if(rows.length === 0){
            const tmpRow = {
                layerNumber: 1 ,
                remarque: "",
                layer_details: [{
                    threadId: $event.id,
                    color_id: 0,
                    material_id: 0,
                    pourcentage: 0
                }]
            };
            const data = await contremarqueService.addCarpetCompositionLayer(carpetCompositionId.value, tmpRow);
            const row = formatDataLayers(data);
            rows.value.push(row);
        }else{
            rows.value.forEach( async (row) => {
                row.layer_details.push({
                    threadId: $event.id,
                    color_id: 0,
                    material_id: 0,
                    pourcentage: 0
                });
                const data = await contremarqueService.updateCarpetCompositionLayer(carpetCompositionId.value, row.id, r)
            });
        }
    };
    
    const addRow = async () => {
        if(dynamicColumns.value.length > 0){
            const newRowIndex = rows.value.length + 1;
            const layerDetails = dynamicColumns.value.map((col, index) => {
                return {
                    threadId: col.id,
                    color_id: 0,
                    material_id: 0,
                    pourcentage: 0
                };
            });

            const data = await contremarqueService.addCarpetCompositionLayer(carpetCompositionId.value, {
                layerNumber: newRowIndex ,
                remarque: '',
                layer_details: layerDetails
            });
            const row = formatDataLayers(data);
            rows.value.push(row);
        }
    }; 
    const addThreads = async (data) => {
        dynamicColumns.value = data.threads;
        for (let i = 0; i < data.layerCount; i++) {
            await addRow();
        }
    }; 
    
    const newCarpetComposition = (CompositionId) => {
        carpetCompositionId.value = parseInt(CompositionId);
    };
    
    onMounted(() => {
        if(props.compositionData){
            formatData(props.compositionData);
        }
    });
    
    const formatData = (compositionData) => {
        if(compositionData && compositionData.id){
            carpetCompositionId.value = compositionData.id;
            
            if(compositionData.layers && compositionData.layers[0] && compositionData.layers[0].layer_details){
                const th = compositionData.layers[0].layer_details.map(d => {
                    return d.thread
                });
                dynamicColumns.value = th;
            }
            
            rows.value = compositionData.layers.map(data => {
                const row = formatDataLayers(data);
                return row
            });
        }
    };

    const formatDataLayers = (data) => {
        const layerDetails = data.layer_details.map(l => ({
            threadId: l.thread.id,
            color_id: l.color,
            material_id: l.material,
            pourcentage: parseFloat(l.percentage)
        }));

        const row = ref({
            id: data.id,
            layerNumber: data.layer_number,
            remarque: data.remarque,
            layer_details: layerDetails
        });

        // Set up watcher for each row's layer_details
        watchRowLayerDetails(row.value);

        return row.value;
    }
    watch(
        () => props.compositionData,
        (newValue) => {
            formatData(newValue)
        }
    );
    
    const watchRowLayerDetails = (row) => {
        watch(
            () => row,
            async (newLayerDetails) => {
                const data = await contremarqueService.updateCarpetCompositionLayer(carpetCompositionId.value, newLayerDetails.id, newLayerDetails)
            },
            { deep: true }
        );
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
