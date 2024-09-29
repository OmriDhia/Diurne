<template>
    <div class="row align-items-start p-0 pt-2 bg-white" id="fullscreen" style="overflow-x: auto;">
        <div class="col-12 mb-2 mt-3">
            <d-composition-thread :carpetCompositionId="carpetCompositionId" :carpetSpecificationId="props.carpetSpecificationId" @addThread="addColumn"></d-composition-thread>
        </div>
        <div class="col-12">
            <table class="table table-striped">
                <thead>
                <tr class="border-top text-black bg-black">
                    <th class="border-start border-end text-white">N° couche</th>
                    <template v-for="(col, index) in dynamicColumns" :key="index">
                        <th :style="{backgroundColor: col.techColorId}"> Col. N° {{index+1}} </th>
                        <th :style="{backgroundColor: col.techColorId}"> Matière {{index+1}}A </th>
                        <th  class="border-end-1" :style="{backgroundColor: col.techColorId}"> %{{index+1}}A </th>
                    </template>
                    <th  class="border-end bg-gradient-dark text-white">Remarque</th>
                    <th  class="border-end bg-gradient-dark text-white">Actions</th>
                </tr>
                </thead>
                <tbody>
                <!-- Boucle pour générer les lignes -->
                <tr v-for="(row, rowIndex) in rows" :key="rowIndex">
                    <td width="100"  class="border-start border-end">{{ row.layer_number }}</td>
                    <template v-for="(detail, detailIndex) in row.layer_details" :key="detailIndex">
                        <td> {{ detail.threadId }} </td>
                        <td> {{ detail.color_id }} </td>
                        <td  class="border-end"> {{ detail.pourcentage }} </td>
                    </template>
                    <td  class="border-end">{{ row.remarque }}</td>
                    <td width="50"  class="border-end">delete</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <div class="row justify-content-end">
                <div class="col-auto">
                    <d-btn-outlined icon="plus" label="Ajouter" @click="addRow"></d-btn-outlined>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, watch, onMounted } from 'vue';
    import dCompositionThread from "./_Partials/d-composition-thread.vue";
    import dBtnOutlined from "../../base/d-btn-outlined.vue";

    const props = defineProps({
        carpetSpecificationId: {
            type: Number,
        },
        compositionData: {
            type: Array,
        },
    });

    const carpetCompositionId = ref(null)
    const dynamicColumns = ref([
        { threadNumber: 1, techColorId: "#ffa07a" },
        { threadNumber: 2, techColorId: "#98fb98" }
    ]);

    // Lignes de départ avec détails de couche (layerNumber et remarque)
    const rows = ref([
        {
            layerNumber: 1,
            remarque: "Remarque 1",
            layer_details: [
                { threadId: 1, color_id: 101, material_id: 201, pourcentage: 50 },
                { threadId: 2, color_id: 102, material_id: 202, pourcentage: 50 }
            ]
        },
        {
            layerNumber: 2,
            remarque: "Remarque 2",
            layer_details: [
                { threadId: 1, color_id: 101, material_id: 201, pourcentage: 60 },
                { threadId: 2, color_id: 102, material_id: 202, pourcentage: 40 }
            ]
        }
    ]);

    // Fonction pour ajouter une nouvelle colonne
    const addColumn = () => {
        const newColumnIndex = dynamicColumns.value.length + 1;
        dynamicColumns.value.push({
            threadNumber: newColumnIndex,
            techColorId: 100 + newColumnIndex
        });

        rows.value.forEach(row => {
            row.layer_details.push({
                threadId: newColumnIndex,
                color_id: 100 + newColumnIndex,
                material_id: 200 + newColumnIndex,
                pourcentage: 0
            });
        });
    };

    // Fonction pour ajouter une nouvelle ligne (couche)
    const addRow = () => {
        const newRowIndex = rows.value.length + 1;
        rows.value.push({
            layerNumber: newRowIndex,
            remarque: `Remarque ${newRowIndex}`,
            layer_details: dynamicColumns.value.map((col, index) => ({
                threadId: index + 1,
                color_id: 100 + index + 1,
                material_id: 200 + index + 1,
                pourcentage: 0
            }))
        });
    };
    
    onMounted(() => {
        if(props.compositionData){
            formatData(props.compositionData);
        }
    });
    
    const formatData = (compositionData) => {
        if(compositionData[0] && compositionData[0].layer_details){
            const th = compositionData[0].layer_details.map(d => {
                return d.thread
            });
            carpetCompositionId.value = th[0].carpet_composition;
            dynamicColumns.value = th;
            rows.value = compositionData;
        }
    }

    watch(
        () => props.compositionData,
        (newValue) => {
            formatData(newValue)
        }
    );
    
</script>

<style type="scss" scoped>
    .table > thead > tr > th {
        font-size: 0.6rem;
        vertical-align: middle;
        color: #000000;
    }
    .border-top{
        border-top: 1px solid #dee2e6 !important;
    }
    .table > thead > tr {
        border-radius-: 10px 0px 0px 10px;
    }
</style>
