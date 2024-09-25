<template>
        <div class="row justify-content-between pe-0">
            <template v-for="(measurement, index) in measurements" :key="index">
                <div class="col-xl-5-1 col-md-12 block-custom-border mt-2 mb-2">
                    <div class="row align-items-center">
                        <h6 class="p-0 pb-2 pt-2 m-0 ms-1 title-border-bottom title-width">{{ measurement.name }}</h6>
                    </div>
                    <div class="row align-items-center justify-content-between p-0 mt-2 mb-2">
                        <template v-for="(unit, uIndex) in measurement.unit" :key="uIndex">
                            <div class="col-md-4">
                                <d-input :label="unit.abbreviation" v-model="unit.value" class="text-center"></d-input>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
</template>

<script setup>
    import {useStore} from "vuex";
    import {computed, onMounted, ref, watch} from "vue";
    import contremarqueService from "../../../Services/contremarque-service";
    import dInput from "../../base/d-input.vue";

    const props = defineProps({
        
    })
    const store = useStore();
    const measurements = ref([]);

    const getMeasurements = async () => {
        try {
            const unitOfMeasurements = await contremarqueService.getUnitOfMeasurements();
            const meas = await contremarqueService.getMeasurements();

            measurements.value = meas.map(m => ({
                ...m,
                unit: unitOfMeasurements.map(u => ({
                    ...u,
                    value: ""
                }))
            }));
        } catch (e) {
            console.error(e.message);
        }
    };

    onMounted(() => {
        getMeasurements();
    });

    watch(
        () => measurements.value,
        (newMeasurements) => {
            store.commit('setMeasurements', newMeasurements);
        }
    );
</script>
